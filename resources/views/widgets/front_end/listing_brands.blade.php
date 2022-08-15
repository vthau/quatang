<?php
$apiCore = new \App\Api\Core;
$apiFE = new \App\Api\FE;

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile() ? 1 : 0;

$brands = $apiCore->getBrands(['active' => 1]);
if (!count($brands)) {
    return;
}

$tempArr = [];
$char = "";
$tempChar = "";
foreach ($brands as $brand) {
    $char = $apiCore->stripVN(mb_substr($brand->getTitle(), 0, 1));
    $char = strtoupper($char);
    if ($tempChar != $char) {
        $tempChar = $char;

        $tempArr[$char][] = [
            'avatar' => $brand->getAvatar('profile'),
            'title' => $brand->getTitle(),
            'href' => $brand->getHref(),
        ];
    } else {
        $tempArr[$tempChar][] = [
            'avatar' => $brand->getAvatar('profile'),
            'title' => $brand->getTitle(),
            'href' => $brand->getHref(),
        ];
    }
}

?>

<style type="text/css">
    .brand-item.mobi_brand {
        width: 100% !important;
    }
</style>

<div class="brand-sort-wrapper">
    <div class="brand-chars">
    <?php
    $count = 0;
    foreach ($tempArr as $k => $v):
    ?>
        <div class="brand-char-item <?php if (!$count):?>active<?php endif;?>" onclick="jsbindbrand(this, '{{$k}}')">
            <a href="javascript:void(0)" class="brand-char">{{$k}}</a>
        </div>
    <?php $count++;
    endforeach;?>
    </div>

    <div class="brand-sorts">
        @if ($isMobile)
            <div class="row">
                <?php
                $count = 0;
                foreach ($tempArr as $k => $v):
                ?>
                <div class="col-sm-6 brand-sort-item <?php if (!$count):?>active<?php endif;?>" id="brand-sort-{{$k}}">
                    @foreach ($v as $item)
                        <div class="brand-item mobi_brand">
                            <a href="{{$item['href']}}" title="{{$item['title']}}">
                                <img src="{{$item['avatar']}}" />
                                <div class="brand-title">{{$item['title']}}</div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <?php $count++;
                endforeach;?>
            </div>
        @else

        <?php
        $count = 0;
        foreach ($tempArr as $k => $v):
        ?>
            <div class="brand-sort-item <?php if (!$count):?>active<?php endif;?>" id="brand-sort-{{$k}}">
            @foreach ($v as $item)
                <div class="brand-item">
                    <a href="{{$item['href']}}" title="{{$item['title']}}">
                        <div class="brand-avatar" style="background-image:url('{{$item['avatar']}}')"></div>
                        <div class="brand-title">{{$item['title']}}</div>
                    </a>
                </div>
            @endforeach
            </div>
        <?php $count++;
        endforeach;?>

        @endif
    </div>
</div>

