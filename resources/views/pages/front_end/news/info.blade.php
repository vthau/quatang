<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiFE = new \App\Api\FE;
$others = $apiFE->getNews(['except' => $item->id, 'random' => 1, 'limit' => 3]);
?>

@extends('templates.front_end.master')

@push('style')
    <link rel="stylesheet" href="{{url('public/css/ttv/bai_viet.css')}}">
@endpush

@section('content')
    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop', [
                'bannerBG' => $item->getBanner(),
            ])
        </div>
    </div>

    <div class="container mb__50">
        @include('modals.breadcrumb', [
            'text1' => 'tin tức',
        ])

        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="info_title mb__20">
                    <h1 class="text-uppercase">{{$item->getTitle()}}</h1>
                </div>

                @if(!empty($item->getAvatar()))
                    <div class="info_avatar">
                        <img src="{{$item->getAvatar()}}" />
                    </div>
                @endif

                <div class="news-description">
                    <?php echo $item->mo_ta;?>
                </div>
            </div>
        </div>

        @if (count($others))
            <div class="col-md-12 mb-2">
                @include('widgets.front_end.other_news')
            </div>
        @endif
    </div>

@stop
