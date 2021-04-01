<?php

namespace App;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Wishlist extends Model {
    
    protected $table = "wishlists";

    public function user(){
       return $this->belongsTo(User::class);
    }

    public function product(){
       return $this->belongsTo(Product::class);
    }

    public function count_wishlist() {
        $wishlists = DB::table('wishlists')->select('*')->where('user_id', '1')->get();
        return count($wishlists);
    }

}
