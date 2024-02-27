<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api_requests\UserChangePasswordApiRequest;
use App\Http\Requests\Api_requests\UpdateUserApiRequest;
use App\Http\Requests\Api_requests\UpdateFcmIdRequest;
use App\Repositories\webservices\UserRepository;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\UserDevice;
use Carbon\Carbon;
use Session;

class UserApiController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $input = $request->all();
            $input['id'] = Session::get('userId');
            $user = $this->userRepository->findWithoutFail($input['id']);

            if(empty($user)) {
                return $this->sendError(trans('auth.user_not_found'));
            }
            $input['phone'] = $user->id . 'del' . $user->phone;
            $result = $this->userRepository->update($input);
            if($user->delete()) {
                UserDevice::where('user_id', $input['id'])->delete();
                $data['id'] = $input['id'];
                // $data['me_data'] = $this->setStartupMeData(0);
                return $this->sendResponse($data, trans('auth.user_deleted'));
            }
        } catch(\Exception $e) {
            \Log::info(json_encode($e->getMessage()));
            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }

    public function logoutUser(Request $request)
    {
        try {
            $uuid = $request->header('uuid');
            $platform = $request->header('platform');
            $userId = Session::get('userId');
            $user = $this->userRepository->findWithoutFail($userId);
            if($userId != 0) {
                UserDevice::where('user_id', $userId)
                    ->where('uuid', $uuid)
                    ->where('platform', $platform)
                    ->update([
                        'remember_token' => null
                    ]);
            }
            $data['id'] = $userId;
            // $data['me_data'] = $this->setStartupMeData(0);
            return $this->sendResponse($data, trans('auth.user_logout'));
        } catch(\Exception $e) {
            \Log::info(json_encode($e->getMessage()));
            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }

    public function changePassword(UserChangePasswordApiRequest $request)
    {
        try {
            $user_id = Session::get('userId');
            $uuid = $request->header('uuid');
            $platform = $request->header('platform');

            $user = $this->userRepository->findWithoutFail($user_id);
            if(empty($user)) {
                return $this->sendError(trans('auth.user_not_found'));
            }
            $current_password = md5($user->phone . $request->input('current_password'));

            if($current_password !== $user->password) {
                return $this->sendError(trans('passwords.current_password_incorrect'));
            }

            $user->password = md5($user->phone . $request->input('password'));

            if($user->save()) {
                UserDevice::where('user_id', $user_id)
                    ->where('uuid', '!=', $uuid)
                    ->where('platform', '!=', $platform)
                    ->update(['remember_token' => null]);
            }

            return $this->sendResponse(array("id" => $user_id), trans('passwords.password_changed'));

        } catch(\Exception $e) {
            \Log::info(json_encode($e->getMessage()));
            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }

    public function update(UpdateUserApiRequest $request)
    {
        try {
            $input = $request->all();
            $input['id'] = Session::get('userId');
            $user = $this->userRepository->findWithoutFail($input['id']);
            if(empty($user)) {
                return $this->sendError(trans('auth.user_not_found'));
            }

            // Check if the user has uploaded an image
            if ($request->hasFile('user_image')) {
                clearMediaCollection($user, User::IMAGE);
                storeMedia($user, $input['user_image'], User::IMAGE);
            }
            $result = $this->userRepository->update($input);
            return $this->sendResponse(new UserResource($result), trans('auth.user_updated'));
        } catch(\Exception $e) {
            \Log::info(json_encode($e->getMessage()));
            return $this->sendError(trans('auth.something_went_wrong'),500);
        }
    }

    /**
     * Display the user profile data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        try {
            $input = $request->all();
            $input['id'] = Session::get('userId');
            $user = $this->userRepository->findWithoutFail($input['id']);
            if(empty($user)) {
                return $this->sendError(trans('auth.user_not_found'));
            }
            return $this->sendResponse(new UserResource($user), trans('auth.data_fetched'));
        } catch(\Exception $e) {
            \Log::info(json_encode($e->getMessage()));
            return $this->sendError(trans('auth.something_went_wrong'),500);
        }
    }

    public function updateFcmId(UpdateFcmIdRequest $request)
    {
        try {
            $userId = Session::get('userId');
            $uuid = $request->header('uuid');
            $platform = $request->header('platform');
            $input = $request->all();
            if($userId != 0) {
                $token = $request->header('access-token');
                $data = \JWTAuth::setToken($token)->getPayload();
                $expiry = $data['exp'];
                $expiryDateTime = Carbon::parse(date("Y-m-d", $expiry));
                $now = Carbon::now();
                if($now->diffInDays($expiryDateTime) <= 28) {
                    $checkToken = UserDevice::where('remember_token', $token)
                              ->where('uuid', $uuid)->first();
                    if(!empty($checkToken)) {
                        $newToken = \JWTAuth::refresh($token);
                        $checkToken->update([
                                        'remember_token'=>$newToken,
                                        'fcm_id'=>$input['fcm_token'] ?? ''
                                    ]);
                        return $this->sendRefreshToken(array("token" =>$newToken), trans('auth.token_expired'), 200, 3);
                    }
                }
                UserDevice::updateOrCreate(
                    ['user_id' => $userId, 'uuid' => $uuid, 'platform' => $platform],
                    ['fcm_id' => $input['fcm_token'] ?? '']
                );
            }

            return $this->sendResponse(array(),trans('auth.fcm_updated'));
        } catch (\Exception $e) {
            \Log::error("Update Fcm Id : ".$e->getMessage());

            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }

    public function updateNotificationStatus(Request $request) {

        try {
            $user_id = Session::get('userId');
            $user = $this->userRepository->findWithoutFail($user_id);
            $status = $request->input('status');
            $fcmNotification = $status ? '1' : '0';

            if(empty($user)) {
                return $this->sendError(trans('auth.user_not_found'));
            }

            $user->fcm_notification = $fcmNotification;
            $user->save();

            return $this->sendResponse(array("id" => $user_id), trans('auth.notification_setting_updated'));

        } catch (\Exception $e) {
            \Log::info(json_encode($e->getMessage()));
            return $this->sendError(trans('auth.something_went_wrong'),500);
        }

    }
}
