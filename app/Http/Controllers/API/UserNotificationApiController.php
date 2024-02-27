<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserNotificationResource;
use App\Repositories\webservices\UserNotificationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class UserNotificationApiController extends AppBaseController
{
     /** @var  UserNotificationRepository */
    private $userNotificationRepository;

    public function __construct(UserNotificationRepository $userNotificationRepo)
    {
        $this->userNotificationRepository = $userNotificationRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $input = $request->all();
            $input['uuid'] = $request->header('uuid') ?? '';
            $msg = trans('auth.data_fetched');
            $userNotification = $this->userNotificationRepository->getByRequest($input);
            if(count($userNotification['result']) == 0){
                $msg = trans('auth.notifications_empty');
            }
            $data = UserNotificationResource::collection($userNotification['result']);
            $arr = [];
            foreach ($data as $row) {
                $array_index = array_search($row['send_date'], array_column($arr, 'date'), true);
                if(!is_integer($array_index)) {
                    array_push($arr, 
                        array(
                            "date" => $row['send_date'],
                            "value" => array(
                                array(
                                    "id" => $row['id'],
                                    "title" => $row['title'],
                                    "body" => $row['body'],
                                    "notification_image_url" => $row['notification_image_url'],
                                    "notification_type" => $row['notification_type'],
                                    "selected_id" => $row['selected_id']
                                )
                            )
                        )
                    );
                } else {
                    array_push($arr[$array_index]['value'], 
                        array(
                            "id" => $row['id'],
                            "title" => $row['title'],
                            "body" => $row['body'],
                            "notification_image_url" => $row['notification_image_url'],
                            "notification_type" => $row['notification_type'],
                            "selected_id" => $row['selected_id']
                        )
                    );
                }
            }
            return $this->sendResponse($arr, $msg ,$userNotification['total_count']);
        } catch(\Exception $e) {
            Log::info(json_encode($e->getMessage()));
            
            return $this->sendError(trans('auth.something_went_wrong'),500);
        }
    }
}
