<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();
?>

@extends('templates.be.master')

@section('content')

    <style type="text/css">
        .bg-ngang-preview2 img {
            width: 100%;
            max-height: 250px;
        }

        .bg_doctor_img img {
            max-width: 500px;
            max-height: 250px;
        }
    </style>

    <div>
        <div class="fade-in">
            <form action="{{url('admin/settings/save')}}" method="post" id="frm-add" enctype="multipart/form-data" >
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><strong>{{$pageTitle}}</strong></div>
                            <div class="card-body card-block">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="required">Tên Website</label>
                                            <input value="{{$apiCore->getSetting('site_title')}}" name="site_title" type="text" autocomplete="off" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="required">Điện Thoại Công Ty</label>
                                            <input value="{{$apiCore->getSetting('site_phone')}}" name="site_phone" type="text" autocomplete="off" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="required">Địa Chỉ Công Ty</label>
                                            <input value="{{$apiCore->getSetting('site_address')}}" name="site_address" type="text" autocomplete="off" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="required">Email Liên Hệ</label>
                                            <input value="{{$apiCore->getSetting('site_email')}}" name="site_email" type="text" autocomplete="off" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Tên Công Ty</label>
                                            <input value="{{$apiCore->getSetting('site_company')}}" name="site_company" type="text" autocomplete="off" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Mã số thuế</label>
                                            <input value="{{$apiCore->getSetting('site_mst')}}" name="site_mst" type="text" autocomplete="off" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Hotline</label>
                                            <input value="{{$apiCore->getSetting('site_hotline')}}" name="site_hotline" type="text" autocomplete="off" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Tổng đài tư vấn</label>
                                            <input value="{{$apiCore->getSetting('site_tuvan')}}" name="site_tuvan" type="text" autocomplete="off" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Giới Thiệu Ngắn (100 - 200 chữ)</label>
                                            <textarea name="site_short_description" rows="5" class="form-control">{{$apiCore->getSetting('site_short_description')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Từ Khóa SEO</label>
                                            <textarea name="site_seo" rows="5" class="form-control">{{$apiCore->getSetting('site_seo')}}</textarea>
                                        </div>
                                    </div>
                                </div>


                                <div class="row" id="req-logo">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Logo Công Ty (vuông)</label>
                                            <div>
                                                <label>(Recommended: square 256 x 256)</label>
                                            </div>

                                            <div>
                                                <input name="site_logo" id="upload-logo" type="file" accept="image/*" />

                                                <div class="alert alert-danger hidden mt-3">Vui lòng không upload hình lớn hơn <b class="max-size-text"></b>.</div>
                                            </div>
                                        </div>

                                        <div class="form-group" id="logo-preview">
                                            @if ($logo)
                                                <div class='img-preview'>
                                                    <img src="{{$logo->getPhoto()}}" />
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                <div class="row" id="req-logo_ngang">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Backdrop (ngang)</label>
                                            <div>
                                                <label>(Recommended: 1920 x 250)</label>
                                            </div>

                                            <div>
                                                <input name="site_logo_ngang" id="upload-logo_ngang" type="file" accept="image/*" />

                                                <div class="alert alert-danger hidden mt-3">Vui lòng không upload hình lớn hơn <b class="max-size-text"></b>.</div>
                                            </div>
                                        </div>

                                        <div class="form-group" id="logo2-preview">
                                            @if ($logo2)
                                                <div class='bg-ngang-preview2'>
                                                    <img src="{{$logo2->getPhoto()}}" />
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm mb-1">
                                    <i class="fa fa-check-circle mr-1"></i>
                                    Xác Nhận
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            @if(!empty($message))
            @if($message == 'ITEM_ADDED')
            showMessage(gks.successADD);
            @elseif($message == 'ITEM_EDITED')
            showMessage(gks.successEDIT);
            @elseif($message == 'ITEM_DELETED')
            showMessage(gks.successDEL);
            @elseif($message == 'ITEM_UPDATED')
            showMessage(gks.successUPDATE);
            @endif
            @endif

            //upload
            $("#upload-logo").change(function () {
                jQuery('#req-logo .alert-danger').addClass('hidden');
                if(this.files[0].size > gks.maxSize) {
                    jQuery('#req-logo .alert-danger').removeClass('hidden');

                    jQuery(this).val("");
                    jQuery('#logo-preview').empty();
                    return false;
                }

                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                    var filesAmount = input.files.length;

                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $('#logo-preview').empty().append("<div class='img-preview'><img src='" + e.target.result + "' ></div>");
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            });

            $("#upload-logo_ngang").change(function () {
                jQuery('#req-logo_ngang .alert-danger').addClass('hidden');
                if(this.files[0].size > gks.maxSize) {
                    jQuery('#req-logo_ngang .alert-danger').removeClass('hidden');

                    jQuery(this).val("");
                    jQuery('#logo2-preview').empty();
                    return false;
                }

                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                    var filesAmount = input.files.length;

                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $('#logo2-preview').empty().append("<div class='bg-ngang-preview2'><img src='" + e.target.result + "' ></div>");
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            });

        });
    </script>
@stop
