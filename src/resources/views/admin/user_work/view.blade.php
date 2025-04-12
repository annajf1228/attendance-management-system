@extends('admin.base')

@section('title', $titles['webTitle'])

@section('content')
<div class="w-75 m-auto admin-user-work-view">
    <h1>{{ $titles['pageTitle'] }}</h1>
    <form method="GET" action="{{ route('admin.user-work.view') }}" class="admin-user-work-view__search">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th colspan="2">検索条件</th>
                </tr>
                <tr>
                    <th>社員番号</th>
                    <td>
                        <input type="text" name="employee_number" class="form-control" value="{{ $employeeNumber }}">
                    </td>
                </tr>
                <tr>
                    <th>
                        名前
                    </th>
                    <td><input type="text" name="name" class="form-control" value="{{ $name }}"></td>
                </tr>
                <tr>
                    <th>日付</th>
                    <td><input type="date" name="work_date" class="form-control" value="{{ $workDate }}"></td>
                </tr>
            </tbody>
        </table>
        <div class="search-btn">
            <button type="submit" class="btn btn-secondary">検索</button>
        </div>
    </form>

    <div class="admin-user-work-view__work-record">
        <p>{{ $users->total() }}件</p>
        @if ($users->total() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>社員番号</th>
                    <th>名前</th>
                    <th>出勤時間</th>
                    <th>退勤時間</th>
                    <th>休憩時間</th>
                    <th>備考</th>

                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->employee_number }}</td>
                    <td class="admin-link">
                        <a href="{{ route('admin.user-work.index', ['id' => $user->id, 'year' =>  $user->workRecords->work_date->format('Y'), 'month' => $user->workRecords->work_date->format('n')]) }}" target="_blank">
                            {{ $user->name }}
                        </a>
                    </td>
                    <td>{{ $user->workRecords?->clock_in?->format('H:i') }}</td>
                    <td>{{ $user->workRecords?->clock_out?->format('H:i') }}</td>
                    <td>{{ gmdate('H:i', $user->workRecords?->break_time * 60) }}</td>
                    <td>{{ $user->workRecords?->memo }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div>
            {{ $users->links() }}
        </div>
        @else
        <p>該当するスタッフが見つかりませんでした。</p>
        @endif
    </div>
</div>
@endsection