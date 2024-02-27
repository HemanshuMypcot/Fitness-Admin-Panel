<?php
/*
    *   Developed by : Ankita - Mypcot Infotech
    *   Created On : 03-07-2023
    *   https://www.mypcot.com/
*/

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Support\Str;
use App\Models\EmailNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;


class LoginController extends Controller
{
    /**
     * Login screen will be displayed
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(session()->has('data')) {
            return redirect('webadmin/dashboard');
        }
        return view('backend/auth/login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        \Log::info("Backend: Login started at: ".Carbon::now()->format('H:i:s:u'));
        try {
            $validationErrors = $this->validateLogin($request);
            if (count($validationErrors)) {
                \Log::error("Auth Exception: " . implode(", ", $validationErrors->all()));
                return redirect()->back()->withErrors(array("msg"=>implode("\n", $validationErrors->all())));
            }

            $email = strtolower($request->email);

            $response = Admin::with('role')->where([['email', $email],['password', md5($email.$request->password)]])->get();

            if(!count($response)) {
                \Log::error("Backend: User not found - "."email: ".$email);
                return redirect()->back()->withErrors(array("msg"=>"Invalid login credentials"));
            }

            if($response[0]['status'] != 1 ) {
                \Log::error("Backend: Account Suspended - "."email: ".$email);
                return redirect()->back()->withErrors(array("msg"=>"Your account is deactivated."));
            }
            if($response[0]['login_allowed'] != 1 ) {
                \Log::error("Backend: Not allowed to login - "."email: ".$email);
                return redirect()->back()->withErrors(array("msg"=>"Your are not allowed to login"));
            }

            \Log::info("Backend: Login Successful!");
            $data=array(
                "id"=>$response[0]['id'],
                "name"=>$response[0]['admin_name'],
                "email"=>$request->email,
                "role_id"=>$response[0]['role_id'],
                "force_pwd_change_flag" => $response[0]['force_pwd_change_flag'],
                "pwd_expiry_date" => $response[0]['pwd_expiry_date'],
                "permissions"=>$response[0]['role']['permission'],
                "is_head"=>$response[0]['is_head']
            );
            $request->session()->put('data',$data);
            return redirect('webadmin/dashboard');

        } catch (\Exception $e) {
            \Log::error("Backend: Login failed: " . $e->getMessage());
            return redirect()->back()->withErrors(array("msg"=>"Something went wrong"));
        }
    }

    /**
     * Validates input login
     *
     * @param Request $request
     * @return Response
     */
    public function validateLogin(Request $request)
    {
        return \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ])->errors();
    }

    public function forgotPassword(Request $request)
    {
        return view('backend/auth/forgot-password');
    }

    public function forgotPasswordStore(Request $request)
    {
        $table = 'admins';
        $password_reset_table = 'password_resets';

        $request->validate([
            'email' => 'required|email|exists:' . $table . ',email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $email = trim(strtolower($request->email));
        $token = Str::random(60);
        
        DB::table($password_reset_table)->updateOrInsert(
            [
                'email' => $email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ],
            [
                'email'   => $email,
            ]
        );
        $action_link =  URL::temporarySignedRoute(
            'password.reset',
            now()->addHours(48),
            ['token' => $token,
            'email' => $email]
        );

        $adminData = Admin::where('email', $email)->first();
        $emailData = EmailNotification::where([['mail_key', 'FORGOT_PASSWORD'], ['user_type', 'all'], ['status', 1]])->first();

        $subjects = $emailData['subject'] ?? '';
        $admin_name = $adminData['admin_name'];
        $url = $action_link;
        $from_name = $emailData['from_name'] ?? '';

        $emailData['content'] = str_replace('$$admin_name$$', $admin_name, $emailData['content'] ?? '');
        $emailData['content'] = str_replace('$$url$$', $url, $emailData['content']);
        $emailData['content'] = str_replace('$$from_name$$', $from_name, $emailData['content']);
        $content =  htmlspecialchars_decode(stripslashes($emailData['content'])); 
        
        if(config('global.TRIGGER_FPWD_EMAIL'))
        Mail::send('backend/auth/email-forgot', ['body' => $content], function ($message) use ($email) {
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $message->to($email, 'Fitness studio Team')->subject('Forgot Password - Fitness studio Team');
        });
        
        Log::info('Reset Password Mail Send Successfully');
        
        return back()->with('status', __('passwords.sent'));
    }

    public function passwordReset(Request $request)
    {
        $token = $request->token;
        $password_reset_table = 'password_resets';
        $check_token = DB::table($password_reset_table)->where(['token' => $token])->first();
        if (!$check_token) {
            return abort(404);
        }
        return view('backend/auth/reset-password', ['request' => $request]);
    }


    /**
     * Handle an incoming new password request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function passwordUpdate(Request $request)
    {
        $msg_data = array();
        $is_user = $request->c;
        $password_reset_table = 'password_resets';
        if ($is_user) {
            $password_reset_table = 'user_password_resets';
        }
        $request->validate([
            'token' => ['required'],
            'email' => 'required|email|exists:' . $password_reset_table . ',email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = trim(strtolower($request->email));
        $token = $request->token;
        $check_token = DB::table($password_reset_table)->where(['email' => $email, 'token' => $token])->first();
        if (!$check_token) {
            return  back()->withInput($request->only('email'))
                ->withErrors(['email' => __('passwords.token')]);
        }
        if ($is_user) {
            User::where('email', $email)->update([
                'password' => md5($email . $request->password),
            ]);
            DB::table($password_reset_table)->where(['email' => $email])->delete();
            return redirect()->route('')->with('status', __('passwords.reset'));
            return redirect()->route('password.request')->with('status', __('passwords.reset'));
            // return back()->with('status', __('passwords.reset'));
        } else {
            Admin::where('email', $email)->update([
                'password' => md5($email . $request->password),
            ]);
            DB::table($password_reset_table)->where(['email' => $email])->delete();
            return redirect()->route('login')->with('status', __('passwords.reset'));
        }
    }
}
