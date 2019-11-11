<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Wishlist;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller {

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('wishlist')->with([
            'newSubtotal' => getNumbers()->get('newSubtotal'),
            'newTax' => getNumbers()->get('newTax'),
            'newTotal' => getNumbers()->get('newTotal'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product)
    {
        $duplicates = Wishlist::search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id === $product->id;
        });

        if ($duplicates->isNotEmpty()) {
            return redirect()->route('wishlist.index')->with('success_message', 'Item is already in your wishlist!');
        }

        Wishlist::add($product->id, $product->name, 1, $product->price)
            ->associate('App\Product');

        return redirect()->route('wishlist.index')->with('success_message', 'Item was added to your wishlist!');
    }

}