@extends('admin.base')

@section('content')
<div class="container w-75">
    <div class="row">
        <div class="col mt-4 my-5">
            <h1 class="user-top-title fs-2">管理者管理</h1>
        </div>
    </div>
    <div class="row">
        <div class="col ms-5">
            <a href="{{ route('admin.index') }}" class="user-top-a">管理者管理 一覧</a>
        </div>
        <div class="col">
            <a href="{{ route('admin.create') }}" class="user-top-a">管理者管理 新規登録</a>
        </div>
    </div>
    <div class="row">
        <div class="col mt-4 my-5">
            <h1 class="user-top-title fs-2">スタッフ管理</h1>
        </div>
    </div>
    <div class="row">
        <div class="col ms-5">
            <a href="" class="user-top-a">スタッフ管理 一覧</a>
        </div>
        <div class="col">
            <a href="" class="user-top-a">スタッフ管理 新規登録</a>
        </div>
    </div>

    <div class="row">
        <div class="col mt-5 my-5">
            <h1 class="user-top-title fs-2">スタッフ勤怠管理</h1>
        </div>
    </div>
    <div class="row">
        <div class="col ms-5">
            <a href="" class="user-top-a">スタッフ勤怠管理 一覧</a>
        </div>
    </div>
</div>
@endsection