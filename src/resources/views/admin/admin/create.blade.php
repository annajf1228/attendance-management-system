@extends('admin.base')

@section('title', $titles['webTitle'])

@section('content')
<h1>{{ $titles['pageTitle'] }}</h1>
<form action="{{ route('admin.store') }}" method="POST" class="admin-form">
    @csrf
    @include('admin.admin.parts.form' , ['readOnly' => false])
    <div class="d-flex justify-content-between">
        <a href="{{ route('admin.index' ) }}" class="btn btn-outline-secondary">一覧へ</a>
        <button type="submit" class="btn btn-outline-primary">登録</button>
    </div>
</form>
@endsection