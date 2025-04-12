@extends('admin.base')

@section('content')
<div class="w-75 admin-top">
    <div class="admin-top__content">
        <h1 class="title">管理者管理</h1>
        <div class="link-list">
            <a href="{{ route('admin.index') }}">管理者管理 一覧</a>
            <a href="{{ route('admin.create') }}">管理者管理 新規登録</a>
        </div>
    </div>
    <div class="admin-top__content">
        <h1 class="title">スタッフ管理</h1>
        <div class="link-list">
            <a href="{{ route('admin.user.index') }}">スタッフ管理 一覧</a>
            <a href="{{ route('admin.user.create') }}">スタッフ管理 新規登録</a>
        </div>
    </div>
    <div class="admin-top__content">
        <h1 class="title">スタッフ勤怠管理</h1>
        <div class="link-list">
            <a href="{{ route('admin.user-work.view') }}">スタッフ勤怠管理 一覧</a>
        </div>
    </div>
</div>
@endsection