<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute को स्वीकार किया जाना चाहिए।',
    'accepted_if' => ':attribute को तब स्वीकार किया जाना चाहिए जब :other :value हो।',
    'active_url' => ':attribute एक वैध यूआरएल नहीं है।',
    'after' => ':attribute :date के बाद की तारीख होनी चाहिए।',
    'after_or_equal' => ':attribute :date के बाद या उसके बराबर की तारीख होनी चाहिए।',
    'alpha' => ':attribute में केवल अक्षर होने चाहिए।',
    'alpha_dash' => ':attribute में केवल अक्षर, संख्याएँ, डैश और अंडरस्कोर होने चाहिए।',
    'alpha_num' => ':attribute में केवल अक्षर और संख्याएँ होनी चाहिए।',
    'array' => ':attribute एक सरणी होनी चाहिए।',
    'before' => ':attribute :date से पहले की तारीख होनी चाहिए।',
    'before_or_equal' => ':attribute :date से पहले या उसके बराबर की तारीख होनी चाहिए।',
    'between' => [
        'numeric' => ':attribute :min और :max के बीच होनी चाहिए।',
        'file' => ':attribute :min और :max किलोबाइट के बीच होनी चाहिए।',
        'string' => ':attribute :min और :max वर्णों के बीच होनी चाहिए।',
        'array' => ':attribute :min और :max आइटम के बीच होनी चाहिए।',
    ],
    'boolean' => ':attribute फ़ील्ड true या false होनी चाहिए।',
    'confirmed' => ':attribute पुष्टिकरण मेल नहीं खाता।',
    'current_password' => 'पासवर्ड गलत है।',
    'date' => ':attribute मान्य दिनांक नहीं है।',
    'date_equals' => ':attribute :date के बराबर की तारीख होनी चाहिए।',
    'date_format' => ':attribute :format प्रारूप से मेल नहीं खाती।',
    'declined' => ':attribute को अस्वीकार किया जाना चाहिए।',
    'declined_if' => ':attribute को तब अस्वीकार कर दिया जाना चाहिए जब :other :value हो।',
    'different' => ':attribute और :other अलग-अलग होने चाहिए।',
    'digits' => ':attribute :digits अंक होनी चाहिए।',
    'digits_between' => ':attribute :min और :max अंकों के बीच होनी चाहिए।',
    'dimensions' => ':attribute में अमान्य प्रतिकृति लंबाई-चौड़ाई हैं.',
    'distinct' => ':attribute फ़ील्ड में डुप्लिकेट वैल्यु है।',
    'email' => ':attribute एक वैध ईमेल पता होना चाहिए।',
    'ends_with' => ':attribute निम्नलिखित में से किसी एक के साथ समाप्त होनी चाहिए: :values।',
    'enum' => 'चुने हुए :attribute अमान्य है।.',
    'exists' => 'चुने हुए :attribute अमान्य है।.',
    'file' => ':attribute एक फ़ाइल होनी चाहिए।',
    'filled' => ':attribute फ़ील्ड का एक वैल्यु होना चाहिए।',
    'gt' => [
        'numeric' => ':attribute :value से अधिक होनी चाहिए।',
        'file' => ':attribute :value किलोबाइट से अधिक होनी चाहिए।',
        'string' => ':attribute :value वर्णों से अधिक होनी चाहिए।',
        'array' => ':attribute में :value आइटम से अधिक होना चाहिए।',
    ],
    'gte' => [
        'numeric' => ':attribute :value से अधिक या उसके बराबर होनी चाहिए।',
        'file' => ':attribute :value किलोबाइट से अधिक या उसके बराबर होनी चाहिए।',
        'string' => ':attribute :value वर्णों से अधिक या उसके बराबर होनी चाहिए।',
        'array' => ':attribute में :value आइटम या अधिक होने चाहिए।',
    ],
    'image' => ':attribute एक प्रतिकृति होनी चाहिए।',
    'in' => 'चुने हुए :attribute अमान्य है.',
    'in_array' => ':attribute फ़ील्ड :other में मौजूद नहीं है।',
    'integer' => ':attribute एक पूर्णांक होना चाहिए।',
    'ip' => ':attribute एक वैध आईपी पता होना चाहिए।',
    'ipv4' => ':attribute एक वैध आईपीवी4 पता होना चाहिए।',
    'ipv6' => ':attribute एक वैध आईपीवी6 पता होना चाहिए।',
    'json' => ':attribute एक वैध JSON स्ट्रिंग होनी चाहिए।',
    'lt' => [
        'numeric' => ':attribute :value से कम होनी चाहिए।',
        'file' => ':attribute :value किलोबाइट से कम होनी चाहिए।',
        'string' => ':attribute :value वर्णों से कम होना चाहिए।',
        'array' => ':attribute में :value आइटम से कम होना चाहिए।',
    ],
    'lte' => [
        'numeric' => ':attribute :value से कम या उसके बराबर होनी चाहिए।',
        'file' => ':attribute :value किलोबाइट से कम या उसके बराबर होनी चाहिए।',
        'string' => ':attribute :value वर्णों से कम या उसके बराबर होनी चाहिए।',
        'array' => ':attribute में :value आइटम से अधिक नहीं होना चाहिए।',
    ],
    'mac_address' => ':attribute एक वैध मैक पता होना चाहिए।',
    'max' => [
        'numeric' => ':attribute :max से अधिक नहीं होनी चाहिए।',
        'file' => ':attribute :max किलोबाइट से अधिक नहीं होनी चाहिए।',
        'string' => ':attribute :max वर्णों से अधिक नहीं होनी चाहिए।',
        'array' => ':attribute में :max से अधिक आइटम नहीं होने चाहिए।',
    ],
    'mimes' => ':attribute :values फ़ाइल होनी चाहिए।',
    'mimetypes' => ':attribute :values फ़ाइल होनी चाहिए।',
    'min' => [
        'numeric' => ':attribute कम से कम :min होनी चाहिए.',
        'file' => ':attribute कम से कम :min किलोबाइट होनी चाहिए।',
        'string' => ':attribute कम से कम :min अक्षर होनी चाहिए।',
        'array' => ':attribute में कम से कम :min आइटम होने चाहिए।',
    ],
    'multiple_of' => ':attribute :value का गुणज होना चाहिए।',
    'not_in' => 'चुने हुए :attribute अमान्य है।',
    'not_regex' => ':attribute प्रारूप अमान्य है।',
    'numeric' => ':attribute एक संख्या होनी चाहिए.',
    'password' => 'पासवर्ड गलत है।',
    'present' => ':attribute फ़ील्ड मौजूद होना चाहिए।',
    'prohibited' => ':attribute फ़ील्ड निषिद्ध है.',
    'prohibited_if' => ':attribute फ़ील्ड निषिद्ध है जब :other :value है।',
    'prohibited_unless' => ':attribute फ़ील्ड तब तक निषिद्ध है जब तक :other :value में न हो।',
    'prohibits' => ':attribute फ़ील्ड :other को उपस्थित होने से रोकती है।',
    'regex' => ':attribute प्रारूप अमान्य है।',
    'required' => ':attribute फ़ील्ड आवश्यक है.',
    'required_array_keys' => ':attribute फ़ील्ड में :values के लिए प्रविष्टियाँ होनी चाहिए।',
    'required_if' => ':attribute फ़ील्ड तब आवश्यक है जब :other :value हो।',
    'required_unless' => ':attribute फ़ील्ड तब तक आवश्यक है जब तक :other :value में न हो।',
    'required_with' => ':attribute फ़ील्ड तब आवश्यक है जब :value मौजूद हो।',
    'required_with_all' => ':attribute फ़ील्ड तब आवश्यक होती है जब :value मौजूद हों।',
    'required_without' => ':attribute फ़ील्ड तब आवश्यक है जब :value मौजूद नहीं है।',
    'required_without_all' => ':attribute फ़ील्ड तब आवश्यक होती है जब कोई भी :value मौजूद न हो।',
    'same' => ':attribute और :other का मिलान होना चाहिए।',
    'size' => [
        'numeric' => ':attribute :size होनी चाहिए।',
        'file' => ':attribute :size किलोबाइट होनी चाहिए।',
        'string' => ':attribute :size अक्षर होना चाहिए।',
        'array' => ':attribute में :size वाले आइटम शामिल होने चाहिए।',
    ],
    'starts_with' => ':attribute निम्नलिखित में से किसी एक से शुरू होनी चाहिए: :values।',
    'string' => ':attribute एक स्ट्रिंग होनी चाहिए।',
    'timezone' => ':attribute एक वैध समयक्षेत्र होना चाहिए।',
    'unique' => ':attribute पहले ही ली जा चुकी है।',
    'uploaded' => ':attribute अपलोड करने में विफल रहा।',
    'url' => ':attribute एक वैध यूआरएल होना चाहिए।',
    'uuid' => ':attribute एक वैध यूयूआईडी होनी चाहिए।',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
