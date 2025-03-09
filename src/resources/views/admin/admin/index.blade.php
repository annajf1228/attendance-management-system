@extends('admin.base')

@section('title', $titles['webTitle'])

@section('content')
<div class="w-75 m-auto">
    <h1>{{ $titles['pageTitle'] }}</h1>
    <p class="admin-index-create-btn"><a href="{{ route('admin.create') }}" class="btn btn-outline-primary w-25">新規登録</a></p>
    <p>{{ $admins->total() }}件</p>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">社員番号</th>
                <th scope="col">名前</th>
                <th scope="col">作成日時</th>
                <th scope="col">更新日時</th>
                <th scope="col">詳細</th>
                <th scope="col">編集</th>

            </tr>
        </thead>
        <tbody>
            @foreach($admins as $admin)
            <tr>
                <td>{{ $admin->employee_number }}</td>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->created_at }}</td>
                <td>{{ $admin->updated_at }}</td>
                <td><a href="{{ route('admin.show', $admin->id ) }}" class="btn btn-outline-warning">詳細</a></td>
                <td><a href="{{ route('admin.edit', $admin->id ) }}" class="btn btn-outline-success">編集</a></td>

            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $admins->links() }}
    </div>
</div>
@endsection