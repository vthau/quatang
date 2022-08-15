<?php
if (!isset($leftProducts) || !count($leftProducts)) {
    return;
}
$title = (isset($leftTitle)) ? $leftTitle : "";

?>

<div class="block-deals-of-opt2 other-products-wrapper">
    <div class="block-title ">
        <h2 class="title">{{$title}}</h2>
    </div>

    <div class="block-content products-wrapper">
        @foreach ($leftProducts as $product)
        <div class="product-item">
            <div class="product-item-wrapper">
                <div class="product-photo">
                    <a href="{{$product->getHref(true)}}" title="{{$product->getTitle()}}">
                        <div class="product-img" style="background-image:url('{{$product->getAvatar()}}')"></div>
                    </a>

                    @if ($product->is_new)
                        <div class="product-ic ic-right">
                            <img src="{{url('public/images/icons/img_new.jpg')}}" />
                        </div>
                    @endif

                    @if ($product->is_sale)
                        <div class="product-ic ic-left">
                            <img src="{{url('public/images/icons/img_sale.png')}}" />
                        </div>
                    @endif
                </div>
                <div class="product-info">
                    <div class="product-title">
                        <a href="{{$product->getHref(true)}}" title="{{$product->getTitle()}}">
                            {{$product->getShortTitle(50)}}
                        </a>
                    </div>
                    <div class="product-desc">
                        <div class="product-price">
                            <span class="number_format">{{$product->price_pay}}</span><span class="currency_format">₫</span>
                        </div>

                        <div class="product-cart" onclick="jscartdh({{$product->id}})">
                            <i class="fas fa-shopping-cart"></i>
                        </div>

                        <div class="product-love  sp-love-{{$product->id}}" onclick="jssplove(this, {{$product->id}})">
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
        @endforeach
    </div>
</div>
