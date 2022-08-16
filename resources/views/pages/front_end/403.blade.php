<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();
?>

@extends('templates.front_end.master')

@section('content')
<div id="shopify-section-us_heading" class="shopify-section page_section_heading">
    <div class="page-head tc pr oh page_bg_img page_head_us_heading">
        @include('modals.backdrop')
    </div>
</div>

<div class="container mb__50">
    @include('modals.breadcrumb', [
    'text1' => 'Không có quyền truy cập',
    ])

    <div class="row">
        <h3 class="col-md-12 text-center">
            Bạn không có quyền truy cập vào trang này
        </h3>
    </div>
</div>

@stop
