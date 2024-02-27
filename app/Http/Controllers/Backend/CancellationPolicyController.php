<?php

namespace App\Http\Controllers\Backend;
use App\Models\Policy;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Utils\Utils;

use Illuminate\Http\Request;
class CancellationPolicyController extends Controller
{
    public function index()
    {
        $data['data'] = Policy::where('type', 'cancellation_policy')->first();
        $data['cancellation_policy_view'] = checkPermission('cancellation_policy_view');
        $data['cancellation_policy_edit'] = checkPermission('cancellation_policy_edit');
        foreach($data['data']['translations'] as $trans) {
            $translated_keys = array_keys(Policy::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value) {
                $data['data'][$value.'_'.$trans['locale']] = $trans[$value];
            }
        }
        $data['translated_block'] = Policy::TRANSLATED_BLOCK;
        return view('backend/general_setting/cancellation_policy',$data);
    }

    public function update(Request $request)
    {
        try{
            DB::beginTransaction();
            $data = Policy::where('type', 'cancellation_policy')->first();
            $input=$request->all();
            $input['updated_by'] = session('data')['id'] ?? 0;
            $translated_keys = array_keys(Policy::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value)
            {
                $input[$value] = (array) json_decode($input[$value]);
            }
            $saveArray = Utils::flipTranslationArray($input, $translated_keys);
            $data->update($saveArray);
            DB::commit();
            successMessage('Data Saved successfully', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: " . $e->getMessage());
            errorMessage(trans('auth.something_went_wrong'));
        }
    }
}
