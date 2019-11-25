<?php

namespace App;

// use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Product extends Model
{
    // use SearchableTrait, Searchable;

    protected $fillable = ['quantity'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'products.name' => 10,
            'products.details' => 5,
            'products.description' => 2,
        ],
    ];

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function labels()
    {
        return $this->belongsToOne('App\ProductLabel');
    }

    public function relation() {
        return $this->belongsToMany('App\ProductsRelations');        
    }

    public function presentPrice()
    {
        return $this->price.' USD'; 
    }

    public function scopeMightAlsoLike($query)
    {
        return $query->inRandomOrder()->take(4);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        $extraFields = [
            'categories' => $this->categories->pluck('name')->toArray(),
            'product_labels' => $this->labels->pluck('name')->toArray(),
        ];

        return array_merge($array, $extraFields);
    }  
    
    public function wishlist(){
        return $this->hasMany(Wishlist::class);
    }

    public function scopeFilter($query, $params) {
        if ( isset($params['min_price']) && trim($params['min_price'] !== '') ) {
            $query->where('price', ' >=', trim($params['min_price']));
        }        
        
        if ( isset($params['max_price']) && trim($params['max_price']) !== '' ) {
            $query->where('price', '<=', trim($params['max_price']));
        }
        
        return $query;
    }

}
