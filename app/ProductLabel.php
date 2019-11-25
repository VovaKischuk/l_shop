<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class ProductLabel extends Model {

    protected $table = 'product_labels';

    protected $fillable = ['quantity'];

    public $timestamps = false;

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }

}