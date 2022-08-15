<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

?>

@extends('templates.ttv.master')

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
                @include ('widgets.brand_sort')

                @include ('widgets.product_new')
            </div>
        </div>
    </div>

@stop
