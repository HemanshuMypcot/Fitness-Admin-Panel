<?php
use Spatie\MediaLibrary\MediaCollections\Models\Media;



if(!function_exists('formatName')){
    function formatName($unformatted_name = '',$reverse_format = false)
    {
        $formatted_name = '';

        if (!empty($unformatted_name)) {
            if($reverse_format){
                $formatted_name = str_replace('-', '_', $unformatted_name);
            }else{
                $formatted_name = str_replace('-', ' ', $unformatted_name);
            }
        }

        return $formatted_name;
    }
}
if (!function_exists('errorMessage')) {
    function errorMessage($msg = '', $data = array(), $expireSessionCode = "", $warning_status = '')
    {
        $return_array = array();
        $return_array['success'] = '0';
        if($warning_status != ''){
            $return_array['success'] = '2';
        }
        if ($expireSessionCode != "") {
            $return_array['success'] = $expireSessionCode;
        }
        $return_array['message'] = $msg;
        if (isset($data) && count($data) > 0)
            $return_array['data'] = $data;
        if (isset($other_data) && !empty($other_data)) {
            foreach ($other_data as $key => $val)
                $return_array[$key] = $val;
        }
        echo json_encode($return_array);
        exit();
    }
}

if (!function_exists('successMessage')) {
    function successMessage($msg = '', $data = array())
    {
        $return_array = array();
        $return_array['success'] = '1';
        $return_array['message'] = $msg;
        if (isset($data) && count($data) > 0)
            $return_array['data'] = $data;
        if (isset($other_data) && !empty($other_data)) {
            foreach ($other_data as $key => $val)
                $return_array[$key] = $val;
        }
        echo json_encode($return_array);
        exit();
    }
}

if (!function_exists('generateRandomOTP')) {
    function generateRandomOTP()
    {
        // return (rand(1000,9999));
        return (1234);
    }
}

if (!function_exists('displayStatus')) {
    function displayStatus($displayValue = "")
    {
        return $displayValue ? 'Active' : 'In-Active';
    }
}

if (!function_exists('checkPermission')) {
    function checkPermission($name)
    {
        if (session('data')['role_id'] == 1) {
            if(strpos($name, 'delete') !== false && session('data')['is_head'] == false) {
                return false;
            }
            return true;
        }
        $permissions = Session::get('permissions');
        $permission_array = array();
        for ($i = 0; $i < count($permissions); $i++) {
            $permission_array[$i] = $permissions[$i]->codename;
        }
        if (in_array($name, $permission_array)) {
            return true;
        } else {
            return false;
        }
    }
}

if (! function_exists('storeMedia')) {

    function storeMedia($model, $file, $collectionName)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = time().'.'.$extension;

        $model->addMedia($file)->usingFileName($fileName)->toMediaCollection($collectionName, config('app.media_disc'));
    }
}
if (! function_exists('getMedia')) {

    function getMedia($image, $obj)
    {
        if (!empty($obj->getMedia($image))) {
            $media = $obj->getMedia($image);
            if (isset($media[0])) {
                return $media[0]->getFullUrl();
            }
            return false;
        }
    }
}

if (! function_exists('getMediaArray')) {

    function getMediaArray($image, $obj)
    {
        if (!empty($obj->getMedia($image))) {
            $media = $obj->getMedia($image);
            if (isset($media[0])) {
                return $media[0];
            }
            return false;
        }
    }
}

if (! function_exists('clearMediaCollection')) {

    function clearMediaCollection($data, $collectionName)
    {
        $data->clearMediaCollection($collectionName);
    }
}



/**
 *   created by : Arjun Singh
 *   Created On : 14-Sept-2023
 *   Uses : To display globally records deleted or not
 *   @param $key
 *   @return Response
 */
if (!function_exists('isRecordDeleted')) {
    function isRecordDeleted($value = NULL)
    {
        if ($value == NULL) {
            $isDeleted = false;
        } else {
            $isDeleted = true;
        }
        return $isDeleted;
    }
}

/**
 *   created by : Arjun Singh
 *   Created On : 14-Sept-2023
 *   Uses : To display globally records deleted or not
 * @param $key
 * @return Response
 */
if (! function_exists('covertDurationTime')) {
    function covertDurationTime($seconds)
    {
        if ($seconds >= 3600) {
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            $durations = "$hours hours ".($minutes != 0 ? "$minutes mins" : '');
        } else {
            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;
            $durations = ($minutes != 0 ? "$minutes mins " : '').($remainingSeconds != 0 ? "$remainingSeconds sec" : '');
        }

        return $durations;
    }
}

//for applying max length on translable name
function translationMaxLengthValidation($jsonInput, $maxLength)
{
    $inputText = json_decode($jsonInput, true);
    $isMaxLength = false;
    foreach ($inputText as $text) {
        if (mb_strlen($text) > $maxLength) {
            $isMaxLength = true;
        }
    }

    return $isMaxLength;
}
