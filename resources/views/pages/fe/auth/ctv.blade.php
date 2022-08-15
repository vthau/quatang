<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile() ? 1 : 0;

$apiFE = new \App\Api\FE;
$provinces = $apiFE->getProvinces();
?>

@extends('templates.ttv.master')

@section('content')
    <style type="text/css">
        .mobi_policy {
            width: 100%;
            max-width: 100%;
        }

        .mobi_policy div {
            width: auto !important;
        }
    </style>

    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop')
        </div>
    </div>

    <div class="container mt__30 mb__30">
        <div class="row">
            <div class="col-md-8">
                <div class="content @if($isMobile) mobi_policy @endif">
                    <?php echo $apiCore->getSetting('partner_policy');?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="frm_ctv">
                    <h3 class="text-uppercase">{{$apiCore->getSetting('text_dk_dang_ki_doi_tac')}}</h3>
                    <form method="post" action="{{url('auth/dk-dt')}}" accept-charset="UTF-8" id="frm-ctv"
                          onsubmit="return jsvaliddkctv();"
                    >
                        @csrf
                        <div class="form-row input-name">
                            <label class="frm-label required">* {{$apiCore->getSetting('text_dk_ho_ten')}}</label>
                            <input class="input" type="text" name="name" autocomplete="off" required
                                   value="{{$dk_name}}"
                            >

                            <div class="alert alert-danger hidden mt-1">{{$apiCore->getSetting('text_dk_loi_ht')}}</div>
                        </div>
                        <div class="form-row input-email">
                            <label class="frm-label required">* {{$apiCore->getSetting('text_dk_email')}}</label>
                            <input class="input" type="email" name="email" required autocapitalize="off" autocomplete="off"
                                   value="{{$dk_email}}"
                            >

                            <div class="alert alert-danger hidden mt-1"></div>
                        </div>
                        <div class="form-row input-password">
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
                            <label class="frm-label">{{$apiCore->getSetting('text_dk_chung_chi_hanh_nghe')}}</label>
                            <input type="text" name="chung_chi_hanh_nghe" autocomplete="off">
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


                        <div class="form-row mt__10 text-bold">
                            <input required name="agree" class="width_height_20" type="checkbox" style="position:relative; top: -3px;" />
                            <a class="text-site text-bold" href="{{url('chinh-sach-bao-mat')}}">{{$apiCore->getSetting('text_dk_toi_da_doc')}}</a>
                        </div>

                        <div class="form-group alert alert-danger hidden mt-2 mb-2" id="err-ctv"></div>

                        <div class="form-row overflow-hidden clearfix mt__30">
                            <button type="submit" class="button fs-13 width_full text-uppercase mb__15">{{$apiCore->getSetting('text_dk_xac_nhan')}}</button>
                            <button type="button" class="button fs-13 button_primary width_full text-uppercase mb__15" onclick="openPage('{{url('auth/redirect/facebook?f=ctv')}}')">
                                <img width="20" class="mr__5" src="{{url('public/images/facebook.png')}}" style="margin-top: -5px;" /> {{$apiCore->getSetting('text_dk_facebook')}}
                            </button>
                            <button type="button" class="button fs-13 width_full text-uppercase mb__15" onclick="openPage('{{url('auth/redirect/google?f=ctv')}}')">
                                <img width="20" class="mr__5" src="{{url('public/images/google.png')}}" style="margin-top: -3px;" /> {{$apiCore->getSetting('text_dk_google')}}
                            </button>
                        </div>

                        <input type="hidden" name="referer" value="{{$referer}}" />
                        <input type="hidden" name="ref" value="{{$ref}}" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{url('public/themes/fe/js/auth.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            @if ($err_signup)
            jQuery('#frm-ctv #err-ctv').removeClass('hidden').text("{{$err_message}}");
            @endif

            @if (!empty($dk_name))
            pushMessage("Vui lòng cung cấp thêm thông tin để tạo tài khoản");
            @endif
        });
    </script>
@stop
