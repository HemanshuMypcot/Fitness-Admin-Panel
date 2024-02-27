<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use App\Http\Resources\ContactResource;
use App\Repositories\webservices\ContactRepository;
use App\Models\Contact;
use App\Http\Requests\Api_requests\CreateContactApiRequest;

class ContactApiController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $contactRepository;

    public function __construct(ContactRepository $contactRepo)
    {
        $this->contactRepository = $contactRepo;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateContactApiRequest $request)
    {
        try {
            $input = $request->all();
            $contact = $this->contactRepository->create($input);
            // if (config('global.mail_send')== true) {
            //     $this->contactMail($contact);              
            // }
            return $this->sendResponse(new ContactResource($contact), trans('auth.contact_created'));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }

    public function contactMail($contact)
    {
        $name = $contact->name;
        $email_id = $contact->email;
        $subject = $contact->subject;
        $message = $contact->message;
        \Mail::send('email',[ 'email_id' => $email_id ,'name' => $name, 'clientMessage' => $message], function ($email) use ($subject) {
            $email->to('aaditi.k@mypcot.com', 'aaditi kadam');
            $email->from(config('global.EMAIL_FROM'), config('global.EMAIL_FROM_NAME'));
            $email->subject($subject);

        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        try {
            $types = ['system_email', 'system_contact_no', 'address', 'latitude', 'longitude'];
            $settings = GeneralSetting::whereIn('type', $types)->select('value','type')->get();

            $result = [];

            foreach ($settings as $setting) {
                $result[$setting->type] = $setting->value;
            }
            return $this->sendResponse($result, trans('auth.data_fetched'));

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return $this->sendError(trans('auth.something_went_wrong'), 500);
        }
    }

}
