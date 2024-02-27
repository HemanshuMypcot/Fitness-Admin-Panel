<?php

namespace App\Console\Commands;

use App\Models\NotificationUser;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $senderToken = config('global.FCM_SERVER_KEY');
        $isSendFcmNotification = config('global.SEND_FCM_NOTIFICATION');
        if ($isSendFcmNotification && ! empty($senderToken)) {
            // manage notification attempt
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $notificationMaxAttempt = config('global.NOTIFICATION_ATTEMPT');
            if ($notificationMaxAttempt >= 1) {
                $notificationUsers = NotificationUser::with('userDevice','notification')->where('is_send', '0')->where('trigger_date', '<=',$now)->where('attempt',
                    '<=', $notificationMaxAttempt)->get();
                foreach ($notificationUsers as $notificationUser) {
                    if (! empty($notificationUser->userDevice->fcm_id)) {
                        try {
                            $type = $notificationUser->notification->type ?? '';
                            $mappedId = $notificationUser->notification->selected_id ?? '';
                            $notificationData = [
                                'title' => $notificationUser->title ?? '',
                                'body'  => $notificationUser->body ?? '',
                                'image' => $notificationUser->notification->image_url ?? '',
                                'type'  => $type ?? '',
                                'selected_id' => $mappedId ?? ''
                            ];
                            Log::error($notificationData);
                            $response = Http::acceptJson()->withToken($senderToken)->post(
                                'https://fcm.googleapis.com/fcm/send',
                                [
                                    'to'           => $notificationUser->userDevice->fcm_id,
                                    'notification' => $notificationData,
                                ]
                            );
                            $responseArray = $response->json();
                            if (isset($responseArray['success']) && $responseArray['success']) {
                                $oldAttempt = $notificationUser->attempt ?? 0;
                                $notificationUser->update([
                                    'is_send' => '1',
                                    'attempt' => $oldAttempt + 1,
                                    'response' => $response->getBody() ?? 'Something went wrong',
                                ]);
                            } else {
                                $oldAttempt = $notificationUser->attempt ?? 0;
                                $notificationUser->update([
                                    'is_send'  => '0',
                                    'attempt'  => $oldAttempt + 1,
                                    'response' => $response->getBody() ?? 'Something went wrong',
                                ]);
                            }

                        } catch (\Exception $e) {
                            \Log::error("Send Notification Error: ".$e->getMessage());
                        }

                    }
                }
            }
        }
        
        return 0;
    }
}
