<?php

namespace App\MediaLibrary;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Notification;
use App\Models\MovieCategory;
use App\Models\Instructor;
use App\Models\Location;
use App\Models\HomeCollection;
use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

/**
 * Class CustomPathGenerator
 */
class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        $path = '{PARENT_DIR}'.DIRECTORY_SEPARATOR.$media->id.DIRECTORY_SEPARATOR;

        switch ($media->collection_name) {
            case MovieCategory::IMAGE;
                return str_replace('{PARENT_DIR}', MovieCategory::IMAGE, $path);
            case CourseCategory::IMAGE;
                return str_replace('{PARENT_DIR}', CourseCategory::IMAGE, $path);
            case Notification::NOTIFICATION_IMAGE;
                return str_replace('{PARENT_DIR}', Notification::NOTIFICATION_IMAGE, $path);
            case Course::IMAGE;
                return str_replace('{PARENT_DIR}', Course::IMAGE, $path);
            case User::IMAGE;
                return str_replace('{PARENT_DIR}', User::IMAGE, $path);
            case HomeCollection::SINGLE_COLLECTION_IMAGE;
                return str_replace('{PARENT_DIR}', HomeCollection::SINGLE_COLLECTION_IMAGE, $path);
            case 'default';
                return '';
        }
    }

    /**
     * @param  Media  $media
     *
     * @return string
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media).'thumbnails/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media).'rs-images/';
    }
}
