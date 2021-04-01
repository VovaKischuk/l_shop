<header class="with-background">
    <div class="top_nav_row">

        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <div class="top-nav">
                        <div class="logo">
                            <a href="/">
                                <img src="/img/logo.png">
                            </a>
                        </div>
                        {{-- {{ //menu('main', 'partials.menus.main') }}--}}
                    </div>
                </div>

                <div class="col-md-10">
                    <div class="top-nav">
                        <div class="header_search">
                            @include('partials.search')
                        </div>

                        <div class="top-nav-right">

                            <div class="cart_block">
                                <a href="{{ route('cart.index') }}">
                                    <div class="img">
                                        <img src="/img/cart_white.png" />
                                    </div>

                                    @if (Cart::instance('default')->count() > 0)
                                        <span class="cart-count">
                                        {{ Cart::instance('default')->count() }}
                                    </span>
                                    @else
                                        <span class="cart-count">
                                        {{ Cart::instance('default')->count() }}
                                    </span>
                                    @endif

                                </a>
                            </div>

                            <div class="wishlist_block">
                                <a href="{{ route('wishlist.index') }}">
                                    <div class="img">
                                        <img src="/img/wishlist_white.png" />
                                    </div>
                                    @if ( $wishlist->count_wishlist() > 0)
                                        <span class="wishlist-count">
                                        {{ $wishlist->count_wishlist() }}
                                    </span>
                                    @endif
                                </a>
                            </div>

                            @include('partials.menus.main-right')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
