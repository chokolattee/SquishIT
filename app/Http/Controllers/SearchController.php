<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Searchable\Search;
use App\Models\Item;
use App\Models\Customer;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $searchResults = (new Search())
            ->registerModel(Item::class, function ($modelSearchAspect) {
                $modelSearchAspect
                    ->addSearchableAttribute('item_name')
                    ->whereNull('items.deleted_at') // Exclude soft-deleted items
                    ->where(function($query) {
                        $query->WhereHas('category', function($q) {
                                  $q->whereNull('deleted_at'); // Exclude items with deleted categories
                              });
                    });
            })
            ->search(trim($request->term));
    
        foreach ($searchResults as $result) {
            if ($result->searchable instanceof Item) {
                $result->searchable->load(['firstImage', 'category']);
            }
        }
    
        return view('search', compact('searchResults'));
    }
}