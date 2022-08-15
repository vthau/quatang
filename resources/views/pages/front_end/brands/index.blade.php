<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

?>

@extends('templates.front_end.master')

@section('content')
    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop')
        </div>
    </div>

    <div class="container mb__50">
        @include('modals.breadcrumb', [
            'text1' => 'thương hiệu',
        ])

        <div class="block-deals-of-opt2">
            <div class="block-content">
                @include ('widgets.front_end.listing_brands')

                @include ('widgets.front_end.best_seller_products')

                @include ('widgets.front_end.new_products')
            </div>
        </div>
    </div>

@stop
