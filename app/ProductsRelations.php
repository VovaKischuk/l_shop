<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsRelations extends Model {

    public $table = 'products_relations';
    public $timestamps = false;
    public $fillable = ['product_id', 'product_related_id'];

     public function products() {
         return $this->belongsToMany('App\Models\Product', 'PIVOT', 'product_id', 'product_related_id');
     }

}
