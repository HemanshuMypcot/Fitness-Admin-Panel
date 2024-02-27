<?php

namespace App\Repositories\webservices;

use App\Models\HomeCollection;
use App\Models\HomeCollectionMapping;

class HomeCollectionRepository
{

    /**
     * Get HomeCollection
     *
     * @param  array  $attributes
     * @return array
     */
    public function getByRequest(array $attributes) {

        $homeCollection = HomeCollection::with('translations')->where('status', '1');
        if(isset($attributes['id'])) {
            $homeCollection->where('id',$attributes['id']);
        }
        $homeCollection->orderBy('sequence');
        $homeCollectionData = $homeCollection->get()->toArray();
        $data = array_filter($homeCollectionData, function ($item) {
            return $item['type'] === 'Single' || ( $item['type'] !== 'Single' && !empty($item['collection_data']) );
        });

        $homeCollection = collect($data);
        // Added by = Aakanksha ---> as requested by app team
        $data['total_count'] = $homeCollection->count()+1;

        if (!empty($attributes['page']) && !empty($attributes['paginate']) && !empty($data['total_count'])) {
            $page = $attributes['page'];
            $paginate = $attributes['paginate'];
            $startIndex = ($page - 1) * $paginate;
            $itemsPerPage = $paginate;
            $paginatedResults = $homeCollection->slice($startIndex, $itemsPerPage);
            $remainingCount = max(0, $data['total_count'] - $startIndex - $itemsPerPage);
            $data['remaining_count'] = $remainingCount;
            $data['result'] = $paginatedResults->values()->all();
        } else {
            $data['remaining_count'] = 0;
            $data['result'] = $homeCollection->all();
        }

        return $data;
    }
}
