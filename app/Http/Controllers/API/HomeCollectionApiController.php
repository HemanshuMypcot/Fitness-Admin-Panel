<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\HomeCollectionResource;
use App\Models\HomeCollection;
use App\Repositories\webservices\HomeCollectionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeCollectionApiController extends AppBaseController
{
     /** @var  HomeCollectionRepository */
    private $homeCollectionRepository;

    public function __construct(HomeCollectionRepository $homeCollectionRepo)
    {
        $this->homeCollectionRepository = $homeCollectionRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $input = $request->all();
            $msg = trans('auth.data_fetched');
            $homeCollection = $this->homeCollectionRepository->getByRequest($input);
            // Added by = Aakanksha ---> as requested by app team
            $categoryData = [
                "id" => 2,
                "type" => "Category",
                "sequence" => 2
            ];
            $homeCollection['result'][] = $categoryData;
            if(count($homeCollection['result']) == 0) {
                $msg = trans('auth.collection_not_found');
            }else{
                 // Added by = Aakanksha ---> as requested by app team
                array_multisort(array_column($homeCollection['result'], 'sequence'), SORT_ASC, $homeCollection['result']);
            }
            return $this->sendResponse(HomeCollectionResource::collection($homeCollection['result']), $msg, $homeCollection['total_count']);
        } catch(\Exception $e) {
            Log::info(json_encode($e->getMessage()));

            return $this->sendError(trans('auth.something_went_wrong'),500);
        }
    }

}
