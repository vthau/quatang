<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiFE = new \App\Api\FE;
$others = $apiFE->getOtherEvents($item->id);
?>

@extends('templates.ttv.master')

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
            'text1' => 'góc tư vấn',
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
                @include('widgets.ttv.blog_info_others')
            </div>
        @endif
    </div>

@stop
