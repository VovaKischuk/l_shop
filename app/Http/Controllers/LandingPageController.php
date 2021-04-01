<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Wishlist;

class LandingPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where('featured', true)->take(8)->inRandomOrder()->get();
        
        $pagination = 9;
        $categories = Category::all();

        if (request()->category) {
            $products = Product::with('categories')->whereHas('categories', function ($query) {
                $query->where('slug', request()->category);
            });
            $categoryName = optional($categories->where('slug', request()->category)->first())->name;
        } else {
            $categoryName = 'Featured';
        }
        
        $wishlist = new Wishlist;
        
        return view('landing-page')->with([
            'products' => $products,
            'categories' => $categories,
            'categoryName' => $categoryName,            
            'wishlist' => $wishlist
        ]);
    }
}
