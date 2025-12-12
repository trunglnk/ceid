<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Validator;
use App\Role;
use App\Menu;
use App\RoleMenu;
use DB;
use Illuminate\Validation\Rule;
use App\Traits\ExecuteString;
use App\Traits\GetDataCache;


class SystemController extends Controller
{
    use ExecuteString, GetDataCache;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get listing menu
     * 
     * thangnv create
     */
    public function indexMenu(Request $request)
    {
        $perPage = $request->get('perpage', 10);
        $page = $request->get('page', 1);
        $search = $request->get('search');

        $query = Menu::query();

        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'ilike', "%{$search}%");
                $query->orWhere('router_link', 'ilike', "%{$search}%");
                $query->orWhere('fa_icon', 'ilike', "%{$search}%");
            });
        }

        $query->with('parent');

        $query->orderBy('parent_id', 'desc');

        $data = $query->paginate($perPage, ['*'], 'page', $page);

        return view('system.menus.index', [
            'data' => $data,
            'search' => $search,
            'perPage' => $perPage,
            'menu_parents' => $this->getDataByName(Menu::class),
        ]);
    }

    public function addMenu(Request $request)
    {
        $info = $request->all();
        $user = Auth::user();
        $validator = Validator::make($info, [
            'name' => 'required|max:255',
            'router_link' => 'required|max:255',
            'fa_icon' => 'required',
            'order' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.create_error'));
        }

        Menu::query()
            ->create($info);

        return back()
            ->with('alert-type', 'alert-success')
            ->with('alert-content', __('system.create_success'));
    }

    public function updateMenu(Request $request, $id)
    {
        $menu = Menu::query()->findOrFail($id);
        $user = Auth::user();
        $info = $request->only(['parent_id', 'name', 'router_link', 'fa_icon', 'order', 'active']);
        $validator = Validator::make($info, [
            'name' => 'required|max:255',
            'router_link' => 'required|max:255',
            'fa_icon' => 'required',
            'order' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.update_error'));
        }

        if (empty($info['active'])) {
            $info['active'] = false;
        }

        $menu->update($info);

        return back()
            ->with('alert-type', 'alert-success')
            ->with('alert-content', __('system.update_success'));
    }

    public function deleteMenu(Request $request, $id)
    {
        $user = Auth::user();
        try {
            DB::beginTransaction();

            RoleMenu::query()
                ->where('menu_id', $id)
                ->delete();

            Menu::destroy($id);

            DB::commit();

            return back()->withInput()
                ->with('alert-type', 'alert-success')
                ->with('alert-content', __('system.delete_success'));
        } catch (\Exception $exception) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.delete_error'));
        }
    }

    public function showUpdateMenu($id)
    {
        return view('system.menus.detail', [
            'menu' => Menu::with('functions.role')->find($id),
            'menu_parents' => $this->getDataByName(Menu::class),
            'roles' => $this->getDataByName(Role::class),
        ]);
    }

    public function addFunctionOfMenu(Request $request, $id)
    {
        $info = $request->all();
        $info['menu_id'] = $id;

        $validator = Validator::make($info, [
            'role_id' => 'required|unique_with:role_menus,menu_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code'    => 400,
                'message' => __('system.invalid'),
                'result'  => $validator->errors()
            ], 400, []);
        }

        $menu = $this->getDataByName(Menu::class)->firstWhere('id', $id);
        if (empty($menu)) {
            return response()->json([
                'code'    => 404,
                'message' => __('system.not_found'),
                'result'  => $validator->errors()
            ], 404, []);
        }

        $roleMenuOfRoleCount = RoleMenu::where('role_id', $info['role_id'])->where('home_router', true)->count();
        if ($roleMenuOfRoleCount <= 0) {
            $info['home_router'] = true;
        }
        $roleMenu = RoleMenu::query()->create($info);

        return response()->json([
            'code'    => 200,
            'message' => __('system.create_success'),
            'result'  => $roleMenu->loadMissing('role')
        ], 200, []);
    }

    public function deleteFunctionOfMenu($id)
    {
        $roleMenu = RoleMenu::find($id);
        if (empty($roleMenu)) {
            return response()->json([
                'code'    => 404,
                'message' => __('system.not_found'),
                'result'  => []
            ], 404, []);
        }

        try {
            DB::beginTransaction();

            if ($roleMenu->home_router) {
                $roleMenuFirst = RoleMenu::where('role_id', $roleMenu->role_id)->first();
                if (isset($roleMenuFirst)) {
                    $roleMenuFirst->home_router = true;
                    $roleMenuFirst->save();
                }
            }

            $roleMenu->delete();
            DB::commit();

            return response()->json([
                'code'    => 200,
                'message' => __('system.delete_success'),
                'result'  =>  []
            ], 200, []);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'code'    => 500,
                'message' => __('system.delete_error'),
                'result'  =>  []
            ], 500, []);
        }
    }

    public function indexRole(Request $request)
    {
        $perPage = $request->get('perpage', 10);
        $page = $request->get('page', 1);
        $search = $request->get('search');

        $query = Role::query();

        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'ilike', "%{$search}%");
                $query->orWhere('code', 'ilike', "%{$search}%");
                $query->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        $data = $query->paginate($perPage, ['*'], 'page', $page);

        return view('system.roles.index', [
            'data' => $data,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function addRole(Request $request)
    {
        $info = $request->all();
        $user = Auth::user();
        $validator = Validator::make($info, [
            'code' => 'required|unique:roles,code|max:255',
            'name' => 'required|max:255',
            'description' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.validator'));
        }

        Role::query()
            ->create($info);

        return back()->withInput()
            ->with('alert-type', 'alert-success')
            ->with('alert-content', __('system.create_success'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = Auth::user();
        $role = Role::query()->findOrFail($id);
        $info = $request->only(['code', 'name', 'description', 'system']);
        $validator = Validator::make($info, [
            'code' => [
                Rule::unique('roles')->ignore($role->id),
                'max:255'
            ],
            'name' => 'required|max:255',
            'description' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.update_error'));
        }

        $role->update($info);

        return back()->withInput()
            ->with('alert-type', 'alert-success')
            ->with('alert-content', __('system.update_success'));
    }

    public function deleteRole($id)
    {
        $user = Auth::user();
        Role::destroy($id);
        return back()->withInput()
            ->with('alert-type', 'alert-success')
            ->with('alert-content', __('system.delete_success'));
    }

    public function showUpdateRole($id)
    {
        return view('system.roles.edit', [
            'role' => Role::with('functions.menu')->find($id),
            'menu_all' => $this->getDataByName(Menu::class)
        ]);
    }

    public function addFunctionOfRole(Request $request, $id)
    {
        $info = $request->all();
        $info['role_id'] = $id;
        $validator = Validator::make($info, [
            'menu_id' => 'required|unique_with:role_menus,role_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code'    => 400,
                'message' => __('system.invalid'),
                'result'  => $validator->errors()
            ], 400, []);
        }

        $role = $this->getDataByName(Role::class)->firstWhere('id', $id);
        if (empty($role)) {
            return response()->json([
                'code'    => 404,
                'message' => __('system.not_found'),
                'result'  => $validator->errors()
            ], 404, []);
        }

        $roleMenuOfRoleCount = RoleMenu::where('role_id', $id)->where('home_router', true)->count();
        if ($roleMenuOfRoleCount <= 0) {
            $info['home_router'] = true;
        }

        $roleMenu = RoleMenu::query()->create($info);

        return response()->json([
            'code'    => 200,
            'message' => __('system.create_success'),
            'result'  => $roleMenu->loadMissing('menu')
        ], 200, []);
    }

    public function deleteFunctionOfRole($id)
    {
        $roleMenu = RoleMenu::find($id);
        if (empty($roleMenu)) {
            return response()->json([
                'code'    => 404,
                'message' => __('system.not_found'),
                'result'  => []
            ], 404, []);
        }

        try {
            DB::beginTransaction();

            if ($roleMenu->home_router) {
                $roleMenuFirst = RoleMenu::where('role_id', $roleMenu->role_id)->first();
                if (isset($roleMenuFirst)) {
                    $roleMenuFirst->home_router = true;
                    $roleMenuFirst->save();
                }
            }

            $roleMenu->delete();
            DB::commit();

            return response()->json([
                'code'    => 200,
                'message' => __('system.delete_success'),
                'result'  =>  []
            ], 200, []);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'code'    => 500,
                'message' => __('system.delete_error'),
                'result'  =>  []
            ], 500, []);
        }
    }

    public function indexFunctions(Request $request)
    {
        $search_role = $request->get('search_role');
        $search_menu = $request->get('search_menu');

        $perPage = $request->get('perpage', 10);
        $page = $request->get('page', 1);

        $query = RoleMenu::query()->orderBy('role_id', 'desc');

        if (isset($search_role) && is_array($search_role)) {
            $query->whereIn('role_id', $search_role);
        }

        if (isset($search_menu) && is_array($search_menu)) {
            $query->whereIn('menu_id', $search_menu);
        }

        $query->with(['role', 'menu']);

        $data = $query->paginate($perPage, ['*'], 'page', $page);

        return view('system.functions.index', [
            'data' => $data,
            'perPage' => $perPage,
            'name_role' => $this->getDataByName(Role::class),
            'name_menu' => $this->getDataByName(Menu::class),
            'search_role' => $search_role,
            'search_menu' => $search_menu
        ]);
    }

    public function deleteFunctions(Request $request, $id)
    {
        $roleMenu = RoleMenu::find($id);
        if (empty($roleMenu)) {
            if ($request->ajax()) {
                return response()->json([
                    'code'    => 404,
                    'message' => __('system.not_found'),
                    'result'  => []
                ], 404, []);
            }
            return back()
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.not_found'));
        }

        try {
            DB::beginTransaction();

            if ($roleMenu->home_router) {
                $roleMenuFirst = RoleMenu::where('role_id', $roleMenu->role_id)->first();
                if (isset($roleMenuFirst)) {
                    $roleMenuFirst->home_router = true;
                    $roleMenuFirst->save();
                }
            }

            $roleMenu->delete();
            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'code'    => 200,
                    'message' => __('system.delete_success'),
                    'result'  =>  []
                ], 200, []);
            }
            return back()->withInput()
                ->with('alert-type', 'alert-success')
                ->with('alert-content', __('system.delete_success'));
        } catch (\Exception $exception) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json([
                    'code'    => 500,
                    'message' => __('system.delete_error'),
                    'result'  =>  []
                ], 500, []);
            }
            return back()->withInput()
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.delete_error'));
        }
    }

    public function addFunctions(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make(
            $info,
            [
                'role_id' => 'required|exists:roles,id|unique_with:role_menus,menu_id',
                'menu_id' => 'required|exists:menus,id'
            ],
            [
                'role_id.unique_with' => 'Danh mục đã tồn tại.'
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.validator'));
        }
        $rolemenu = RoleMenu::query()
            ->create($info);
        $user = Auth::user();
        $this->forget('menu', $user->role_id);

        return back()
            ->with('alert-type', 'alert-success')
            ->with('alert-content', __('system.create_success'));
    }

    public function cache()
    {
        Cache::flush();
    }
}
