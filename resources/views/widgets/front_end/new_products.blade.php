<?php
$apiFE = new \App\Api\FE();
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$products = $apiFE->getProducts(['limit' => 10, 'random' => 1, 'is_new' => 1]);
?>

<style data-shopify>
    .nt_se_1590633365311 {
        margin-top: 60px !important;
        margin-right: !important;
        margin-bottom: 50px !important;
        margin-left: !important;
    }

    .nt_se_1590633365311 .medizin_laypout {
        border-color: #4e97fd
    }

    .nt_se_1590633365311 .loop-product-stock .sold-bar {
        background-image: -webkit-linear-gradient(215deg, #4e97fd 0%, #77ccfd 100%);
        background-image: linear-gradient(235deg, #4e97fd 0%, #77ccfd 100%);
        border-radius: 4px;
    }

    .price .number_format {
        font-size: 17px;
    }
    .price .currency_format {
        font-size: 10px !important;
    }
</style>

@if (count($products))
    <div id="shopify-section-1590633365311"
         class="shopify-section nt_section type_prs_countd_deal type_carousel tp_se_cdt">

        <div class="nt_se_1590633365311 container ">
            <div class="medizin_laypout">
                <div class="product-cd-header in_flex wrap al_center fl_center tc ">
                    <h6 class="product-cd-heading section-title text-uppercase">sản phẩm mới</h6>
{{--                    <div class="countdown-wrap in_flex fl_center al_center wrap pr_deal_dt hidden">--}}
{{--                        <div class="countdown-label"></div>--}}
{{--                        <div class="countdown pr_coun_dt"--}}
{{--                             data-date="{{date('Y/m/d', strtotime("+2 days", time()))}}"></div>--}}
{{--                    </div>--}}
                </div>
                <div
                    class="products nt_products_holder row fl_center row_pr_1 js_carousel nt_slider nt_cover ratio1_1 position_8 space_ prev_next_3 btn_owl_1 dot_owl_1 dot_color_1 btn_vi_2 equal_nt"
                    data-flickity='{"imagesLoaded": 0,"adaptiveHeight": 0, "contain": 1, "groupCells": "100%", "dragThreshold" : 5, "cellAlign": "left","wrapAround": true,"prevNextButtons": true,"percentPosition": 1,"pageDots": false, "autoPlay" : 0, "pauseAutoPlayOnHover" : true, "rightToLeft": false }'>
                    <?php
                    $count = 0;
                    foreach ($products as $product):
                    $count++;
                    ?>
                        @if ($isMobile)
                            <div
                                class="col-lg-15 col-md-3 col-12 pr_animated done mt__10 pr_grid_item product nt_pr desgin__1 ">
                                <div class="product-inner pr droplets_width_sm index_sale_item">
                                    <div class="product-info mb__15">
                                        <h3 class="product-title pr fs__14 mg__0 fwm">
                                            <a class="cd chp" href="{{$product->getHref(true)}}">{{$product->getTitle()}}</a>
                                        </h3>
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
                                    <div class="cat_grid_item__overlay item__position nt_bg_lz lazyload center product-custom"
                                         data-bgset="{{$product->getAvatar()}}">
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
                                    </div>
                                </div>
                            </div>
                        @else
                            <div
                                class="col-lg-15 col-md-3 col-6 pr_animated done mt__10 pr_grid_item product nt_pr desgin__1 ">
                                <div class="product-inner pr droplets_width_sm index_sale_item">
                                    <div class="product-info mb__15">
                                        <h3 class="product-title pr fs__14 mg__0 fwm">
                                            <a class="cd chp" href="{{$product->getHref(true)}}">{{$product->getTitle()}}</a>
                                        </h3>
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
                                    <div class="cat_grid_item__overlay item__position nt_bg_lz lazyload center product-custom"
                                         data-bgset="{{$product->getAvatar()}}">
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
                                    </div>
                                </div>
                            </div>
                        @endif
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
@endif
