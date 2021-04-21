<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturers extends Model
{
    protected $table = 'manufacturers';

    public $timestamps = false;

    public function products() {

        return $this->belongsToMany('App\Models\Product');
    }
}
