<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$apiFE = new \App\Api\FE();
$events = $apiFE->getRandomEvents(3);
?>

@extends('templates.ttv.master')

@push('style')
    <link rel="stylesheet" href="{{url('public/css/ttv/bai_viet.css')}}">
@endpush

@section('content')
    @if (count($events) && !$isMobile)
        <div id="shopify-section-blog-slider" class="shopify-section nt_section type_carousel">
            <div class="nt_se_blog-slider nt_full">
                <div
                    class="articles row no-gutters nt_cover ratio4_3 position_8 equal_nt js_carousel nt_slider prev_next_0 btn_owl_1 dot_owl_1 dot_color_1 btn_vi_1 flickity-enabled is-draggable flickity_prev_enable flickity_next_disable"
                    data-flickity="{&quot;imagesLoaded&quot;: 0,&quot;adaptiveHeight&quot;: 1, &quot;contain&quot;: 1, &quot;groupCells&quot;: &quot;100%&quot;, &quot;dragThreshold&quot; : 5, &quot;cellAlign&quot;: &quot;left&quot;,&quot;wrapAround&quot;: false,&quot;prevNextButtons&quot;: true,&quot;percentPosition&quot;: 1,&quot;pageDots&quot;: false, &quot;autoPlay&quot; : 0, &quot;pauseAutoPlayOnHover&quot; : true, &quot;rightToLeft&quot;: false }"
                    tabindex="0">
                    <div class="flickity-viewport" style="height: 475.734px; touch-action: pan-y;">
                        <div class="flickity-slider" style="left: 0px; transform: translateX(-100%);">
                            <?php
                            $count = 0;
                            foreach ($events as $item):
                            $count++;
                            if ($count > 3) {
                                break;
                            }
                            ?>
                            @if ($count == 1)
                                <article
                                    class="post-386064384139 post_nt_loop post_2 post-thumbnail pr oh col-lg-4 col-md-4 col-12 is-selected"
                                    style="position: absolute; left: 100%;">
                                    <a class="db oh bgd" href="{{$item->getHref()}}">
                                        <div class="nt_bg_lz pr_lazy_img lazyloaded"
                                             data-bgset="{{$item->getAvatar()}}"
                                             data-ratio="1.5"
                                             style="background-image: url('{{$item->getAvatar()}}');">
                                            <picture style="display: none;">
                                                <source
                                                    data-srcset="{{$item->getAvatar()}}"
                                                    sizes="634px"
                                                    srcset="{{$item->getAvatar()}}">
                                                <img alt="" class="lazyautosizes lazyloaded" data-sizes="auto" data-ratio="1.5"
                                                     data-parent-fit="cover" sizes="634px"></picture>
                                        </div>
                                    </a>
                                    <div class="pa tc cg w__100">

                                        <h2 class="post-title fs__14 ls__2 mt__10 mb__5 tu">
                                            <a class="chp" href="{{$item->getHref()}}">{{$item->getTitle()}}</a></h2><span class="post-time cg"><time
                                                class="entry-date"><time
                                                    datetime="2020-04-06T02:14:00Z">{{date('M d, Y', strtotime($item->created_at))}}</time></time></span></div>
                                </article>
                            @elseif ($count == 2)
                                <article
                                    class="post-386064351371 post_nt_loop post_2 post-thumbnail pr oh col-lg-4 col-md-4 col-12 is-selected"
                                    style="position: absolute; left: 133.33%;">
                                    <a class="db oh bgd" href="{{$item->getHref()}}">
                                        <div class="nt_bg_lz pr_lazy_img lazyloaded"
                                             data-bgset="{{$item->getAvatar()}}"
                                             data-ratio="1.5"
                                             style="background-image: url('{{$item->getAvatar()}}');">
                                            <picture style="display: none;">
                                                <source
                                                    data-srcset="{{$item->getAvatar()}}"
                                                    sizes="634px"
                                                    srcset="{{$item->getAvatar()}}">
                                                <img alt="" class="lazyautosizes lazyloaded ls-is-cached" data-sizes="auto"
                                                     data-ratio="1.5" data-parent-fit="cover" sizes="634px"></picture>
                                        </div>
                                    </a>
                                    <div class="pa tc cg w__100">
                                        <h2 class="post-title fs__14 ls__2 mt__10 mb__5 tu">
                                            <a class="chp" href="{{$item->getHref()}}">{{$item->getTitle()}}</a></h2><span class="post-time cg"><time
                                                class="entry-date"><time
                                                    datetime="2020-04-06T02:13:00Z">{{date('M d, Y', strtotime($item->created_at))}}</time></time></span></div>
                                </article>
                            @elseif ($count == 3)
                                <article
                                    class="post-386064318603 post_nt_loop post_2 post-thumbnail pr oh col-lg-4 col-md-4 col-12 is-selected"
                                    style="position: absolute; left: 166.67%;">
                                    <a class="db oh bgd" href="{{$item->getHref()}}">
                                        <div class="nt_bg_lz pr_lazy_img lazyloaded"
                                             data-bgset="{{$item->getAvatar()}}"
                                             data-ratio="1.5"
                                             style="background-image: url('{{$item->getAvatar()}}');">
                                            <picture style="display: none;">
                                                <source
                                                    data-srcset="{{$item->getAvatar()}}"
                                                    sizes="634px"
                                                    srcset="{{$item->getAvatar()}}">
                                                <img alt="" class="lazyautosizes lazyloaded ls-is-cached" data-sizes="auto"
                                                     data-ratio="1.5" data-parent-fit="cover" sizes="634px"></picture>
                                        </div>
                                    </a>
                                    <div class="pa tc cg w__100">
                                        <h2 class="post-title fs__14 ls__2 mt__10 mb__5 tu">
                                            <a class="chp" href="{{$item->getHref()}}">{{$item->getTitle()}}</a></h2><span class="post-time cg"><time
                                                class="entry-date"><time
                                                    datetime="2020-04-06T02:10:00Z">{{date('M d, Y', strtotime($item->created_at))}}</time></time></span></div>
                                </article>
                            @endif
                            <?php endforeach;?>
                        </div>
                    </div>

                </div>
            </div>
            <style data-shopify="">
                .no-gutters {
                    margin-right: 0;
                    margin-left: 0;
                }

                .no-gutters .post-thumbnail {
                    padding-right: 0;
                    padding-left: 0;
                }

                .nt_se_blog-slider .post-thumbnail > div {
                    background: rgba(0, 0, 0, 0.8);
                    padding: 20px;
                    bottom: 0;
                }

                .nt_se_blog-slider .post-thumbnail a:not(:hover), .nt_se_blog-slider .post-thumbnail .post-meta .cw {
                    color: #fff;
                }

                .nt_se_blog-slider .post-thumbnail .post-meta,
                .nt_se_blog-slider .post-thumbnail .post-time {
                    color: #878787;
                }
            </style>
        </div>
    @endif

    <div class="container mt__20 mb__20">
        <div class="row">
            <div class="col-md-12 mb-2">
                @if (count($items))
                    <div id="shopify-section-blog-template" class="shopify-section nt_section type_isotope">
                        <div class="nt_svg_loader dn"></div>
                        <div
                            class="articles products nt_products_holder row des_cnt_1 nt_cover ratio4_3 position_8 equal_nt">
                            @foreach ($items as $item)
                                <article class="post-384305660043 post_nt_loop post_2 col-lg-6 col-md-6 col-12 mb__40">
                                    <a class="db oh bgd" href="{{$item->getHref()}}">
                                        <div class="nt_bg_lz pr_lazy_img"
                                             data-bgset="{{$item->getAvatar()}}"
                                             data-ratio="1.5"
                                             style="background-image: url('{{$item->getAvatar()}}');">
                                            <picture style="display: none;">
                                                <source
                                                    data-srcset="{{$item->getAvatar()}}"
                                                    sizes="634px"
                                                    srcset="{{$item->getAvatar()}}">
                                                <img alt="" class="lazyautosizes" data-sizes="auto" data-ratio="1.5"
                                                     data-parent-fit="cover" sizes="634px"></picture>
                                        </div>
                                    </a>

                                    <div class="post-content">
                                        <div class="tc cg">
                                            <h2 class="post-title fs__14 ls__2 mt__10 mb__5 tu">
                                                <a class="chp" href="{{$item->getHref()}}">{{$item->getTitle()}}</a>
                                            </h2>
                                        </div>

                                        <a href="{{$item->getHref()}}" class="more-link text-uppercase">Xem chi tiết</a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <div class="view-more">
                        <div class="more-pagination">
                            {{ $items->appends(request()->query())->links() }}
                        </div>
                    </div>

                @else
                    <div class="alert alert-warning">Đang cập nhật...</div>
                @endif
            </div>
        </div>
    </div>

@stop
