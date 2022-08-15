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

    <div class="container mt__60 mb__30">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="block-deals-of-opt2">
                    <div class="block-title text-center">
                        <h2 class="title text-uppercase">Quên Mật Khẩu</h2>
                    </div>
                    <div class="block-content frm-login" id="frm-forgot">
                        <form method="POST" action="" accept-charset="UTF-8" onsubmit="return dtjsauthfp()" autocomplete="off" >
                            @csrf
                            <input type="hidden" id="frm-base-url" value="{{url('')}}" />

                            <div class="form-group mb-2 text-center">
                                <div class="alert alert-warning">
                                    Hãy nhập địa chỉ email của bạn, chúng tôi sẽ gửi email kích hoạt lấy lại mật khẩu sau ít phút.
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <input required autocomplete="off" class="form-control text-center" type="email" name="email" id="frm-email"
                                       placeholder="địa chỉ email"
                                />
                            </div>
                            <div class="form-group mt-2 mb-2 text-center" id="err-email">
                                <div class="alert alert-danger hidden">Hãy nhập email hợp lệ.</div>
                            </div>

                            <div class="mt-2 text-center">
                                <button class="button button_primary text-uppercase" type="submit">gửi yêu cầu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>

    <script src="{{url('public/themes/fe/js/auth.js')}}" type="text/javascript"></script>
@stop
