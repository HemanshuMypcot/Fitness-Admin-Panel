<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateHomeCollectionRequest;
use App\Http\Requests\UpdateHomeCollectionRequest;
use App\Models\Course;
use App\Models\HomeCollection;
use App\Models\HomeCollectionMapping;
use App\Utils\Utils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Ramsey\Collection\Collection;
use Yajra\DataTables\DataTables;

class HomeCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['home_collection_add'] = checkPermission('home_collection_add');
        $data['home_collection_view'] = checkPermission('home_collection_view');
        $data['home_collection_edit'] = checkPermission('home_collection_edit');
        $data['home_collection_status'] = checkPermission('home_collection_status');
        $data['collection_types'] = HomeCollection::COLLECTION_TYPES;

        return view('backend/home_collection/index', ["data" => $data]);
    }


    public function fetch(Request $request)
    {
        $localeLanguage = \App::getLocale();

        if ($request->ajax()) {
            try {
                $query = HomeCollection::select('*')->orderBy('updated_at','desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request , $localeLanguage) {
                        if (isset($request['search']['search_collection_title']) && !is_null($request['search']['search_collection_title'])) {
                            $query->whereHas('translations', function ($translationQuery) use ($request ,$localeLanguage) {
                                $translationQuery->where('locale',$localeLanguage)->where('title', 'like', "%" . $request['search']['search_collection_title'] . "%");
                            });
                        }
                        if (isset($request['search']['search_collection_type']) && !is_null($request['search']['search_collection_type'])) {
                            $query->where('type',$request['search']['search_collection_type']);
                        }
                        if (isset($request['search']['search_status']) && !is_null($request['search']['search_status'])) {
                            $query->where('status', 'like', "%" . $request['search']['search_status'] . "%");
                        }
                        if (isset($request['search']['search_sequence']) && !is_null($request['search']['search_sequence'])) {
                            $query->where('sequence', 'like', "%" . $request['search']['search_sequence'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('title', function ($event) {
                        return $event->title ?? '';

                    })
                    ->editColumn('type', function ($event) {

                        return HomeCollection::COLLECTION_TYPES[$event->type] ?? '';
                    })
                    ->editColumn('sequence', function ($event) {

                        return $event->sequence ?? '';
                    })
                    ->editColumn('action', function ($event) {
                        $home_collection_edit = checkPermission('home_collection_edit');
                        $home_collection_view = checkPermission('home_collection_view');
                        $home_collection_status = checkPermission('home_collection_status');
                        $home_collection_delete = checkPermission('home_collection_delete');
                        $is_head = session('data')['is_head'] ?? false;
                        $actions = '<span style="white-space:nowrap;">';
                        if ($home_collection_view) {
                            $actions .= '<a href="home_collection/view/' . $event['id'] . '" class="btn btn-primary btn-sm src_data" data-size="large" data-title="View Book Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($home_collection_edit) {
                            $actions .= ' <a href="home_collection/edit/' . $event['id'] . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($home_collection_delete && $is_head) {
                            $dataUrl = $event->title ?? '';
                            $actions .= ' <a data-option="'.$dataUrl.'" data-url="home_collection/delete/'.$event->id.'" class="btn btn-danger btn-sm delete-data" title="delete"><i class="fa fa-trash"></i></a>';
                        }
                        if ($home_collection_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="home_collection/publish" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="home_collection/publish" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['title','sequence', 'action'])->setRowId('id')->make(true);
            } catch (\Exception $e) {
                \Log::error("Something Went Wrong. Error: " . $e->getMessage());
                return response([
                    'draw'            => 0,
                    'recordsTotal'    => 0,
                    'recordsFiltered' => 0,
                    'data'            => [],
                    'error'           => 'Something went wrong',
                ]);
            }
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['translated_block'] = HomeCollection::TRANSLATED_BLOCK;
        $data['collection_types'] = HomeCollection::COLLECTION_TYPES;
        $data['mappingCollectionType'] = HomeCollectionMapping::MAPPING_COLLECTION_TYPES;

        return view('backend/home_collection/add',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateHomeCollectionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateHomeCollectionRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $input['updated_by'] = session('data')['id'] ?? 0;
            $input['type'] = $input['collection_type'];
            $input['is_scrollable'] = '1';
            $input['created_by'] = session('data')['id'] ?? 0;
            $input['display_in_column'] = 1;
            $translated_keys = array_keys(HomeCollection::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value) {
                $input[$value] = (array) json_decode($input[$value]);
            }
            $saveArray = Utils::flipTranslationArray($input, $translated_keys);
            $collection = HomeCollection::create($saveArray);
            if (! empty($collection) && $input['type'] == HomeCollection::SINGLE && ! empty($input['single_image'])) {
                storeMedia($collection, $input['single_image'], HomeCollection::SINGLE_COLLECTION_IMAGE);
            }
            if (! empty($collection) && $input['type'] == HomeCollection::COURSE) {
                $mappedIds =  $input['time_start']." - ".$input['time_end'];
                $homeCollectionDetails = [
                    'home_collection_id' => $collection->id,
                    'mapped_ids'         => $mappedIds,
                    'sequence'           => 1,
                    'mapped_to'          => $input['type'],
                ];
                HomeCollectionMapping::create($homeCollectionDetails);
            }
            DB::commit();

            successMessage('Data Saved successfully', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: ".$e->getMessage());

            errorMessage("Something Went Wrong.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['collection'] = HomeCollection::find($id);
        $data['singleImage'] = $data['collection']->getMedia(HomeCollection::SINGLE_COLLECTION_IMAGE)->first();

        return view('backend/home_collection/view')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (empty($id)) {

            return redirect()->back();
        }
        $collection = HomeCollection::find($id);
        $collectionDetails = HomeCollectionMapping::where('home_collection_id', $collection->id)->first();
        $data['translated_block'] = HomeCollection::TRANSLATED_BLOCK;
        $data['collection_types'] = HomeCollection::COLLECTION_TYPES;
        $data['collectionDetails'] = $collectionDetails;
       $data['singleImage'] = $collection->getMedia(HomeCollection::SINGLE_COLLECTION_IMAGE);
        $data['mappingCollectionType'] = HomeCollectionMapping::MAPPING_COLLECTION_TYPES;
        $data['multipleCollectionData'] = [];
        $data['collection'] = $collection->toArray();
        foreach($data['collection']['translations'] as $trans) {
            $translated_keys = array_keys(HomeCollection::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value) {
                $data['collection'][$value.'_'.$trans['locale']] = str_replace("<br/>", "\r\n", $trans[$value]);
            }
        }

        return view('backend/home_collection/edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateHomeCollectionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHomeCollectionRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $input['type'] = $input['collection_type'];
            $input['is_scrollable'] = '1';
            $input['updated_by'] = session('data')['id'] ?? 0;
            $collection = HomeCollection::find($input['id']);
            if (!empty($collection)){
                $translated_keys = array_keys(HomeCollection::TRANSLATED_BLOCK);
                foreach ($translated_keys as $value) {
                    $input[$value] = (array) json_decode($input[$value]);
                }
                $saveArray = Utils::flipTranslationArray($input, $translated_keys);
                $collection->update($saveArray);
            }
            if (! empty($collection) && $input['type'] == HomeCollection::SINGLE && ! empty($input['single_image'])) {
                $collection->clearMediaCollection(HomeCollection::SINGLE_COLLECTION_IMAGE);
                storeMedia($collection, $input['single_image'], HomeCollection::SINGLE_COLLECTION_IMAGE);
            }
            $detail = HomeCollectionMapping::where('home_collection_id', $collection->id)->first();
            if (! empty($collection) && $input['type'] == HomeCollection::COURSE) {
                $mappedIds =  $input['time_start']." - ".$input['time_end'];
                $homeCollectionDetails = [
                    'mapped_ids' => $mappedIds,
                ];
                if ($detail) {
                    $detail->update($homeCollectionDetails);
                }
            }
            DB::commit();
            successMessage('Data Update successfully', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: ".$e->getMessage());

            errorMessage("Something Went Wrong.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $collection = HomeCollection::find($id);
            if (!empty($collection)){
                $collection->delete();

                DB::commit();
                successMessage('Collection Deleted successfully', []);
            }

            errorMessage('Collection not found', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: " . $e->getMessage());

            errorMessage("Something Went Wrong.");
        }
    }

    /**
     * @param  Request  $request
     *
     */
    public function updateStatus(Request $request)
    {
        try {
            DB::beginTransaction();

            $msg_data = array();
            $input = $request->all();
            $input['updated_by'] = session('data')['id'] ?? 0;

            $homeCollection = HomeCollection::find($input['id']);
            if ($homeCollection->exists()) {
                $homeCollection->update($input);
                DB::commit();
                if ($request->status == 1) {
                    successMessage(trans('message.enable'), $msg_data);
                } else {
                    errorMessage(trans('message.disable'), $msg_data);
                }
            }

            errorMessage('Home Collection not found', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: " . $e->getMessage());

            errorMessage("Something Went Wrong");
        }
    }

    public function getMappedListing($type)
    {
        $localeLanguage = \App::getLocale();

        $data = [];
        if ($type == HomeCollectionMapping::COURSE){
            $data['course'] = Course::with([
                'translations' => function ($query) use ($localeLanguage) {
                    $query->where('locale', $localeLanguage);
                }])->get();
        }
        return Response::json($data);
    }
}
