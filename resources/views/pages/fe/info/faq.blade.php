<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();
?>

@extends('templates.ttv.master')

@section('content')
    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop')
        </div>
    </div>

    <div class="container mt__20 mb__20">
        <div class="row">
            <h4 class="col-md-12 mb-2 faqs">
                @if (count($faqs))
                    <?php
                    $count = 0;
                    foreach ($faqs as $faq):
                    $count++;
                    ?>
                    <div class="clearfix mb-2 mt-2 overflow-hidden">
                        <div class="text-bold text-uppercase fs-14">{{$count . '. ' . $faq->question}}</div>
                        <div class="clearfix mt-2 fs-13">
                            <?php echo nl2br($faq->answer);?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                @else
                    <div class="alert alert-warning">Đang cập nhật...</div>
                @endif
            </div>
        </div>
    </div>

@stop
