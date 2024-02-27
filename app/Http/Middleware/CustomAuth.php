<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use DB;
use Carbon\carbon;
use Session;

class CustomAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(session()->has('data'))
        {
            $id = session('data')['role_id'];
            $roleData = Role::find($id);
            $date = Carbon::now();
            $currentDate = $date->format('Y-m-d');
            
            if($roleData) {
                $role_permissions = implode(',',$roleData['permission']);
            }
            if(empty($role_permissions)) {
                $role_permissions = 0;
            }
            $permissions = DB::select("SELECT codename FROM permissions where status = '1' and id in (".$role_permissions.")");
            if(session('data')['force_pwd_change_flag'] == 1 || (!empty(session('data')['pwd_expiry_date'])&&session('data')['pwd_expiry_date'] <= $currentDate)){
                return redirect('webadmin/password_expired');
            }
            $langauge = explode('-', explode(',', $request->header('Accept-Language'))[0]);
            Session::flash('permissions', $permissions);
            if(in_array($langauge[0], config('translatable.locales'))) {
                \App::setLocale($langauge[0]);
            }
            return $next($request);
        } else {
            return redirect('webadmin/');
        }
    }
}
