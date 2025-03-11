<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Login\LoginUserRequest;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class UserLoginController extends Controller
{
    /**
     * ログイン画面表示
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $type = 'user';
        return view('login.index', ['type' => $type]);
    }

    /**
     * ログイン処理
     * @param \App\Http\Requests\User\Login\LoginUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginUserRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('user.kintai.index')->with('success', 'ログインに成功しました。');
        }
        return back()->with([
            'error' => 'メールアドレスまたはパスワードが間違っています',
        ]);
    }

    /**
     * ログアウト処理
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('user.login')->with('message', 'ログアウトしました。');
    }
}
