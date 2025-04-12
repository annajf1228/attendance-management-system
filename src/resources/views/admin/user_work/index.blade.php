@extends('admin.base')

@section('title', $titles['webTitle'])

@section('content')
<div class="admin-user-work-index">
    @if ($isAttendance)
    <h1>{{ $titles['pageTitle'] }}　{{ $selectedYear }}年{{ $selectedMonth }}月分</h1>
    <div class="admin-user-work-index__content">
        <div class="admin-user-work-index__search">
            <form method="GET" action="{{ route('admin.user-work.index') }}">
                <input type="hidden" name="id" value="{{ $id }}" readonly>
                <select name="year" id="year">
                    @for ($year = $startDate->format('Y'); $year <= $endDate->format('Y'); $year++)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                            {{ $year }}年
                        </option>
                        @endfor
                </select>

                <select name="month" id="month">
                    <option value='-' hidden>-</option>
                    @foreach ($monthList as $month)
                    <option value="{{ $month }}" {{ $selectedMonth == $month ? 'selected' : '' }}>
                        {{ $month }}月
                    </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-secondary btn-sm">検索</button>
            </form>
            <a href="{{ route('admin.user-work.download-csv', ['id' => $id, 'work_date' => $selectedYear . '-' . $selectedMonth . '-' . 1]) }}" class="btn btn-secondary btn-sm">
                CSV出力
            </a>
        </div>
        <div class="admin-user-work-index__work-record">
            <table class="work-record table table-bordered">
                <thead>
                    <tr>
                        <th class="day">日</th>
                        <th class="day-of-week">曜日</th>
                        <th class="clock-in">開始時間</th>
                        <th class="clock-out">終了時間</th>
                        <th class="break-time">休憩時間</th>
                        <th class="work-time">勤務時間</th>
                        <th class="edit-btn">編集</th>
                        <th class="memo">備考</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workRecordData as $workRecord)
                    @if($workRecord['is_saturday'])
                    <tr class="saturday-style">
                        @elseif($workRecord['is_holiday'])
                    <tr class="holiday-style">
                        @else
                    <tr>
                        @endif
                        <td>{{ $workRecord['day'] }}</td>
                        <td class="day-of-week-style">{{ $workRecord['day_of_week'] }}</td>
                        <td>{{ $workRecord['clock_in'] }}</td>
                        <td>{{ $workRecord['clock_out'] }}</td>
                        <td>{{ $workRecord['break_time'] }}</td>
                        <td>{{ $workRecord['work_time'] }}</td>
                        <td>
                            @if($workRecord['work_record_id'] != null)
                            <a href="{{ route('admin.user-work.edit', $workRecord['work_record_id'] ) }}" class="btn btn-outline-success btn-sm">編集</a>
                            @endif
                        </td>
                        <td class="memo-td">{{ $workRecord['memo'] }}</td>
                    </tr>
                    @endforeach
                    <tr class="sum-style">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>合計</td>
                        <td>{{ $sumBreakTime }}</td>
                        <td>{{ $sumWorkTime }}</td>
                        <td></td>
                        <td>※合計時間は15分単位の切捨て</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @else
    <p>勤怠データがありません</p>
    @endif
</div>

@if ($isAttendance)
<script>
    // 年変更時に月のリストを変更する処理
    const yearSelect = document.getElementById('year');
    const monthSelect = document.getElementById('month');
    const startYear = {{ $startDate->year }};
    const startMonth = {{ $startDate->month }};
    const endYear = {{ $endDate->year }};
    const nowMonth = new Date().getMonth() + 1;

    const createMonthOptions = (minMonth, maxMonth) => {
        let option = document.createElement('option');
        option.textContent = '-';
        option.hidden = true;
        monthSelect.appendChild(option);
        for (let i = minMonth; i <= maxMonth; i++) {
            let option = document.createElement('option');
            option.value = i;
            option.textContent = `${i}月`;
            monthSelect.appendChild(option);
        }
    };

    const updateMonthOptions = () => {
        const selectedYear = parseInt(yearSelect.value);
        let selectedMonth = parseInt(monthSelect.value);
        let minMonth = 1;
        let maxMonth = 12;

        if (selectedYear === endYear) {
            maxMonth = nowMonth;
        }
        if (selectedYear === startYear) {
            minMonth = startMonth;
        }

        monthSelect.innerHTML = '';
        const isSelectedMonthValid = Array.from({
            length: maxMonth - minMonth + 1
        }, (_, i) => minMonth + i).includes(selectedMonth);
        if (isSelectedMonthValid) {
            createMonthOptions(minMonth, maxMonth);
            monthSelect.value = selectedMonth;
        } else {
            createMonthOptions(minMonth, maxMonth);
        }
    }
    yearSelect.addEventListener('change', updateMonthOptions);
</script>
@endif
@endsection