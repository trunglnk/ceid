<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Menu;
use App\User;
use App\Role;
use App\Traits\GetDataCache;
use DB;
use App\TinhThanhUser;

class UserController extends Controller
{
    use GetDataCache;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->get('perpage', 10);
        $page = $request->get('page', 1);
        $search = $request->get('search');
        $search_role = $request->get('search_role');
        $search_role_code = $request->get('search_role_code');
        $search_status = $request->get('search_status');

        $query = User::query()->with([
            'role',
        ]);

        if ($user->role->code != 'sysadmin') {
            $query->where('role_id', '!=', 1);
        }

        $roles = $this->getDataByName(Role::class)->sortBy('name');

        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'ilike', "%{$search}%");
                $query->orWhere('username', 'ilike', "%{$search}%");
                $query->orWhere('email', 'ilike', "%{$search}%");
            });
        }
        if (isset($search_role)) {
            $query->whereIn('role_id', $search_role);
        }
        if (isset($search_role_code)) {
            $query->whereHas('role', function ($query) use ($search_role_code) {
                $query->where('code', $search_role_code);
            });
        }

        if (isset($search_status)) {
            $query->whereIn('active', $search_status);
        }

        $query->orderBy('name');
        $users = $query->paginate($perPage, ['*'], 'page', $page);

        if ($request->ajax()) {
            return response()->json([
                'message' => __('message.success'),
                'result'  => $users
            ], 200, []);
        }
        return view('system.user.index', [
            'perPage' => $perPage,
            'data' => $users,
            'search' => $search,
            'search_status' => $search_status,
            'search_role' => $search_role,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::query()->findOrFail($id);

        $info = $request->only(['name', 'username', 'email', 'role_id', 'password', 'active', 'tinh_thanh_ids']);
        if ($user->role->system) {
            $info['role_id'] = $user->role_id;
        }
        if (isset($info['username'])) {
            $info['username'] = trim(strtolower($info['username']));
        }

        $validator = Validator::make(
            $info,
            [
                'name' => 'required|max:255',
                'username' => 'required|max:255|unique:users,username,' . $id,
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'role_id' => 'required|exists:roles,id',
                'active' => 'boolean'
            ],
            [
                'email.email' => 'Địa chỉ email không hợp lệ',
                'email.unique' => 'Địa chỉ email đã tồn tại!'
            ]
        );

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.invalid'));
        }

        if (!empty($info['password'])) {
            $info['password'] = Hash::make($request->password);
        } else {
            unset($info['password']);
        }

        if (empty($info['active'])) {
            $info['active'] = false;
        }

        DB::beginTransaction();
        try {
            TinhThanhUser::where('user_id', $id)->delete();
            if (isset($info['tinh_thanh_ids']) && is_array($info['tinh_thanh_ids'])) {
                foreach ($request->get('tinh_thanh_ids') as $tinh_thanh_id) {
                    TinhThanhUser::create([
                        'user_id' => $id,
                        'tinh_thanh_id' => $tinh_thanh_id
                    ]);
                }
            }
            $user->update($info);
            DB::commit();
            if ($user->id == Auth::user()->id) {
                Auth::logout();
                return redirect('/login');
            }
            return redirect()
                ->route('users')
                ->with('alert-type', 'alert-success')
                ->with('alert-content', __('system.update_success'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return back()->withInput()
                ->with('alert-type', 'alert-danger')
                ->with('alert-content', __('system.update_error'));
        }
    }

    public function delete(Request $request, $id)
    {
        $user = \App\User::find($id);
        if ($user->role_id == 1) {
            return back()->withInput()
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.delete_error_sysadmin'));
        }

        DB::beginTransaction();
        try {
            \App\TinhThanhUser::where('user_id', $user->id)->delete();
            $user->delete();
            DB::commit();
            if ($user->id == Auth::user()->id) {
                Auth::logout();
                return redirect('/login');
            }
            return back()->withInput()
                ->with('alert-type', 'alert-success')
                ->with('alert-content', __('system.delete_success'));
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            DB::rollBack();
            return back()->withInput()
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.delete_error'));
        }
    }

    /**
     * Thêm mới người dùng
     */
    public function add(Request $request)
    {
        $user = Auth::user();
        $info = $request->all();
        if (isset($info['username'])) {
            $info['username'] = trim(strtolower($info['username']));
        }
        $validator = Validator::make(
            $info,
            [
                'username' => 'required|unique:users,username|max:255',
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'role_id' => 'required|exists:roles,id',
                'password' => 'required|max:255|min:6|confirmed',
                'active' => 'boolean'
            ],
            [
                'email.email' => 'Địa chỉ email không hợp lệ',
                'email.unique' => 'Địa chỉ email đã tồn tại!'
            ]
        );

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.create_error'));
        }

        if (isset($info['password'])) {
            $info['password'] = Hash::make($info['password']);
        }

        if (empty($info['active'])) {
            $info['active'] = false;
        }

        User::query()
            ->create($info);

        return back()
            ->with('alert-type', 'alert-success')
            ->with('alert-content', __('system.create_success'));
    }

    public function showUpdate($id)
    {
        if (User::findOrFail($id)->role->system) {
            $roles = $this->getDataByName(Role::class);
        } else $roles = $this->getDataByName(Role::class)->where('system', false);
        $tinhthanhuser = TinhThanhUser::where('user_id', $id)->get()->pluck('tinh_thanh_id')->toArray();
        return view('system.user.detail', [
            'user' => User::find($id),
            'tinhs' => \App\TinhThanh::query()->get(),
            'roles' => $roles->sortBy('name'),
            'tinh_thanh_ids' => $tinhthanhuser
        ]);
    }
}
