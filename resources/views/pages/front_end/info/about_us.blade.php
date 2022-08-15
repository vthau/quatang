<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();
$body = $apiCore->getSetting('about_us');

if (!empty($body)) {
    $body = str_replace('../public/uploaded/tinymce', url('') . '/public/uploaded/tinymce', $body);
}
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
            'text1' => 'về chúng tôi',
        ])

        <div class="row">
            <div class="col-md-12">
                <?php echo $body;?>
            </div>
        </div>
    </div>

@stop
