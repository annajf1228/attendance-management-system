@extends('user.base')

@section('content')
<div class="user-top">
    <div class="user-top__clock-container">
        <div class="user-top__clock-box">
            <p id="clock"></p>
        </div>
        <div class="user-top__clock-button">
            <form method="POST" action="{{ route('user.staff.store-clock-in') }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg">出勤</button>
            </form>
            <form method="POST" action="{{ route('user.staff.update-clock-out') }}">
                @csrf
                <button type="submit" class="btn btn-danger btn-lg">退勤</button>
            </form>
        </div>
    </div>
    <div class="user-top__work-record">
        <p class="title">{{ $nowYear }}年{{ $nowMonth }}月</p>
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
                        <a href="{{ route('user.staff.edit', $workRecord['work_record_id'] ) }}" class="btn btn-outline-success btn-sm">編集</a>
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

<script>
    // リアルタイム時計
    const clockElement = document.getElementById('clock');

    const updateClock = () => {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const formattedTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        clockElement.textContent = formattedTime;
    };

    updateClock();
    setInterval(updateClock, 1000);
</script>
@endsection