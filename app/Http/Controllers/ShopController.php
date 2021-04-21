<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Product;
use App\Category;
use App\Models\Manufacturers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Dotenv\Regex\Result;
use App\ProductLabel;
use App\Models\Wishlist;
use Illuminate\Pagination\Paginator;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        
        $pagination = 9;
        $categories = Category::all();
        $wishlist = new Wishlist;
        $manufacturers = Manufacturers::all();

        Paginator::useBootstrap();

        if (request()->category) {
            $products = Product::with('categories')->whereHas('categories', function ($query) {
                $query->where('slug', request()->category);
            });
            $categoryName = optional($categories->where('slug', request()->category)->first())->name;
        } else {
            $products = Product::where('featured', true);
            $categoryName = 'Featured';
        }

        if ($request->filled('min_price')) {
            $products = $products->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $products = $products->where('price', '<', $request->max_price);
        }

        $new_min_price = $request->min_price ? $request->min_price : 0;
        $new_max_price = $request->max_price ? $request->max_price : 0;

        if (request()->sort == 'low_high') {
            $products = $products->orderBy('price')->paginate($pagination);
        } elseif (request()->sort == 'high_low') {
            $products = $products->orderBy('price', 'desc')->paginate($pagination);
        } else {
            $products = $products->paginate($pagination);
        }


        foreach ($products as $product) {
            ProductLabel::getNameLabel($product->label_id);
        }

        $min_price = Product::where('featured', true)->min('price');
        $max_price = Product::where('featured', true)->max('price');

        return view('shop', compact('products', 'categories', 'categoryName', 'wishlist', 'min_price', 'max_price', 'new_min_price', 'new_max_price', 'manufacturers') );
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {        
        $product = Product::where('slug', $slug)->firstOrFail();
        $mightAlsoLike = Product::where('slug', '!=', $slug)->mightAlsoLike()->get();
        
        $stockLevel = getStockLevel($product->quantity);
        $user = auth()->user();

        $review = $this->getReview($product->id);
        $modeReview = $this->modaReview($product->id);
        
        $relationProduct = $this->getRelationProduct($product->id); 
        
        $wishlist = new Wishlist;

        return view('product', compact('product', 'review', 'modeReview', 'user', 'stockLevel', 'mightAlsoLike', 'relationProduct', 'wishlist') );
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|min:3',
        ]);

        $query = $request->input('query');
            
        $products = Product::where('name', 'like', "%$query%")
                           ->orWhere('details', 'like', "%$query%")
                           ->orWhere('description', 'like', "%$query%")
                           ->paginate(10);
        
        // $products = Product::search($query)->paginate(10);
        $wishlist = new Wishlist;
        return view('search-results', compact('products', 'wishlist') );
    }

    public function searchAlgolia(Request $request)
    {
        return view('search-results-algolia');
    }

    public function saveReview(Request $request) {
        $user_name = $request->get('user_name');
        $user_email = $request->get('user_email');
        $review = $request->get('review');
        $mark = $request->get('mark');
        $product_id = $request->get('product_id');
        $user = auth()->user();
        $user_id = $user->id;
        $time = date("Y-m-d H:i:s");
        
        $data = array(
                    'product_id' => $product_id,
                    'user_id'    => $user_id,
                    'user_name'  => $user_name,
                    'user_email' => $user_email,
                    'created_at' => $time,
                    'mark'       => $mark,
                    'review'     => $review,
                    'publish'    => 0
                );

        DB::table('products_reviews')->insert($data);
        return back()->withInput();
    }

    public function getReview($product_id) {
        $result = DB::table('products_reviews')
                                    ->select('*')
                                    ->where('product_id', '=', $product_id)
                                    ->where('publish', '=', 1)->get();
        
        return $result; 
    }

    public function modaReview($product_id) {
        $result = DB::table('products_reviews')->select('mark')
                                    ->where('product_id', '=', $product_id)
                                    ->where('publish', '=', 1)->get();
        
        $sum = 0;                            
        foreach($result as $review) {
            $sum += $review->mark;
        }
        if (count($result) > 0) {
            return $sum / count($result);        
        } else {
            return 0;
        }
        
    }

    public function getRelationProduct($product_id) {
        $result = DB::table('products_relations')->select('*')
                                                ->where('product_id', '=', $product_id)->get();
        return $result;                                                
    }

}
