<?php
$apiFE = new \App\Api\FE();
$brands = $apiFE->getTopBrands();
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();
?>

@if (count($brands))

    <div class="block-sidebar block-filter no-hide">
        <div class="block-title">
            <strong>THƯƠNG HIỆU NỔI BẬT</strong>
        </div>
        <div class="block-content">
            <div class="filter-options-item filter-options-categori">
                <div class="filter-options-content">
                    <ol class="items">
                        @foreach ($brands as $brand)
                            <li class="item">
                                <label>
                                    <a href="{{$brand->getHref()}}">
                                        <span>{{$brand->getTitle()}}</span>
                                    </a>
                                </label>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>

@endif
