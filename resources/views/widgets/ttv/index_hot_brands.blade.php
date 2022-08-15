<?php
$apiFE = new \App\Api\FE();
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$brands = $apiFE->getBrands(['limit' => 6, 'random' => 1]);
?>

<style data-shopify="">
    #shopify-section-1590291296324 {
        background-color: #f8f8f8 !important;
    }

    .nt_se_1590291296324 {
        margin-top: 30px !important;
        /*margin-bottom: 30px !important;*/
        /*padding-top: 75px !important;*/
        /*padding-bottom: 75px !important;*/
    }
</style>

@if (count($brands))
<div id="shopify-section-1590291296324" class="shopify-section nt_section type_brand_list type_carousel">
    <div class="nt_se_1590291296324 container">
        <div class="mt__30 nt_banner_holder row equal_nt nt_contain position_8 al_center cat_space_0">
            @foreach($brands as $brand)
            <div class="cat_space_item col-lg-2 col-md-6 col-6 brand_item nt_1590291296324-0 tc">
                <a href="{{$brand->getHref()}}" class="db">
                    <img src="{{$brand->getAvatar()}}"
                        data-widths="[180, 360, 540, 720, 900, 1080, 1296, 1512, 1728, 2048]" data-sizes="auto"
                        class="lz_op_ef w__100 lazyautosizes lazyloaded" alt="{{$brand->getTitle()}}"
                        style="max-width: 150px;"
                    />
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
