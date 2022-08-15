<?php
$apiFE = new \App\Api\FE();
$apiCore = new \App\Api\Core();
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$bg1 = $isMobile ? $apiCore->getSetting('mobi_banner_bg_1') : $apiCore->getSetting('banner_bg_1');
$bg2 = $isMobile ? $apiCore->getSetting('mobi_banner_bg_2') : $apiCore->getSetting('banner_bg_2');
$bg3 = $isMobile ? $apiCore->getSetting('mobi_banner_bg_3') : $apiCore->getSetting('banner_bg_3');
$bg4 = $isMobile ? $apiCore->getSetting('mobi_banner_bg_4') : $apiCore->getSetting('banner_bg_4');

$title1 = $apiCore->getSetting('banner_title_1');
$title2 = $apiCore->getSetting('banner_title_2');
$title3 = $apiCore->getSetting('banner_title_3');
$title4 = $apiCore->getSetting('banner_title_4');

$link1 = $apiCore->getSetting('banner_link_1');
$link2 = $apiCore->getSetting('banner_link_2');
$link3 = $apiCore->getSetting('banner_link_3');
$link4 = $apiCore->getSetting('banner_link_4');

?>

<style data-shopify>
    #shopify-section-1581505806578 {
        background-color: #efefef !important;
    }
</style>
<style data-shopify>
    .nt_se_1581505806578 .img_slider_block {
        padding-top: 400px
    }

    @media (min-width: 768px) {
        .nt_se_1581505806578 .img_slider_block {
            padding-top: 550px
        }
    }

    @media (min-width: 1025px) {
        .nt_se_1581505806578 .img_slider_block {
            padding-top: 620px
        }
    }

    #nt_1585640154849 .nt_img_txt > a:after {
        background-color: #000;
        opacity: 0.0
    }

    #nt_1585640154849 .pa_txts {
        top: 50%;
        left: 25%;
        transform: translate(-25%, -50%);
    }

    @media (min-width: 768px) {
        #nt_1585640154849 .pa_txts {
            top: 50%;
            left: 0%;
            transform: translate(-0%, -50%);
        }
    }

    #b_1585640505189 {
        font-size: 15px;
        font-weight: 500;
        color: #e4573d
    }

    @media (min-width: 768px) {
        #b_1585640505189 {
            font-size: 22px
        }
    }

    #b_1585640508369 {
        height: 7px
    }

    @media (min-width: 768px) {
        #b_1585640508369 {
            height: 15px
        }
    }

    #b_1585640512264 {
        font-size: 29px;
        font-weight: 600;
        color: #222222
    }

    @media (min-width: 768px) {
        #b_1585640512264 {
            font-size: 45px
        }
    }

    #b_1585640518653 {
        height: 10px
    }

    @media (min-width: 768px) {
        #b_1585640518653 {
            height: 20px
        }
    }

    #b_1590565477331 {
        font-size: 14px;
        font-weight: 400;
        color: #696969
    }

    @media (min-width: 768px) {
        #b_1590565477331 {
            font-size: 16px
        }
    }

    #b_1590375921354 {
        height: 10px
    }

    @media (min-width: 768px) {
        #b_1590375921354 {
            height: 20px
        }
    }

    #b_1585640453760 {
        font-weight: 600;
        min-height: 45px;
        color: #ffffff;
        background-color: #4e97fd;
        border-color: #4e97fd
    }

    #b_1585640453760.btn_icon_true:after {
        color: #ffffff
    }

    #nt_1585640159361 .nt_img_txt > a:after {
        background-color: #000;
        opacity: 0.0
    }

    #nt_1585640159361 .pa_txts {
        top: 50%;
        left: 25%;
        transform: translate(-25%, -50%);
    }

    @media (min-width: 768px) {
        #nt_1585640159361 .pa_txts {
            top: 50%;
            left: 0%;
            transform: translate(-0%, -50%);
        }
    }

    #b_1585640474337 {
        font-size: 16px;
        font-weight: 600;
        color: #e4573d
    }

    @media (min-width: 768px) {
        #b_1585640474337 {
            font-size: 22px
        }
    }

    #b_1585640490350 {
        height: 7px
    }

    @media (min-width: 768px) {
        #b_1585640490350 {
            height: 15px
        }
    }

    #b_1585640480446 {
        font-size: 29px;
        font-weight: 600;
        color: #222222
    }

    @media (min-width: 768px) {
        #b_1585640480446 {
            font-size: 45px
        }
    }

    #b_1585640492985 {
        height: 10px
    }

    @media (min-width: 768px) {
        #b_1585640492985 {
            height: 20px
        }
    }

    #b_1590376518903 {
        font-size: 14px;
        font-weight: 400;
        color: #696969
    }

    @media (min-width: 768px) {
        #b_1590376518903 {
            font-size: 16px
        }
    }

    #b_1590376535412 {
        height: 10px
    }

    @media (min-width: 768px) {
        #b_1590376535412 {
            height: 20px
        }
    }

    #b_1585640485544 {
        font-weight: 600;
        min-height: 45px;
        color: #ffffff;
        background-color: #4e97fd;
        border-color: #4e97fd
    }

    #b_1585640485544.btn_icon_true:after {
        color: #ffffff
    }

    #nt_1585640162346 .nt_img_txt > a:after {
        background-color: #000;
        opacity: 0.0
    }

    #nt_1585640162346 .pa_txts {
        top: 50%;
        left: 25%;
        transform: translate(-25%, -50%);
    }

    @media (min-width: 768px) {
        #nt_1585640162346 .pa_txts {
            top: 50%;
            left: 0%;
            transform: translate(-0%, -50%);
        }
    }

    #b_1585640443284 {
        font-size: 14px;
        font-weight: 400;
        color: #222222
    }

    @media (min-width: 768px) {
        #b_1585640443284 {
            font-size: 22px
        }
    }

    #b_1585640460694 {
        height: 10px
    }

    @media (min-width: 768px) {
        #b_1585640460694 {
            height: 15px
        }
    }

    #b_1585640447349 {
        font-size: 28px;
        font-weight: 600;
        color: #222222
    }

    @media (min-width: 768px) {
        #b_1585640447349 {
            font-size: 45px
        }
    }

    #b_1590563062221 {
        height: 4px
    }

    @media (min-width: 768px) {
        #b_1590563062221 {
            height: 10px
        }
    }

    #b_1590563045753 {
        font-size: 28px;
        font-weight: 600;
        color: #222222
    }

    @media (min-width: 768px) {
        #b_1590563045753 {
            font-size: 45px
        }
    }

    #b_1585640466340 {
        height: 10px
    }

    @media (min-width: 768px) {
        #b_1585640466340 {
            height: 20px
        }
    }

    #b_1590375776939 {
        font-size: 14px;
        font-weight: 400;
        color: #696969
    }

    @media (min-width: 768px) {
        #b_1590375776939 {
            font-size: 16px
        }
    }

    #b_1590564996993 {
        height: 10px
    }

    @media (min-width: 768px) {
        #b_1590564996993 {
            height: 20px
        }
    }

    #b_1585640524232 {
        font-weight: 600;
        min-height: 45px;
        color: #ffffff;
        background-color: #4e97fd;
        border-color: #4e97fd
    }

    #b_1585640524232.btn_icon_true:after {
        color: #ffffff
    }
