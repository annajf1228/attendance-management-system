<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Login\LoginAdminRequest;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AdminLoginController extends Controller
{
    /**
     * ログイン画面表示
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $type = 'admin';
        return view('login.index', ['type' => $type]);
    }

    /**
     * ログイン処理
     * @param \App\Http\Requests\Admin\Login\LoginAdminRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginAdminRequest $request): RedirectResponse
    {
        $credentials = $request->only('employee_number', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.top')->with('success', 'ログインに成功しました。');
        }
        return back()->with([
            'error' => '社員番号またはパスワードが間違っています',
        ]);
    }

    /**
     * ログアウト処理
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('message', 'ログアウトしました。');
    }
}
