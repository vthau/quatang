<?php
$apiFE = new \App\Api\FE();
$sales = $apiFE->getActiveSales();
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();
?>

@if (count($sales))

    <div class="container">
        <div class="block-deals-of-opt2">
            <div class="block-title ">
                <h2 class="title">chương trình khuyến mãi</h2>
            </div>
            <div class="block-content" id="block-active-sales">
                @if (count($sales))
                    <?php
                    foreach ($sales as $sale):
                    ?>
                    <div class="active-sale-item col-md 12">
                        <div class="active-sale-wrapper">
                            <div class="active-sale-photo">
                                <a href="{{$sale->getHref(true)}}" title="{{$sale->getTitle()}}">
                                    <div class="active-sale-img" style="background-image:url('{{$sale->getBanner()}}')"></div>
                                </a>
                            </div>

                            @if (count($sales) > 1)
                            <div class="fake-slide-prev" onclick="getSaleBanner('prev')">
                                <i class="fas fa-caret-left"></i>
                            </div>

                            <div class="fake-slide-next" onclick="getSaleBanner('next')">
                                <i class="fas fa-caret-right"></i>
                            </div>
                            @endif
                        </div>
                    </div>
                    <?php
                        break;
                    endforeach;
                    ?>
                @endif
            </div>
        </div>
    </div>

    <script type="text/javascript">
        @if (count($sales) > 1)
            var bannerSales = [];
            var currentBannerSale = 1;

            @foreach ($sales as $sale)
                bannerSales.push({
                    'photo': '{{$sale->getBanner()}}',
                    'title': '{{$sale->getTitle()}}',
                    'href': '{{$sale->getHref(true)}}',
                });
            @endforeach

            function getSaleBanner(pos) {
                var items = bannerSales;
                if (items.length) {
                    if (pos === 'prev') {
                        if (parseInt(currentBannerSale) === 1) {
                            currentBannerSale = items.length;
                        } else if (parseInt(currentBannerSale) === parseInt(items.length)) {
                            currentBannerSale = 1;
                        } else {
                            currentBannerSale = parseInt(items.length) - 1;
                        }
                    } else if (pos === 'next') {
                        if (parseInt(currentBannerSale) === parseInt(items.length)) {
                            currentBannerSale = 1;
                        } else {
                            currentBannerSale = currentBannerSale + 1;
                        }
                    }
                    var bind = jQuery('.active-sale-photo');
                    bind.find('a').removeAttr('href').attr('href', items[currentBannerSale -1].href)
                        .removeAttr('title').attr('title', items[currentBannerSale -1].title);
                    bind.find('.active-sale-img').removeAttr('style').attr('style', 'background-image:url(\'' + items[currentBannerSale -1].photo + '\')');
                }
            }
        @endif
    </script>
@endif
