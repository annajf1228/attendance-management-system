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
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $workRecordList  = Common::mapByDate($workRecords);
        $sumBreakTime = Common::formatMinutesToTime($workRecordList->sum('break_time_minutes'));
        $sumWorkTime = Common::formatMinutesToTime($workRecordList->sum('work_time_minutes'));

        $workRecordData = Common::getWorkRecordData($targetMonthPeriods, $workRecordList);

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
        $titles = $this->getUserTitle(config('const.title.page_title.user.staff.index'));

        $id = Auth::guard('web')->user()->id;
        $now = Carbon::now();
        $selectedYear = $request->query('year', $now->year);
        $selectedMonth = $request->query('month', $now->month);

        $startDate = $this->workRecordRepository->getWorkDateByUser($id, true);
        $endDate = $this->workRecordRepository->getWorkDateByUser($id, false);

        $targetDate = Carbon::create($selectedYear, $selectedMonth, 1);

        if (!$targetDate->between($startDate->startOfMonth(), $now->startOfMonth())) {
            abort(404);
        }

        $maxMonth = ($selectedYear == $now->year) ? $now->month : 12;
        $minMonth = ($selectedYear == $startDate->year) ? $startDate->month : 1;
        $monthList = range($minMonth, $maxMonth);

        $targetMonthPeriods = Common::getMonthPeriod($targetDate);
        $workRecords = $this->userRepository->findOrFailWithWorkRecord($id, $targetMonthPeriods)->workRecords;
        $workRecordList  = Common::mapByDate($workRecords);
        $sumBreakTime = Common::formatMinutesToTime($workRecordList->sum('break_time_minutes'));
        $sumWorkTime = Common::formatMinutesToTime($workRecordList->sum('work_time_minutes'));

        $workRecordData = Common::getWorkRecordData($targetMonthPeriods, $workRecordList);

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
        $titles = $this->getUserTitle(config('const.title.page_title.user.staff.edit'));

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

}
