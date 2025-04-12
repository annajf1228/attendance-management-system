@extends('admin.base')

@section('title', $titles['webTitle'])

@section('content')
<div class="w-75 m-auto">
    <h1>{{ $titles['pageTitle'] }}</h1>
    <p class="admin-index-create-btn"><a href="{{ route('admin.user.create') }}" class="btn btn-outline-primary w-25">新規登録</a></p>
    <p>{{ $users->total() }}件</p>
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
            @foreach($users as $user)
            <tr>
                <td>{{ $user->employee_number }}</td>
                <td class="admin-link">
                    <a href="{{ route('admin.user-work.index', ['id' => $user->id]) }}" target="_blank">
                        {{ $user->name }}
                    </a>
                </td>
                <td>{{ $user->created_at->format('Y/m/d H:i') }}</td>
                <td>{{ $user->updated_at->format('Y/m/d H:i') }}</td>
                <td><a href="{{ route('admin.user.show', $user->id ) }}" class="btn btn-outline-warning">詳細</a></td>
                <td><a href="{{ route('admin.user.edit', $user->id ) }}" class="btn btn-outline-success">編集</a></td>

            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $users->links() }}
    </div>
</div>
@endsection