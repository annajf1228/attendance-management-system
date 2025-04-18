<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('const.title.web_title.admin'))</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="base">
    <header class="base__header admin-header-bg">
        <nav class="base__header-nav">
            <a class="base__header-title" href="{{ route('admin.top') }}">{{ config('const.title.web_title.admin') }}</a>
            <div class="base__header-user">
                <span class="base__header-user-name">
                    {{ Auth::guard('admin')->user()->name }}
                </span>
                <form action="{{ route('admin.logout') }}" method="POST" id="admin-logout-form">
                    @csrf
                    <p class="base__header-logout" onclick="document.getElementById('admin-logout-form').submit();">
                        <img src="{{ asset('images/logout-icon.png') }}" alt="ログアウト" class="base__header-logout-icon-img">
                        <span>ログアウト</span>
                    </p>
                </form>
            </div>
        </nav>
    </header>

    <div class="base__main">
        <aside class="base__sidebar admin-sidebar">
            <nav>
                <ul>
                    <li><a href="{{ route('admin.top') }}">TOP</a></li>
                    <li><a href="{{ route('admin.index') }}">管理者管理</a></li>
                    <li><a href="{{ route('admin.user.index') }}">スタッフ管理</a></li>
                    <li><a href="{{ route('admin.user-work.view') }}">スタッフ勤怠管理</a></li>
                </ul>
            </nav>
        </aside>
        <main class="base__content">
        @if (session('success'))
            <div class="alert alert-success">{{session('success')}}</div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">{{session('error')}}</div>
            @endif
            @if (session('message'))
            <div class="alert alert-secondary">{{session('message')}}</div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>