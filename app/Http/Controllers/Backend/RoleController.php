<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     *
     * @return Application|Factory|View
     */
    public function roles()
    {
        $data['role_permission'] = checkPermission('role_permission');
        $data['roles'] = Role::all();
        $roles = Role::all();
        return view('backend/role/index', ["data" => $data],['roles' => $roles]);
    }

    /**
     * @param  Request  $request
     *
     * @return Application|ResponseFactory|Response
     */
    public function roleData(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Role::select('*');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_role']) && !is_null($request['search']['search_role'])) {
                            $query->where('role_name', $request['search']['search_role']);
                        }
                        $query->get();
                    })
                    ->editColumn('role_name', function ($event) {
                        return $event->role_name;
                    })
                    ->editColumn('action', function ($event) {
                        $role_permission = checkPermission('role_permission');
                        $actions = '<span style="white-space:nowrap;">';

                        if ($event->id != 1) {
                            if ($role_permission) {
                                $actions .= ' <a href="role_permission/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Edit Permissions"><i class="fa fa-edit"></i></a>';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                ->addIndexColumn()
                ->rawColumns(['role_name', 'action'])->setRowId('id')->make(true);
            } catch (\Exception $e) {
                \Log::error("Something Went Wrong. Error: " . $e->getMessage());

                return response([
                    'draw'            => 0,
                    'recordsTotal'    => 0,
                    'recordsFiltered' => 0,
                    'data'            => [],
                    'error'           => 'Something went wrong',
                ]);
            }
        }
    }

    /**
     * @param $id
     *
     * @return Application|Factory|View
     */
    public function assignRolePermission($id)
    {
        $roleData = Role::find($id)->toArray();
        $data['roleData'] = $roleData;
        $permissions = $roleData['permission'] ?? [];
        $data['role_permissions'] = $permissions;
        $permissionArr = Permission::where([['status', '1']])->get()->toArray();

        $formatedPermissions = array();
        $permisstion_type = array('List', 'Add', 'Edit', 'View', 'Status');

        foreach ($permissionArr as $key => $value) {
            if ($value['parent_status'] == 'parent') {
                if (!isset($formatedPermissions[$value['id']])) {
                    $formatedPermissions[$value['id']]['permission']['id'] = $value['id'];
                    $formatedPermissions[$value['id']]['permission']['label'] = $value['name'];
                    $formatedPermissions[$value['id']]['permission']['parent_status'] = $value['parent_status'];
                    //For List permission
                    $formatedPermissions[$value['id']][$permisstion_type[0]]['id']  = $value['id'];
                    $formatedPermissions[$value['id']][$permisstion_type[0]]['codename']  = $value['codename'];
                    $formatedPermissions[$value['id']][$permisstion_type[0]]['parent_status']  = $value['parent_status'];
                }
            } else {
                foreach ($permisstion_type as $k => $v) {
                    if ($v == $value['name']) {
                        $formatedPermissions[$value['parent_status']][$v]['id']  = $value['id'];
                        $formatedPermissions[$value['parent_status']][$v]['codename']  = $value['codename'];
                        $formatedPermissions[$value['parent_status']][$v]['parent_status']  = $value['parent_status'];
                    } else {
                        if (!isset($formatedPermissions[$value['parent_status']][$v])) {
                            $formatedPermissions[$value['parent_status']][$v]['id']  = '';
                            $formatedPermissions[$value['parent_status']][$v]['codename']  = '';
                        }
                    }
                }
            }
        }
        $data['permissions'] = array_values($formatedPermissions);
        $data['permission_types'] = $permisstion_type;

        return view('backend/role/assign_role', ["data" => $data]);
    }

    public function publishPermission(Request $request)
    {
        $id = $_GET['id'];
        $roleData = Role::find($id)->toArray();
        $permissions = $roleData['permission'];
        $permission_id = $request->id;
        $status = $request->status;

        if ($request->status) {
            $permissions[] = $permission_id;
        } elseif (($key = array_search($permission_id, $permissions)) !== false) {
            unset($permissions[$key]);
        }
        $msg_data = array();
        $roles = Role::find($id);
        $roles->permission = $permissions;
        $roles->save();
        successMessage('Permission Updated Successfully', $msg_data);
    }
}
