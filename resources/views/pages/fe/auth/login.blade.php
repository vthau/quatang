<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiFE = new \App\Api\FE;
$provinces = $apiFE->getProvinces();
?>

@extends('templates.ttv.master')

@section('content')
    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop')
        </div>
    </div>

    <div class="container mt__60 mb__30">
        <div class="frm frm_dang_nhap">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div id="frm-login">
                        <h2 class="text-uppercase">{{$apiCore->getSetting('text_dn_title')}}</h2>
                        <form method="post" action="{{url('auth/dang-nhap')}}" id="customer_login" accept-charset="UTF-8">
                            @csrf

                            <p class="form-row">
                                <label class="frm-label required">* {{$apiCore->getSetting('text_dn_email')}}</label>
                                <input type="email" name="email" autocomplete="email" required autocapitalize="off" value="{{$err_email}}">
                            </p>
                            <p class="form-row">
                                <label class="frm-label required">* {{$apiCore->getSetting('text_dn_mat_khau')}}</label>
                                <input type="password" name="password" required>
                            </p>
                            <p class="form-row hidden">
                                <input class="width_height_20" type="checkbox" name="remember" checked="checked" />
                                Ghi Nhớ Đăng Nhập
                            </p>
                            <p class="form-row overflow-hidden clearfix">
                                <span class="float-right ml__10 text-capitalize cursor-pointer" onclick="openFrm('frm_dang_ki')">{{$apiCore->getSetting('text_dn_chua_co_tai_khoan')}}</span>
                                <a href="{{url('auth/mat-khau')}}" >{{$apiCore->getSetting('text_dn_quen_mat_khau')}}</a>
                            </p>

                            <div class="form-group alert alert-danger hidden mt-2 mb-2" id="err-login"></div>

                            <div class="form-row overflow-hidden clearfix mt__30">
                                <button type="submit" class="button fs-13 width_full text-uppercase mb__15">{{$apiCore->getSetting('text_dn_xac_nhan')}}</button>
                                <button type="button" class="button fs-13 button_primary width_full text-uppercase mb__15" onclick="openPage('{{url('auth/redirect/facebook')}}')">
                                    <img width="20" class="mr__5" src="{{url('public/images/facebook.png')}}" style="margin-top: -5px;" /> {{$apiCore->getSetting('text_dn_facebook')}}
                                </button>
                                <button type="button" class="button fs-13 width_full text-uppercase mb__15" onclick="openPage('{{url('auth/redirect/google')}}')">
                                    <img width="20" class="mr__5" src="{{url('public/images/google.png')}}" style="margin-top: -3px;" /> {{$apiCore->getSetting('text_dn_google')}}
                                </button>
                            </div>

                            <input type="hidden" name="referer" value="{{$referer}}" />
                        </form>
                    </div>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>

        <div class="frm frm_dang_ki hidden">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <h2 class="text-uppercase">{{(int)$dk_dt ? 'đăng kí đối tác' : $apiCore->getSetting('text_dk_dang_ki_thanh_vien')}}</h2>
                    <form method="post" action="{{url('auth/dang-ki')}}" accept-charset="UTF-8" id="frm-signup"
                          onsubmit="return dtjsauthdk();"
                    >
                        @csrf
                        <div class="form-row input-name">
                            <label class="frm-label required">* {{$apiCore->getSetting('text_dk_ho_ten')}}</label>
                            <input class="input" type="text" name="name" autocomplete="off" required
                                   value="{{$dk_name}}"
                            />

                            <div class="alert alert-danger hidden mt-1">{{$apiCore->getSetting('text_dk_loi_ht')}}</div>
                        </div>
                        <div class="form-row input-email">
                            <label class="frm-label required">* {{$apiCore->getSetting('text_dk_email')}}</label>
                            <input class="input" type="email" name="email" required autocapitalize="off" autocomplete="off"
                                   value="{{$dk_email}}"
                            />

                            <div class="alert alert-danger hidden mt-1"></div>
                        </div>
                        <div class="form-row input-password1">
                            <label class="frm-label required">* {{$apiCore->getSetting('text_dk_mat_khau')}}</label>
                            <input class="input" type="password" value="" name="password" required autocomplete="off" />
                        </div>
                        <div class="form-row input-password2">
                            <label class="frm-label required">* {{$apiCore->getSetting('text_dk_xac_nhan_mat_khau')}}</label>
                            <input class="input" type="password" value="" name="password_confirm" required autocomplete="off" />

                            <div class="alert alert-danger hidden mt-1">{{$apiCore->getSetting('text_dk_loi_xnmk')}}</div>
                        </div>
                        <div class="form-row input-phone">
                            <label class="frm-label required">* {{$apiCore->getSetting('text_dk_dien_thoai')}}</label>
                            <input class="input" type="text" name="phone" autocomplete="off" required
                                   onkeypress="return isInputPhone(event, this)"
                                   oncopy="return false;" oncut="return false;" onpaste="return false;"
                            />

                            <div class="alert alert-danger hidden mt-1">{{$apiCore->getSetting('text_dk_loi_dt')}}</div>
                        </div>
                        <div class="form-row">
                            <label class="frm-label">{{$apiCore->getSetting('text_dk_dia_chi')}}</label>
                            <input type="text" name="address" autocomplete="off">
                        </div>

                        <div class="form-row mt__10">
                            <select name="province_id" class="form-control select-css" onchange="jscartaddressopts(this, 'district')">
                                <option value="">Hãy chọn tỉnh / thành</option>
                                @foreach($provinces as $ite)
                                    <option value="{{$ite->id}}">{{$ite->title}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-row mt__10">
                            <select name="district_id" class="form-control select-css" onchange="jscartaddressopts(this, 'ward')">
                                <option value="">Hãy chọn quận / huyện</option>
                            </select>
                        </div>

                        <div class="form-row mt__10">
                            <select name="ward_id" class="form-control select-css">
                                <option value="">Hãy chọn phường / xã</option>
                            </select>
                        </div>

                        <p class="form-row overflow-hidden mt__10 mb__10">
                            <span class="float-right ml__10 text-capitalize cursor-pointer" onclick="openFrm('frm_dang_nhap')">{{$apiCore->getSetting('text_dk_tro_ve_dang_nhap')}}</span>
                        </p>

                        <div class="form-group alert alert-danger hidden mt-2 mb-2" id="err-signup"></div>

                        <div class="form-row overflow-hidden clearfix mt__30">
                            <button type="submit" class="button fs-13 width_full text-uppercase mb__15">{{$apiCore->getSetting('text_dk_xac_nhan')}}</button>
                            <button type="button" class="button fs-13 button_primary width_full text-uppercase mb__15" onclick="openPage('{{url('auth/redirect/facebook')}}')">
                                <img width="20" class="mr__5" src="{{url('public/images/facebook.png')}}" style="margin-top: -5px;" /> {{$apiCore->getSetting('text_dk_facebook')}}
                            </button>
                            <button type="button" class="button fs-13 width_full text-uppercase mb__15" onclick="openPage('{{url('auth/redirect/google')}}')">
                                <img width="20" class="mr__5" src="{{url('public/images/google.png')}}" style="margin-top: -3px;" /> {{$apiCore->getSetting('text_dk_google')}}
                            </button>
                        </div>

                        <input type="hidden" name="referer" value="{{$referer}}" />
                        <input type="hidden" name="ref" value="{{$ref}}" />
                    </form>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>

    <script src="{{url('public/themes/fe/js/auth.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            @if ($err_login)
            jQuery('#frm-login #err-login').removeClass('hidden').text("{{$err_message}}");
            @endif

            @if ($err_signup)
            jQuery('#frm-signup #err-signup').removeClass('hidden').text("{{$err_message}}");
            @endif

            @if (count($params) && isset($params['v']) && $params['v'] == 'dk')
                openFrm('frm_dang_ki');
            @endif

            @if (!empty($dk_name))
                pushMessage("Vui lòng cung cấp thêm thông tin để tạo tài khoản");
            @endif
        });

        function openFrm(frm) {
            jQuery('.frm').addClass('hidden');
            jQuery('.' + frm).removeClass('hidden');
        }
    </script>
@stop