</style>

<div id="shopify-section-1581505806578" class="shopify-section nt_section type_slideshow type_carousel">
    <div class="SlideshowWrapper nt_se_1581505806578 nt_full se_height_cus_h nt_first">
        <div class="fade_flick_1 slideshow row no-gutters equal_nt nt_slider js_carousel prev_next_0 btn_owl_1 dot_owl_2 dot_color_1 btn_vi_2"
            data-flickity='{ "fade":0,"cellAlign": "center","imagesLoaded": 0,"lazyLoad": 0,"freeScroll": 0,"wrapAround": true,"autoPlay" : 0,"pauseAutoPlayOnHover" : true, "rightToLeft": false, "prevNextButtons": false,"pageDots": true, "contain" : 1,"adaptiveHeight" : 1,"dragThreshold" : 5,"percentPosition": 1 }'>
            <div id="nt_1585640154849" class="col-12 slideshow__slide">
                <a href="{{$link1}}">
                    <div class="oh pr nt_img_txt">
                        <div class="js_full_ht4 img_slider_block dek_img_slide">
                            <div class="nt_bg_lz lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0"
                                data-bgset="{{url('public/') . $bg1}}"
                                data-ratio="3.096774193548387" data-sizes="auto"></div>
                        </div>
                        <div class="js_full_ht4 img_slider_block mb_img_slide">
                            <div class="nt_bg_lz lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0"
                                data-bgset="{{url('public/') . $bg1}}"
                                data-ratio="3.096774193548387" data-sizes="auto"></div>
                        </div>
                        <div class="caption-wrap caption-w-1 pe_none z_100 tl_md tl">
                            <div class="pa_txts caption">
                                <div class="left_right">
                                    <div id="b_1585640508369" class="slt4_space"></div>
                                    <h3 id="b_1585640512264" class="slt4_h3 lh__1 mg__0 max_width_500px">{{$title1}}</h3>
                                    <div id="b_1585640518653" class="slt4_space"></div>
                                </div>
                            </div>
                        </div>
                        <a href="#" target="_blank" class="pa t__0 l__0 b__0 r__0 pe_none"></a>
                    </div>
                </a>
            </div>
            <div id="nt_1585640159361" class="col-12 slideshow__slide">
                <a href="{{$link2}}">
                    <div class="oh pr nt_img_txt">
                        <div class="js_full_ht4 img_slider_block dek_img_slide">
                            <div class="nt_bg_lz lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0"
                                data-bgset="{{url('public/') . $bg2}}"
                                data-ratio="3.0917874396135265" data-sizes="auto"></div>
                        </div>
                        <div class="js_full_ht4 img_slider_block mb_img_slide">
                            <div class="nt_bg_lz lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0"
                                data-bgset="{{url('public/') . $bg2}}"
                                data-ratio="3.0917874396135265" data-sizes="auto"></div>
                        </div>
                        <div class="caption-wrap caption-w-1 pe_none z_100 tl_md tl">
                            <div class="pa_txts caption">
                                <div id="b_1585640490350" class="slt4_space"></div>
                                <h3 id="b_1585640480446" class="slt4_h3 lh__1 mg__0 max_width_500px">{{$title2}}</h3>
                                <div id="b_1585640492985" class="slt4_space"></div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div id="nt_1585640162346" class="col-12 slideshow__slide">
                <a href="{{$link3}}">
                    <div class="oh pr nt_img_txt">
                        <div class="js_full_ht4 img_slider_block dek_img_slide">
                            <div class="nt_bg_lz lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0"
                                data-bgset="{{url('public/') . $bg3}}"
                                data-ratio="3.096774193548387" data-sizes="auto"></div>
                        </div>
                        <div class="js_full_ht4 img_slider_block mb_img_slide">
                            <div class="nt_bg_lz lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0"
                                data-bgset="{{url('public/') . $bg3}}"
                                data-ratio="3.096774193548387" data-sizes="auto"></div>
                        </div>
                        <div class="caption-wrap caption-w-1 pe_none z_100 tl_md tl">
                            <div class="pa_txts caption">
                                <div id="b_1585640490350" class="slt4_space"></div>
                                <h3 id="b_1585640480446" class="slt4_h3 lh__1 mg__0 max_width_500px">{{$title3}}</h3>
                                <div id="b_1585640492985" class="slt4_space"></div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div id="nt_1585640162346" class="col-12 slideshow__slide">
                <a href="{{$link4}}">
                    <div class="oh pr nt_img_txt">
                        <div class="js_full_ht4 img_slider_block dek_img_slide">
                            <div class="nt_bg_lz lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0"
                                data-bgset="{{url('public/') . $bg4}}"
                                data-ratio="3.096774193548387" data-sizes="auto"></div>
                        </div>
                        <div class="js_full_ht4 img_slider_block mb_img_slide">
                            <div class="nt_bg_lz lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0"
                                data-bgset="{{url('public/') . $bg4}}"
                                data-ratio="3.096774193548387" data-sizes="auto"></div>
                        </div>
                        <div class="caption-wrap caption-w-1 pe_none z_100 tl_md tl">
                            <div class="pa_txts caption">
                                <div id="b_1585640490350" class="slt4_space"></div>
                                <h3 id="b_1585640480446" class="slt4_h3 lh__1 mg__0 max_width_500px">{{$title4}}</h3>
                                <div id="b_1585640492985" class="slt4_space"></div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function () {
        setInterval(function () {
            changeBG();
        }, 5888);
    });

    var curBG = 1;
    function changeBG() {
        var bind = jQuery('#shopify-section-1581505806578');
        curBG++;

        if (curBG > 4) {
            curBG = 1;
        }

        var count = 0;
        bind.find('.flickity-page-dots li').each(function (pos, ele) {
            count++;

            if (count === curBG) {
                jQuery(ele)[0].click();
            }
        });
    }
</script>
