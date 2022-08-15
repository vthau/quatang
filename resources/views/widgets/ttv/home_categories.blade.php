<?php
$apiFE = new \App\Api\FE();
$apiCore = new \App\Api\Core();
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$bg1 = $apiCore->getSetting('cate_bg_1');
$bg2 = $apiCore->getSetting('cate_bg_2');
$bg3 = $apiCore->getSetting('cate_bg_3');

$title1 = $apiCore->getSetting('cate_title_1');
$title2 = $apiCore->getSetting('cate_title_2');
$title3 = $apiCore->getSetting('cate_title_3');

$link1 = $apiCore->getSetting('cate_link_1');
$link2 = $apiCore->getSetting('cate_link_2');
$link3 = $apiCore->getSetting('cate_link_3');
?>

<div id="shopify-section-1590219862375" class="shopify-section nt_section type_banner2 type_packery">
    <div class="nt_se_1590219862375 container">
        <div class="mt__30 nt_banner_holder row fl_center js_packery cat_space_30"
             data-packery='{ "itemSelector": ".cat_space_item","gutter": 0,"percentPosition": true,"originLeft": true }'>
            <div class="grid-sizer"></div>

            <div class="cat_space_item col-lg-4 col-md-6 col-12 pr_animated done" id="bk_1590219862375-0">
                <div class="banner_hzoom nt_promotion oh pr">
                    <div class="nt_bg_lz pr_lazy_img lazyload item__position "
                         data-bgset="{{url('public/' . $bg1)}}"
                         data-ratio="1.6777777777777778" data-sizes="auto" data-parent-fit="cover">

                    </div>
                    <a href="{{$link1}}" class="pa t__0 l__0 r__0 b__0"></a>
                    <div class="nt_promotion_html pa t__0 l__0 tl pe_none">
                        <h3 style="margin: 0;">{{$title1}}</h3>
                    </div>
                </div>
            </div>

            <div class="cat_space_item col-lg-4 col-md-6 col-12 pr_animated done" id="bk_1590219862375-1">
                <div class="banner_hzoom nt_promotion oh pr">
                    <div class="nt_bg_lz pr_lazy_img lazyload item__position "
                         data-bgset="{{url('public/' . $bg2)}}"
                         data-ratio="1.6535714285714285" data-sizes="auto" data-parent-fit="cover"></div>
                    <a href="{{$link2}}" class="pa t__0 l__0 r__0 b__0"></a>
                    <div class="nt_promotion_html pa t__0 l__0 tl pe_none">
                        <h3 style="margin: 0;">{{$title2}}</h3>
                    </div>
                </div>
            </div>

            <div class="cat_space_item col-lg-4 col-md-6 col-12 pr_animated done" id="bk_1590286940815">
                <div class="banner_hzoom nt_promotion oh pr">
                    <div class="nt_bg_lz pr_lazy_img lazyload item__position "
                         data-bgset="{{url('public/' . $bg3)}}"
                         data-ratio="1.6777777777777778" data-sizes="auto" data-parent-fit="cover"></div>
                    <a href="{{$link3}}" class="pa t__0 l__0 r__0 b__0"></a>
                    <div class="nt_promotion_html pa t__0 l__0 tl pe_none">
                        <h3 style="margin: 0;">{{$title3}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style data-shopify>
        .nt_se_1590219862375 {
            margin-top: 30px !important;
            margin-right: !important;
            margin-bottom: 30px !important;
            margin-left: !important;
        }
    </style>
    <style data-shopify>
        #bk_1590219862375-0 .nt_promotion_html {
            top: 50%;
            left: 11%;
            transform: translate(-11%, -50%);
        }

        #bk_1590219862375-0 .nt_promotion_html, #bk_1590219862375-0 .nt_promotion_html > *, #bk_1590219862375-0 .nt_promotion_html .btn_icon_true:after {
            color: #222222
        }

        #bk_1590219862375-0 .nt_promotion > a:after {
            background-color: #000;
            opacity: 0.0
        }

        #bk_1590219862375-0 .nt_bg_lz {
            padding-top: 59.60264900662252%;
        }

        #bk_1590219862375-1 .nt_promotion_html {
            top: 50%;
            left: 11%;
            transform: translate(-11%, -50%);
        }

        #bk_1590219862375-1 .nt_promotion_html, #bk_1590219862375-1 .nt_promotion_html > *, #bk_1590219862375-1 .nt_promotion_html .btn_icon_true:after {
            color: #222222
        }

        #bk_1590219862375-1 .nt_promotion > a:after {
            background-color: #000;
            opacity: 0.0
        }

        #bk_1590219862375-1 .nt_bg_lz {
            padding-top: 60.47516198704104%;
        }

        #bk_1590286940815 .nt_promotion_html {
            top: 50%;
            left: 11%;
            transform: translate(-11%, -50%);
        }

        #bk_1590286940815 .nt_promotion_html, #bk_1590286940815 .nt_promotion_html > *, #bk_1590286940815 .nt_promotion_html .btn_icon_true:after {
            color: #222222
        }

        #bk_1590286940815 .nt_promotion > a:after {
            background-color: #000;
            opacity: 0.0
        }

        #bk_1590286940815 .nt_bg_lz {
            padding-top: 59.60264900662252%;
        }</style>
</div>

