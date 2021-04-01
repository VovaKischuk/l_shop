@extends('layout')

@section('title', 'Shopping Cart')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/algolia.css') }}">
@endsection

@section('content')

<div class="container thanks_form">
    <div class="row">
        <div class="col-md-12">
            <div class="cart_image">
                <img src="/img/cart.jpg" />
            </div>
            <h2 style="text-align:center; padding-bottom:80px;" >Thank you for your order</h2>
            <div class="back_shop" style="padding-bottom:80px;">
                <a href="/" >Back to shop</a>
            </div>
        </div>
    </div>
</div>

@endsection
