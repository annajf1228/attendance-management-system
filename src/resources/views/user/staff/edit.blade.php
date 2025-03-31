@extends('user.base')

@section('title', $titles['webTitle'])

@section('content')
<div class="user-staff-edit">
    <h1>{{ $titles['pageTitle'] }}</h1>
    <div class="user-staff-edit__form">
        <form action="{{ route('user.staff.update') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $workRecord->id }}" readonly>
            <table class="table table-bordered">
                <tr>
                    <th colspan="4" class="table-title text-center">{{ $workRecord->work_date->format('Y年m月d日') }}</th>
                </tr>
                <tr>
                    <th class="table-title">開始時間</th>
                    <th class="table-data time">{{ $workRecord->clock_in->format('H:i') }}</th>
                    <th class="table-title">終了時間</th>
                    <th class="table-data time">{{ $workRecord->clock_out?->format('H:i') }}</th>
                </tr>
                <tr>
                    <th colspan="1" class="table-title">休憩時間</th>
                    <td colspan="4" class="table-data">
                        <select name="break_time" class="@error('break_time') is-invalid @enderror">
                            @foreach ($breakTimeList as $key => $breakTime)
                            <option value="{{ $key }}" {{ $key == old('break_time', $workRecord->break_time) ? 'selected' : '' }}>{{ $breakTime }}</option>
                            @endforeach
                        </select>
                        @error('break_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <th colspan="1" class="table-title">備考</th>
                    <td colspan="4" class="table-data">
                        <textarea name="memo" class="@error('memo') is-invalid @enderror">{{ old('memo',$workRecord->memo ?? '') }}</textarea>
                        @error('memo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </td>
                </tr>
            </table>
            <div class="d-flex justify-content-between">
                <a href="{{ route('user.staff.index' ) }}" class="btn btn-outline-secondary">一覧へ</a>
                <button type="submit" class="btn btn-outline-primary" onclick="return confirm('更新してもよろしいですか？')">更新</button>
            </div>
        </form>
    </div>
</div>
@endsection