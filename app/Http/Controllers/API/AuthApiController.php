<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDevice;
use App\Http\Requests\Api_requests\UserRegistrationRequest;
use App\Http\Requests\Api_requests\LoginWithPasswordRequest;
use App\Http\Requests\Api_requests\ForgotPasswordRequest;
use App\Http\Requests\Api_requests\UserOtpRequest;
use App\Http\Requests\Api_requests\ResendOtpRequest;
use App\Http\Requests\Api_requests\ChangePasswordApiRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use App\Models\Otp;

class AuthApiController extends AppBaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(UserRegistrationRequest $request)
    {
        try {
            $input = $request->all();
            $input['password'] = md5($request['phone'].$request['password']);
            $uuid = $request->header('uuid');
            $platform = $request->header('platform');
            $checkUser = User::where('phone', $input['phone'])->first();

            if (!empty($checkUser) && $checkUser->is_verified == 'Y') {
                return $this->sendError(trans('auth.user_already_exist'));
            }

            if (empty($checkUser)) {
                $input['approved_on'] = Carbon::now();
                $input['whatsapp_no'] = $input['phone'];
                $userData = User::create($input);
            } else {
                $userData = $checkUser;
            }

            //$token = JWTAuth::fromUser($userData);

            if (!empty($input['fcm_token'])) {
                UserDevice::updateOrCreate(
                    ['user_id' => $userData->id, 'uuid' => $uuid, 'platform' => $platform],
                    ['fcm_id' => $input['fcm_token'] ?? '']
                );
            }

            //Create OTP
            $currentDateTime = Carbon::now();
            $expiry_time = date('Y-m-d H:i:s',(strtotime("$currentDateTime +  3 min")));
            $otpData['workflow'] = 'registration';
            $otpData['expiry_time'] = $expiry_time;
            $otpData['mobile_no'] = $input['phone'];
            $otpData['mobile_no_with_code'] = '+91'.$input['phone'];
            $otpData['otp_code'] = $this->generateRandomString();
            $otpData['otp_verified'] = 'N';
            $otpChk = Otp::where([['mobile_no', $input['phone']]])->first();
            if(!empty($otpChk)) {
                $last_count = $otpChk->verify_count;
                $last_hitting_time = $otpChk->updated_at;
                $next_1_hour_time = (strtotime("$last_hitting_time +  1 hour"));
                $current_time =  time();
                $new_count = 0;
                if($current_time > $next_1_hour_time || $last_count < 3) {
                    $new_count = $last_count+1;
                    if($new_count > 3){
                        $new_count=1;
                    }
                } else {
                    $new_count=1;
                }
                $new_count = $last_count+1;
                if($new_count > 3){
                    $new_count=1;
                }
                $otpData['verify_count'] = $new_count;
                Otp::where('mobile_no', $otpData['mobile_no'])->update($otpData);
            } else {
                $otpData['verify_count'] = 1;
                Otp::create($otpData);
            }

            return $this->sendResponse($userData->toArray(), trans('auth.otp_sent'));
        } catch (\Exception $e) {
            \Log::error("Registration failed: " . $e->getMessage());
            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }

    public function loginWithPassword(LoginWithPasswordRequest $request)
    {
        try {
            $input = $request->all();
            $uuid = $request->header('uuid');
            $platform = $request->header('platform');
            $checkUser = User::where('phone', $input['phone'])
                            ->where('password', md5($input['phone'].$input['password']))
                            ->first();
            if (empty($checkUser)) {
                return $this->sendError(trans('auth.invalid_login'));
            }

            if($checkUser->status != '1' || $checkUser->approval_status != 'accepted' || $checkUser->is_verified == 'N') {
                return $this->sendError(trans('auth.invalid_login'));
            }

            if (empty($checkUser->password)) {
                return $this->sendError(trans('auth.invalid_login'));
            }

            if (!empty($input['fcm_token'])) {
                UserDevice::updateOrCreate(
                    ['user_id' => $checkUser->id, 'uuid' => $uuid, 'platform' => $platform],
                    ['fcm_id' => $input['fcm_token'] ?? '']
                );
            }

            //Create token and save
            $uuid = $request->header('uuid');
            $token = JWTAuth::fromUser($checkUser);

            UserDevice::updateOrCreate(
                ['user_id' => $checkUser->id, 'uuid' => $uuid, 'platform' => $platform],
                ['remember_token' => $token]
            );

            $checkUser['remember_token'] = $token;
            
            return $this->sendResponse($checkUser, trans('auth.loggedin_successfully'));
        } catch (\Exception $e) {
            \Log::error("Login failed: " . $e->getMessage());
            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $input = $request->all();
            $uuid = $request->header('uuid');
            $checkUser = User::where('phone', $input['phone'])->first();

            if (empty($checkUser)) {
                return $this->sendError(trans('auth.user_not_found'));
            }

            // Create OTP
            $currentDateTime = Carbon::now();
            $expiry_time = date('Y-m-d H:i:s', (strtotime("$currentDateTime + 3 min")));
            $otpData['workflow'] = 'forgot_password';
            $otpData['expiry_time'] = $expiry_time;
            $otpData['mobile_no'] = $input['phone'];
            $otpData['mobile_no_with_code'] = '+91' . $input['phone'];
            $otpData['otp_code'] = $this->generateRandomString();
            $otpData['otp_verified'] = 'N';

            // Update or create OTP record
            $otpChk = Otp::where([['mobile_no', $input['phone']]])->first();
            if (!empty($otpChk)) {
                $last_count = $otpChk->verify_count;
                $last_hitting_time = $otpChk->updated_at;
                $next_1_hour_time = (strtotime("$last_hitting_time +  1 hour"));
                $current_time = time();
                $new_count = 0;
                if ($current_time > $next_1_hour_time || $last_count < 3) {
                    $new_count = $last_count + 1;
                    if ($new_count > 3) {
                        $new_count = 1;
                    }
                } else {
                    $new_count = 1;
                }
                $otpData['verify_count'] = $new_count;
                Otp::where('mobile_no', $otpData['mobile_no'])->update($otpData);
            } else {
                $otpData['verify_count'] = 1;
                Otp::create($otpData);
            }

            // Send OTP if SMS configuration is enabled
            if (config('global.sms_send')) {
                $result = $this->sendOTPMessage($otpData['otp_code'], $otpData['mobile_no']);
                if (!$result) {
                    \Log::error("OTP sending failed - SMS credentials not set");
                } elseif (is_string($result) && is_object(json_decode($result))) {
                    $data = json_decode($result);
                    if ($data->status == "error") {
                        \Log::error("OTP sending failed - " . $data->message);
                    }
                }
            }

            return $this->sendResponse($checkUser->toArray(), trans('auth.otp_sent'));
        } catch (\Exception $e) {
            \Log::error("Otp sending failed: " . $e->getMessage());
            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }

    public function validateOtp(UserOtpRequest $request)
    {
        try {
            $input = $request->all();
            if(!empty($request->otp_code) && isset($input['otp_code'])){
                $otpChk = Otp::where([['otp_code', $input['otp_code']],['mobile_no', $input['mobile_number']],['verify_count', '<=', '3'],['workflow', $input['workflow']]])->get();
                if(count($otpChk) > 0 && $otpChk[0]->otp_verified == 'N') {
                    $currentDateTime = Carbon::now();
                    if(strtotime($currentDateTime) > strtotime($otpChk[0]->expiry_time)){
                        return $this->sendError(trans('auth.otp_expired'));
                    }
                    $otps = Otp::find($otpChk[0]->id);
                    $otps->otp_verified = 'Y';
                    $otps->save();

                    User::where('phone', $input['mobile_number'])->update(
                        ['is_verified' => 'Y']
                    );

                    $user = User::where('phone', $input['mobile_number'])->first();
                    //Create token and save
                    $uuid = $request->header('uuid');
                    $platform = $request->header('platform');
                    $token = JWTAuth::fromUser($user);

                    UserDevice::updateOrCreate(
                        ['user_id' => $user->id, 'uuid' => $uuid, 'platform' => $platform],
                        ['remember_token' => $token]
                    );      
                    
                    $user['remember_token'] = $token;

                    //$user['me_data'] = $this->setStartupMeData(1);

                    if($input['workflow'] == 'registration') {
                        $msg = trans('auth.registered_successfully');
                    } else {
                        $msg = trans('auth.loggedin_successfully');
                    }

                    return $this->sendResponse($user, $msg);
                } else{
                    //otp expired
                    return $this->sendError(trans('auth.invalid_otp'));
                }
            }else{
                return $this->sendError(trans('auth.please_enter_otp_code'));
            }
        } catch (\Exception $e) {
            \Log::error("OTP Verification failed: " . $e->getMessage());
            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }

    public function resendOtp(ResendOtpRequest $request)
    {
        try {
            $input = $request->all();
            $checkUser = User::where('phone', $input['phone'])->first();

            if (empty($checkUser)) {
                return $this->sendError(trans('auth.account_not_registered'));
            }

            //Create OTP
            $currentDateTime = Carbon::now();
            $expiry_time = date('Y-m-d H:i:s',(strtotime("$currentDateTime +  3 min")));
            $otpData['workflow'] = $input['workflow'];
            $otpData['expiry_time'] = $expiry_time;
            $otpData['mobile_no'] = $input['phone'];
            $otpData['mobile_no_with_code'] = '+91'.$input['phone'];
            $otpData['otp_code'] = $this->generateRandomString();
            $otpData['otp_verified'] = 'N';
            $otpChk = Otp::where([['mobile_no', $input['phone']],['workflow', $input['workflow']]])->first();
            if(!empty($otpChk)) {
                $last_count = $otpChk->verify_count;
                $last_hitting_time = $otpChk->updated_at;
                $next_1_hour_time = (strtotime("$last_hitting_time +  1 hour"));
                $current_time =  time();
                $new_count = 0;
                if($current_time > $next_1_hour_time || $last_count < 3) {
                    $new_count = $last_count+1;
                    if($new_count > 3){
                        $new_count=1;
                    }
                } else {
                    $new_count=1;
                }
                $new_count = $last_count+1;
                if($new_count > 3){
                    $new_count=1;
                }
                $otpData['verify_count'] = $new_count;
                Otp::where('mobile_no', $otpData['mobile_no'])->update($otpData);
            } else {
                $otpData['verify_count'] = 1;
                Otp::create($otpData);
            }
            //Frame and Send response
            $userData['id'] = $checkUser->id;
            $userData['phone'] = $checkUser->phone;
            $userData['name'] = $checkUser->name;

            if(config('global.sms_send')) {
                $result =$this->sendOTPMessage($userData['otp_code'], $userData['phone']);
                if(!$result) {
                    \Log::error("OTP sending failed - SMS credentials not set");
                } else if(is_string($result) && is_object(json_decode($result))) {
                    $data = json_decode($result);
                    if($data->status == "error") {
                        \Log::error("OTP sending failed - ".$data->message);
                    }
                }
            }

            return $this->sendResponse($userData, trans('auth.otp_sent'));
        } catch (\Exception $e) {
            \Log::error("OTP Verification failed: " . $e->getMessage());
            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }

    public function changePassword(ChangePasswordApiRequest $request) {
        try {
                $input = $request->all();
                $checkUser = User::where('phone', $input['phone'])->first();

                if (empty($checkUser)) {
                    return $this->sendError(trans('auth.account_not_registered'));
                }

                $checkUser->password = md5($input['phone'].$input['password']);
                $checkUser->save();
                return $this->sendResponse($checkUser, trans('auth.password_changed'));
            } catch(\Exception $e) {
                \Log::info(json_encode($e->getMessage()));
                return $this->sendError(trans('auth.something_went_wrong'),500);
    
            }
    }

}
