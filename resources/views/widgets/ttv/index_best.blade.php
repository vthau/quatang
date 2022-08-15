<?php
$apiFE = new \App\Api\FE();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$products = $apiFE->getHotProducts(2);
?>

@if (count($products))
<div id="shopify-section-1590290189161" class="shopify-section nt_section type_banner2 type_packery">
    <div class="nt_se_1590290189161 container">
        <div class="mt__30 nt_banner_holder row fl_center js_packery cat_space_30"
             data-packery='{ "itemSelector": ".cat_space_item","gutter": 0,"percentPosition": true,"originLeft": true }'>
            <div class="grid-sizer"></div>
            <?php
            $count = 0;
            foreach ($products as $product):
            $count++;
            ?>
            @if ($count == 1)
            <div class="cat_space_item col-lg-8 col-md-6 col-12 pr_animated done" id="bk_1590290189161-0">
                <div class="banner_hzoom nt_promotion oh pr">
                    <div class="nt_bg_lz pr_lazy_img lazyload item__position "
                         data-bgset="{{$product->getAvatar2()}}"
                         data-ratio="2.6742857142857144" data-sizes="auto" data-parent-fit="cover"></div>
                    <a href="{{$product->getHref()}}" class="pa t__0 l__0 r__0 b__0"></a>
                    <div class="nt_promotion_html pa t__0 l__0 tl pe_none"><h3
                            style="font-size: 23px; margin: 0;" class="text-uppercase">{{$product->getCategory()->getTitle()}}</h3>

                        <p class="mg__0 fs__15" class="text-uppercase">{{$product->getTitle()}}</p>

                        <p style="margin: 0;">
                            @if ($product->price_main != $product->price_pay)
                                <del class="price_old">
                                    <span class="number_format">{{$product->price_main}}</span>
                                    <span class="currency_format">₫</span>
                                </del>
                            @endif
                            <strong style="font-size: 25px;  color:#e4573d">
                                <span class="number_format">{{$product->price_pay}}</span>
                                <span class="currency_format">₫</span>
                            </strong>

                        </p>

                        <style>
                            .post-info.mb__10 > h4 {
                                white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;
                            }

                            .nt_se_1590628119328 .cat_grid_item__content {
                                background: #f8f8f8;
                                padding-bottom: 15px;
                                border-radius: 5px;
                            }
                        </style>
                    </div>
                </div>
            </div>
            @elseif ($count == 2)
            <div class="cat_space_item col-lg-4 col-md-12 col-12 pr_animated done" id="bk_1590290189161-1">
                <div class="banner_hzoom nt_promotion oh pr">
                    <div class="nt_bg_lz pr_lazy_img lazyload item__position "
                         data-bgset="{{$product->getAvatar2()}}"
                         data-ratio="1.2942857142857143" data-sizes="auto" data-parent-fit="cover"></div>
                    <a href="{{$product->getHref()}}" class="pa t__0 l__0 r__0 b__0"></a>
                    <div class="nt_promotion_html pa t__0 l__0 tl pe_none"><p style="margin: 0;" class="text-uppercase">{{$product->getCategory()->getTitle()}}</p>
                        <h3 style="margin: 0;" class="text-uppercase">{{$product->getTitle()}}</h3>
                        <p style="margin: 0;">
                            @if ($product->price_main != $product->price_pay)
                                <del class="price_old">
                                    <span class="number_format">{{$product->price_main}}</span>
                                    <span class="currency_format">₫</span>
                                </del>
                            @endif
                            <strong style="font-size: 25px;  color:#e4573d">
                                <span class="number_format">{{$product->price_pay}}</span>
                                <span class="currency_format">₫</span>
                            </strong>

                        </p>

                        <a style="color: #fff; margin-top:10px;"
                           class="button_primary button pe_auto round_true" href="{{$product->getHref()}}">Mua Ngay</a></div>
                </div>
            </div>
            @endif
            <?php endforeach;?>
        </div>
    </div>

    <style data-shopify>
        .nt_se_1590290189161 {
            margin-top: 50px !important;
            margin-right: !important;
            margin-bottom: 50px !important;
            margin-left: !important;
        }
        #bk_1590290189161-0 .nt_promotion_html {
            top: 50%;
            left: 8%;
            transform: translate(-8%, -50%);
        }

        #bk_1590290189161-0 .nt_promotion_html, #bk_1590290189161-0 .nt_promotion_html > *, #bk_1590290189161-0 .nt_promotion_html .btn_icon_true:after {
            color: #222222
        }

        #bk_1590290189161-0 .nt_promotion > a:after {
            background-color: #000;
            opacity: 0.0
        }

        #bk_1590290189161-0 .nt_bg_lz {
            padding-top: 37.39316239316239%;
        }

        #bk_1590290189161-1 .nt_promotion_html {
            top: 50%;
            left: 12%;
            transform: translate(-12%, -50%);
        }

        #bk_1590290189161-1 .nt_promotion_html, #bk_1590290189161-1 .nt_promotion_html > *, #bk_1590290189161-1 .nt_promotion_html .btn_icon_true:after {
            color: #222222
        }

        #bk_1590290189161-1 .nt_promotion > a:after {
            background-color: #000;
            opacity: 0.0
        }

        #bk_1590290189161-1 .nt_bg_lz {
            padding-top: 77.2626931567329%;
        }
    </style>
