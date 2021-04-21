@extends('layout')

@section('title', 'Products')

@section('content')

    @component('components.breadcrumbs')
        <a href="/">Home</a>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span>Shop</span>
    @endcomponent

    <div class="container">
        @if (session()->has('success_message'))
            <div class="alert alert-success">
                {{ session()->get('success_message') }}
            </div>
        @endif

        @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="products-header">
                    <h1 class="stylish-heading">{{ $categoryName }}</h1>
                    <div>
                        <strong>Price: </strong>
                        <a href="{{ route('shop.index', ['category'=> request()->category, 'sort' => 'low_high']) }}">Low to High</a> |
                        <a href="{{ route('shop.index', ['category'=> request()->category, 'sort' => 'high_low']) }}">High to Low</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="products-section container">
        <div class="sidebar">
            <h3>By Category</h3>
            <ul>
                @foreach ($categories as $category)
                    <li><a href="{{ route('shop.index', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>
            <div class="filter">
                <h3>Filter</h3>
                <form action="{{route('shop.index')}}" method="GET">
                    <div class="filter_block">
                        <h4>Price</h4>
                        <p class="price-filters">
                            <input type="number" name="min_price" id="price-filter-min" value="{{ $new_min_price }}">
                            <span>-</span>
                            <input type="number" name="max_price" id="price-filter-max" value="{{ $new_max_price }}">
                            <input type="submit" value="OK" class="price_ok">
                        </p>
                        <div id="slider-range" data-price-min="{{ $min_price }}" data-price-max="{{ $max_price }}"></div>
                    </div>

                    <div class="filter_block">
                        <h4>Manufacturers</h4>
                        @foreach ($manufacturers as $manufactur)
                            <div>
                                <input type="checkbox" name="manufacturer" value="{{ $manufactur->id }}">
                                <label>{{ $manufactur->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="button button-plain filter">
                        Filter
                    </button>
                </form>
            </div>
        </div>

        <div>

            <div class="products text-center">
                @forelse ($products as $product)
                    <div class="product">
                        <a class="image" href="{{ route('shop.show', $product->slug) }}">
                            <img src="{{ productImage($product->image) }}" alt="product">
                            @if (App\ProductLabel::getNameLabel($product->label_id))                        
                                <span class="label">{{App\ProductLabel::getNameLabel($product->label_id)}}</span>
                            @endif
                        </a> 
                        <a href="{{ route('shop.show', $product->slug) }}"><div class="product-name">{{ $product->name }}</div></a>
                        <div class="product-price">{{ $product->presentPrice() }}</div>
                        <div class="short_description">
                            {{ $product->short_description }}
                        </div>
                        
                        <div class="product_button">
                            @if ($product->quantity > 0)
                                <form action="{{ route('cart.store', $product) }}" method="POST">
                                    {{ csrf_field() }}
                                    <button type="submit" class="button button-plain">
                                        <img src="/img/cart_white.png" />                                        
                                    </button>
                                </form>
                            @endif
                            
                            @if (isset(Auth::user()->id))

                                @if ($product->quantity > 0)
                                    <form action="{{route('wishlist.store')}}" id="contact_form" method="post">
                                        {{csrf_field()}}
                                        <input name="user_id" type="hidden" value="{{Auth::user()->id}}" />
                                        <input name="product_id" type="hidden" value="{{$product->id}}" />
                                        <button type="submit" class="button button-plain wishlist">
                                            <img src="/img/wishlist_white.png" />
                                        </button>
                                    </form>
                                @endif
                            @endif

                            @if ($product->quantity > 0)
                                <form action="{{ route('cart.store', $product) }}" method="POST">
                                    {{ csrf_field() }}
                                    <button type="submit" class="button button-plain compare">
                                        <img src="/img/compare_white.png" />
                                    </button>
                                </form>
                            @endif

                            <a class="detail" href="{{ route('shop.show', $product->slug) }}">
                                Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="text-align: left">No items found</div>
                @endforelse
            </div>

            <div class="spacer"></div>
            {{ $products->appends(request()->input())->links() }}
        </div>
    </div>
    
@endsection
