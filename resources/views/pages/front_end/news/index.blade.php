<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$apiFE = new \App\Api\FE();
$items = $apiFE->getNews([
    'pagination' => 1,
    'page' => (isset($params['page']) && (int)$params['page']) ? (int)$params['page'] : 1,
//    'limit' => 2,
]);
?>

@extends('templates.front_end.master')

@push('style')
    <link rel="stylesheet" href="{{url('public/css/ttv/bai_viet.css')}}">
@endpush

@section('content')

    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop')
        </div>
    </div>

    <div class="container mb__50">
        @include('modals.breadcrumb', [
            'text1' => 'tin tức',
        ])

        <div class="row">
            <div class="col-md-12">
                @if (count($items))
                    <div id="shopify-section-blog-template" class="shopify-section nt_section type_isotope">
                        <div class="nt_svg_loader dn"></div>
                        <div class="articles products nt_products_holder row des_cnt_1 nt_cover ratio4_3 position_8 equal_nt">
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
