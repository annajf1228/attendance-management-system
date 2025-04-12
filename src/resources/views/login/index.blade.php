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
        @if (session('error'))
        <div class="alert alert-danger">{{session('error')}}</div>
        @endif
        @if (session('message'))
        <div class="alert alert-secondary">{{session('message')}}</div>
        @endif

        @if ($type === 'user')
        <form action="{{ route('user.login') }}" method="POST">
            @csrf
            <h1 class="login-index__main-title">{{ config('const.title.web_title.user') }}</h1>

            <div class="mb-3">
                <label for="FormControlEmail" class="form-label">
                    メールアドレス
                </label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="FormControlEmail" placeholder="xxx@exsample.com">
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="LoginUserPassword" class="form-label">
                    パスワード
                </label>
                <div class="password-box">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="LoginUserPassword" placeholder="パスワードを入力してください">
                    <img class="password-img" src="{{ asset('images/close-eye.svg') }}" onclick="togglePassword('LoginUserPassword', this)">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
            <button type="submit" class="login-index__main-user-login-button">ログイン</button>
        </form>
        @else
        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <h1 class="login-index__main-title">{{ config('const.title.web_title.admin') }}</h1>
            <div class="mb-3">
                <label for="FormControlEmployeeNumber" class="form-label">
                    社員番号
                </label>
                <input type="text" name="employee_number" class="form-control @error('employee_number') is-invalid @enderror" id="FormControlEmployeeNumber" placeholder="社員番号を入力してください">
                @error('employee_number')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="LoginAdminPassword" class="form-label">
                    パスワード
                </label>
                <div class="password-box">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="LoginAdminPassword" placeholder="パスワードを入力してください">
                    <img class="password-img" src="{{ asset('images/close-eye.svg') }}" onclick="togglePassword('LoginAdminPassword', this)">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="login-index__main-admin-login-button">ログイン</button>
        </form>
        @endif
    </main>
</body>

</html>