</div>
@endif

<?php
$products = $apiFE->getRandomProducts(10);
?>
@if (count($products))
    <div id="shopify-section-1590661766300" class="shopify-section nt_section type_featured_collection tp_se_cdt">
        <div class="nt_se_1590661766300 container">
            <div class="row al_center fl_center title_10 ">
                <div class="col-auto col-md"><h3 class="dib tc section-title fs__24 text-uppercase">Sản phẩm được yêu thích</h3></div>
            </div>
            <div class="products nt_products_holder row fl_center row_pr_1 cdt_des_1 round_cd_false nt_cover ratio_nt position_8 space_30">
                <?php
                $count = 0;
                foreach ($products as $product):
                $count++;
                ?>
                    <div class="col-lg-15 col-md-3 col-6 pr_animated done mt__30 pr_grid_item product nt_pr desgin__1">
                        <div class="product-inner pr">
                            <div class="product-image pr oh lazyloaded product-custom" >
                                <span class="tc nt_labels pa pe_none cw">
                                    @if ($product->discount)
                                    <span class="onsale nt_label">-{{$product->discount}}%</span>
                                    @endif
                                </span>
                                <a class="db" href="{{$product->getHref(true)}}">
                                    <div class="pr_lazy_img main-img nt_img_ratio nt_bg_lz lazyloaded"
                                         data-bgset="{{$product->getAvatar()}}" data-parent-fit="width" data-wiis="" data-ratio="1.0"
                                         style="padding-top: 100%; background-image: url('{{$product->getAvatar()}}');">
                                    </div>
                                </a><div class="hover_img pa pe_none t__0 l__0 r__0 b__0 op__0">
                                    <div class="pr_lazy_img back-img pa nt_bg_lz lazyloaded"
                                         data-bgset="{{$product->getAvatar()}}" data-parent-fit="width" data-wiis="" data-ratio="1.0"
                                         style="padding-top: 100%; background-image: url('{{$product->getAvatar()}}');">
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
                                    <a href="javascript:void(0)" onclick="jscartdh({{$product->id}})"
                                       class="pr pr_atc cd br__40 bgw tc dib  cb chp ttip_nt tooltip_top_left"
                                    >
                                        <span class="tt_txt text-capitalize">thêm vào giỏ</span>
                                        <i class="iccl iccl-cart"></i>
                                        <span class="text-capitalize">thêm vào giỏ</span>
                                    </a>
                                </div>

                            </div>
                            <div class="product-info mt__15">
                                <h3 class="product-title pr fs__14 mg__0 fwm">
                                    <a class="cd chp" href="{{$product->getHref(true)}}">{{$product->getTitle()}}</a>
                                </h3>
                                <span class="price dib mb__5">
                                    @if ($product->price_main != $product->price_pay)
                                        <del class="price_old">
                                        <span class="money number_format" >{{$product->price_main}}</span><span class="currency_format">₫</span>
                                    </del>
                                    @endif
                                    <ins class=" text-bold">
                                        <span class="money number_format" >{{$product->price_pay}}</span><span class="currency_format">₫</span>
                                    </ins>

                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
@endif
