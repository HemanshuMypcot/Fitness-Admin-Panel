<?php

namespace App\Repositories\webservices;

//Common
use App\Models\Book;
use App\Utils\SearchScopeUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Class BookRepository
 *
 * @package App\Repositories\webservices
 * @version Sep 25, 2023
 */
class BookRepository
{
    /**
     * Get Book Data
     *
     * @param  int  $book_id
     * @return null
     */
    public function findWithoutFail($book_id)
    {
        try {
            $book = Book::find($book_id);
            if (!empty($book)) {
                $book->append('video', 'audio', 'people_also_read_data', 'media_language','wish_list');
            }
            return $book;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getByRequest(array $attributes)
    {
        $book = Book::where('status', '1')->where('visible_on_app', '1');
        if (!empty($attributes['id'])) {
            $ids = explode(',', $attributes['id']);
            $book->whereIn('books.id', $ids);
        }

        if (! empty($attributes['query']) || ! empty($attributes['order_by'])) {
            $book->leftJoin('book_translations', function ($join) {
                $join->on("book_translations.book_id", '=', "books.id");
                $join->where('book_translations.locale', App::getLocale());
            })
                ->select('books.*');
        }
        if (isset($attributes['artist_id']) && is_array($attributes['artist_id'])) {
            $book->whereIn('artist_id', $attributes['artist_id']);
        }
        if (isset($attributes['language_id']) && is_array($attributes['language_id'])) {
            $book->whereIn('language_id', $attributes['language_id']);
        }
        if (isset($attributes['book_category_id']) && is_array($attributes['book_category_id'])) {
            $book->whereIn('book_category_id', $attributes['book_category_id']);
        }
        
        if (!empty($attributes['query'])) {
            $book = SearchScopeUtils::fullSearchQuery($book, $attributes['query'], 'book_translations.name');
        }

        $orderBy = $attributes['order_by'] ?? 'sequence';
        $sortBy = $attributes['sort_by'] ?? 'asc';
        $modelCasting = new Book();
        $casts = $modelCasting->getCasts();
        if (in_array($orderBy, array_keys($casts), true)) {
            if (in_array($orderBy, ['view_count'], true)){
                $book->orderByRaw("cast($orderBy as unsigned) $sortBy");
            }else{
                $book->orderBy($orderBy, $sortBy);
            }
        } else {
            $orderBy = 'book_translations.'.$orderBy;
            $orderClause = 'UPPER('.$orderBy.') '.$sortBy;
            $book->orderByRaw($orderClause);
        }

        $data['total_count'] = $book->count();
        if (isset($attributes['paginate'])) {
            $data['result'] = $book->paginate($attributes['paginate']);
        } else {
            $data['result'] = $book->get();
        }
        return $data;
    }

}
