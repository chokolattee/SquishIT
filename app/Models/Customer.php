<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Customer extends Model implements Searchable
{
    use HasFactory;
    protected $table = 'customers';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 
        'title',
        'fname',
        'lname',
        'addressline',
        'town',
        'phone',
        'created_at', 
        'updated_at' 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
{
    return $this->hasMany(Review::class);
}

public function getSearchResult(): SearchResult
     {
        $url = route('customers.show', $this->customer_id);
     
         return new SearchResult(
            $this,
            $this->fname . " ". $this->lname,
            $url
         );
     }
}
