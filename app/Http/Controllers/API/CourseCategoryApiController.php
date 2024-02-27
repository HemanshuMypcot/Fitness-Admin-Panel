<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseCategory;
use App\Http\Requests\Api_requests\ListCourseCategoryApiRequest;
use App\Repositories\webservices\CourseCategoryRepository;
use App\Http\Resources\CourseCategoryResource;

class CourseCategoryApiController extends AppBaseController
{
    /** @var  CourseCategoryRepository */
    private $categoryRepository;
    public function __construct(CourseCategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListCourseCategoryApiRequest $request)
    {
        try {
            $input = $request->all();
            $msg = trans('auth.data_fetched');
            $category = $this->categoryRepository->getByRequest($input);
            if(count($category['result']) == 0){
                $msg = trans('auth.course_category_empty');
            }
            return $this->sendResponse(CourseCategoryResource::collection($category['result']), $msg,$category['total_count']);

        } catch(\Exception $e) {
            \Log::info(json_encode($e->getMessage()));

            return $this->sendError(trans('auth.something_went_wrong'),500);
        }
    }
}
