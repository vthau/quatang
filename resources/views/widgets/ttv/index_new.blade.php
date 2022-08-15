<?php
$apiFE = new \App\Api\FE();
$products = $apiFE->getNewProducts(3);
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();
?>

@if (count($products))

    <div id="shopify-section-1581505806578" class="shopify-section nt_section type_slideshow type_carousel">
        <div class="SlideshowWrapper nt_se_1581505806578 nt_full se_height_cus_h nt_first">
            <div
                class="fade_flick_1 slideshow row no-gutters equal_nt nt_slider js_carousel prev_next_0 btn_owl_1 dot_owl_2 dot_color_1 btn_vi_2"
                data-flickity='{ "fade":0,"cellAlign": "center","imagesLoaded": 0,"lazyLoad": 0,"freeScroll": 0,"wrapAround": true,"autoPlay" : 0,"pauseAutoPlayOnHover" : true, "rightToLeft": false, "prevNextButtons": false,"pageDots": true, "contain" : 1,"adaptiveHeight" : 1,"dragThreshold" : 5,"percentPosition": 1 }'>
                <?php
                $count = 0;
                foreach ($products as $product):
                $count++;
                ?>
                @if ($count == 1)
                    <div id="nt_1585640154849" class="col-12 slideshow__slide">
                        <div class="oh pr nt_img_txt">
                            <div class="js_full_ht4 img_slider_block dek_img_slide">
                                <div
                                    class="nt_bg_lz lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0"
                                    data-bgset="{{$product->getAvatar2()}}"
                                    data-ratio="3.096774193548387" data-sizes="auto"></div>
                            </div>
                            <div class="js_full_ht4 img_slider_block mb_img_slide">
                                <div
                                    class="nt_bg_lz lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0"
                                    data-bgset="{{$product->getAvatar2()}}"
                                    data-ratio="3.096774193548387" data-sizes="auto"></div>
                            </div>
                            <div class="caption-wrap caption-w-1 pe_none z_100 tl_md tl">
                                <div class="pa_txts caption">
                                    <div class="left_right">
                                        <h4 id="b_1585640505189" class="slt4_h4 mg__0 lh__1 f_body text-uppercase">
                                            {{$product->getCategory()->getTitle()}}
                                        </h4>
                                        <div id="b_1585640508369" class="slt4_space"></div>
                                        <h3 id="b_1585640512264" class="slt4_h3 lh__1 mg__0 max_width_500px">{{$product->getTitle()}}</h3>
                                        <div id="b_1585640518653" class="slt4_space"></div>
                                        <p id="b_1590565477331" class="slt4_p mg__0 dn db_md max_width_500px">{{$product->mo_ta_ngan}}</p>
                                        <div id="b_1590375921354" class="slt4_space"></div>
                                        <a id="b_1585640453760"
                                           class="slt4_btn button pe_auto round_true btn_icon_false"
                                           href="{{$product->getHref(true)}}" target="_blank">Mua Ngay</a></div>
                                </div>
                            </div>
                            <a href="#" target="_blank" class="pa t__0 l__0 b__0 r__0 pe_none"></a>
                        </div>
                    </div>
                @elseif ($count == 2)
                    <div id="nt_1585640159361" class="col-12 slideshow__slide">
                        <div class="oh pr nt_img_txt">
                            <div class="js_full_ht4 img_slider_block dek_img_slide">
                                <div
                                    class="nt_bg_lz lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0"
                                    data-bgset="{{$product->getAvatar2()}}"
                                    data-ratio="3.0917874396135265" data-sizes="auto"></div>
                            </div>
                            <div class="js_full_ht4 img_slider_block mb_img_slide">
                                <div
                                    class="nt_bg_lz lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0"
                                    data-bgset="{{$product->getAvatar2()}}"
                                    data-ratio="3.0917874396135265" data-sizes="auto"></div>
                            </div>
                            <div class="caption-wrap caption-w-1 pe_none z_100 tl_md tl">
                                <div class="pa_txts caption">
                                    <div class="left_right"><h4 id="b_1585640474337" class="slt4_h4 mg__0 lh__1 f_body text-uppercase">{{$product->getCategory()->getTitle()}}</h4>
                                        <div id="b_1585640490350" class="slt4_space"></div>
                                        <h3 id="b_1585640480446" class="slt4_h3 lh__1 mg__0 max_width_500px">{{$product->getTitle()}}</h3>
                                        <div id="b_1585640492985" class="slt4_space"></div>
                                        <p id="b_1590376518903" class="slt4_p mg__0 dn db_md max_width_500px">{{$product->mo_ta_ngan}}</p>
                                        <div id="b_1590376535412" class="slt4_space"></div>
                                        <a id="b_1585640485544"
                                           class="slt4_btn button pe_auto round_true btn_icon_false" href="{{$product->getHref(true)}}"
                                           target="_blank">Xem Thêm</a></div>
                                </div>
                            </div>
                            <a href="#" target="_blank" class="pa t__0 l__0 b__0 r__0 pe_none"></a>
                        </div>
                    </div>
                @elseif ($count == 3)
                    <div id="nt_1585640162346" class="col-12 slideshow__slide">
                        <div class="oh pr nt_img_txt">
                            <div class="js_full_ht4 img_slider_block dek_img_slide">
                                <div
                                    class="nt_bg_lz lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0"
                                    data-bgset="{{$product->getAvatar2()}}"
                                    data-ratio="3.096774193548387" data-sizes="auto"></div>
                            </div>
                            <div class="js_full_ht4 img_slider_block mb_img_slide">
                                <div
                                    class="nt_bg_lz lazyload item__position center center img_tran_ef pa l__0 t__0 r__0 b__0"
                                    data-bgset="{{$product->getAvatar2()}}"
                                    data-ratio="3.096774193548387" data-sizes="auto"></div>
                            </div>
                            <div class="caption-wrap caption-w-1 pe_none z_100 tl_md tl">
                                <div class="pa_txts caption">
                                    <div class="left_right"><h4 id="b_1585640443284" class="slt4_h4 mg__0 lh__1 f_body text-uppercase">{{$product->getCategory()->getTitle()}}</span>
                                        </h4>
                                        <div id="b_1585640460694" class="slt4_space"></div>
                                        <h3 id="b_1585640447349" class="slt4_h3 lh__1 mg__0 max_width_500px">{{$product->getTitle()}}</h3>
                                        <div id="b_1585640466340" class="slt4_space"></div>
                                        <p id="b_1590375776939" class="slt4_p mg__0 dn db_md max_width_500px">{{$product->mo_ta_ngan}}</p>
                                        <div id="b_1590564996993" class="slt4_space"></div>
                                        <a id="b_1585640524232"
                                           class="slt4_btn button pe_auto round_true btn_icon_false"
                                           href="{{$product->getHref(true)}}" target="_blank">Chi Tiết</a></div>
                                </div>
                            </div>
                            <a href="#" target="_blank" class="pa t__0 l__0 b__0 r__0 pe_none"></a>
                        </div>
                    </div>
                @endif
                <?php endforeach;?>
            </div>
        </div>

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
            }</style>
    </div>

@endif
