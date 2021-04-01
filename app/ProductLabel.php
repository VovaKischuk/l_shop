<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class ProductLabel extends Model {

    protected $table = 'product_labels';

    protected $fillable = ['quantity'];

    public $timestamps = false;

    public function products() {

        return $this->belongsToMany('App\Product');
    }

    public static function getNameLabel($label_id) {
        $label_name = DB::table('product_labels')->select('name')->where('id', $label_id)->get();
        
        if (!empty($label_name[0]->name) ) {
            return $label_name[0]->name;
        } else {
            return 0;
        }
         
    }

}