@extends('admin.base')

@section('title', $titles['webTitle'])

@section('content')
<h1>{{ $titles['pageTitle'] }}</h1>

<div class="admin-form">
    <form action="{{ route('admin.update') }}" method="POST" id="admin-update-form">
        <input type="hidden" name="id" value="{{ $admin->id }}" readonly>
        @csrf
        @include('admin.admin.parts.form' , ['readOnly' => true])
    </form>
    <div class="d-flex justify-content-between">
        <a href="{{ route('admin.show', $admin->id) }}" class="btn btn-outline-secondary">詳細へ</a>
        <div class="d-flex">
            <button type="submit" class="btn btn-outline-primary mx-2" form="admin-update-form" onclick="return confirm('更新してもよろしいですか？')">更新</button>
            <form action="{{ route('admin.destroy') }}" method="POST" id="admin-delete-form">
                <input type="hidden" name="id" value="{{ $admin->id }}" readonly>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" form="admin-delete-form" onclick="return confirm('削除してもよろしいですか？')">削除</button>
            </form>

        </div>
    </div>
</div>
@endsection