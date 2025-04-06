<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\ShowUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Http\Requests\Admin\User\DestroyUserRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

/**
 * スタッフ管理コントローラ
 */
class UserController extends Controller
{
    public function __construct(private UserRepository $userRepository) {}

    /**
     * スタッフ一覧表示
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $titles = $this->getAdminTitle(config('const.title.page_title.admin.user'), config('const.title.sub_title.index'));
        $users = $this->userRepository->paginate(10, ['id' => true]);

        return view('admin.user.index', compact('users', 'titles'));
    }

    /**
     * スタッフ新規登録画面表示
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): View
    {
        $titles = $this->getAdminTitle(config('const.title.page_title.admin.user'), config('const.title.sub_title.create'));

        return view('admin.user.create', compact('titles'));
    }

    /**
     * スタッフ新規登録
     * 
     * @param  \App\Http\Requests\Admin\User\StoreUserRequest $request
     * @return \Illuminate\\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->userRepository->save([
                'employee_number' => $request->employee_number,
                'name' => $request->name,
                'email' => $request->email,
                'join_date' => $request->join_date,
                'password' => Hash::make($request->password),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', '登録に失敗しました。');
        }
        return redirect(route('admin.user.create'))->with('success', '新規登録が完了しました。');
    }


    /**
     * スタッフ詳細画面表示
     * 
     * @param  \App\Http\Requests\Admin\User\ShowUserRequest $request
     * @return \Illuminate\Contracts\View\View
     */
    public function show(ShowUserRequest $request): View
    {
        $titles = $this->getAdminTitle(config('const.title.page_title.admin.user'), config('const.title.sub_title.show'));
        $user = $this->userRepository->findOrFail($request->id);

        return view('admin.user.show', compact('user', 'titles'));
    }

    /**
     * スタッフ編集画面表示
     * 
     * @param  \App\Http\Requests\Admin\User\ShowUserRequest $request
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(ShowUserRequest $request): View
    {
        $titles = $this->getAdminTitle(config('const.title.page_title.admin.user'), config('const.title.sub_title.edit'));
        $user = $this->userRepository->findOrFail($request->id);

        return view('admin.user.edit', compact('user', 'titles'));
    }

    /**
     * スタッフ更新
     * 
     * @param  \App\Http\Requests\Admin\User\UpdateUserRequest $request
     * @return \Illuminate\\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            unset($request['_token'], $request['id']);
            $user = $this->userRepository->update($id, $request->all());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', '登録に失敗しました。');
        }
        return redirect(route('admin.user.show', $user->id))->with('success', '更新が完了しました。');
    }

    /**
     * スタッフ削除
     * 
     * @param  \App\Http\Requests\Admin\User\DestroyUserRequest $request
     * @return \Illuminate\\Http\RedirectResponse
     */
    public function destroy(DestroyUserRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->userRepository->delete($request->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', '削除に失敗しました。');
        }
        return redirect(route('admin.user.index'))->with('success', '削除が完了しました。');
    }
}
