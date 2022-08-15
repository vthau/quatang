<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$apiFE = new \App\Api\FE;
$products = $apiFE->getProducts([
    'pagination' => 1,
    'page' => (isset($params['page']) && (int)$params['page']) ? (int)$params['page'] : 1,
//    'limit' => 1,
]);
?>

@extends('templates.front_end.master')

@section('content')
    <style type="text/css">
        .active_menu {
            opacity: 1 !important;
            visibility: visible !important;
            top: 70px !important;
        }
    </style>

    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop')
        </div>
    </div>

    <div class="container mb__50">
        @include('modals.breadcrumb', [
            'text1' => 'sản phẩm',
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

    <script type="text/javascript">
        jQuery(document).ready(function () {
            @if (isset($active) && (int)$active)
                @if ($isMobile)
                    setTimeout(function () {
                        jQuery('#mobi_menu')[0].click();
                        jsbindmobimenuside('menu_2');
                    }, 1888);
                @else
                    jQuery('#menu_san_pham').addClass('active_menu');
                    setTimeout(function () {
                        jQuery('#menu_san_pham').removeClass('active_menu');
                    }, 3888);
                @endif
            @endif
        });
    </script>
@stop
