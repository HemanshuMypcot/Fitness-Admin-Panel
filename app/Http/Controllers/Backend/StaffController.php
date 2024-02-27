<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStaffRequest;
use App\Http\Requests\UpdateStaffPasswordRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Admin;
use App\Models\Collection;
use App\Models\Country;
use App\Models\Role;
use Carbon\carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $data['staff_add'] = checkPermission('staff_add');
        $data['staff_view'] = checkPermission('staff_view');
        $data['staff_edit'] = checkPermission('staff_edit');
        $data['staff_status'] = checkPermission('staff_status');
        $roles = Role::where('status', 1)->get();
        return view('backend/staff/index', ["data" => $data], ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Admin::with('role')->orderBy('updated_at', 'desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if ($request['search']['search_email'] && !is_null($request['search']['search_email'])) {
                            $query->where('email', 'like', "%" . $request['search']['search_email'] . "%");
                        }
                        if ($request['search']['search_name'] && !is_null($request['search']['search_name'])) {
                            $query->where('admin_name', 'like', "%" . $request['search']['search_name'] . "%");
                        }
                        if ($request['search']['search_nick_name'] && !is_null($request['search']['search_nick_name'])) {
                            $query->where('nick_name', 'like', "%" . $request['search']['search_nick_name'] . "%");
                        }
                        if ($request['search']['search_phone'] && !is_null($request['search']['search_phone'])) {
                            $query->where('phone', 'like', "%" . $request['search']['search_phone'] . "%");
                        }
                        if ($request['search']['search_role'] && !is_null($request['search']['search_role'])) {
                            $query->where('role_id', $request['search']['search_role']);
                        }
                        if (isset($request['search']['search_status']) && !is_null($request['search']['search_status'])) {
                            $query->where('status', $request['search']['search_status']);
                        }
                        $query->get();
                    })
                    ->editColumn('admin_name', function ($event) {
                        return $event->admin_name . ' (' . $event->nick_name . ')';
                    })
                    ->editColumn('email', function ($event) {
                        return $event->email;
                    })
                    ->editColumn('phone', function ($event) {
                        return  $event->phone;
                    })
                    ->editColumn('role', function ($event) {
                        return $event->role->role_name;
                    })
                    ->editColumn('action', function ($event) {
                        $staff_view = checkPermission('staff_view');
                        $staff_edit = checkPermission('staff_edit');
                        $staff_status = checkPermission('staff_status');
                        $customer_change_password = checkPermission('customer_change_password');
                        $actions = '<span style="white-space:nowrap;">';

                        if ($staff_view) {
                            $actions .= '<a href="staff/view/' . $event->id . '" class="btn btn-primary btn-sm src_data" data-size="large" data-title="View Staff Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($staff_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="publish/staff" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="publish/staff" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        if ($customer_change_password && $event->id != 1) {
                            $actions .= ' <a href="staff/change_password/' . $event->id . '" class="btn btn-success btn-sm src_data" data-size="large" data-title="Change Staff Password" title="Change Password"><i class="fa fa-key"></i></a>';
                        }
                        if ($staff_edit && $event->id != 1) {
                            $actions .= ' <a href="staff/edit/' . $event->id . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }

                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['admin_name', 'email', 'phone', 'role', 'action'])->setRowId('id')->make(true);
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

    public function add() {
        $data['role'] = Role::where('status', 1)->get();

        return view('backend/staff/add',["data"=>$data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateStaffRequest  $request
     * @return Response
     */
    public function store(CreateStaffRequest $request)
    {
        try {
            $currentDate = Carbon::now();
            $expiryDate = $currentDate->addDays(90);
            DB::beginTransaction();
            $input = $request->all();
            $input['email'] = strtolower(trim($input['email']));
            $input['country_id'] = '91';
            $input['password'] =  md5($input['email'].$input['password']);
            $input['is_head'] = $request->is_head ? '1': '0';
            $input['created_by'] = session('data')['id'];
            $input['pwd_expiry_date'] = $expiryDate;
            $input['force_pwd_change_flag'] = '1';
            Admin::create($input);

            DB::commit();
            successMessage('Staff Saved successfully', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: ".$e->getMessage());

            errorMessage(trans('auth.something_went_wrong'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|Response
     */
    public function view($id)
    {
        $data['data'] = Admin::with('role')->where('id',$id)->first();

        return view('backend/staff/view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View|Response
     */
    public function edit($id)
    {
        $data['data'] = Admin::find($id);
        $data['roles'] = Role::all();

        return view('backend/staff/edit', ["data" => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateStaffRequest  $request
     * @return Response
     */
    public function update(UpdateStaffRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $input['is_head'] = $request->is_head ? '1': '0';
            $input['updated_by'] = session('data')['id'];
            $staff =  Admin::where('id',$input['id'])->first();
            $staff->update($input);
            DB::commit();
            successMessage('Staff Updated successfully', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: ".$e->getMessage());

            errorMessage(trans('auth.something_went_wrong'));
        }
    }

    /**
     * @param  Request  $request
     *
     */
    public function updateStatus(Request $request)
    {
        try {
            DB::beginTransaction();

            $msg_data = array();
            $input = $request->all();
            $input['updated_by'] = session('data')['id'];
            $staff = Admin::where('id',$input['id'])->first();

            if ($staff->exists()) {
                $staff->update($input);
                DB::commit();
                if ($request->status == 1) {
                    successMessage(trans('message.enable'), $msg_data);
                } else {
                    errorMessage(trans('message.disable'), $msg_data);
                }
            }
            errorMessage('Staff not found', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: " . $e->getMessage());
            errorMessage(trans('auth.something_went_wrong'));
        }
    }

    public function changePassword($id) {
        $data['staff'] = Admin::find($id);
        return view('backend/staff/change_password',$data);
    }

    public function changeStaffPassword(UpdateStaffPasswordRequest $request)
    {
        $msg_data = array();
        $admin = Admin::find($_GET['id']);
        if ($request->new_password != $request->confirm_password) {
            errorMessage('Password not matched!', $msg_data);
        }

        if ($admin->password == md5($admin->email . $request->new_password)) {
            errorMessage(__('passwords.new_password_cannot_same_current_password'), $msg_data);
        }

        $admin->password = md5($admin->email . $request->new_password);
        $admin->save();
        successMessage('Password updated successfully!', $msg_data);
        //return back()->with('success','Password updated successfully!');
    }
}
