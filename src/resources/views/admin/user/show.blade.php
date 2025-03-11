@extends('admin.base')

@section('title', $titles['webTitle'])

@section('content')
<h1>{{ $titles['pageTitle'] }}</h1>
<div class="admin-form">
    <table class="table table-bordered admin-form-table">
        <tbody>
            <tr>
                <th>社員番号</th>
                <td>
                    {{ $user->employee_number }}
                </td>
            </tr>
            <tr>
                <th>名前</th>
                <td>
                    {{ $user->name }}
                </td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td>
                    {{ $user->email }}
                </td>
            </tr>
            <tr>
                <th>入社日</th>
                <td>
                    {{ $user->join_date->format('Y/m/d') }}
                </td>
            </tr>
            <tr>
                <th>作成日時</th>
                <td>
                    {{ $user->created_at->format('Y/m/d H:i') }}
                </td>
            </tr>
            <tr>
                <th>更新日時</th>
                <td>
                    {{ $user->updated_at->format('Y/m/d H:i') }}
                </td>
            </tr>
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <a href="{{ route('admin.user.index' ) }}" class="btn btn-outline-secondary">一覧へ</a>
        <a href="{{ route('admin.user.edit' ,$user->id ) }}" class="btn btn-outline-success">編集</a>
    </div>
</div>
@endsection