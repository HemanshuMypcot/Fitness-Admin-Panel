<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Http\Requests\ManageGeneralSettingRequest;

use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['data'] = GeneralSetting::pluck('value', 'type')->toArray();
        return view('backend/general_setting/general_settings',$data);
    }

    public function updateSetting(ManageGeneralSettingRequest $request)
    {
        $param = $_GET['param'];
        $msg_data = array();
        $msg = "Data Saved Successfully";
        if (isset($param) && !empty($param)) {
            try {
                switch ($param) {
                    case 'general':
                        GeneralSetting::where("type", 'system_email')->update(["value" => $request->system_email]);
                        GeneralSetting::where("type", 'system_contact_no')->update(["value" => $request->system_contact_no]);
                        GeneralSetting::where("type", 'meta_title')->update(["value" => $request->meta_title]);
                        GeneralSetting::where("type", 'system_contact_no')->update(["value" => $request->system_contact_no]);
                        GeneralSetting::where("type", 'meta_keywords')->update(["value" => $request->meta_keywords]);
                        GeneralSetting::where("type", 'meta_description')->update(["value" => $request->meta_description]);
                        break;
                    case 'aboutus':
						if (empty($request->editiorData)) {
							errorMessage('AboutUs cannot be empty', $msg_data);
						}
                        GeneralSetting::where("type", 'about_us')->update(["value" => $request->editiorData]);
                        break;
                    case 'tnc':
						if (empty($request->editiorData)) {
							errorMessage('Terms and Condition cannot be empty', $msg_data);
						}
                        GeneralSetting::where("type", 'terms_and_condition')->update(["value" => $request->editiorData]);
                        break;
                    case 'privacy':
						if (empty($request->editiorData)) {
							errorMessage('Privacy cannot be empty', $msg_data);
						}
                        GeneralSetting::where("type", 'privacy_policy')->update(["value" => $request->editiorData]);
                        break;
                    case 'social':
                        GeneralSetting::where("type", 'fb_link')->update(["value" => strip_tags($request->fb_link)]);
                        GeneralSetting::where("type", 'insta_link')->update(["value" => strip_tags($request->insta_link)]);
                        GeneralSetting::where("type", 'twitter_link')->update(["value" => strip_tags($request->twitter_link)]);
                        break;
                    case 'appLink':
                        GeneralSetting::where("type", 'android_url')->update(["value" => strip_tags($request->android_url)]);
                        GeneralSetting::where("type", 'ios_url')->update(["value" => strip_tags($request->ios_url)]);
                        break;
                    case 'appVersion':
                        GeneralSetting::where("type", 'android_version')->update(["value" => strip_tags($request->android_version)]);
                        GeneralSetting::where("type", 'ios_version')->update(["value" => strip_tags($request->ios_version)]);
                        break;
                    default:
                        throw new \Exception("Invalid Paramter passed");
                }
                successMessage($msg, $msg_data);
                //return redirect('webadmin/generalSetting');
                //return redirect()->back()->withErrors(array("msg"=>$msg));
            } catch (\Exception $e) {
                \Log::error("General Setting Submit. Error: " . $e->getMessage());
                errorMessage('Something Went Wrong', $msg_data);
            }
        } else {
            errorMessage('Something Went Wrong', $msg_data);
        }
    }
}
