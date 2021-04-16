<div class="might-like-section">
    <div class="container">
        <h2>You might also like...</h2>
        <div class="might-like-grid">
            @foreach ($mightAlsoLike as $product)
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
            @endforeach

        </div>
    </div>
</div>
