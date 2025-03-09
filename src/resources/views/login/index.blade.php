<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if ($type === 'user')
    <title>{{ config('const.title.web_title.user') }} | {{ config('const.title.sub_title.login') }}</title>
    @else
    <title>{{ config('const.title.web_title.admin')}} | {{ config('const.title.sub_title.login') }}</title>
    @endif
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="login-index">
    <main class="login-index__main">
        @if ($type === 'user')
        <form action="{{ route('user.login') }}" method="POST">
        @else
        <form action="{{ route('admin.login') }}" method="POST">
        @endif
            @csrf
            @if ($type === 'user')
            <h1 class="login-index__main-title">{{ config('const.title.web_title.user') }}</h1>
            @else
            <h1 class="login-index__main-title">{{ config('const.title.web_title.admin') }}</h1>
            @endif

            @if (session('error'))
            <div class="alert alert-danger">{{session('error')}}</div>
            @endif
            @if (session('message'))
            <div class="alert alert-secondary">{{session('message')}}</div>
            @endif

            @if ($type === 'user')
            <div class="mb-3">
                <label for="FormControlEmail" class="form-label">
                    メールアドレス
                </label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="FormControlEmail" placeholder="xxx@exsample.com">
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @else
            <div class="mb-3">
                <label for="FormControlEmployeeNumber" class="form-label">
                    社員番号
                </label>
                <input type="text" name="employee_number" class="form-control @error('employee_number') is-invalid @enderror" id="FormControlEmployeeNumber" placeholder="社員番号を入力してください">
                @error('employee_number')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @endif
            <div class="mb-3">
                <label for="FormControlPassword" class="form-label">
                    パスワード
                </label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="FormControlPassword" placeholder="パスワードを入力してください">
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @if ($type ==='user')
            <button type="submit" class="login-index__main-user-login-button">ログイン</button>
            @else
            <button type="submit" class="login-index__main-admin-login-button">ログイン</button>
            @endif
        </form>
    </main>
</body>
</html>