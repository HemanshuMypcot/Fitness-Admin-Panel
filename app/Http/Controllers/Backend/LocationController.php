<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use App\Models\Location;
use App\Models\LocationTranslation;
use App\Utils\Utils;
use App\Http\Requests\AddLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['location_add'] = checkPermission('location_add');
        $data['location_edit'] = checkPermission('location_edit');
        $data['location_view'] = checkPermission('location_view');
        $data['location_status'] = checkPermission('location_status');
        $data['location_delete'] = checkPermission('location_delete');
        return view('backend/location/index',['data' =>$data]);
    }

    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Location::leftJoin('location_translations', function ($join) {
                                        $join->on("location_translations.location_id", '=', "locations.id");
                                        $join->where('location_translations.locale', \App::getLocale());
                                     })
                                     ->select('locations.*')
                                     ->orderBy('updated_at','desc');
                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_name']) && !is_null($request['search']['search_name'])) {
                            $query->where('location_translations.name', 'like', "%" . $request['search']['search_name'] . "%");
                        }
                        if (isset($request['search']['search_title']) && !is_null($request['search']['search_title'])) {
                            $query->where('location_translations.title', 'like', "%" . $request['search']['search_title'] . "%");
                        }
                        if (isset($request['search']['search_google_address']) && !is_null($request['search']['search_google_address'])) {
                            $query->where('google_address', 'like', "%" . $request['search']['search_google_address'] . "%");
                        }
                        if (isset($request['search']['search_status']) && !is_null($request['search']['search_status'])) {
                            $query->where('status', $request['search']['search_status']);
                        }
                        if (isset($request['search']['search_type']) && !is_null($request['search']['search_type'])) {
                            $query->where('type', $request['search']['search_type']);
                        }
                        $query->get()->toArray();
                    })
                    ->editColumn('name_'.\App::getLocale(), function ($event) {
                        $Key_index = array_search(\App::getLocale(), array_column($event->translations->toArray(), 'locale'));
                        return $event['translations'][$Key_index]['name'];
                    })->editColumn('title_'.\App::getLocale(), function ($event) {
                        $Key_index = array_search(\App::getLocale(), array_column($event->translations->toArray(), 'locale'));
                        return $event['translations'][$Key_index]['title'];
                    })->editColumn('google_address', function ($event) {
                        return $event['google_address'];
                    })
                    ->editColumn('action', function ($event) {
                        $location_edit = checkPermission('location_edit');
                        $location_view = checkPermission('location_view');
                        $location_status = checkPermission('location_status');
                        $location_delete = checkPermission('location_delete');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($location_view) {
                            $actions .= '<a href="location/view/' . $event['id'] . '" class="btn btn-primary btn-sm src_data" data-size="large" data-title="View location Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($location_edit) {
                            $actions .= ' <a href="location/edit/' . $event['id'] . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($location_status) {
                            if ($event->status == '1') {
                                $actions .= ' <input type="checkbox" data-url="location/publish" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery" checked>';
                            } else {
                                $actions .= ' <input type="checkbox" data-url="location/publish" id="switchery' . $event->id . '" data-id="' . $event->id . '" class="js-switch switchery">';
                            }
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['name_'.\App::getLocale(), 'title_'.\App::getLocale(),'location','status', 'action'])->setRowId('id')->make(true);
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
        $data['translated_block'] = Location::TRANSLATED_BLOCK;

        return view('backend/location/add',$data);
    }

    public function updateStatus(Request $request)
    {
        try {
            DB::beginTransaction();

            $msg_data = array();
            $input = $request->all();
            $input['updated_by'] = session('data')['id'] ?? 0;
            $location = Location::where('id',$input['id'])->first();

            if ($location->exists()) {
                $location->update($input);
                DB::commit();
                if ($request->status == 1) {
                    successMessage(trans('message.enable'), $msg_data);
                } else {
                    errorMessage(trans('message.disable'), $msg_data);
                }
            }
            errorMessage('Location not found', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: " . $e->getMessage());
            errorMessage(trans('auth.something_went_wrong'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddLocationRequest $request)
    {
        try{
            DB::beginTransaction();
            $input = $request->all();
            $input['created_by'] = session('data')['id'] ?? 0;
            $translated_keys = array_keys(Location::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value) {
                $input[$value] = (array) json_decode($input[$value] ?? '');
            }

            $saveArray = Utils::flipTranslationArray($input, $translated_keys);
            $data = Location::create($saveArray);
            // storeMedia($data, $input['image'], Location::IMAGE);
            DB::commit();

            successMessage('Data Saved successfully', []);
        }catch(\Exception $e)
        {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: ".$e->getMessage());

            errorMessage("Something Went Wrong.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\location  $location
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['location'] = Location::find($id);
        foreach($data['location']['translations'] as $trans) {
            $translated_keys = array_keys(Location::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value) {
                $data['location'][$value.'_'.$trans['locale']] = $trans[$value];
            }
        }
        $data['translated_block'] = Location::TRANSLATED_BLOCK;
        return view('backend/location/view',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['location'] = Location::find($id);
        $working_days_data = json_decode($data['location']['working_days'],true);
        $data['working_days'] = $working_days_data;
        foreach($data['location']['translations'] as $trans) {
            $translated_keys = array_keys(Location::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value) {
                $data['location'][$value.'_'.$trans['locale']] = str_replace("<br/>", "\r\n", $trans[$value]);
            }
        }
        $data['translated_block'] = Location::TRANSLATED_BLOCK;
        return view('backend/location/edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLocationRequest $request)
    {
        try{
            DB::beginTransaction();
            $data = Location::find($_GET['id']);
            $input=$request->all();
            $data['updated_by'] = session('data')['id'] ?? 0;

            if (!$data) {
                errorMessage('Location Not Found', []);
            }
            $translated_keys = array_keys(Location::TRANSLATED_BLOCK);
            foreach ($translated_keys as $value)
            {
                $input[$value] = (array) json_decode($input[$value] ?? '');
            }

            $location = Utils::flipTranslationArray($input, $translated_keys);
            $data->update($location);
            DB::commit();

            successMessage('Data Saved successfully', []);
        }catch(\Exception $e)
        {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: ".$e->getMessage());

            errorMessage("Something Went Wrong.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        //
    }

    public function deleteImage(Request $request)
    {
        $msg_data = array();
        $data = Location::find($_GET['id']);
        $data->clearMediaCollection(Location::IMAGE);
        successMessage('image deleted successfully', $msg_data);
    }
}
