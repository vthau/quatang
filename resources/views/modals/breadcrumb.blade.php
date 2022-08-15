<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();
?>

<div class="pr clearfix copy_btn_breadcrumb">
    @if (!$isMobile)

        <div class="pa copy_btn_wrapper">
            @if (isset($refLink) && !empty($refLink))
                <button class="button text-uppercase copy_btn" onclick="jsbindcateqr()">
                    qr code
                </button>
                <button class="button text-uppercase copy_btn" onclick="copyToClipboardLink()">
                    copy link giới thiệu
                </button>
            @endif

            @if (isset($pdfLink) && !empty($pdfLink))
                <button class="button text-uppercase copy_btn" onclick="gotoPage('{{$pdfLink}}')">
                    <i class="fa fa-file-pdf"></i> pdf
                </button>
            @endif

            @if (isset($excelLink) && !empty($excelLink))
                <button class="button text-uppercase copy_btn" onclick="gotoPage('{{$excelLink}}')">
                    <i class="fa fa-file-excel"></i> excel
                </button>
            @endif
        </div>
    @endif

    <ul class="breadcrumb">
        <li><a href="{{url('')}}">Trang Chủ</a></li>
        @if (isset($text2) && !empty($text2))
            @if (isset($text2link) && !empty($text2link))
            <li><a href="{{$text2link}}">{{$text2}}</a></li>
            @else
                <li>{{$text2}}</li>
            @endif
        @endif
        @if (isset($text1) && !empty($text1))
            @if (isset($text1link) && !empty($text1link))
                <li><a href="{{$text1link}}">{{$text1}}</a></li>
            @else
                <li>{{$text1}}</li>
            @endif
        @endif

    </ul>

    @if ($isMobile)
        <div class="clearfix text-right">
            @if (isset($refLink) && !empty($refLink))
                <button class="button text-uppercase copy_btn" onclick="jsbindcateqr()">
                    qr code
                </button>
                <button class="button text-uppercase copy_btn" onclick="copyToClipboardLink()">
                    copy link giới thiệu
                </button>
            @endif
            @if (isset($pdfLink) && !empty($pdfLink))
                <button class="button text-uppercase copy_btn" onclick="gotoPage('{{$pdfLink}}')">
                    <i class="fa fa-file-pdf"></i> pdf
                </button>
            @endif
            @if (isset($excelLink) && !empty($excelLink))
                <button class="button text-uppercase copy_btn" onclick="gotoPage('{{$excelLink}}')">
                    <i class="fa fa-file-excel"></i> excel
                </button>
            @endif
        </div>
    @endif
</div>

@if (isset($refLink) && !empty($refLink))
<div class="mfp-wrap mfp-close-btn-in mfp-auto-cursor mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_2 hidden" tabindex="-1" style="overflow: hidden auto;">
    <div class="mfp-container mfp-s-ready mfp-inline-holder">
        <div class="mfp-content">
            <div class="popup_qr">
                {!! \QrCode::size(200)->generate($refLink); !!}
            </div>

            <button title="Close (Esc)" type="button" class="mfp-close" onclick="jsspvideoclose()">×</button>
        </div>
    </div>
</div>
@endif
