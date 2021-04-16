<?php
use App\Models\Product;
?>

@extends('layout')

@section('title', $product->name)

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/algolia.css') }}">
@endsection

@section('content')

    @component('components.breadcrumbs')
        <a href="/">Home</a>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span><a href="{{ route('shop.index') }}">Shop</a></span>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span>{{ $product->name }}</span>
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

    <div class="product-section container">
        <div>
            <div class="product-section-image">
                <img src="{{ productImage($product->image) }}" alt="product" class="active" id="currentImage">
            </div>
            <div class="product-section-images">
                <div class="product-section-thumbnail selected">
                    <img src="{{ productImage($product->image) }}" alt="product">
                </div>

                @if ($product->images)
                    @foreach (json_decode($product->images, true) as $image)
                    <div class="product-section-thumbnail">
                        <img src="{{ productImage($image) }}" alt="product">
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="product-section-information">
            <h1 class="product-section-title">{{ $product->name }}</h1>
            <div class="moda_review">
                <i class="fa fa-star"></i> {{ $modeReview }} 
            </div>
            <div class="product-section-subtitle"><?php echo $product->details ?></div>
            <div>{!! $stockLevel !!}</div>
            <div class="product-section-price">{{ $product->presentPrice() }}</div>

            <p>
                {!! $product->description !!}
            </p>
            
            <p>&nbsp;</p>

            @if ($product->quantity > 0)
                <form action="{{ route('cart.store', $product) }}" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="button button-plain">Add to Cart</button>
                </form>
            @endif
        </div>
       
        <div class="block_review">
            <div class="review_header">
                <h2><?php print 'Write a comment for this product!' ?></h2>
            </div>

            <form action="{{ URL::to('/saveReview') }}" name="add_review" method="POST">
            
                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                
                <div id="jshop_review_write" >
                    <div class = "row">
                        <div class = "col-md-3">
                            <?php print 'Name' ?>
                        </div>
                        <div class = "col-md-3">
                            <input type="text" name="user_name" id="review_user_name" class="inputbox" value="<?php if (!empty($user->name) ) echo $user->name; ?>"/>
                        </div>
                    </div>
                    <div class = "row-fluid">
                        <div class = "span3">
                            <?php print 'Email' ?>
                        </div>
                        <div class = "span9">
                            <input type="text" name="user_email" id="review_user_email" class="inputbox" value="<?php if(!empty($user->email)) echo $user->email; ?>" />
                        </div>
                    </div>
                    <div class = "row-fluid">
                        <div class = "span3">
                            <?php print 'Review' ?>
                        </div>
                        <div class = "span9">
                            <textarea name="review" id="review_review" rows="4" cols="40" class="inputbox"></textarea>
                        </div>
                    </div>
                    <div class = "row-fluid">
                        <div class = "span3">
                            Rating for product
                        </div>
                        <div class = "span9">
                            Bad
                            <?php for($i = 1; $i <= 5; $i++){ ?>
                                <input name="mark" type="radio" class="star {split:<?php print 2 ?>}" value="<?php print $i?>" <?php if ($i==5){ ?>checked="checked"<?php } ?>/>
                            <?php } ?>
                            Good
                        </div>
                    </div>
                    <div class = "row-fluid">
                        <div class = "span3"></div>
                        <div class = "span9">
                            <input type="submit" class="btn btn-primary button validate" value="<?php print 'Submit' ?>" />
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="users_review">
            <div class="review_header">
                <h2><?php print 'Comments' ?></h2>
            </div>
            @foreach ($review as $value)
                <div class="review_block">
                <div>
                    <span class="review_user">{{ $value->user_name }}</span>
                        <i class="fa fa-star"></i> {{ $value->mark }} 
                    <span class='review_time'>{{ $value->created_at }}</span>
                </div>
            
                <div class="review_text">{{ $value->review }}</div>                 
                </div>
            @endforeach            
        </div>

    </div> <!-- end product-section -->

    <div class="relation_product">
        <div class="products-section container">
            <h2>Related product</h2>
            <div class="products text-center">
                @foreach ($relationProduct as $key => $value)
                    @php
                      $product = Product::where('id', $value->product_related_id)->firstOrFail();
                    @endphp
                    <div class="releted_product_block">
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
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('partials.might-like')

@endsection

@section('extra-js')
    <script>
        (function(){
            const currentImage = document.querySelector('#currentImage');
            const images = document.querySelectorAll('.product-section-thumbnail');

            images.forEach((element) => element.addEventListener('click', thumbnailClick));

            function thumbnailClick(e) {
                currentImage.classList.remove('active');

                currentImage.addEventListener('transitionend', () => {
                    currentImage.src = this.querySelector('img').src;
                    currentImage.classList.add('active');
                })

                images.forEach((element) => element.classList.remove('selected'));
                this.classList.add('selected');
            }

        })();
    </script>

    <!-- Include AlgoliaSearch JS Client and autocomplete.js library -->
    <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    <script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
    <script src="{{ asset('js/algolia.js') }}"></script>

@endsection
