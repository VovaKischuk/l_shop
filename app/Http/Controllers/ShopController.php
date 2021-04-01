<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use DB;
use Dotenv\Regex\Result;
use App\ProductLabel;
use App\Wishlist;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
        $pagination = 9;
        $categories = Category::all();
        
        if (request()->category) {
            $products = Product::with('categories')->whereHas('categories', function ($query) {
                $query->where('slug', request()->category);
            });
            $categoryName = optional($categories->where('slug', request()->category)->first())->name;
        } else {
            $products = Product::where('featured', true);
            $categoryName = 'Featured';
        }
        
        if (request()->sort == 'low_high') {
            $products = $products->orderBy('price')->paginate($pagination);
        } elseif (request()->sort == 'high_low') {
            $products = $products->orderBy('price', 'desc')->paginate($pagination);
        } else {
            $products = $products->paginate($pagination);
        }

        foreach ($products as $product) {
            // ProductLabel::getNameLabel($product->label_id);
        }
        
        $wishlist = new Wishlist;

        return view('shop')->with([
            'products' => $products,
            'categories' => $categories,
            'categoryName' => $categoryName,
            'wishlist' => $wishlist
        ]);
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

        return view('product')->with([            
            'product' => $product,
            'review' => $review,
            'modeReview' => $modeReview,
            'user' => $user,    
            'stockLevel' => $stockLevel,
            'mightAlsoLike' => $mightAlsoLike,
            'relationProduct' => $relationProduct,
            'wishlist' => $wishlist
        ]);
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
        return view('search-results')->with([
            'products' => $products,
            'wishlist' => $wishlist
        ]);
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
