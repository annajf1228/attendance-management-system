<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\Admin\UserWork\IndexUserWorkRequest;
use App\Http\Requests\Admin\UserWork\EditUserWorkRequest;
use App\Http\Requests\Admin\UserWork\UpdateUserWorkRequest;
use App\Http\Requests\Admin\UserWork\DownloadCsvUserWorkRequest;
use App\Libraries\Common;
use App\Repositories\WorkRecordRepository;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * スタッフ勤怠管理コントローラ
 */
class UserWorkController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private WorkRecordRepository $workRecordRepository,
    ) {}

    /**
     * スタッフ勤怠管理一覧表示
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function view(Request $request): View
    {
        $titles = $this->getAdminTitle(config('const.title.page_title.admin.user_work'), config('const.title.sub_title.index'));
        $employeeNumber = $request->input('employee_number');
        $name = $request->input('name');
        $workDate = $request->filled('work_date') ? $request->input('work_date') : now()->format('Y-m-d');
        $request->merge([
            'work_date' => $workDate,
        ]);
        $users = $this->userRepository->searchWithPaginate(10, ['id' => true], $request->all());

        foreach ($users as $user) {
            $user->workRecords = $user->workRecords()->where('work_date', '=', $request->work_date)->first();
        }

        return view('admin.user_work.view', compact('users', 'titles', 'employeeNumber', 'name', 'workDate'));
    }

    /**
     * スタッフ勤怠月間勤怠 一覧
     * 
     * @param \App\Http\Requests\Admin\UserWork\IndexUserWorkRequest $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(IndexUserWorkRequest $request): View
    {
        $id = $request->id;
        $now = Carbon::now();
        $selectedYear = $request->query('year', $now->year);
        $selectedMonth = $request->query('month', $now->month);

        $startDate = $this->workRecordRepository->getWorkDateByUser($id, true);
        $endDate = $this->workRecordRepository->getWorkDateByUser($id, false);

        $isAttendance = $startDate ? true : false;

        if (!$isAttendance) {
            // 勤怠データがない場合の処理
            $titles = $this->getAdminTitle('勤怠データなし', config('const.title.sub_title.index'));
            return view('admin.user_work.index', compact(
                'titles',
                'isAttendance',
            ));
        }

        // 勤怠データがある場合の処理
        $targetDate = Carbon::create($selectedYear, $selectedMonth, 1);

        if (!$targetDate->between($startDate->startOfMonth(), $now->startOfMonth())) {
            abort(404);
        }

        $maxMonth = ($selectedYear == $now->year) ? $now->month : 12;
        $minMonth = ($selectedYear == $startDate->year) ? $startDate->month : 1;
        $monthList = range($minMonth, $maxMonth);

        $targetMonthPeriods = Common::getMonthPeriod($targetDate);
        $user = $this->userRepository->findOrFailWithWorkRecord($id, $targetMonthPeriods);
        $workRecords = $user->workRecords;
        $workRecordList  = Common::mapByDate($workRecords);
        $sumBreakTime = Common::formatMinutesToTime($workRecordList->sum('break_time_minutes'));
        $sumWorkTime = Common::formatMinutesToTime($workRecordList->sum('work_time_minutes'));
        $workRecordData = Common::getWorkRecordData($targetMonthPeriods, $workRecordList);
        $titles = $this->getAdminTitle($user->name . 'さん月間勤怠', config('const.title.sub_title.index'));

        return view('admin.user_work.index', compact(
            'titles',
            'workRecordData',
            'sumBreakTime',
            'sumWorkTime',
            'startDate',
            'endDate',
            'monthList',
            'selectedYear',
            'selectedMonth',
            'id',
            'isAttendance',
        ));
    }

    /**
     * スタッフ勤怠一日毎の編集画面表示
     * 
     * @param \App\Http\Requests\Admin\UserWork\EditUserWorkRequest $request
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(EditUserWorkRequest $request): View
    {
        $workRecord = $this->workRecordRepository->findOrFail($request->work_record_id);
        $user = $workRecord->user;
        $titles = $this->getAdminTitle($user->name . 'さん日間勤怠', config('const.title.sub_title.edit'));
        $breakTimeList = config('const.break_time_list');

        return view('admin.user_work.edit', compact('titles', 'workRecord', 'breakTimeList'));
    }

    /**
     * スタッフ勤怠一日毎の編集処理
     * 
     * @param \App\Http\Requests\Admin\UserWork\UpdateUserWorkRequest $request
     * @return \Illuminate\\Http\RedirectResponse
     */
    public function update(UpdateUserWorkRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            unset($request['_token'], $request['id']);
            $workRecord = $this->workRecordRepository->update($id, $request->all());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', '登録に失敗しました。');
        }

        return redirect(route('admin.user-work.edit', $workRecord->id))->with('success', '更新が完了しました。');
    }

    /**
     * CSVダウンロード
     * 
     * @param \App\Http\Requests\Admin\UserWork\DownloadCsvUserWorkRequest $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadCsv(DownloadCsvUserWorkRequest $request): StreamedResponse
    {
        $headerTitle = [
            '名前',
            '出勤時間',
            '退勤時間',
            '休憩時間(分)',
            '備考',
        ];

        // データ取得
        $id = $request->id;
        $selectedDate = Carbon::create($request->work_date);
        $targetDate = Carbon::create($selectedDate->year, $selectedDate->month, 1);
        $targetMonthPeriods = Common::getMonthPeriod($targetDate);
        $user = $this->userRepository->findOrFailWithWorkRecord($id, $targetMonthPeriods);
        $workRecords = $user->workRecords;
        foreach ($targetMonthPeriods as $dateTime) {
            $workRecord = $workRecords->where('work_date', $dateTime)->first();
            $workRecordData[] = [
                'clock_in'   => $workRecord->clock_in ?? '',
                'clock_out'   => $workRecord->clock_out ?? '',
                'break_time'  => $workRecord->break_time ?? '',
                'memo'        => $workRecord->memo ?? '',
            ];
        }

        $downloadCsvCallback = function () use ($headerTitle, $user, $workRecordData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headerTitle);

            foreach ($workRecordData as $workRecord) {
                fputcsv($handle, [
                    $user->name,
                    $workRecord['clock_in'],
                    $workRecord['clock_out'],
                    $workRecord['break_time'],
                    $workRecord['memo'],
                ]);
            }

            fclose($handle);
        };
        $fileName = preg_replace('/[ \x{3000}\t\r\n]+/u', '_', $user->name) . '_' . $targetDate->format('Y-m') . '.csv';
        $responseHeader = [
            'Content-Type' => 'text/csv',
        ];
        return response()->streamDownload($downloadCsvCallback, $fileName, $responseHeader);
    }
}
