<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();
?>

@extends('templates.ttv.master')

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

        <div class="block-deals-of-opt2">
            <div class="block-content">
                <div class="container container_cat pop_default cat_default mb__60">
                    <div class="cat_toolbar row fl_center al_center mt__30">
                        {{--                    <div class="cat_filter col op__0 pe_none"><a rel="nofollow" href="#" data-no-instant=""--}}
                        {{--                                                                 data-opennt="#shopify-section-nt_filter" data-pos="left"--}}
                        {{--                                                                 data-remove="true" data-class="popup_filter" data-bg="hide_btn"--}}
                        {{--                                                                 class="has_icon btn_filter mgr"><i--}}
                        {{--                                class="iccl fwb iccl-filter fwb mr__5"></i>Filter</a>--}}
                        {{--                        <a rel="nofollow" href="#" data-no-instant="" class="btn_filter js_filter dn mgr"><i--}}
                        {{--                                class="iccl fwb iccl-filter fwb mr__5"></i>Filter</a></div>--}}

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

                        {{--            <div class="cat_sortby cat_sortby_js col tr"><a class="in_flex fl_between al_center sortby_pick"--}}
                        {{--                                                            rel="nofollow" data-no-instant="" href="#"><span--}}
                        {{--                        class="sr_txt dn">Best selling</span><span class="sr_txt_mb">Sort by</span><i--}}
                        {{--                        class="ml__5 mr__5 facl facl-angle-down"></i></a>--}}
                        {{--                <div class="nt_sortby dn">--}}
                        {{--                    <svg class="ic_triangle_svg" viewBox="0 0 20 9" role="presentation">--}}
                        {{--                        <path--}}
                        {{--                            d="M.47108938 9c.2694725-.26871321.57077721-.56867841.90388257-.89986354C3.12384116 6.36134886 5.74788116 3.76338565 9.2467995.30653888c.4145057-.4095171 1.0844277-.40860098 1.4977971.00205122L19.4935156 9H.47108938z"--}}
                        {{--                            fill="#ffffff"></path>--}}
                        {{--                    </svg>--}}
                        {{--                    <h3 class="mg__0 tc cd tu ls__2 dn_lg db">Sort by<i class="pegk pe-7s-close fs__50 ml__5"></i></h3>--}}
                        {{--                    <div class="nt_ajaxsortby wrap_sortby"><a class="truncate"--}}
                        {{--                                                              href="/collections/women?sort_by=best-selling&amp;sort_by=manual">Featured</a><a--}}
                        {{--                            class="truncate selected"--}}
                        {{--                            href="/collections/women?sort_by=best-selling&amp;sort_by=best-selling">Best selling</a><a--}}
                        {{--                            class="truncate" href="/collections/women?sort_by=best-selling&amp;sort_by=title-ascending">Alphabetically,--}}
                        {{--                            A-Z</a><a class="truncate"--}}
                        {{--                                      href="/collections/women?sort_by=best-selling&amp;sort_by=title-descending">Alphabetically,--}}
                        {{--                            Z-A</a><a class="truncate"--}}
                        {{--                                      href="/collections/women?sort_by=best-selling&amp;sort_by=price-ascending">Price,--}}
                        {{--                            low to high</a><a class="truncate"--}}
                        {{--                                              href="/collections/women?sort_by=best-selling&amp;sort_by=price-descending">Price,--}}
                        {{--                            high to low</a><a class="truncate"--}}
                        {{--                                              href="/collections/women?sort_by=best-selling&amp;sort_by=created-ascending">Date,--}}
                        {{--                            old to new</a><a class="truncate"--}}
                        {{--                                             href="/collections/women?sort_by=best-selling&amp;sort_by=created-descending">Date,--}}
                        {{--                            new to old</a></div>--}}
                        {{--                </div>--}}
                        {{--            </div>--}}

                    </div>

                    <div class="filter_area_js filter_area lazypreload lazyloaded"
                         data-include="/collections/women?sort_by=best-selling&amp;section_id=nt_filter" data-currentinclude="">
                        <div id="shopify-section-nt_filter" class="shopify-section nt_ajaxFilter"><h3
                                class="mg__0 tu bgb cw visible-sm fs__16 pr">Filter<i
                                    class="close_pp pegk pe-7s-close fs__40 ml__5"></i></h3>
                            <div class="cat_shop_wrap">
                                <div class="cat_fixcl-scroll">
                                    <div class="cat_fixcl-scroll-content css_ntbar">
                                        <div class="row wrap_filter">
                                            <div class="col-12 col-md-3 widget blockid_color">
                                                <h5 class="widget-title">By Color</h5>
                                                <div class="loke_scroll">
                                                    <ul class="nt_filter_block nt_filter_color css_ntbar">
                                                        <li><a href="/collections/women/color-black?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag color black">
                                                                <div class="filter-swatch"><span
                                                                        class="lazyload bg_color_black"></span></div>
                                                                black</a></li>
                                                        <li><a href="/collections/women/color-cyan?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag color cyan">
                                                                <div class="filter-swatch"><span
                                                                        class="lazyload bg_color_cyan"></span></div>
                                                                cyan</a></li>
                                                        <li><a href="/collections/women/color-green?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag color green">
                                                                <div class="filter-swatch"><span
                                                                        class="lazyload bg_color_green"></span></div>
                                                                green</a></li>
                                                        <li><a href="/collections/women/color-grey?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag color grey">
                                                                <div class="filter-swatch"><span
                                                                        class="lazyload bg_color_grey"></span></div>
                                                                grey</a></li>
                                                        <li><a href="/collections/women/color-pink?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag color pink">
                                                                <div class="filter-swatch"><span
                                                                        class="lazyload bg_color_pink"></span></div>
                                                                pink</a></li>
                                                        <li><a href="/collections/women/color-pink-clay?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag color pink clay">
                                                                <div class="filter-swatch"><span
                                                                        class="lazyload bg_color_pink-clay"></span></div>
                                                                pink clay</a></li>
                                                        <li><a href="/collections/women/color-sliver?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag color sliver">
                                                                <div class="filter-swatch"><span
                                                                        class="lazyload bg_color_sliver"></span></div>
                                                                sliver</a></li>
                                                        <li><a href="/collections/women/color-white?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag color white">
                                                                <div class="filter-swatch"><span
                                                                        class="lazyload bg_color_white"></span></div>
                                                                white</a></li>
                                                        <li><a href="/collections/women/color-white-cream?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag color white cream">
                                                                <div class="filter-swatch"><span
                                                                        class="lazyload bg_color_white-cream"></span></div>
                                                                white cream</a></li>
                                                        <li><a href="/collections/women/color-beige?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag color beige">
                                                                <div class="filter-swatch"><span
                                                                        class="lazyload bg_color_beige"></span></div>
                                                                beige</a></li>
                                                        <li><a href="/collections/women/color-blue?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag color blue">
                                                                <div class="filter-swatch"><span
                                                                        class="lazyload bg_color_blue"></span></div>
                                                                blue</a></li>
                                                        <li><a href="/collections/women/color-brown?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag color brown">
                                                                <div class="filter-swatch"><span
                                                                        class="lazyload bg_color_brown"></span></div>
                                                                brown</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <style>
                                                .cat_filter {
                                                    opacity: 1;
                                                    pointer-events: auto
                                                }

                                                .type_toolbar_filter {
                                                    display: block
                                                }
                                            </style>
                                            <div class="col-12 col-md-3 widget block_1581914074326">
                                                <h5 class="widget-title">By Price</h5>
                                                <div class="loke_scroll">
                                                    <ul class="nt_filter_block nt_filter_styleck css_ntbar">
                                                        <li><a href="/collections/women/price-50-150?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag price $50-$150">$50-$150</a>
                                                        </li>
                                                        <li><a href="/collections/women/price-7-50?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag price $7-$50">$7-$50</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <style>
                                                .cat_filter {
                                                    opacity: 1;
                                                    pointer-events: auto
                                                }

                                                .type_toolbar_filter {
                                                    display: block
                                                }
                                            </style>
                                            <div class="col-12 col-md-3 widget block_1581913909406">
                                                <h5 class="widget-title">By Size</h5>
                                                <div class="loke_scroll">
                                                    <ul class="nt_filter_block nt_filter_styleck css_ntbar">
                                                        <li><a href="/collections/women/size-l?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag size l">l</a>
                                                        </li>
                                                        <li><a href="/collections/women/size-m?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag size m">m</a>
                                                        </li>
                                                        <li><a href="/collections/women/size-s?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag size s">s</a>
                                                        </li>
                                                        <li><a href="/collections/women/size-uk-2?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag size uk 2">uk
                                                                2</a></li>
                                                        <li><a href="/collections/women/size-uk3?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag size uk3">uk3</a>
                                                        </li>
                                                        <li><a href="/collections/women/size-uk4?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag size uk4">uk4</a>
                                                        </li>
                                                        <li><a href="/collections/women/size-xl?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag size xl">xl</a>
                                                        </li>
                                                        <li><a href="/collections/women/size-xs?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag size xs">xs</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <style>.cat_filter {
                                                    opacity: 1;
                                                    pointer-events: auto
                                                }

                                                .type_toolbar_filter {
                                                    display: block
                                                }</style>
                                            <div class="col-12 col-md-3 widget blockid_brand">
                                                <h5 class="widget-title">By Brand</h5>
                                                <div class="loke_scroll">
                                                    <ul class="nt_filter_block nt_filter_styleck css_ntbar">
                                                        <li><a href="/collections/women/vendor-ck?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag vendor ck">ck</a>
                                                        </li>
                                                        <li><a href="/collections/women/vendor-h-m?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag vendor h&amp;m">h&amp;m</a>
                                                        </li>
                                                        <li><a href="/collections/women/vendor-kalles?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag vendor kalles">kalles</a>
                                                        </li>
                                                        <li><a href="/collections/women/vendor-levis?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag vendor levi's">levi's</a>
                                                        </li>
                                                        <li><a href="/collections/women/vendor-monki?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag vendor monki">monki</a>
                                                        </li>
                                                        <li><a href="/collections/women/vendor-nike?sort_by=best-selling"
                                                               aria-label="Narrow selection to products matching tag vendor nike">nike</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <style>.cat_filter {
                                                    opacity: 1;
                                                    pointer-events: auto
                                                }

                                                .type_toolbar_filter {
                                                    display: block
                                                }</style>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (count($products))
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <div id="shopify-section-collection_page" class="shopify-section tp_se_cdt">
                                    <div class="nt_svg_loader dn"></div>
                                    <div class="products nt_products_holder row fl_center row_pr_1 cdt_des_1 round_cd_false nt_cover ratio_nt position_8 space_30 nt_default">
                                        @foreach($products as $product)
                                            <div
                                                class="col-lg-3 col-md-3 col-6 pr_animated done mt__30 pr_grid_item product nt_pr desgin__1">
                                                <div class="product-inner pr">
                                                    <div class="product-image pr oh lazyloaded product-custom" >
                                                        <span class="tc nt_labels pa pe_none cw"></span>
                                                        <a class="db" href="{{$product->getHref(true)}}">
                                                            <div class="pr_lazy_img main-img nt_img_ratio nt_bg_lz lazyloaded"
                                                                 data-id="14246008717451"
                                                                 data-bgset="{{$product->getAvatar()}}"
                                                                 data-parent-fit="width" data-wiis="" data-ratio="0.7837837837837838"
                                                                 style="padding-top: 127.586%; background-image: url('{{$product->getAvatar()}}');">
                                                                <picture style="display: none;">
                                                                    <source
                                                                        data-srcset="{{$product->getAvatar()}}"
                                                                        sizes="270px"
                                                                        srcset="{{$product->getAvatar()}}">
                                                                    <img alt="" class="lazyautosizes lazyloaded" data-sizes="auto"
                                                                         data-ratio="0.7837837837837838" sizes="270px"></picture>
                                                            </div>
                                                        </a>
                                                        <div class="hover_img pa pe_none t__0 l__0 r__0 b__0 op__0">
                                                            <div class="pr_lazy_img back-img pa nt_bg_lz lazyloaded"
                                                                 data-id="14246008750219"
                                                                 data-bgset="{{$product->getAvatar()}}"
                                                                 data-parent-fit="width" data-wiis="" data-ratio="0.7837837837837838"
                                                                 style="padding-top: 127.586%; background-image: url('{{$product->getAvatar()}}');">
                                                                <picture style="display: none;">
                                                                    <source
                                                                        data-srcset="{{$product->getAvatar()}}"
                                                                        sizes="270px"
                                                                        srcset="{{$product->getAvatar()}}">
                                                                    <img alt="" class="lazyautosizes lazyloaded" data-sizes="auto"
                                                                         data-ratio="0.7837837837837838" sizes="270px"></picture>
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
                                                            {{--                                        <a href="/products/mercury-tee" data-no-instant="" data-id="4540696920203"--}}
                                                            {{--                                           class="wishlistadd cb chp ttip_nt tooltip_right" rel="nofollow"><span--}}
                                                            {{--                                                class="tt_txt">Add to Wishlist</span><i--}}
                                                            {{--                                                class="facl facl-heart-o"></i></a>--}}
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
                                                        {{--                                    <div class="swatch__list_js swatch__list lh__1 nt_swatches_on_grid lazyloaded"--}}
                                                        {{--                                         data-include="/products/mercury-tee/?view=swfalse" data-currentinclude=""><span--}}
                                                        {{--                                            data-id="14246008717451" data-vid="32283789000843" data-bgset="//cdn.shopify.com/s/files/1/0332/6420/5963/products/pr17-1_1x1.jpg?v=1581496396--}}

                                                        {{--" data-pd="127.5862069" class="nt_swatch_on_bg swatch__list--item pr ttip_nt tooltip_top_right js__white-cream"><span--}}
                                                        {{--                                                class="tt_txt">White Cream</span><span--}}
                                                        {{--                                                class="swatch__value bg_color_white-cream lazyloaded"></span></span><span--}}
                                                        {{--                                            data-id="14256933142667" data-vid="32283789099147" data-bgset="//cdn.shopify.com/s/files/1/0332/6420/5963/products/pr15-1_019bf7ac-022d-4774-a721-6c10af50b4e3_1x1.jpg?v=1581559165--}}

                                                        {{--" data-pd="127.5862069" class="nt_swatch_on_bg swatch__list--item pr ttip_nt tooltip_top_right js__pink-clay"><span--}}
                                                        {{--                                                class="tt_txt">Pink Clay</span><span--}}
                                                        {{--                                                class="swatch__value bg_color_pink-clay lazyloaded"></span></span></div>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

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
                                </div>
                            </div>
                        </div>

                        <div class="view-more mt__100">
                            <div class="more-pagination">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @endif
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
