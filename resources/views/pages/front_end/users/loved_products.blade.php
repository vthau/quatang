<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();
?>

@extends('templates.front_end.master')

@section('content')

    <style>
        .dev_view_cat.dev_desktop a.view_3 {
            border-color: #222
        }

        .dev_view_cat.dev_desktop a.view_3:before {
            background: #222;
            box-shadow: 13px 0 0 #222, 26px 0 0 #222, 39px 0 0 #222
        }

        .dev_view_cat.dev_tablet a.view_3 {
            border-color: #222
        }

        .dev_view_cat.dev_tablet a.view_3:before {
            background: #222;
            box-shadow: 13px 0 0 #222, 26px 0 0 #222, 39px 0 0 #222
        }

        .dev_view_cat.dev_mobile a.view_6 {
            border-color: #222
        }

        .dev_view_cat.dev_mobile a.view_6:before {
            background: #222;
            box-shadow: 13px 0 0 #222, 13px 0 0 #222
        }
    </style>

    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop')
        </div>
    </div>

    <div class="container container_cat pop_default cat_default mb__60">
        @include('modals.breadcrumb', [
           'text1' => 'Sản Phẩm Yêu Thích',
        ])

        @if (!count($products))
            <div class="empty_cart_page tc">
                <style type="text/css">
                    .empty_cart_page>i:after {
                        display: none;
                    }

                </style>
                <i class="las la-shopping-bag pr mb__30 fs__90"></i>
                <h4 class="cart_page_heading mg__0 mb__20 tu fs__30">bạn chưa có danh sách yêu thích!!!</h4>
                <div class="cart_page_txt">Hãy chọn những sản phẩm tốt nhất của chúng tôi với giá hợp lí nhất.</div>
                <div class="mt__30"></div>
                <p class="mb__15"><a class="button button_primary tu " href="{{url('/')}}">trở lại trang chủ</a>
                </p>
            </div>
        @else
            <div class="cat_toolbar row fl_center al_center mt__30">
                <div class="cat_view col-auto hidden">
                    <div class="dn dev_desktop dev_view_cat">
                        <a rel="nofollow" data-no-instant="" href="#" data-dev="dk" data-col="6"
                           class="pr mr__10 cat_view_page view_6"></a>
                        <a rel="nofollow" data-no-instant="" href="#" data-dev="dk" data-col="4"
                           class="pr mr__10 cat_view_page view_4"></a>
                        <a rel="nofollow" data-no-instant="" href="#" data-dev="dk" data-col="3"
                           class="pr mr__10 cat_view_page view_3"></a><a rel="nofollow" data-no-instant="" href="#"
                                                                         data-dev="dk" data-col="15"
                                                                         class="pr mr__10 cat_view_page view_15"></a><a
                            rel="nofollow" data-no-instant="" href="#" data-dev="dk" data-col="2"
                            class="pr cat_view_page view_2"></a></div>
                    <div class="dn dev_tablet dev_view_cat">
                        <a rel="nofollow" data-no-instant="" href="#" data-dev="tb" data-col="6"
                           class="pr mr__10 cat_view_page view_6"></a>
                        <a rel="nofollow" data-no-instant="" href="#" data-dev="tb" data-col="4"
                           class="pr mr__10 cat_view_page view_4"></a>
                        <a rel="nofollow" data-no-instant="" href="#" data-dev="tb" data-col="3"
                           class="pr cat_view_page view_3"></a>
                    </div>
                    <div class="flex dev_mobile dev_view_cat">
                        <a rel="nofollow" data-no-instant="" href="#" data-dev="mb" data-col="12"
                           class="pr mr__10 cat_view_page view_12"></a>
                        <a rel="nofollow" data-no-instant="" href="#" data-dev="mb" data-col="6"
                           class="pr cat_view_page view_6"></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div id="shopify-section-collection_page" class="shopify-section tp_se_cdt">
                        <div class="nt_svg_loader dn"></div>
                        <div class="products nt_products_holder row fl_center row_pr_1 cdt_des_1 round_cd_false nt_cover ratio_nt position_8 space_30 nt_default">
                            @foreach($products as $product)
                                <div class="col-lg-3 col-md-3 col-6 pr_animated done mt__30 pr_grid_item product nt_pr desgin__1">
                                    <div class="product-inner pr">
                                        <div class="product-image pr oh lazyloaded product-custom" >
                                            <span class="tc nt_labels pa pe_none cw"></span>
                                            <a class="db" href="{{$product->getHref(true)}}">
                                                <div class="pr_lazy_img main-img nt_img_ratio nt_bg_lz lazyloaded"
                                                     data-bgset="{{$product->getAvatar()}}"
                                                     data-parent-fit="width"
                                                     style="padding-top: 127.586%; background-image: url('{{$product->getAvatar()}}');">
                                                    <picture style="display: none;">
                                                        <source
                                                            data-srcset="{{$product->getAvatar()}}"
                                                            sizes="270px"
                                                            srcset="{{$product->getAvatar()}}">
                                                        <img alt="" class="lazyautosizes lazyloaded" data-sizes="auto"
                                                             sizes="270px"></picture>
                                                </div>
                                            </a>
                                            <div class="hover_img pa pe_none t__0 l__0 r__0 b__0 op__0">
                                                <div class="pr_lazy_img back-img pa nt_bg_lz lazyloaded"
                                                     data-bgset="{{$product->getAvatar()}}"
                                                     data-parent-fit="width"
                                                     style="padding-top: 127.586%; background-image: url('{{$product->getAvatar()}}');">
                                                    <picture style="display: none;">
                                                        <source
                                                            data-srcset="{{$product->getAvatar()}}"
                                                            sizes="270px"
                                                            srcset="{{$product->getAvatar()}}">
                                                        <img alt="" class="lazyautosizes lazyloaded" data-sizes="auto"
                                                             sizes="270px"></picture>
                                                </div>
                                            </div>
                                            @if($product->is_new || $product->is_best_seller)
                                                <div class="hot_best ts__03 pa">
                                                    @if($product->is_new)
                                                        <div class="hot_best_text is_new">mới</div>
                                                    @endif
                                                    @if($product->is_best_seller)
                                                        <div class="hot_best_text is_hot">bán chạy</div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if ($product->price_main != $product->price_pay)
                                                <div class="discount_percent ts__03 pa">
                                                    <div class="discount_percent_text">giảm {{$product->discount}}%</div>
                                                </div>
                                            @endif
                                            <div class="nt_add_w ts__03 pa ">
                                                <div class="product-love sp-love-{{$product->id}}" onclick="jssplove(this, {{$product->id}})">
                                                    @if ($product->isLoved())
                                                        <i class="fas fa-heart active" title="Đã Yêu Thích SP"></i>
                                                    @else
                                                        <i class="fas fa-heart" title="Thêm SP Yêu Thích"></i>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="hover_button op__0 tc pa flex column ts__03">
                                                <a href="javascript:void(0)" data-id="4540696920203" onclick="jscartdh({{$product->id}})"
                                                   class="pr pr_atc cd br__40 bgw tc dib cb chp ttip_nt tooltip_top_left"
                                                   rel="nofollow"><span class="tt_txt text-capitalize">thêm vào giỏ</span><i
                                                        class="iccl iccl-cart"></i><span class="text-capitalize">thêm vào giỏ</span>
                                                </a>
                                            </div>

                                        </div>
                                        <div class="product-info mt__15">
                                            <h3 class="product-title pr fs__14 mg__0 fwm"><a
                                                    class="cd chp" href="{{$product->getHref(true)}}">{{$product->getTitle()}}</a></h3>
                                            <span class="price dib mb__5">
                                                @if ($product->price_main != $product->price_pay)
                                                    <del class="price_old">
                                                    <span class="number_format">{{$product->price_main}}</span>
                                                    <span class="currency_format">₫</span>
                                                </del>
                                                @endif
                                                <ins>
                                                    <span class="number_format">{{$product->price_pay}}</span>
                                                    <span class="currency_format">₫</span>
                                                </ins>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop
