<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();
$apiFE = new \App\Api\FE;

$products = $apiFE->getProducts([
    'pagination' => 1,
    'brand' => $item->id,
    'page' => (isset($params['page']) && (int)$params['page']) ? (int)$params['page'] : 1,
//    'limit' => 1,
]);
?>

@extends('templates.front_end.master')

@section('content')
    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop', [
                'bannerBG' => $item->getBanner(),
            ])
        </div>
    </div>

    <div class="container mb__50">
        @include('modals.breadcrumb', [
            'text1' => $item->getTitle(),
            'text2' => 'thương hiệu',
            'text2link' => url('thuong-hieu'),
        ])

        <div class="row">
            <div class="col-md-12">
                <div class="panel-content products-wrapper">
                    <div class="row">
                        @if (count($products))
                            @include('widgets.front_end.pagination_products')
                        @else
                            <div class="alert alert-warning">Đang Cập Nhật...</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
