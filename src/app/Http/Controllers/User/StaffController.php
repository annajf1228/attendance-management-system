<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Staff\IndexStaffRequest;
use App\Http\Requests\User\Staff\EditStaffRequest;
use App\Http\Requests\User\Staff\UpdateStaffRequest;
use App\Repositories\UserRepository;
use App\Repositories\WorkRecordRepository;
use App\Libraries\Common;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;



/**
 * スタッフページコントローラ
 */
class StaffController extends Controller
{
    public function __construct(
        private UserRepository $userRepository,
        private WorkRecordRepository $workRecordRepository,
    ) {}

    /**
     * タイトル取得
     * 
     * @param string $pageTitle
     * @return array
     */
    protected function getTitle(string $pageTitle): array
    {
        return [
            'pageTitle' => $pageTitle,
            'webTitle' => config('const.title.web_title.user') . ' | ' . $pageTitle,
        ];
    }

    /**
     * TOP画面表示
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function top(): View
    {
        $id = Auth::guard('web')->user()->id;
        $now = Carbon::now();
        $nowYear = $now->year;
        $nowMonth = $now->month;
        $targetDate = Carbon::create($nowYear, $nowMonth, 1);
        $targetMonthPeriods = Common::getMonthPeriod($targetDate);
        $workRecords = $this->userRepository->findOrFailWithWorkRecord($id, $targetMonthPeriods)->workRecords;
        $workRecordList  = $this->mapByDate($workRecords);
        $sumBreakTime = Common::formatMinutesToTime($workRecordList->sum('break_time_minutes'));
        $sumWorkTime = Common::formatMinutesToTime($workRecordList->sum('work_time_minutes'));

        $workRecordData = $this->getWorkRecordData($targetMonthPeriods, $workRecordList);

        return view('user.top', compact('workRecordData', 'sumBreakTime', 'sumWorkTime', 'nowYear', 'nowMonth'));
    }

    /**
     * スタッフ勤怠一覧表示
     * 
     * @param \App\Http\Requests\User\Staff\IndexStaffRequest $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(IndexStaffRequest $request): View
    {
        $titles = $this->getTitle(config('const.title.page_title.user.staff.index'));

        $id = Auth::guard('web')->user()->id;
        $now = Carbon::now();
        $selectedYear = $request->query('year', $now->year);
        $selectedMonth = $request->query('month', $now->month);

        $startDate = $this->workRecordRepository->getWorkDateByUser($id, true);
        $endDate = $this->workRecordRepository->getWorkDateByUser($id, false);

        $targetDate = Carbon::create($selectedYear, $selectedMonth, 1);
        if (!$targetDate->between($startDate, $now)) {
            abort(404);
        }

        $maxMonth = ($selectedYear == $now->year) ? $now->month : 12;
        $minMonth = ($selectedYear == $startDate->year) ? $startDate->month : 1;
        $monthList = range($minMonth, $maxMonth);

        $targetMonthPeriods = Common::getMonthPeriod($targetDate);
        $workRecords = $this->userRepository->findOrFailWithWorkRecord($id, $targetMonthPeriods)->workRecords;
        $workRecordList  = $this->mapByDate($workRecords);
        $sumBreakTime = Common::formatMinutesToTime($workRecordList->sum('break_time_minutes'));
        $sumWorkTime = Common::formatMinutesToTime($workRecordList->sum('work_time_minutes'));

        $workRecordData = $this->getWorkRecordData($targetMonthPeriods, $workRecordList);

        return view('user.staff.index', compact(
            'titles',
            'workRecordData',
            'sumBreakTime',
            'sumWorkTime',
            'startDate',
            'endDate',
            'monthList',
            'selectedYear',
            'selectedMonth'
        ));
    }

    /**
     * スタッフ　出勤処理
     * 
     * @return　\Illuminate\Http\RedirectResponse
     */
    public function storeClockIn(): RedirectResponse
    {
        $id = Auth::guard('web')->user()->id;
        $today = Carbon::today();
        $now = Carbon::now();
        $toDayWorkRecord = $this->workRecordRepository->findByUserAndDate($id, $today);

        if (isset($toDayWorkRecord)) {
            return redirect()->back()->with('error', '本日は既に出勤済みです。');
        }

        DB::beginTransaction();
        try {
            $this->workRecordRepository->save([
                'user_id' => $id,
                'work_date' => $now,
                'clock_in' => $now,
                'status' => config('const.work_status.clocked_in'),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', '登録に失敗しました。');
        }
        return redirect(route('user.top'))->with('success', 'おはようございます！出勤しました');
    }

    /**
     * スタッフ　退勤処理
     * 
     * @return　\Illuminate\Http\RedirectResponse
     */
    public function updateClockOut(): RedirectResponse
    {
        $id = Auth::guard('web')->user()->id;
        $today = Carbon::today();
        $now = Carbon::now();
        $toDayWorkRecord = $this->workRecordRepository->findByUserAndDate($id, $today);

        if (!$toDayWorkRecord) {
            return back()->with('error', '出勤してください');
        }

        if ($toDayWorkRecord->clock_out) {
            return back()->with('error', '本日は既に退勤しています');
        }

        $this->workRecordRepository->update($toDayWorkRecord->id, [
            'clock_out' => $now,
            'status' => config('const.work_status.clocked_out')
        ]);

        return redirect(route('user.top'))->with('success', 'お疲れ様です。退勤しました。');
    }

    /**
     * スタッフ勤怠一日毎の編集画面表示
     * 
     * @param \App\Http\Requests\User\Staff\EditStaffRequest $request
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(EditStaffRequest $request): View
    {
        $titles = $this->getTitle(config('const.title.page_title.user.staff.edit'));

        $id = Auth::guard('web')->user()->id;
        $workRecord = $this->workRecordRepository->findOrFail($request->work_record_id);
        if ($workRecord->user_id !== $id) {
            abort(404);
        }
        $breakTimeList = config('const.break_time_list');

        return view('user.staff.edit', compact('titles', 'workRecord', 'breakTimeList'));
    }

    /**
     * スタッフ勤怠一日毎の編集処理
     * 
     * @param \App\Http\Requests\User\Staff\UpdateStaffRequest $request
     * @return \Illuminate\\Http\RedirectResponse
     */
    public function update(UpdateStaffRequest $request): RedirectResponse
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

        return redirect(route('user.staff.edit', $workRecord->id))->with('success', '更新が完了しました。');
    }

    /**
     * 勤怠データを日付ごとに整理
     * 
     * @param \Illuminate\Support\Collection $workRecords
     * @return \Illuminate\Support\Collection
     */
    private function mapByDate(Collection $workRecords): Collection
    {
        return $workRecords->mapWithKeys(function ($workRecord) {
            $strDate = $workRecord->work_date->toDateString();
            $workTime = Common::getWorkTime($workRecord->clock_in, $workRecord->clock_out, $workRecord->break_time, true);
            $workTimeMinutes = Common::getWorkTime($workRecord->clock_in, $workRecord->clock_out, $workRecord->break_time, false);

            return [
                $strDate => [
                    'work_record_id' => $workRecord->id ?? null,
                    'clock_in'  => $workRecord->clock_in ? $workRecord->clock_in->format('H:i') : null,
                    'clock_out' => $workRecord->clock_out ? $workRecord->clock_out->format('H:i') : null,
                    'break_time' => $workRecord->break_time ? Carbon::createFromTime(0, 0)->addMinutes($workRecord->break_time)->format('H:i') : null,
                    'work_time' => $workTime ?? null,
                    'memo'       => $workRecord->memo ?? null,
                    'break_time_minutes' => $workRecord->break_time ? $workRecord->break_time : null,
                    'work_time_minutes' => $workTimeMinutes,
                ]
            ];
        });
    }

    /**
     * 勤怠データの取得
     * 
     * @param \Carbon\CarbonPeriod $targetMonthPeriods
     * @param \Illuminate\Support\Collection $workRecordList
     * @return array
     */
    private function getWorkRecordData(CarbonPeriod $targetMonthPeriods, Collection $workRecordList): array
    {
        foreach ($targetMonthPeriods as $dateTime) {
            $date = $dateTime->toDateString();
            $workRecordData[] = [
                'work_record_id' => $workRecordList[$date]['work_record_id'] ?? '',
                'date' => $dateTime->format('Y-m-d'),
                'day'         => $dateTime->day,
                'day_of_week' => $dateTime->isoFormat('ddd'),
                'clock_in'    => $workRecordList[$date]['clock_in'] ?? '',
                'clock_out'   => $workRecordList[$date]['clock_out'] ?? '',
                'break_time'  => $workRecordList[$date]['break_time'] ?? '',
                'work_time'  => $workRecordList[$date]['work_time'] ?? '',
                'memo'        => $workRecordList[$date]['memo'] ?? '',
                'is_saturday'   => $dateTime->isSaturday(),
                'is_holiday'    => $dateTime->isHoliday() || $dateTime->isSunday() ? true : false,
            ];
        }

        return $workRecordData;
    }
}
