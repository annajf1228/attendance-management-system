<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AdminRepository;
use App\Http\Requests\Admin\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\Admin\ShowAdminRequest;
use App\Http\Requests\Admin\Admin\UpdateAdminRequest;
use App\Http\Requests\Admin\Admin\DestroyAdminRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

/**
 * 管理者管理コントローラ
 */
class AdminController extends Controller
{

    public function __construct(private AdminRepository $adminRepository) {}

    /**
     * タイトル取得
     * @param string $subTitle
     * @return array
     */
    protected function getTitle(string $subTitle): array
    {
        $pageTitle = config('const.title.page_title.admin') . ' ' . $subTitle;
        return [
            'pageTitle' => $pageTitle,
            'webTitle' => config('const.title.web_title.admin') . ' | ' . $pageTitle,
        ];
    }

    /**
     * TOP画面表示
     * @return \Illuminate\Contracts\View\View
     */
    public function top(): View
    {
        return view('admin.top');
    }

    /**
     * 管理者一覧表示
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $titles = $this->getTitle(config('const.title.sub_title.index'));
        $admins = $this->adminRepository->paginate(10, ['id' => true]);

        return view('admin.admin.index', compact('admins', 'titles'));
    }

    /**
     * 管理者新規登録画面表示
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): View
    {
        $titles = $this->getTitle(config('const.title.sub_title.create'));

        return view('admin.admin.create', compact('titles'));
    }

    /**
     * 管理者新規登録
     * @param  \App\Http\Requests\admin\admin\StoreAdminRequest  $request
     * @return \Illuminate\\Http\RedirectResponse
     */
    public function store(StoreAdminRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->adminRepository->save([
                'employee_number' => $request->employee_number,
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', '登録に失敗しました。');
        }
        return redirect(route('admin.create'))->with('success', '新規登録が完了しました。');
    }


    /**
     * 管理者詳細画面表示
     * @param  \App\Http\Requests\Admin\Admin\ShowAdminRequest  $request
     * @param  int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show(ShowAdminRequest $request, int $id): View
    {
        $titles = $this->getTitle(config('const.title.sub_title.show'));
        $admin = $this->adminRepository->findOrFail($id);

        return view('admin.admin.show', compact('admin', 'titles'));
    }

    /**
     * 管理者編集画面表示
     * @param  \App\Http\Requests\Admin\Admin\ShowAdminRequest  $request
     * @param  int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(ShowAdminRequest $request, int $id): View
    {
        $titles = $this->getTitle(config('const.title.sub_title.edit'));
        $admin = $this->adminRepository->findOrFail($id);

        return view('admin.admin.edit', compact('admin', 'titles'));
    }

    /**
     * 管理者更新
     * @param  \Illuminate\Http\Request\Admin\Admin\UpdateAdminRequest  $request
     * @return \Illuminate\\Http\RedirectResponse
     */
    public function update(UpdateAdminRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            unset($request['_token'], $request['id']);
            $admin = $this->adminRepository->update($id, $request->all());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', '登録に失敗しました。');
        }
        return redirect(route('admin.show', $admin->id))->with('success', '更新が完了しました。');
    }

    /**
     * 管理者削除
     * @param  App\Http\Requests\Admin\Admin\DestroyAdminRequest $Request
     * @return \Illuminate\\Http\RedirectResponse
     */
    public function destroy(DestroyAdminRequest $Request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->adminRepository->delete($Request->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', '削除に失敗しました。');
        }
        return redirect(route('admin.index'))->with('success', '削除が完了しました。');
    }
    
}
