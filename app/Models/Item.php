<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Item extends Model implements Searchable
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'items';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $fillable = ['item_name', 'description', 'cost_price', 'sell_price', 'category_id', 'image'];

    public function images()
    {
        return $this->hasMany(ItemImage::class, 'item_id', 'id');
    }

    public function firstImage()
    {
        return $this->hasOne(ItemImage::class, 'item_id')->orderBy('item_id');
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('items.edit', $this->id);

        return new SearchResult(
            $this,
            $this->item_name,
            $url
        );
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'item_id');
    }
}
