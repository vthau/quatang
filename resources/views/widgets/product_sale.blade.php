<?php
$apiFE = new \App\Api\FE();
$products = $apiFE->getSaleProducts();
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();
?>

@if (count($products))

    <div class="container">
        <div class="block-deals-of-opt2">
            <div class="block-title ">
                <h2 class="title">Sản phẩm khuyến mãi</h2>
            </div>
            <div class="block-content" id="block-sale-products">
                @if (count($products) > 6 && !$isMobile)
                <div class="uni-carousel">
                    <div class="uni-carousel-inner">
                        <div class="uni-carousel-inner-limit">
                            <?php
                            $count = 0;
                            foreach ($products as $product):
                            ?>
                            <div class="uni-carousel-item col-md-2 <?php if (!$count):?>uni-carousel-item_active<?php endif;?>">
                                <div class="uni-carousel-item-inner new-p-item">
                                    <div class="new-p-photo">
                                        <a href="{{$product->getHref(true)}}" title="{{$product->getTitle()}}">
                                            <div class="new-p-img" style="background-image:url('{{$product->getAvatar()}}')"></div>
                                        </a>

                                        @if ($product->is_best_seller)
                                            <div class="new-p-ic ic-right">
                                                <img src="{{url('public/images/icons/img_best_seller.png')}}" />
                                            </div>
                                        @endif

                                        @if ($product->is_new)
                                            <div class="new-p-ic ic-left">
                                                <img src="{{url('public/images/icons/img_new.jpg')}}" />
                                            </div>
                                        @endif
                                    </div>
                                    <div class="new-p-info">
                                        <div class="new-p-title">
                                            <a href="{{$product->getHref(true)}}" title="{{$product->getTitle()}}">
                                                {{$product->getShortTitle(50)}}
                                            </a>
                                        </div>
                                        <div class="new-p-desc">
                                            <div class="new-p-price">
                                                <span class="number_format">{{$product->price_pay}}</span><span class="currency_format">₫</span>
                                            </div>

                                            <div class="new-p-cart" onclick="jscartdh({{$product->id}})">
                                                <i class="fas fa-shopping-cart"></i>
                                            </div>

                                            <div class="new-p-love sp-love-{{$product->id}}" onclick="jssplove(this, {{$product->id}})">
                                                @if ($product->isLoved())
                                                    <i class="fas fa-heart active" title="Đã Yêu Thích SP"></i>
                                                @else
                                                    <i class="fas fa-heart" title="Thêm SP Yêu Thích"></i>
                                                @endif
                                            </div>
                                        </div>
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
                    foreach ($products as $product):
                    ?>
                        <div class="new-p-item col-md-2">
                            <div class="new-p-wrapper">
                                <div class="new-p-photo">
                                    <a href="{{$product->getHref(true)}}" title="{{$product->getTitle()}}">
                                        <div class="new-p-img" style="background-image:url('{{$product->getAvatar()}}')"></div>
                                    </a>

                                    @if ($product->is_best_seller)
                                        <div class="new-p-ic ic-right">
                                            <img src="{{url('public/images/icons/img_best_seller.png')}}" />
                                        </div>
                                    @endif

                                    @if ($product->is_new)
                                        <div class="new-p-ic ic-left">
                                            <img src="{{url('public/images/icons/img_new.jpg')}}" />
                                        </div>
                                    @endif
                                </div>
                                <div class="new-p-info">
                                    <div class="new-p-title">
                                        <a href="{{$product->getHref(true)}}" title="{{$product->getTitle()}}">
                                            {{$product->getShortTitle(50)}}
                                        </a>
                                    </div>
                                    <div class="new-p-desc">
                                        <div class="new-p-price">
                                            <span class="number_format">{{$product->price_pay}}</span><span class="currency_format">₫</span>
                                        </div>

                                        <div class="new-p-cart" onclick="jscartdh({{$product->id}})">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>

                                        <div class="new-p-love sp-love-{{$product->id}}" onclick="jssplove(this, {{$product->id}})">
                                            @if ($product->isLoved())
                                                <i class="fas fa-heart active" title="Đã Yêu Thích SP"></i>
                                            @else
                                                <i class="fas fa-heart" title="Thêm SP Yêu Thích"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    endforeach;
                    ?>
                @endif
            </div>
        </div>
    </div>


@endif
