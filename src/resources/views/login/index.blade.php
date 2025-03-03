<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | ログイン</title>
    @vite(['resources/sass/app.scss', 'resources/sass/login/index.scss', 'resources/js/app.js'])
</head>
<body class="login-index">
    <main class="login-index__main">
        @if ($type === 'user')
        <form action="{{ route('user.login') }}" method="POST">
        @else
        <form action="{{ route('admin.login') }}" method="POST">
        @endif
            @csrf
            <h1 class="login-index__main-title">{{ config('app.name') }}</h1>

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
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="FormControlEmail" placeholder="メールアドレスを入力してください">
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