<?php

use Illuminate\Support\Facades\Lang;

$apiFE = new \App\Api\FE;
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$apiCore = new \App\Api\Core;


if (!isset($products) || !count($products)) {
    return;
}

$lastPage = $products->lastPage();
?>

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

<div class="container container_cat pop_default cat_default mb__60">
    <div class="cat_toolbar row fl_center al_center mt__30">
{{--            <div class="cat_filter col op__0 pe_none"><a rel="nofollow" href="#" data-no-instant=""--}}
{{--                                                         data-opennt="#shopify-section-nt_filter" data-pos="left"--}}
{{--                                                         data-remove="true" data-class="popup_filter" data-bg="hide_btn"--}}
{{--                                                         class="has_icon btn_filter mgr"><i--}}
{{--                        class="iccl fwb iccl-filter fwb mr__5"></i>Filter</a>--}}
{{--                <a rel="nofollow" href="#" data-no-instant="" class="btn_filter js_filter dn mgr"><i--}}
{{--                        class="iccl fwb iccl-filter fwb mr__5"></i>Filter</a></div>--}}

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

    <div class="row">
        <div class="col-lg-12 col-12">
            <div id="shopify-section-collection_page" class="product-list shopify-section tp_se_cdt">
                <div class="nt_svg_loader dn"></div>
            </div>
            <h6 class="ajax-loading text-center mt-4">??ang t???i s???n ph???m....</h6>

        </div>
    </div>

    <!-- <div class="view-more mt__100">
        <div class="more-pagination">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div> -->
</div>

<script>
let page = 1;
const lastPage = {!! json_encode($lastPage) !!};

loadMoreProduct(page);

window.addEventListener(
  "scroll",
  () => {
    const { scrollTop, scrollHeight, clientHeight } = document.documentElement;

    if (scrollTop + clientHeight >= scrollHeight - 10) {
        page++;
        if(page > lastPage) return $(".ajax-loading").show().html("???? h???t s???n ph???m!");
        loadMoreProduct(page);
    }
  },
  {
    passive: true,
  }
);

function loadMoreProduct(page) {
  $.ajax({
    url: "{{url('')}}" + "/load-product-more",
    type: "get",
    datatype: "html",
    data: {
      page,
      _token: "{{csrf_token()}}",
    },
    beforeSend: function () {
      $(".ajax-loading").show();
    },
    success: function (data) {
      if (data.lenght == 0) return $(".ajax-loading").html("???? h???t s???n ph???m!");

      setTimeout(function () {
        $(".ajax-loading").hide();
        $(".product-list").append(data);
      }, 1000);
    },
  });
}


</script>



