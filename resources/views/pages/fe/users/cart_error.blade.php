<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile() ? 1 : 0;

?>

@extends('templates.ttv.master')

@section('content')
    <style type="text/css">
        .mfp-ajax-holder .mfp-content, .mfp-inline-holder .mfp-content {
            width: auto;
        }
        @if ($isMobile)
        .mfp-content {
            position: absolute;
            left: 0;
            top: 50%;
            margin-top: -150px;
        }
        @endif

        .mini_cart_actions {
            margin-top: 0;
        }

        #shopify-section-cart-template input[type=email],
        #shopify-section-cart-template input[type=text] {
            height: 35px;
            border-radius: 5px !important;
            color: #000 !important;
        }

        textarea {
            border-radius: 5px !important;
            color: #000 !important;
        }

        .price .number_format {
            @if($isMobile)
            font-size: 16px;
            @else
            font-size: 18px;
            @endif
        }

        .cart_countdown {
            display: inline-block;
            margin-bottom: 30px;
            background-color: #fcb800;
            font-size: 15px;
            font-weight: 500;
            border-radius: 4px;
        }

        .cart_countdown.dn {
            display: none !important;
        }

        .height_80px {
            height: 80px;
        }
    </style>

    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop')
        </div>
    </div>

    <div id="shopify-section-cart-template" class="shopify-section cart_page_section container mb__60">
        @include('modals.breadcrumb', [
           'text1' => 'giỏ hàng',
        ])

        <div class="empty_cart_page tc" style="margin-top: 20px !important;">
            <i class="las la-bug pr mb__30 fs__90 required"></i>
            <h4 class="cart_page_heading mg__0 mb__20 tu fs__30 required">Lỗi Giao Dịch!!!</h4>
            <div class="cart_page_txt">Giao dịch không thành công! Vui lòng liên hệ nhân viên quản trị để được hỗ trợ.</div>
            <div class="mt__30"></div>
            <p class="mb__15"><a class="button button_primary tu " href="{{url('/')}}">trở lại trang chủ</a>
            </p>
        </div>
    </div>

@stop
