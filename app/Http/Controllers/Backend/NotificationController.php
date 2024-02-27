<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddAudioRequest;
use App\Http\Requests\AddNotificationRequest;
use App\Http\Requests\SendNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Models\Notification;
use App\Models\User;
use App\Models\CourseCategoryTranslation;
use App\Models\CourseTranslation;
use App\Utils\Utils;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\DataTables;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function index()
    {
        $data['notification_add'] = checkPermission('notification_add');
        $data['notification_edit'] = checkPermission('notification_edit');
        $data['notification_view'] = checkPermission('notification_view');
        $data['notification_send'] = checkPermission('notification_send');

        return view('backend/notification/index',["data"=>$data]);
    }

    /**
     * Fetch a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Notification::orderBy('updated_at','desc');

                return DataTables::of($query)
                    ->filter(function ($query) use ($request) {
                        if (isset($request['search']['search_title']) && !is_null($request['search']['search_title'])) {
                            $query->where('title', 'like', "%" . $request['search']['search_title'] . "%");
                        }
                        $query->get();
                    })
                    ->editColumn('title', function ($event) {
                        return $event->title;
                    })
                    ->editColumn('last_sent', function ($event) {
                        return !empty($event->last_sent) ? Carbon::parse($event->last_sent)->format('d-M-Y h:i A') : '-';

                    })
                    ->editColumn('action', function ($event) {
                        $notification_view = checkPermission('notification_view');
                        $notification_edit = checkPermission('notification_edit');
                        $notification_send = checkPermission('notification_send');
                        $actions = '<span style="white-space:nowrap;">';
                        if ($notification_view) {
                            $actions .= '<a href="notification/view/' . $event['id'] . '" class="btn btn-primary btn-sm src_data" data-size="large" data-title="View Category Details" title="View"><i class="fa fa-eye"></i></a>';
                        }
                        if ($notification_edit) {
                            $actions .= ' <a href="notification/edit/' . $event['id'] . '" class="btn btn-success btn-sm src_data" title="Update"><i class="fa fa-edit"></i></a>';
                        }
                        if ($notification_send) {
                            $actions .= ' <a href="notification/send/' . $event['id'] . '" class="btn btn-secondary btn-sm src_data" title="Send Notification"><i class="fa fa-send"></i></a>';
                        }
                        $actions .= '</span>';
                        return $actions;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['title','last_sent','action'])->setRowId('id')->make(true);
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
     * @return Application|Factory|View|\Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['notificationsType'] = Notification::NOTIFICATION_TYPE;

        return view('backend/notification/add')->with($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  AddNotificationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddNotificationRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $input['created_by'] = session('data')['id'] ?? 0;
            $notification = Notification::create($input);
            //store audio cover image
            if (! empty($input['image']) && $notification) {
                storeMedia($notification, $input['image'], Notification::NOTIFICATION_IMAGE);
            }
            DB::commit();
            successMessage('Data Saved successfully', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: ".$e->getMessage());

            errorMessage("Something Went Wrong.");
        }
    }

    public function getMastersListing($type)
    {
        $data = [];
        if ($type == Notification::CATEGORY){
            $data['category'] = CourseCategoryTranslation::where('locale', 'en')->get();
        }
        if ($type == Notification::COURSE){
            $data['course'] = CourseTranslation::where('locale', 'en')->get();
        }
        if ($type == Notification::COLLECTION) {

            $data['collection'] = Collection::all();
        }
        // print_r($data);exit;
        return Response::json($data);
    }

    public function edit($id)
    {
        $data['notification'] = Notification::find($id);
        $data['notificationsType'] = Notification::NOTIFICATION_TYPE;
        $data['images'] = $data['notification']->getMedia(Notification::NOTIFICATION_IMAGE);
        $type = $data['notification']->type;
        $selectedData = $this->getMastersListing($type)->getOriginalContent();
        if ($type == Notification::CATEGORY){
            // print_r($data['category']);exit;
            $data['category'] =  $selectedData['category'] ?? [];
        }
        if ($type == Notification::COURSE){
            $data['course'] =  $selectedData['course'] ?? [];
        }
        if ($type == Notification::COLLECTION) {
            $data['collection'] =  $selectedData['collection'] ?? [];
        }
        return view('backend/notification/edit')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UpdateNotificationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNotificationRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $input['updated_by'] = session('data')['id'] ?? 0;
            $notification = Notification::find($input['id']);
            if (! empty($notification)) {
                $notification->update($input);
                //store audio cover image
                if (! empty($input['image']) && $notification) {
                    $notification->clearMediaCollection(Notification::NOTIFICATION_IMAGE);
                    storeMedia($notification, $input['image'], Notification::NOTIFICATION_IMAGE);
                }
            }
            DB::commit();
            successMessage('Data Saved successfully', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: ".$e->getMessage());

            errorMessage("Something Went Wrong.");
        }
    }

    public function sendNotification($id)
    {
        $data['notification'] = Notification::find($id);
        $data['users'] = User::whereHas('userDevices', function ($query) {
            $query->where('fcm_id', '!=', null);
        })
            ->where('fcm_notification', '1')
            ->where('status', '1')
            ->get();
        $data['images'] = $data['notification']->getMedia(Notification::NOTIFICATION_IMAGE);
       $data['currentHour'] = Carbon::now()->hour;

        return view('backend/notification/send_notification')->with($data);
    }

    public function sendUserNotification(SendNotificationRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $notification = Notification::find($input['notification_id']);
            if (empty($notification)){
                errorMessage("Notification Not Found.");
            }
            $data['notification'] = $notification;
            $data['user_ids'] = $input['user_ids'] ?? [];
            $data['trigger_date'] = !empty($input['date_time']) ? Carbon::parse($input['date_time'])->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $data['trigger_time'] = !empty($input['trigger_time']) ? $input['trigger_time'] : '0';
            $data['trigger_date'] = Carbon::parse("{$data['trigger_date']} {$data['trigger_time']}:00:00")->format('Y-m-d H:i:s');
            $batchCount = $notification->batch_count + 1;
            $notification->update([
                'last_sent' => Carbon::now(),
                'batch_count' => $batchCount
            ]);
            Log::error("start job");
            dispatch(new \App\Jobs\SendQueueNotification($data));
            Log::error("end job");

            DB::commit();
            successMessage('Data Saved successfully', []);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Something Went Wrong. Error: ".$e->getMessage());

            errorMessage("Something Went Wrong.");
        }
    }

    public function show($id)
    {
        $data['notification'] = Notification::find($id);
        $img = Notification::NOTIFICATION_IMAGE;
        $notify = $data['notification'];
        $media = getMedia($img, $notify);
        if($media) {
            $data['media'] = $media;
        }
        return view('backend/notification/view')->with($data);
    }
}
