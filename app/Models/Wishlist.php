<?php

namespace App\Models;

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

        $user = Auth::user();

        if (isset($user) ) {
            $wishlists = DB::table('wishlists')->select('*')->where('user_id', $user->id)->get();
            return count($wishlists);
        }

    }

}
