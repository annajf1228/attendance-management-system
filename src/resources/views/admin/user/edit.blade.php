@extends('admin.base')

@section('title', $titles['webTitle'])

@section('content')
<h1>{{ $titles['pageTitle'] }}</h1>

<div class="admin-form">
    <form action="{{ route('admin.user.update') }}" method="POST" id="user-update-form">
        <input type="hidden" name="id" value="{{ $user->id }}" readonly>
        @csrf
        @include('admin.user.parts.form' , ['readOnly' => true])
    </form>
    <div class="d-flex justify-content-between">
        <a href="{{ route('admin.user.show', $user->id) }}" class="btn btn-outline-secondary">詳細へ</a>
        <div class="d-flex">
            <button type="submit" class="btn btn-outline-primary mx-2" form="user-update-form" onclick="return confirm('更新してもよろしいですか？')">更新</button>
            <form action="{{ route('admin.user.destroy') }}" method="POST" id="user-delete-form">
                <input type="hidden" name="id" value="{{ $user->id }}" readonly>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" form="user-delete-form" onclick="return confirm('削除してもよろしいですか？')">削除</button>
            </form>
        </div>
    </div>
</div>
@endsection