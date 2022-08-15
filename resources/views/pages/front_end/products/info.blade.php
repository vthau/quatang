<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$apiFE = new \App\Api\FE;

//sale
$sale = null;
$priceMain = $item->price_main;
$priceOne = $item->price_pay;
$percentDiscount = $item->discount;
$priceDiscount = 0;

//combo
$comboIds = (!empty($item->combo)) ? json_decode($item->combo) : [];
?>

@extends('templates.front_end.master')

@push('style')
    <link href="{{url('public/libraries/zoom/magiczoomplus.css')}}" rel="stylesheet">
    <style data-shopify>
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

        #wrap_des_pr {
            background-color: #fff;
        }

        @media (min-width: 1025px) {
            #wrap_des_pr {
                background-color: #f6f6f8;
            }
        }

        #wrap_des_pr {
            margin: 0 !important;
        }

        @media only screen and (max-width: 767px) {
            #wrap_des_pr {
                margin: 0 !important;
            }
        }
    </style>
    <style type="text/css">
        .cate_cate a {
            padding: 0 10px;
            border-left: 1px solid;
        }
        .cate_cate a:first-child {
            border-left: none;
        }

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

        .example-container {
            padding: 30px 20px;
            text-align: center;
            height: 100%;
        }

        .roland-garros-babolat {
            display: table;
            margin: 0 auto;
        }

        .babolat-preview {
            padding: 0 10px;
            display: table-cell;
            vertical-align: top;
        }

        .selectors {
            width: 70px;
            margin: 10px 0;
            display: table-cell;
            vertical-align: top;
        }

        .selectors img {
            width: 60px;
        }

        .selectors a {
            margin-bottom: 5px;
        }

        #product-4610184183889 .fa-heart.active {
            position: relative;
            top: 8px;
        }
        .table_product_info {
            width: 70%;
            margin: 0 auto;
        }
        .table_product_info tr td {
            text-align: center;
            color: #000000;
        }
        .table_product_info tr td:first-child {
            text-transform: uppercase;
            width: 30%;
            text-align: left;
            color: #333;
            font-size: 13px;
            font-weight: bold;
        }
        .p-body {
            margin: 30px 0 !important;
        }

        .fixed_bot {
            position: fixed;
            bottom: 0;
            z-index: 9999999999;
            background-color: #fff;
            width: 100%;
        }
        .fixed_bot > div {
            padding: 13px 3px 10px;
            border-top: 1px solid #000;
        }

        @if ($isMobile)
        .fixed_bot .product-love {
            padding: 5px;
            border: 1px solid;
            border-radius: 50%;
        }

        .fixed_bot .product-love i {
            position: relative;
            top: 2px;
        }
        @endif

        .btn_combo {
            top: 0;
            width: 30px;
            height: 40px;
            line-height: 40px;
            border: 0;
            background: 0 0;
        }
        .btn_combo:hover {
            background-color: #fff;
            border-color: #fff;
            color: #000;
        }
        .group_table td{padding:10px;text-align:center}td.grouped-pr-list-item__thumb img{max-width:100px}td.grouped-pr-list-item__label{text-align:left}.grouped-pr-list-item__info select,.grouped-pr-list-item__quantity .quantity{border-radius:2px;border-color:#ddd;max-width:200px;display:block;margin-left:auto;margin-right:auto}.grouped-pr-list-item__price ins{text-decoration:none}.fgr_frm .single_add_to_cart_button{min-width:230px!important}.grouped_pr_subtotal{font-size:20px;color:#222;font-weight:600;margin-bottom:10px}@media (max-width:767px){td.grouped-pr-list-item__thumb img{width:65px}.variations_form .quantity{min-width:100px;width:100px}.button.out_stock_groupedr{padding:5px 8px;white-space:nowrap;overflow:hidden;max-width:100px;text-overflow:ellipsis}.fgr_frm .single_add_to_cart_button{width:calc(100% - 50px);width:-webkit-calc(100% - 50px)}}
    </style>
@endpush

@section('content')
    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop', [
                'bannerBG' => ($item->getCategory()) ? $item->getCategory()->getBanner() : '',
            ])
        </div>
    </div>

    <div class="container container_cat cat_default thumb_left">
        @include('modals.breadcrumb', [
            'text1' => $item->getCategory() ? $item->getCategory()->getTitle() : '',
            'text1link' => $item->getCategory() ? $item->getCategory()->getHref() : '',
            'text2' => $item->getCategory() && $item->getCategory()->getParent() ? $item->getCategory()->getParent()->getTitle() : '',
            'text2link' => $item->getCategory() && $item->getCategory()->getParent() ? $item->getCategory()->getParent()->getHref() : '',
        ])

        <div class="row product mt__20" id="product-4610184183889">
            <div class="col-md-12 col-12">
                <div class="row mb__10 pr_sticky_content">
                    <div class="col-md-6 col-12">
                        <div class="zoom_wrapper">
                            <div class="example-container">
                                <div class="roland-garros-babolat">
                                    <div class="selectors">
                                        <?php if (!empty($item->video_link)):
                                        $vd = $item->video_link;
                                        $arr = array_filter(explode("/", $vd));

                                        ?>
                                        <a
                                            href="javascript:void(0)" onclick="jsspvideo('{{$item->video_link}}')"
                                            class="mz-thumb">
                                            <img src="http://img.youtube.com/vi/{{$arr[count($arr)]}}/hqdefault.jpg" />
                                        </a>
                                        <?php endif;?>
                                        <a data-zoom-id="rg-babolat"
                                           href="{{$item->getAvatar()}}?scale.width=900"
                                           data-zoom-image-2x="{{$item->getAvatar()}}"
                                           data-image="{{$item->getAvatar()}}?scale.width=350"
                                           data-image-2x="{{$item->getAvatar()}}?scale.width=700"
                                           class="mz-thumb-selected mz-thumb">
                                            <img alt="Magic Zoom Plus - Examples"
                                                 src="{{$item->getAvatar()}}?scale.width=60"
                                                 srcset="{{$item->getAvatar()}}?scale.width=120 2x" />
                                        </a>
                                        @if (count($item->getSlides()))
                                            @foreach($item->getSlides() as $photo)
                                                <a data-zoom-id="rg-babolat"
                                                   href="{{$photo->getPhoto()}}?scale.width=900"
                                                   data-zoom-image-2x="{{$photo->getPhoto()}}"
                                                   data-image="{{$photo->getPhoto()}}?scale.width=350"
                                                   data-image-2x="{{$photo->getPhoto()}}?scale.width=700"
                                                   class="mz-thumb">
                                                    <img alt="Magic Zoom Plus - Examples"
                                                         src="{{$photo->getPhoto()}}?scale.width=60"
                                                         srcset="{{$photo->getPhoto()}}?scale.width=120 2x" />
                                                </a>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="babolat-preview">
                                        <a class="MagicZoom" id="rg-babolat"
                                           href="{{$item->getAvatar()}}?scale.width=900"
                                           data-zoom-image-2x="{{$item->getAvatar()}}"
                                           data-mobile-options="zoomPosition:right;zoomWidth:130%; zoomHeight:100%; zoomDistance:40;"
                                           data-options="zoomPosition: #rg-babolat-zoom; zoomWidth:470; zoomHeight:410; transitionEffect: false;expand: off;">
                                            <figure class="mz-figure mz-hover-zoom mz-ready">
                                                <img alt="Magic Zoom Plus - Examples"
                                                     src="{{$item->getAvatar()}}?scale.width=350"
                                                     srcset="{{$item->getAvatar()}}?scale.width=700 2x"
                                                     style="max-width: 350px; max-height: 350px;">
                                                <div class="mz-lens" style="top: 0px; transform: translate(-10000px, -10000px); width: 183px; height: 160px;">
                                                    <img src="https://magictoolbox.sirv.com/images/magiczoom/roland-garros-babolat-racket.jpg?scale.width=350"
                                                         style="position: absolute; top: 0px; left: 0px; width: 350px; height: 350px; transform: translate(-1px, -191px);"
                                                         alt="Magic Zoom Plus - Examples">
                                                </div>
                                            </figure>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
{{--                    slides--}}
                    <div class="col-md-6 col-12 pr product-images img_action_zoom pr_sticky_img hidden"
                         data-pr-single-media-group>
                        <div class="row theiaStickySidebar">
                            <div class="col-12 col-lg col_thumb">
                                <div
                                    class="p-thumb images sp-pr-gallery equal_nt nt_contain ratio_imgtrue position_8 nt_slider pr_carousel flickity-enabled is-draggable is-fade"
                                    data-flickity='{"fade":true,"draggable":true,"cellSelector": ".p-item:not(.is_varhide)","cellAlign": "center","wrapAround": true,"autoPlay": false,"prevNextButtons":true,"adaptiveHeight": true,"imagesLoaded": false, "lazyLoad": 0,"dragThreshold" : 6,"pageDots": false,"rightToLeft": false }'>
                                    <div data-grname="not4" data-grpvl="ntt4"
                                         class="img_ptw p_ptw p-item sp-pr-gallery__img w__100 nt_bg_lz lazyload blowup"
                                         data-mdid="7203047309393" data-mdtype="image"
                                         data-bgset="{{$item->getAvatar()}}"
                                         data-ratio="1.0" data-rationav="" data-sizes="auto"
                                         data-src="{{$item->getAvatar()}}"
                                         data-width="570" data-height="570" data-cap="{{$item->getTitle()}}"
                                         style="padding-top:100.0%;">
                                        <img class="op_0 dn"
                                             src="{{$item->getAvatar()}}"
                                             alt="{{$item->getTitle()}}">
                                    </div>
                                    @if (count($item->getSlides()))
                                        @foreach($item->getSlides() as $photo)
                                            <div data-grname="not4" data-grpvl="ntt4"
                                                 class="img_ptw p_ptw p-item sp-pr-gallery__img w__100 nt_bg_lz lazyload"
                                                 data-mdid="7203048718417" data-mdtype="image"
                                                 data-bgset="{{$photo->getPhoto()}}"
                                                 data-ratio="1.0" data-rationav="" data-sizes="auto"
                                                 data-src="{{$photo->getPhoto()}}"
                                                 data-width="570" data-height="570" data-cap="{{$item->getTitle()}}"
                                                 style="padding-top:100.0%;">
                                                <img class="op_0 dn"
                                                     src="{{$photo->getPhoto()}}"
                                                     alt="{{$item->getTitle()}}">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                @if ($item->discount)
                                    <span class="tc nt_labels pa pe_none cw">
                                    <span class="onsale nt_label">-{{$item->discount}}%</span>
                                </span>
                                @endif

                                <div class="p_group_btns pa flex">
                                    {{--                                    <button--}}
                                    {{--                                        class="br__40 tc flex al_center fl_center bghp show_btn_pr_gallery ttip_nt tooltip_top_left">--}}
                                    {{--                                        <i class="las la-expand-arrows-alt"></i><span--}}
                                    {{--                                            class="tt_txt">Click to enlarge</span></button>--}}
                                </div>
                            </div>
                            <div class="col-12 col-lg-auto col_nav nav_medium">
                                <div
                                    class="p-nav ratio_imgtrue row equal_nt nt_cover ratio_imgtrue position_8 nt_slider pr_carousel"
                                    data-flickityjs='{"cellSelector": ".n-item:not(.is_varhide)","cellAlign": "left","asNavFor": ".p-thumb","wrapAround": 0,"draggable": 1,"autoPlay": 0,"prevNextButtons": 0,"percentPosition": 1,"imagesLoaded": 0,"pageDots": 0,"groupCells": 0,"rightToLeft": false,"contain":  1,"freeScroll": 0}'>
                                    <div class="row">
                                        <div class="col-3 col-lg-12">
                                            <img class="w__100"
                                                 src="{{$item->getAvatar()}}"
                                                 alt="{{$item->getTitle()}}">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" aria-label="Previous" class="btn_pnav_prev pe_none"><i
                                        class="las la-angle-up"></i></button>
                                <button type="button" aria-label="Next" class="btn_pnav_next pe_none"><i
                                        class="las la-angle-down"></i></button>
                            </div>
                            <div class="dt_img_zoom pa t__0 r__0 dib"></div>
                        </div>
                    </div>
{{--                    review--}}
                    <div class="col-md-6 col-12 product-infors pr_sticky_su">
                        <div id="shopify-section-pr_summary"
                             class="shopify-section summary entry-summary mt__30 theiaStickySidebar">
                            <h1 class="product_title" style="font-size: 25px">{{$item->getTitle()}}</h1>
                            <div class="product_star pr clearfix mb__20">
                                <div class="float-left mr__10">
                                    <?php
                                    for($i=0;$i<=4;$i++):
                                    ?>
                                    @if ($item->star_count - $i >= 1)
                                        <img src="{{url('public/images/star_full.png')}}" />
                                    @elseif ($item->star_count - $i > 0)
                                        <img src="{{url('public/images/star_half.png')}}" />
                                    @else
                                        <img src="{{url('public/images/star_empty.png')}}" />
                                    @endif
                                    <?php endfor;?>
                                </div>
                                <div class="float-left">
                                    <a class="text-bold" onclick="jssptab('ttdg')" href="#product_review">({{count($reviews)}} đánh giá)</a>
                                </div>
                            </div>
                            <div class="flex wrap fl_between al_center price-review">
                                <p class="price" id="price_ppr">
                                    @if ($item->price_main != $item->price_pay)
                                        <del class="price_old">
                                            <span class="number_format">{{$item->price_main}}</span>
                                            <span class="currency_format">₫</span>
                                        </del>
                                    @endif
                                    <strong>
                                        <span class="number_format" style="font-size: 25px;">{{$item->price_pay}}</span>
                                        <span class="currency_format">₫</span>
                                    </strong>

                                </p>

                                <div class="info_label ts__03">
                                    @if ($item->price_main != $item->price_pay)
                                        <div class="info_label_text is_discount">giảm {{$item->discount}}%</div>
                                    @endif
                                    @if($item->is_new)
                                        <div class="info_label_text is_new">mới</div>
                                    @endif
                                    @if($item->is_best_seller)
                                        <div class="info_label_text is_hot">bán chạy</div>
                                    @endif
                                </div>
                            </div>
                            <div id="counter_ppr" class="pr_counter dn cd mt__20" data-min="1" data-max="100"
                                 data-interval="2000"><i class="cd mr__5 fading_true fs__20 las la-eye"></i><span
                                    class="count clc fwm cd"></span> <span class="cd fwm">người</span> đang xem sản phẩm này
                            </div>
                            @if (!empty($item->mo_ta_ngan))
                                <div class="pr_short_des mt__10 mb__10">
                                    <p>{{$item->mo_ta_ngan}}</p>
                                </div>
                            @endif
                            @if ($item->push_sale_profile)
                                <div id="nt_countdow_ppr_txt" class="tl cd_style_dark"><p
                                        class="mess_cd cb mb__10 lh__1 dn fwm tu" style="font-size: 16px"><i
                                            class="cd mr__5 fading_true fs__20 las la-stopwatch"></i>
                                        nhanh tay lên! số lượng có hạn
                                    </p>
                                    <div class="nt_countdow_page nt_loop in_flex fl_between lh__1" data-timezone='false'
                                         data-loop='false' id="nt_countdow_ppr" data-zone="+07" data-shopt=2020/10/06
                                         data-shoph="172317" data-time="8:00:00,16:00:00,23:59:59"></div>
                                    <span class="day dn">ngày</span>
                                    <span class="hr dn">giờ</span>
                                    <span class="min dn">phút</span>
                                    <span class="sec dn">giây</span>
                                </div>
                            @endif
                            <div class="extra-link mt__35 fwsb"></div>

                            @if (!$isMobile)
                                <div class="btn-atc atc-slide btn_full_false btn_des_1 btn_txt_3">
                                    <div id="callBackVariant_ppr" class="nt_default-title nt1_ nt2_">
                                        <form method="post" action=""
                                              id="cart-form_ppr" accept-charset="UTF-8"
                                              class="nt_cart_form variations_form variations_form_ppr"
                                              enctype="multipart/form-data">

                                            <div class="variations_button in_flex column w__100">
                                                <div class="flex wrap">
                                                    <div class="quantity pr mr__10 order-1 qty__true" id="sp_qty_ppr">
                                                        <input type="number" class="input-text qty text tc qty_pr_js"
                                                               step="1" min="1" max="9999" name="quantity" value="1"
                                                               size="4" pattern="[0-9]*" inputmode="numeric"
                                                               id="product_buy_quantity" onkeyup="jscartdhmqvalid(this)"
                                                        >
                                                        <div class="qty tc fs__14">
                                                            <a rel="nofollow" data-no-instant
                                                               class="plus db cb pa pr__15 tr r__0" href="#">
                                                                <i class="facl facl-plus"></i>
                                                            </a>
                                                            <a rel="nofollow" data-no-instant
                                                               class="minus db cb pa pl__15 tl l__0" href="#">
                                                                <i class="facl facl-minus"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <div class="nt_add_w ts__03 pa order-3">
                                                        <div class="product-love sp-love-{{$item->id}}"
                                                             onclick="jssplove(this, {{$item->id}})">
                                                            @if ($item->isLoved())
                                                                <i class="fas fa-heart active" title="Đã Yêu Thích SP"></i>
                                                            @else
                                                                <i class="fas fa-heart" title="Thêm SP Yêu Thích"></i>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <button type="button" data-time='6000' data-ani='shake' onclick="jscartdh({{$item->id}})"
                                                            class="single_add_to_cart_button button truncate w__100 mt__20 order-4">
                                                        <span class="txt_add text-uppercase">thêm vào giỏ</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            {{--                            combo--}}
                            @if (count($comboIds))
                                <div class="btn-atc atc-slide btn_full_false btn_des_1 btn_txt_3">
                                    @if ($isMobile)
                                        <form method="post" action="" id="fgr_frm_id" accept-charset="UTF-8" class="variations_form fgr_frm" enctype="multipart/form-data" novalidate="novalidate">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table">
                                                        <tbody>
                                                        <tr>
                                                            <td colspan="2">
                                                                <div class="text-uppercase text-bold">{{$apiCore->getSetting('text_sp_combo')}}</div>
                                                            </td>
                                                        </tr>
                                                        <?php foreach($comboIds as $iteId):
                                                        $ite = $apiCore->getItem('product', (int)$iteId);
                                                        if (!$ite) {
                                                            continue;
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td colspan="3" class="clearfix pr">
                                                                <div class="float-left mr__10">
                                                                    <a href="{{$ite->getHref(true)}}">
                                                                        <img class="width_height_80" src="{{$ite->getAvatar()}}" />
                                                                    </a>
                                                                </div>
                                                                <div class="overflow-hidden">
                                                                    <div>
                                                                        <a href="{{$ite->getHref(true)}}">
                                                                            {{$ite->getTitle()}}
                                                                        </a>
                                                                    </div>
                                                                    <div class="grouped-pr-list-item__info">
                                                                        <div class="grouped-pr-list-item__price" >
                                                                            @if ($ite->price_main != $ite->price_pay)
                                                                                <del class="price_old">
                                                                                    <span class="number_format">{{$ite->price_main}}</span>
                                                                                    <span class="currency_format">₫</span>
                                                                                </del>
                                                                            @endif
                                                                            <ins>
                                                                                <span class="number_format text-bold" style="font-size: 20px;">{{$ite->price_pay}}</span>
                                                                                <span class="currency_format">₫</span>
                                                                            </ins>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr id="cart_combo_{{$ite->id}}">
                                                            <td class="text-center">
                                                                <div class="grouped-pr-list-item item_group_true">
                                                                    <div class="grouped-pr-list-item__quantity">
                                                                        <div class="quantity pr">
                                                                            <input type="number" class="input-text qty text tc qty_pr_js" step="1" min="0" max="9999"
                                                                                   name="combo_quantity" value="0" size="4" pattern="[0-9]*" inputmode="numeric"
                                                                                   onkeyup="jscartdhmqvalid(this)"
                                                                            >
                                                                            <div class="qty tc fs__14">
                                                                                <button type="button" class="plus btn_combo db cb pa pd__0 pr__15 tr r__0">
                                                                                    <i class="facl facl-plus"></i>
                                                                                </button>
                                                                                <button type="button" class="minus btn_combo db cb pa pd__0 pl__15 tl l__0">
                                                                                    <i class="facl facl-minus"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="grouped-pr-list-item__quantity text-center">
                                                                <div class="clearfix">
                                                                    <button type="button" data-time='6000' data-ani='shake' onclick="jscartdhcombo({{$ite->id}})"
                                                                            class=" button truncate order-4">
                                                                        <span class="txt_add text-uppercase">thêm vào giỏ</span>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <form method="post" action="" id="fgr_frm_id" accept-charset="UTF-8" class="variations_form fgr_frm" enctype="multipart/form-data" novalidate="novalidate">
                                            <table cellspacing="0" class="grouped-product-list group_table mb__20">
                                                <thead>
                                                <tr>
                                                    <td colspan="3">
                                                        <div class="text-uppercase text-bold">{{$apiCore->getSetting('text_sp_combo')}}</div>
                                                    </td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach($comboIds as $iteId):
                                                $ite = $apiCore->getItem('product', (int)$iteId);
                                                if (!$ite) {
                                                    continue;
                                                }
                                                ?>
                                                <tr id="cart_combo_{{$ite->id}}" class="grouped-pr-list-item item_group_true">
                                                    <td class="grouped-pr-list-item__thumb">
                                                        <a href="{{$ite->getHref(true)}}">
                                                            <img alt="{{$ite->getTitle()}}"
                                                                 src="{{$ite->getAvatar('normal')}}"
                                                                 data-widths="[80, 100, 200, 300, 400]"
                                                                 data-sizes="auto" class=" w__100 lz_op_ef lazyautosizes ls-is-cached lazyloaded"
                                                                 data-srcset="{{$ite->getAvatar('normal')}}"
                                                                 sizes="100px"
                                                                 srcset="{{$ite->getAvatar('normal')}}">
                                                        </a>
                                                    </td>
                                                    <td class="grouped-pr-list-item__info">
                                                        <a href="{{$ite->getHref(true)}}" class="dib mb__10 fwm">{{$ite->getTitle()}}</a>
                                                        <div class="grouped-pr-list-item__price" >
                                                            @if ($ite->price_main != $ite->price_pay)
                                                                <del class="price_old">
                                                                    <span class="number_format">{{$ite->price_main}}</span>
                                                                    <span class="currency_format">₫</span>
                                                                </del>
                                                            @endif
                                                            <ins>
                                                                <span class="number_format text-bold" style="font-size: 20px;">{{$ite->price_pay}}</span>
                                                                <span class="currency_format">₫</span>
                                                            </ins>
                                                        </div>
                                                    </td>
                                                    <td class="grouped-pr-list-item__quantity">
                                                        <div class="quantity pr">
                                                            <input type="number" class="input-text qty text tc qty_pr_js" step="1" min="0" max="9999"
                                                                   name="combo_quantity" value="0" size="4" pattern="[0-9]*" inputmode="numeric"
                                                                   onkeyup="jscartdhmqvalid(this)"
                                                            >
                                                            <div class="qty tc fs__14">
                                                                <button type="button" class="plus btn_combo db cb pa pd__0 pr__15 tr r__0">
                                                                    <i class="facl facl-plus"></i>
                                                                </button>
                                                                <button type="button" class="minus btn_combo db cb pa pd__0 pl__15 tl l__0">
                                                                    <i class="facl facl-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="clearfix mt__5">
                                                            <button type="button" data-time='6000' data-ani='shake' onclick="jscartdhcombo({{$ite->id}})"
                                                                    class=" button truncate  mt__5 order-4">
                                                                <span class="txt_add text-uppercase">thêm vào giỏ</span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach;?>
                                                </tbody>
                                            </table>
                                        </form>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="wrap_des_pr">
        <div class="container container_des">
            <div id="shopify-section-pr_description" class="shopify-section shopify-tabs sp-tabs">
                <ul class="ul_none ul_tabs is-flex fl_center fs__16 des_mb_2 des_style_1 tab_product_info">
                    <li class="tab_title_block ttct active">
                        <a class="db cg pr text-uppercase" href="javascript:void(0)" onclick="jssptab('ttct')">thông tin chi tiết</a>
                    </li>
                    @if (!empty($item->mo_ta_text))
                        <li class="tab_title_block ttmt">
                            <a class="db cg pr text-uppercase" href="javascript:void(0)" onclick="jssptab('ttmt')">mô tả</a>
                        </li>
                    @endif
                    @if (!empty($item->cong_dung_text))
                        <li class="tab_title_block ttcd">
                            <a class="db cg pr text-uppercase" href="javascript:void(0)" onclick="jssptab('ttcd')">mô tả</a>
                        </li>
                    @endif
                    @if (!empty($item->thanh_phan_text))
                        <li class="tab_title_block tttp">
                            <a class="db cg pr text-uppercase" href="javascript:void(0)" onclick="jssptab('tttp')">mô tả</a>
                        </li>
                    @endif
                    @if (!empty($item->huong_dan_su_dung_text))
                        <li class="tab_title_block tthd">
                            <a class="db cg pr text-uppercase" href="javascript:void(0)" onclick="jssptab('tthd')">mô tả</a>
                        </li>
                    @endif
                    <li class="tab_title_block ttdg">
                        <a class="db cg pr text-uppercase" href="javascript:void(0)" onclick="jssptab('ttdg')">đánh giá</a>
                    </li>
                </ul>

                <div class="row">
                    <div class="col-md-12 p-body">
                        <div class="p-body-content ttct">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-data__info table_product_info">
                                        @if ($item->getCategory())
                                            <tr>
                                                <td>{{$apiCore->getSetting('text_sp_nsp')}}</td>
                                                <td class="cate_cate">
                                                    <a href="{{$item->getCategory()->getHref()}}"
                                                       title="{{$item->getCategory()->getTitle()}}">
                                                        {{$item->getCategory()->getTitle()}}
                                                    </a>
                                                    @if ($item->getCategoryOthers())
                                                        @foreach($item->getCategoryOthers() as $ite)
                                                            <a href="{{$ite->getHref()}}"
                                                               title="{{$ite->getTitle()}}">
                                                                {{$ite->getTitle()}}
                                                            </a>
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($item->getBrand())
                                            <tr>
                                                <td>{{$apiCore->getSetting('text_sp_th')}}</td>
                                                <td>
                                                    <a href="{{$item->getBrand()->getHref()}}"
                                                       title="{{$item->getBrand()->getTitle()}}">{{$item->getBrand()->getTitle()}}</a>
                                                </td>
                                            </tr>
                                        @endif
                                        @if (!empty($item->made_in))
                                            <tr>
                                                <td>{{$apiCore->getSetting('text_sp_xx')}}</td>
                                                <td>
                                                    {{$item->getLabel('made_in')}}
                                                </td>
                                            </tr>
                                        @endif
                                        @if (!empty($item->kich_thuoc))
                                            <tr>
                                                <td>{{$apiCore->getSetting('text_sp_kt')}}</td>
                                                <td>
                                                    {{$item->kich_thuoc}}
                                                </td>
                                            </tr>
                                        @endif
                                        @if (!empty($item->the_tich))
                                            <tr>
                                                <td>{{$apiCore->getSetting('text_sp_tt')}}</td>
                                                <td>
                                                    {{$item->the_tich}}
                                                </td>
                                            </tr>
                                        @endif
                                        @if (!empty($item->can_nang))
                                            <tr>
                                                <td>{{$apiCore->getSetting('text_sp_cn')}}</td>
                                                <td>
                                                    {{$item->can_nang}}
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="p-body-content ttdg hidden" id="product_review">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-3 mt__20">
                                    <div class="star_wrapper">
                                        <div>
                                            <div class="rating-count-number" id="count_rev_5">{{$item->countReviewStar(5)}}</div>
                                            <div class="rating-group">
                                                <label aria-label="1 star" class="rating__label" for="rating-5-1"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-5-1" value="1" type="radio">
                                                <label aria-label="2 stars" class="rating__label" for="rating-2"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-5-2" value="2" type="radio">
                                                <label aria-label="3 stars" class="rating__label" for="rating-3"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-5-3" value="3" type="radio" >
                                                <label aria-label="4 stars" class="rating__label" for="rating-4"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-5-4" value="4" type="radio">
                                                <label aria-label="5 stars" class="rating__label" for="rating-5"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-5-5" value="5" type="radio" checked>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="rating-count-number" id="count_rev_4">{{$item->countReviewStar(4)}}</div>
                                            <div class="rating-group">
                                                <label aria-label="1 star" class="rating__label" for="rating-4-1"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-4-1" value="1" type="radio">
                                                <label aria-label="2 stars" class="rating__label" for="rating-2"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-4-2" value="2" type="radio">
                                                <label aria-label="3 stars" class="rating__label" for="rating-3"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-4-3" value="3" type="radio" >
                                                <label aria-label="4 stars" class="rating__label" for="rating-4"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-4-4" value="4" type="radio" checked>
                                                <label aria-label="5 stars" class="rating__label" for="rating-5"><i class="rating__icon rating__icon--none fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-4-5" value="5" type="radio">
                                            </div>
                                        </div>
                                        <div>
                                            <div class="rating-count-number" id="count_rev_3">{{$item->countReviewStar(3)}}</div>
                                            <div class="rating-group">
                                                <label aria-label="1 star" class="rating__label" for="rating-3-1"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-3-1" value="1" type="radio">
                                                <label aria-label="2 stars" class="rating__label" for="rating-2"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-3-2" value="2" type="radio">
                                                <label aria-label="3 stars" class="rating__label" for="rating-3"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-3-3" value="3" type="radio" checked>
                                                <label aria-label="4 stars" class="rating__label" for="rating-4"><i class="rating__icon rating__icon--none fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-3-4" value="4" type="radio">
                                                <label aria-label="5 stars" class="rating__label" for="rating-5"><i class="rating__icon rating__icon--none fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-3-5" value="5" type="radio">
                                            </div>
                                        </div>
                                        <div>
                                            <div class="rating-count-number" id="count_rev_2">{{$item->countReviewStar(2)}}</div>
                                            <div class="rating-group">
                                                <label aria-label="1 star" class="rating__label" for="rating-2-1"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-2-1" value="1" type="radio">
                                                <label aria-label="2 stars" class="rating__label" for="rating-2"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-2-2" value="2" type="radio" checked>
                                                <label aria-label="3 stars" class="rating__label" for="rating-3"><i class="rating__icon rating__icon--none fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-2-3" value="3" type="radio" >
                                                <label aria-label="4 stars" class="rating__label" for="rating-4"><i class="rating__icon rating__icon--none fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-2-4" value="4" type="radio">
                                                <label aria-label="5 stars" class="rating__label" for="rating-5"><i class="rating__icon rating__icon--none fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-2-5" value="5" type="radio">
                                            </div>
                                        </div>
                                        <div>
                                            <div class="rating-count-number" id="count_rev_1">{{$item->countReviewStar(1)}}</div>
                                            <div class="rating-group">
                                                <label aria-label="1 star" class="rating__label" for="rating-1-1"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-1-1" value="1" type="radio" checked>
                                                <label aria-label="2 stars" class="rating__label" for="rating-2"><i class="rating__icon rating__icon--none fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-1-2" value="2" type="radio">
                                                <label aria-label="3 stars" class="rating__label" for="rating-3"><i class="rating__icon rating__icon--none fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-1-3" value="3" type="radio" >
                                                <label aria-label="4 stars" class="rating__label" for="rating-4"><i class="rating__icon rating__icon--none fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-1-4" value="4" type="radio">
                                                <label aria-label="5 stars" class="rating__label" for="rating-5"><i class="rating__icon rating__icon--none fa fa-star"></i></label>
                                                <input class="rating__input" name="rating" id="rating-1-5" value="5" type="radio">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7 mt__20">
                                    <form action="" method="post" id="frm-review">
                                        @csrf
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 mb__10">
                                                        <div class="star_rating">
                                                            <div class="rating-group">
                                                                <input class="rating__input hidden" name="staring" id="rating3-star-0" value="0" type="radio" checked>
                                                                <label aria-label="1 star" class="rating__label" for="rating3-star-1"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                                <input class="rating__input" name="staring" id="rating3-star-1" value="1" type="radio">
                                                                <label aria-label="2 stars" class="rating__label" for="rating3-star-2"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                                <input class="rating__input" name="staring" id="rating3-star-2" value="2" type="radio">
                                                                <label aria-label="3 stars" class="rating__label" for="rating3-star-3"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                                <input class="rating__input" name="staring" id="rating3-star-3" value="3" type="radio">
                                                                <label aria-label="4 stars" class="rating__label" for="rating3-star-4"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                                <input class="rating__input" name="staring" id="rating3-star-4" value="4" type="radio">
                                                                <label aria-label="5 stars" class="rating__label" for="rating3-star-5"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                                <input class="rating__input" name="staring" id="rating3-star-5" value="5" type="radio">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if (!$viewer)
                                                    <div class="row">
                                                        <div class="col-md-3 mb__10">
                                                            <label class="frm-label required">* điện thoại</label>
                                                        </div>
                                                        <div class="col-md-9 mb__10" id="ele-phone">
                                                            <input required type="text" class="text-center border-radius-5px" name="phone" autocomplete="off"
                                                                   onkeypress="return isInputPhone(event, this)"
                                                                   oncopy="return false;" oncut="return false;" onpaste="return false;"
                                                            />

                                                            <div class="alert alert-danger mt__5 hidden text-center">Vui lòng nhập số điện thoại (>= 10 số)</div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 mb__10">
                                                            <label class="frm-label">email</label>
                                                        </div>
                                                        <div class="col-md-9 mb__10" id="ele-email">
                                                            <input type="email" class="text-center border-radius-5px" name="email" autocomplete="off" />

                                                            <div class="alert alert-danger mt__5 hidden text-center">Vui lòng nhập email hợp lệ</div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="row">
                                                    <div class="col-md-3 mb__10">
                                                        <label class="frm-label required">* nội dung đánh giá</label>
                                                    </div>
                                                    <div class="col-md-9 mb__10" id="ele-note">
                                                        <textarea required class="min_height_100px border-radius-5px" rows="3" name="note"></textarea>

                                                        <div class="alert alert-danger mt__5 hidden text-center">Vui lòng nhập đánh giá của bạn</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="row">
                                                    <div class="col-md-3 mb__10"></div>
                                                    <div class="col-md-9 mb__10 text-center">
                                                        <div class="alert alert-danger mt__5 mb__5 hidden"></div>
                                                    </div>
                                                </div>

                                                <div class="text-right">
                                                    <button class="button text-uppercase" type="submit">xác nhận</button>
                                                    <input type="hidden" name="star" />
                                                    <input type="hidden" name="item_id" value="{{$item->id}}" />
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-1"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="review_wrapper">
                                        @if (count($item->getReviews(['limit' => 5])))
                                            @foreach($item->getReviews(['limit' => 5]) as $review)
                                                @include('modals.front_end.product_review')
                                            @endforeach
                                        @endif
                                    </div>

                                    @if ($item->countReviews() > 5)
                                        <div class="review_more text-center mt__20">
                                            <button onclick="jsspdgmore()" class="button text-uppercase" type="button">xem thêm đánh giá</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (!empty($item->mo_ta_text))
                            <div class="p-body-content ttmt hidden">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo nl2br($item->mo_ta);?>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (!empty($item->thanh_phan_text))
                            <div class="p-body-content tttp hidden">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo nl2br($item->thanh_phan);?>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (!empty($item->cong_dung_text))
                            <div class="p-body-content ttcd hidden">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo nl2br($item->cong_dung);?>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (!empty($item->huong_dan_su_dung_text))
                            <div class="p-body-content tthd hidden">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php echo nl2br($item->huong_dan_su_dung);?>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (count($products_seen))
        <div class="container container_cat pop_default cat_default mb__60">
            <div class="row al_center fl_center title_10 ">
                <div class="col-auto col-md"><h3 class="dib tc section-title fs__22 text-uppercase">Sản phẩm liên quan</h3></div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-12">
                    <div id="shopify-section-collection_page" class="shopify-section tp_se_cdt">
                        <div class="nt_svg_loader dn"></div>
                        <div class="products nt_products_holder row fl_center row_pr_1 cdt_des_1 round_cd_false nt_cover ratio_nt position_8 space_30 nt_default">
                            @foreach($products_seen as $product)
                                <div
                                    class="col-lg-3 col-md-3 col-12 pr_animated done mt__30 pr_grid_item product nt_pr desgin__1">
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
        </div>
    @endif

    @if (count($products_viewed))
        <div class="container container_cat pop_default cat_default mb__60">
            <div class="row al_center fl_center title_10 ">
                <div class="col-auto col-md"><h3 class="dib tc section-title fs__22 text-uppercase">Sản phẩm đã xem</h3></div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-12">
                    <div id="shopify-section-collection_page" class="shopify-section tp_se_cdt">
                        <div class="nt_svg_loader dn"></div>
                        <div class="products nt_products_holder row fl_center row_pr_1 cdt_des_1 round_cd_false nt_cover ratio_nt position_8 space_30 nt_default">
                            @foreach($products_viewed as $product)
                                <div
                                    class="col-lg-3 col-md-3 col-12 pr_animated done mt__30 pr_grid_item product nt_pr desgin__1">
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
        </div>
    @endif

    @if (!empty($item->video_link))
        <div class="mfp-wrap mfp-close-btn-in mfp-auto-cursor mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_2 hidden" tabindex="-1" style="overflow: hidden auto;">
            <div class="mfp-container mfp-s-ready mfp-inline-holder">
                <div class="mfp-content">
                    <div class="popup_video">
                        @if ($isMobile)
                            <iframe width="400" height="300" src="{{$item->video_link}}" frameborder="0" allowfullscreen></iframe>
                        @else
                            <iframe width="600" height="450" src="{{$item->video_link}}" frameborder="0" allowfullscreen></iframe>
                        @endif
                    </div>
                    <button title="Close (Esc)" type="button" class="mfp-close" onclick="jsspvideoclose()">×</button>
                </div>
            </div>
        </div>
    @endif

    @if ($isMobile)
        <div class="fixed_bot">
            <div class="clearfix">
                <form method="post" action=""
                      id="cart-form_ppr" accept-charset="UTF-8"
                      class="nt_cart_form variations_form variations_form_ppr"
                      enctype="multipart/form-data"
                >
                    <div class="float-left mr__5 ml__5">
                        <div class="product-love sp-love-{{$item->id}}"
                             onclick="jssplove(this, {{$item->id}})">
                            @if ($item->isLoved())
                                <i class="fas fa-heart active" title="Đã Yêu Thích SP"></i>
                            @else
                                <i class="fas fa-heart" title="Thêm SP Yêu Thích"></i>
                            @endif
                        </div>
                    </div>
                    <div class="float-left mr__5 ml__5 variations_form">
                        <div class="quantity pr mr__10 order-1 qty__true" id="sp_qty_ppr">
                            <input type="number" class="input-text qty text tc qty_pr_js"
                                   step="1" min="1" max="9999" name="quantity" value="1"
                                   size="4" pattern="[0-9]*" inputmode="numeric"
                                   id="product_buy_quantity" onkeyup="jscartdhmqvalid(this)"
                            >
                            <div class="qty tc fs__14">
                                <a rel="nofollow" data-no-instant
                                   class="plus db cb pa pr__15 tr r__0" href="#">
                                    <i class="facl facl-plus"></i>
                                </a>
                                <a rel="nofollow" data-no-instant
                                   class="minus db cb pa pl__15 tl l__0" href="#">
                                    <i class="facl facl-minus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-hidden">
                        <button type="button" onclick="jscartdh({{$item->id}})"
                                class="single_add_to_cart_button button truncate w__100">
                            <i class="fa fa-cart-plus mr__5"></i> thêm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <script type="text/javascript">
        jQuery(document).ready(function () {
            @if(count($params) && isset($params['t']))
            jssptab('{{$params['t']}}');
            @endif
        });
    </script>
@stop

