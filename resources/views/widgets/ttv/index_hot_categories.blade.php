<?php
$apiFE = new \App\Api\FE();
$categories = $apiFE->getProductCategories(6);
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();
?>

@if (count($categories))

    <style type="text/css">
        .h3.cat_grid_item__title {
            margin: 0;
            font-size: 12px;
            font-weight: bold;
            position: relative;
            top: 7px;
        }
    </style>

<div id="shopify-section-1590628119328" class="shopify-section nt_section type_carousel type_collection_list">
    <div class="nt_se_1590628119328 container">
        <div class="row al_center fl_center title_10 ">
            <div class="col-auto col-md"><h3 class="dib tc section-title fs__24 text-uppercase">danh mục sản phẩm</h3></div>
{{--            <div class="col-auto"><a href="index.html"></a></div>--}}
        </div>
        <div class="mt__30 nt_cats_holder row fl_center equal_nt hoverz_true ratio1_1 cat_space_20 cat_design_5 nt_slider js_carousel prev_next_3 btn_owl_1 dot_owl_2 dot_color_3 btn_vi_2"
             data-flickity='{"imagesLoaded": false, "adaptiveHeight": true, "contain": true, "groupCells": "100%", "dragThreshold" : 5, "cellAlign": "left","wrapAround": true,"prevNextButtons": false,"percentPosition": true,"pageDots": true, "autoPlay" : false, "pauseAutoPlayOnHover" : true, "rightToLeft": false }'>
            <?php
            $count = 0;
            foreach ($categories as $category):
                $count++;
                ?>
            @if ($count == 1)
            <div id="bk_1590628119328-0"
                 class="cat_grid_item cat_space_item cat_grid_item_1 col-lg-2 col-md-3 col-12">
                <div class="cat_grid_item__content pr oh">
                    <a href="{{$category->getHref()}}" class="db cat_grid_item__link">
                        <div class="cat_grid_item__overlay item__position nt_bg_lz lazyload center"
                             data-bgset="{{$category->getAvatar()}}"
                             data-ratio="1.0" data-sizes="auto" data-parent-fit="width"></div>
                    </a>
                    <div class="cat_grid_item__wrapper pe_none">
                        <div class="cat_grid_item__title h3 text-uppercase">{{$category->getTitle()}}</div>
                    </div>
                </div>
            </div>
            @elseif ($count == 2)
            <div id="bk_1590628119328-1"
                 class="cat_grid_item cat_space_item cat_grid_item_2 col-lg-2 col-md-3 col-12">
                <div class="cat_grid_item__content pr oh">
                    <a href="{{$category->getHref()}}" class="db cat_grid_item__link">
                        <div class="cat_grid_item__overlay item__position nt_bg_lz lazyload center"
                             data-bgset="{{$category->getAvatar()}}"
                             data-ratio="1.0" data-sizes="auto" data-parent-fit="width"></div>
                    </a>
                    <div class="cat_grid_item__wrapper pe_none">
                        <div class="cat_grid_item__title h3 text-uppercase">{{$category->getTitle()}}</div>
                    </div>
                </div>
            </div>
            @elseif ($count == 3)
            <div id="bk_1590628119328-2"
                 class="cat_grid_item cat_space_item cat_grid_item_3 col-lg-2 col-md-3 col-12">
                <div class="cat_grid_item__content pr oh">
                    <a href="{{$category->getHref()}}" class="db cat_grid_item__link">
                        <div class="cat_grid_item__overlay item__position nt_bg_lz lazyload center"
                             data-bgset="{{$category->getAvatar()}}"
                             data-ratio="1.0" data-sizes="auto" data-parent-fit="width"></div>
                    </a>
                    <div class="cat_grid_item__wrapper pe_none">
                        <div class="cat_grid_item__title h3 text-uppercase">{{$category->getTitle()}}</div>
                    </div>
                </div>
            </div>
            @elseif ($count == 4)
            <div id="bk_1590628119328-3"
                 class="cat_grid_item cat_space_item cat_grid_item_4 col-lg-2 col-md-3 col-12">
                <div class="cat_grid_item__content pr oh">
                    <a href="{{$category->getHref()}}" class="db cat_grid_item__link">
                        <div class="cat_grid_item__overlay item__position nt_bg_lz lazyload center"
                             data-bgset="{{$category->getAvatar()}}"
                             data-ratio="1.0" data-sizes="auto" data-parent-fit="width"></div>
                    </a>
                    <div class="cat_grid_item__wrapper pe_none">
                        <div class="cat_grid_item__title h3 text-uppercase">{{$category->getTitle()}}</div>
                    </div>
                </div>
            </div>
            @elseif ($count == 5)
            <div id="bk_1590628203496"
                 class="cat_grid_item cat_space_item cat_grid_item_5 col-lg-2 col-md-3 col-12">
                <div class="cat_grid_item__content pr oh">
                    <a href="{{$category->getHref()}}" class="db cat_grid_item__link">
                        <div class="cat_grid_item__overlay item__position nt_bg_lz lazyload center"
                             data-bgset="{{$category->getAvatar()}}"
                             data-ratio="1.0" data-sizes="auto" data-parent-fit="width"></div>
                    </a>
                    <div class="cat_grid_item__wrapper pe_none">
                        <div class="cat_grid_item__title h3 text-uppercase">{{$category->getTitle()}}</div>
                    </div>
                </div>
            </div>
            @elseif ($count == 6)
            <div id="bk_1590628206023"
                 class="cat_grid_item cat_space_item cat_grid_item_6 col-lg-2 col-md-3 col-12">
                <div class="cat_grid_item__content pr oh">
                    <a href="{{$category->getHref()}}" class="db cat_grid_item__link">
                        <div class="cat_grid_item__overlay item__position nt_bg_lz lazyload center"
                             data-bgset="{{$category->getAvatar()}}"
                             data-ratio="1.0" data-sizes="auto" data-parent-fit="width"></div>
                    </a>
                    <div class="cat_grid_item__wrapper pe_none">
                        <div class="cat_grid_item__title h3 text-uppercase">{{$category->getTitle()}}</div>
                    </div>
                </div>
            </div>
            @endif
            <?php endforeach;?>
        </div>
    </div>

    <style data-shopify>
        .nt_se_1590628119328 {
            margin-top: 40px !important;
            margin-right: !important;
            margin-bottom: 90px !important;
            margin-left: !important;
        }
    </style>
</div>
@endif
