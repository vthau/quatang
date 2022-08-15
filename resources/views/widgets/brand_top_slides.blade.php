<?php
$apiFE = new \App\Api\FE();
$brands = $apiFE->getTopBrands();
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();
?>

@if (count($brands))

    <div class="container margin-tb-60">
        <div class="col-md-12 text-center headHome-th thuonghieu_tieude">
            <h2 class="title">THƯƠNG HIỆU NỔI BẬT</h2>
        </div>
        <div class="col-md-12 thuonghieu_custom" id="block-top-brands">
            @if (count($brands) > 6 && !$isMobile)
                <div class="uni-carousel">
                    <div class="uni-carousel-inner">
                        <div class="uni-carousel-inner-limit">
                            <?php
                            $count = 0;
                            foreach ($brands as $brand):
                            ?>
                            <div class="uni-carousel-item col-md-2 <?php if (!$count):?>uni-carousel-item_active<?php endif;?>">
                                <div class="uni-carousel-item-inner top-brand-item">
                                    <div class="top-brand-photo">
                                        <a href="{{$brand->getHref(true)}}" title="{{$brand->getTitle()}}">
                                            <div class="top-brand-img" style="background-image:url('{{$brand->getAvatar('normal')}}')"></div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $count++;
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>
            @else
                <?php
                foreach ($brands as $brand):
                ?>
                <div class="top-brand-item col-md-2">
                    <div class="top-brand-wrapper">
                        <div class="top-brand-photo">
                            <a href="{{$brand->getHref(true)}}" title="{{$brand->getTitle()}}">
                                <div class="top-brand-img" style="background-image:url('{{$brand->getAvatar('normal')}}')"></div>
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                endforeach;
                ?>
            @endif
        </div>
    </div>

@endif
