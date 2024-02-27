<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendQueueNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;
    public $timeout = 7200; // 2 hours

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::error("start job Queue");
        $response = $this->data;
        $senderToken = config('global.FCM_SERVER_KEY');
        $isSendFcmNotification = config('global.SEND_FCM_NOTIFICATION');
        if ($isSendFcmNotification && !empty($senderToken) && ! empty($response['notification']) && ! empty($response['user_ids'])) {
            $notification = $response['notification'];
            $sendDate = Carbon::now()->format('Y-m-d');
            $title = $notification->title ?? '';
            $body = $notification->body ?? '';
            $userIds = $response['user_ids'] ?? [];
            $userQuery = User::with('userDevices')->whereHas('userDevices', function ($query) {
                $query->where('fcm_id', '!=', null);
            })
                ->where('fcm_notification', '1')
                ->where('status', '1');

            if (! in_array('all', $userIds)) {
                $userQuery->whereIn('id', $userIds);
            }
            $users = $userQuery->get();
            $triggerDate = $response['trigger_date'];
            // Iterate over the users and dump their data
            foreach ($users->chunk(500) as $chunkData) {
                $userChunkData = [];
                foreach ($chunkData as $user) {
                    try{
                        foreach ($user->userDevices as $userDevice) {
                            if (! empty($userDevice->fcm_id)) {
                                $updateBody = str_replace('$$user_name$$', $user->name, $body);
                                $userChunkData[] = [
                                    'notification_id' => $notification->id,
                                    'user_id'         => $user->id,
                                    'user_device_id'  => $userDevice->id,
                                    'batch_number'    => $notification->batch_count,
                                    'send_date'       => $sendDate,
                                    'title'           => $title ?? '',
                                    'body'            => $updateBody ?? '',
                                    'trigger_date'    => $triggerDate ?? '',
                                ];
                            }
                        }
                    }catch (\Exception $e){
                        \Log::error("Send Notification Error: " . $e->getMessage());
                    }
                }
                if (! empty($userChunkData)) {
                    try {
                        DB::table('notification_users')->insert($userChunkData);
                    } catch (\Exception $e) {
                        \Log::error("Store Notification User Error: ".$e->getMessage());
                    }
                }
            }
          
        }
        Log::error("end job Queue");
    }
}
