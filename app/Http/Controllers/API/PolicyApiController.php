<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PolicyResource;
use App\Models\GeneralSetting;
use App\Repositories\webservices\PolicyRepository;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api_requests\PolicyApiRequest;
use Illuminate\Http\Request;

class PolicyApiController extends AppBaseController
{
    /** @var  PolicyRepository */
    private $policyRepository;

    public function __construct(PolicyRepository $PolicyRepo)
    {
        $this->policyRepository = $PolicyRepo;
    }
    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function show(PolicyApiRequest $request)
    {
        try {
            $type = $request->type;
            $policy = $this->policyRepository->findWithoutFail($type);
            if($type == 'about'){
                $policy['about_us_image'] = config('global.static_base_url').'/backend/static_images/about_us_image.png';
            }
            return $this->sendResponse(new PolicyResource($policy));
        } catch (\Exception $e) {
            \Log::info(json_encode($e->getMessage()));

            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }

}
