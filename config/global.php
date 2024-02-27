<?php

return [
	'non_mandatory_token' => [
        'startup_api',
        'course_category.list',
		'policies.show',
		'contact.create',
		'contact.show',
		'courses.list',
		'courses.show',
		'schedule.list',
		'notification_token.update',
        'home_collection.list'
    ],
	'dimensions' => [
		'instructor_image_width' => '1200',
		'instructor_image_height' => '2000',
		'course_image_width' => '1400',
		'course_image_height' => '800',
		'course_category_image_width' => '400',
		'course_category_image_height' => '400',
        'home_collection_image_width' => '1400',
        'home_collection_image_height' => '800'
    ],
	'sizes' =>[
		'MAX_COURSE_CATEGORY_UPLOAD_FILE_SIZE' => '10240',
		'MAX_COURSE_UPLOAD_FILE_SIZE' => '10240',
		'MAX_PROFILE_UPLOAD_FILE_SIZE' => '10240',
        'MAX_UPLOAD_FILE_SIZE' => '10240'
	],

	'startup_data' => [
    	"home" => [
    		"category" => true,
    		"support" => true
    	],
    	"bottom_nav" => [
    		"home" => true,
    		"schedule" => true,
    		"profile" => true
    	],
    	"schedule" => [
    		"courses" => true
    	],
    	"profile" => [
    		"my_account" => [
				"my_classes" => ":based_on_login",
				"change_password" => ":based_on_login",
				"switch_language" => true,
				"notification_settings" => ":based_on_login",
				"share_app" => true,
				"contact" => true,
				"about" => true,
				"tnc" => true,
				"privacy_policy" => true,
				"cancellation_policy" => true,
				"refund_policy" => true,
				"delete_account" => ":based_on_login",
				"logout" => ":based_on_login",
				"login" => ":based_on_logout"
			],
			"extraa_tabs" => [
                "edit" => ":based_on_login",
                "notification_icon" => ":based_on_login"
            ]
    	]
    ],
    'login_with' => [
        "skip_login_android" => true,
        "skip_login_ios" => true,
        "password" => true,
        "fb" => false,
        "google" => false,
        "apple" => false
    ],
    'payment_mode' => [
        "cod" => true,
        "online"=>false
    ],
    'FCM_SERVER_KEY'=>env('FCM_SERVER_KEY',''),
    'SEND_FCM_NOTIFICATION' => true,
    'NOTIFICATION_ATTEMPT'=>1,
    'home_collection_limit' => 5,
    'related_data_limit' => 5,
    'mail_send' => false,
    'sms_send' => false,
	"static_base_url" => env('APP_URL', null),
    'TRIGGER_FPWD_EMAIL' => true,
];

?>
