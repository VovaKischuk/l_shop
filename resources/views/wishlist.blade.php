@extends('layout')

@section('title', 'Shopping Cart')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/algolia.css') }}">
@endsection

@section('content')

    @component('components.breadcrumbs')
        <a href="#">Home</a>
        <i class="fa fa-chevron-right breadcrumb-separator"></i>
        <span>Shopping Wishlist</span>
    @endcomponent

    <div class="cart-section container">
        <div>
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

            <div class="cart-table">
                @foreach ($wishlists as $wishlist)
                <div class="cart-table-row">
                    <div class="cart-table-row-left">
                        <a href="{{ route('shop.show', $wishlist->product->slug) }}">
                            <img src="{{ productImage($wishlist->product->image) }}" alt="item" class="cart-table-img">
                        </a>
                        <div class="cart-item-details">
                            <div class="cart-table-item"><a href="{{ route('shop.show', $wishlist->product->slug) }}">{{ $wishlist->product->name }}</a></div>
                            <div class="cart-table-description">{{ $wishlist->product->details }}</div>
                        </div>
                    </div>
                    
                    <div class="cart-table-actions">
                        <form action="{{ route('wishlist.destroy', $wishlist->product) }}" method="POST">                        
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <button type="submit" class="cart-options">Remove</button>
                        </form>
                        
                    </div>
                </div> <!-- end cart-table-row -->
                @endforeach

            </div> 

        </div>
    </div>

@endsection