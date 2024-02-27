<?php

namespace App\Http\Controllers;
use App\Utils\ResponseMessageUtils;
use App\Utils\MessageUtils;
use Response;
use Session;

class AppBaseController extends Controller
{
    public function sendResponse($result, $message = "", $total = null)
    {
        return ResponseMessageUtils::sendResponse($result, $message, $total);
    }
    public function sendSingleResponse($result, $message = "", $total = null)
    {
        return ResponseMessageUtils::sendSingleResponse($result, $message, $total);
    }
    public function sendMergedResponse($result, $message = "", $total = null)
    {
        return ResponseMessageUtils::sendMergedResponse($result, $message, $total);
    }

    public function sendError($error, $code = 200, $success = 0)
    {
        return ResponseMessageUtils::sendError($error, $code, $success);
    }

    public function sendRefreshToken($data, $error, $code = 200, $success = 0)
    {
        return ResponseMessageUtils::sendRefreshToken($data, $error, $code, $success);
    }

    public function generateRandomString()
    {
        return MessageUtils::generateRandomString();
    }

    public function setStartupMeData($user_id) {
        $startupConfig = config('global.startup_data');
        $data = $startupConfig['me_data'];
        foreach ($data as $main_key => $main_value) {
            foreach ($data[$main_key] as $inter_key => $inter_value) {
                if(!is_array($inter_value)) {
                    if(!is_bool($inter_value) && $inter_value == ':based_on_login') {
                        if($user_id == 0) {
                            $data[$main_key][$inter_key] = false;
                        } else {
                            $data[$main_key][$inter_key] = true;
                        }
                    }
                    if(!is_bool($inter_value) && $inter_value == ':based_on_logout') {
                        if($user_id == 0) {
                            $data[$main_key][$inter_key] = true;
                        } else {
                            $data[$main_key][$inter_key] = false;
                        }
                    }
                } else {
                    foreach ($data[$main_key][$inter_key] as $key => $value) {
                        if(!is_bool($value) && $value == ':based_on_login') {
                            if($user_id == 0) {
                                $data[$main_key][$inter_key][$key] = false;
                            } else {
                                $data[$main_key][$inter_key][$key] = true;
                            }
                        }
                        if(!is_bool($value) && $value == ':based_on_logout') {
                            if($user_id == 0) {
                                $data[$main_key][$inter_key][$key] = true;
                            } else {
                                $data[$main_key][$inter_key][$key] = false;
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function sendOTPMessage($otp, $phone) {

        $smsUrl = config('global.sms_url');
        $smsTemplate = config('global.sms_template');
        $apikey = config('global.sms_api_key');
        $username = config('global.sms_username');
        $template_id = config('global.sms_template_id');
        $sender_id = config('global.sms_sender_id');
        $route = config('global.sms_route');

        if (empty($smsUrl) || empty($smsTemplate) || empty($apikey) || empty($username) || empty($template_id) || empty($sender_id) || empty($route)) {
            return false;
        }

        $smsTemplate = str_replace('{#var1#}', $otp, $smsTemplate);
        $smsTemplate = str_replace('{#var2#}', $smsUrl, $smsTemplate);

        $data = array(
            'username'=> $username,
            'apikey'=> $apikey,
            'apirequest'=>'Text',
            'sender'=> $sender_id,
            'route'=> $route,
            'format'=>'JSON',
            'message'=> $smsTemplate,
            'mobile'=> $phone,
            'TemplateID' => $template_id,
        );

        $uri = 'http://smsao.eweb.co.in/sms-panel/api/http/index.php';

        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);

        $resp = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        return $resp;

    }

}
