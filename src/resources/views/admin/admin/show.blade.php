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
                    {{ $admin->employee_number }}
                </td>
            </tr>
            <tr>
                <th>名前</th>
                <td>
                    {{ $admin->name }}
                </td>
            </tr>
            <tr>
                <th>作成日時</th>
                <td>
                    {{ $admin->created_at->format('Y/m/d H:i') }}
                </td>
            </tr>
            <tr>
                <th>更新日時</th>
                <td>
                    {{ $admin->updated_at->format('Y/m/d H:i') }}
                </td>
            </tr>
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        <a href="{{ route('admin.index' ) }}" class="btn btn-outline-secondary">一覧へ</a>
        <a href="{{ route('admin.edit' ,$admin->id ) }}" class="btn btn-outline-success">編集</a>
    </div>
</div>
@endsection