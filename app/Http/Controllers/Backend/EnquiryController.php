<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;


class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['contact_view'] = checkPermission('contact_view');
        return view('backend/enquiry/index',['data' =>$data]);
    }

    public function fetch(Request $request)
        {
            if ($request->ajax()) {
                try {
                    $query = Contact::orderBy('updated_at','desc');
                    return DataTables::of($query)
                        ->filter(function ($query) use ($request) {
                            if (isset($request['search']['search_name']) && !is_null($request['search']['search_name'])) {
                                $query->where('name', 'like', "%" . $request['search']['search_name'] . "%");
                            }
                            if (isset($request['search']['search_email']) && !is_null($request['search']['search_email'])) {
                                $query->where('email', 'like', "%" . $request['search']['search_email'] . "%");
                            }
                            $query->get()->toArray();
                        })->editColumn('name',function ($event) {
                            return $event['name'];
                        })->editColumn('email',function ($event) {
                            return $event['email'];
                        })->editColumn('created_at',function ($event) {
                            return date('d/m/Y', strtotime($event['created_at']->toDateString()));
                        })
                        ->editColumn('action', function ($event) {
                            $contact_view = checkPermission('contact_view');
                            $actions = '<span style="white-space:nowrap;">';
                            if ($contact_view) {
                                $actions .= '<a href="enquiries/view/' . $event['id'] . '" class="btn btn-primary btn-sm src_data" data-size="large" data-title="View Contact Details" title="View"><i class="fa fa-eye"></i></a>';
                            }
                            $actions .= '</span>';
                            return $actions;
                        })
                        ->addIndexColumn()
                        ->rawColumns(['name','email','created_at', 'action'])->setRowId('id')->make(true);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['contact'] = Contact::find($id);
        return view('backend/enquiry/view',$data);
    }
}
