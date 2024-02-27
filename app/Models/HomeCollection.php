<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class HomeCollection extends Model implements HasMedia
{
    use SoftDeletes;
    use HasFactory;
    use InteractsWithMedia,Translatable;

    public $table = 'home_collections';

    public $fillable = [
        'type',
        'sequence',
        'is_scrollable',
        'display_in_column',
        'time_start',
        'time_end',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'deleted_at',
        'display_in_column',
        'is_scrollable'
    ];
    public $appends = ['single_collection_image','collection_data'];
    
    const SINGLE_COLLECTION_IMAGE = 'single_collection_image';

    const SINGLE = 'Single';
    const COURSE = 'Course';

    const COLLECTION_TYPES = [
        self::SINGLE   => 'Single Image',
        self::COURSE => 'Course'
    ];

    const DISPLAY_IN_COLUMN = [
        1,
        2,
        3,
        4,
    ];
    public $translatedAttributes = ['title', 'description'];
    public const TRANSLATED_BLOCK = [
        'title' => 'input',
        'description' => 'textarea'
    ];

    function homeCollectionDetails()
    {

        return $this->hasMany(HomeCollectionMapping::class, 'home_collection_id');
    }

    public function getCollectionDataAttribute()
    {
        $data = [];
        if ($this->type == HomeCollection::COURSE) {
            $timeStart = $this->time_start;
            $timeEnd = $this->time_end;
            if (!empty($timeStart) && !empty($timeEnd)) {
                $modelName = 'App\Models\\'.$this->type;
                $startOfDay = Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
                $endOfDay = Carbon::now()->endOfDay()->format('Y-m-d H:i:s');
                $targetDateTime = Carbon::now()->format('Y-m-d H:i:s');
                $courses = $modelName::where('status','1')->where('registration_start', '<=', $startOfDay)
                    ->where('registration_end', '>=', $endOfDay)
                    ->whereRaw("(CONCAT(date_start, ' ', time_start) >= ?)", [$targetDateTime])
                    ->whereBetween('time_start',[$timeStart,$timeEnd])
                    ->take(config('global.home_collection_limit'))->get();
                if ($courses->count()){
                    $courses->append('image_url');
                    $format = Session::get('format') ?? 'd M y';
                    foreach ($courses as $course){
                        $data[] = [
                            'id' =>$course->id,
                            'date_start' => Carbon::parse($course->date_start)->translatedFormat($format),
                            'time_start' => date('h:i a', strtotime($course->time_start)),
                            'course_name' => $course->course_name ?? '',
                            'course_image' => $course->image_url ?? ''
                        ];
                    }
                }
            }
        }
        
        return $data;
    }
    public function getSingleCollectionImageAttribute(): string
    {

        /** @var Media $media */
        $media = $this->getMedia(self::SINGLE_COLLECTION_IMAGE)->first();

        return ! empty($media) ? $media->getFullUrl() : '';
    }
}
