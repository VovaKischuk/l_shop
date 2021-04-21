@extends('voyager::master')

@section('css')

    @include('voyager::compass.includes.styles')

@stop


@section('content')

<div class="page-content">
    <div class="alerts"></div>
    <div class="clearfix container-fluid row">
        <div class="col-md-12">
            <div class="list-other" style="display: flex; flex-wrap: wrap;">
                <div class="block" style="width: 130px; border: 1px solid #000; padding: 10px; margin-right: 30px">
                    <a target="_self" href="/admin/manufacturers">
                        <span class="icon voyager-paint-bucket" style="font-size: 30px; display: block; color: #000; text-align: center;"></span>
                        <span class="title" style="text-align: center; display: block;">Manufacturers</span>
                    </a>
                </div>
                <div class="block" style="width: 130px; border: 1px solid #000; padding: 10px; margin-right: 30px;">
                    <a target="_self" href="/admin/product-labels">
                        <span class="icon voyager-new" style="font-size: 30px; display: block; color: #000; text-align: center;"></span>
                        <span class="title" style="text-align: center; display: block;">Product Labels</span>
                    </a>
                </div>
                <div class="block" style="width: 130px; border: 1px solid #000; padding: 10px; margin-right: 30px">
                    <a target="_self" href="/admin/coupons">
                        <span class="icon voyager-dollar" style="font-size: 30px; display: block; color: #000; text-align: center;"></span>
                        <span class="title" style="text-align: center; display: block;">Coupons</span>
                    </a>
                </div>
                <div class="block" style="width: 130px; border: 1px solid #000; padding: 10px; margin-right: 30px">
                    <a target="_self" href="/admin/products-reviews">
                        <span class="icon voyager-chat" style="font-size: 30px; display: block; color: #000; text-align: center;"></span>
                        <span class="title" style="text-align: center; display: block;">Products Reviews</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@stop