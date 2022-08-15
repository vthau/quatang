<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();

$viewer = $apiCore->getViewer();


?>

@extends('templates.be.master')

@section('content')
    <style type="text/css">
        .cate_preview img,
        .mobi_banner_preview img,
        .banner_preview img {
            width: 100%;
            max-width: 300px;
            height: 100px;
            border: 1px solid;
        }

        .cate_preview img {
            height: 200px;
        }

        .mobi_banner_preview img {
            width: 210px !important;
            height: 200px !important;
        }
    </style>

    @if ($saved)
        <div class="alert alert-success">Thông tin đã được lưu thành công.</div>
    @endif

    <div>
        <div class="fade-in">
            <form action="{{url('admin/config/save')}}" method="post" id="frm-add" enctype="multipart/form-data" >
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><strong>{{$pageTitle}}</strong></div>
                            <div class="card-body card-block">

                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="font-weight-bold">4 banners</label>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php for($i=1;$i<=4;$i++):
                                                $bannerBG = $apiCore->getSetting('banner_bg_' . $i);
                                                $bannerBGMobi = $apiCore->getSetting('mobi_banner_bg_' . $i);
                                                ?>
                                                <div class="row banner_upload banner_upload_{{$i}}">
                                                    <div class="col-md-6 mb-2">
                                                        <label class="text-capitalize">tiêu đề {{$i}}</label>
                                                        <input type="text" name="banner_title_{{$i}}" class="form-control"
                                                               value="{{$apiCore->getSetting('banner_title_' . $i)}}" autocomplete="off"
                                                        />
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="text-capitalize">đường dẫn {{$i}}</label>
                                                        <input type="text" name="banner_link_{{$i}}" class="form-control"
                                                               value="{{$apiCore->getSetting('banner_link_' . $i)}}" autocomplete="off"
                                                        />
                                                    </div>
                                                    <div class="col-md-3 mb-2 banner_bg">
                                                        <div class="form-group">
                                                            <label class="text-capitalize">banner {{$i}} (Recommended: 1920 x 621)</label>
                                                            <input type="file" name="banner_{{$i}}" class="form-control" accept="image/*" />
                                                            <div class="alert alert-danger hidden mt-3">Vui lòng không upload hình lớn hơn <b class="max-size-text"></b>.</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <div class="form-group banner_upload_preview">
                                                            @if (!empty($bannerBG))
                                                                <div class="banner_preview">
                                                                    <img src="{{url('public/' . $bannerBG)}}" />
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-2 mobi_banner_bg">
                                                        <div class="form-group">
                                                            <label class="text-capitalize font-weight-bold">mobi banner {{$i}} (Recommended: 840 x 800)</label>
                                                            <input type="file" name="mobi_banner_{{$i}}" class="form-control" accept="image/*" />
                                                            <div class="alert alert-danger hidden mt-3">Vui lòng không upload hình lớn hơn <b class="max-size-text"></b>.</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-2">
                                                        <div class="form-group mobi_banner_upload_preview">
                                                            @if (!empty($bannerBGMobi))
                                                                <div class="mobi_banner_preview">
                                                                    <img src="{{url('public/' . $bannerBGMobi)}}" />
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endfor;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="font-weight-bold">3 giải pháp dưới banner</label>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php for($i=1;$i<=3;$i++):
                                                $cateBG = $apiCore->getSetting('cate_bg_' . $i);
                                                ?>
                                                <div class="row cate_upload cate_upload_{{$i}}">
                                                    <div class="col-md-3">
                                                        <label class="text-capitalize">tiêu đề {{$i}}</label>
                                                        <input type="text" name="cate_title_{{$i}}" class="form-control"
                                                               value="{{$apiCore->getSetting('cate_title_' . $i)}}" autocomplete="off"
                                                        />
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="text-capitalize">đường dẫn {{$i}}</label>
                                                        <input type="text" name="cate_link_{{$i}}" class="form-control"
                                                               value="{{$apiCore->getSetting('cate_link_' . $i)}}" autocomplete="off"
                                                        />
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="text-capitalize">hình ảnh {{$i}} (Recommended: 453 x 270)</label>
                                                            <input type="file" name="cate_{{$i}}" class="form-control" accept="image/*" />
                                                            <div class="alert alert-danger hidden mt-3">Vui lòng không upload hình lớn hơn <b class="max-size-text"></b>.</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group cate_upload_preview">
                                                            @if (!empty($cateBG))
                                                                <div class="cate_preview">
                                                                    <img src="{{url('public/' . $cateBG)}}" />
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endfor;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="font-weight-bold">videos</label>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="text-capitalize">đường dẫn</label>
                                                <?php for($i=1;$i<=4;$i++):
                                                    ?>
                                                <div class="mb-2">
                                                    <input type="text" name="video_{{$i}}" class="form-control"
                                                           value="{{$apiCore->getSetting('video_' . $i)}}" autocomplete="off"
                                                    />
                                                </div>
                                                <?php endfor;?>
                                            </div>
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
            jQuery('.max-size-text').text(gks.maxSizeText);

            // banner
            jQuery('.banner_upload_1 input[name=banner_1]').change(function () {
                jQuery('.banner_upload_1 .banner_bg .alert').addClass('hidden');
                if(this.files[0].size > gks.maxSize) {
                    jQuery('.banner_upload_1 .banner_bg .alert').removeClass('hidden');
                    jQuery(this).val("");
                    jQuery('.banner_upload_1 .banner_upload_preview').empty();
                    return false;
                }

                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                    var filesAmount = input.files.length;

                    for (var i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            jQuery('.banner_upload_1 .banner_upload_preview').empty().append("<div class='banner_preview'><img src='" + e.target.result + "' /></div>");
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            });
            jQuery('.banner_upload_2 input[name=banner_2]').change(function () {
                jQuery('.banner_upload_2 .banner_bg .alert').addClass('hidden');
                if(this.files[0].size > gks.maxSize) {
                    jQuery('.banner_upload_2 .banner_bg .alert').removeClass('hidden');
                    jQuery(this).val("");
                    jQuery('.banner_upload_2 .banner_upload_preview').empty();
                    return false;
                }

                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                    var filesAmount = input.files.length;

                    for (var i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            jQuery('.banner_upload_2 .banner_upload_preview').empty().append("<div class='banner_preview'><img src='" + e.target.result + "' /></div>");
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            });
            jQuery('.banner_upload_3 input[name=banner_3]').change(function () {
                jQuery('.banner_upload_3 .banner_bg .alert').addClass('hidden');
                if(this.files[0].size > gks.maxSize) {
                    jQuery('.banner_upload_3 .banner_bg .alert').removeClass('hidden');
                    jQuery(this).val("");
                    jQuery('.banner_upload_3 .banner_upload_preview').empty();
                    return false;
                }

                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                    var filesAmount = input.files.length;

                    for (var i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            jQuery('.banner_upload_3 .banner_upload_preview').empty().append("<div class='banner_preview'><img src='" + e.target.result + "' /></div>");
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            });
            jQuery('.banner_upload_4 input[name=banner_4]').change(function () {
                jQuery('.banner_upload_4 .banner_bg .alert').addClass('hidden');
                if(this.files[0].size > gks.maxSize) {
                    jQuery('.banner_upload_4 .banner_bg .alert').removeClass('hidden');
                    jQuery(this).val("");
                    jQuery('.banner_upload_4 .banner_upload_preview').empty();
                    return false;
                }

                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                    var filesAmount = input.files.length;

                    for (var i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            jQuery('.banner_upload_4 .banner_upload_preview').empty().append("<div class='banner_preview'><img src='" + e.target.result + "' /></div>");
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            });

            // mobi banner
            jQuery('.banner_upload_1 input[name=mobi_banner_1]').change(function () {
                jQuery('.banner_upload_1 .mobi_banner_bg .alert').addClass('hidden');
                if(this.files[0].size > gks.maxSize) {
                    jQuery('.banner_upload_1 .mobi_banner_bg .alert').removeClass('hidden');
                    jQuery(this).val("");
                    jQuery('.banner_upload_1 .mobi_banner_upload_preview').empty();
                    return false;
                }

                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                    var filesAmount = input.files.length;

                    for (var i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            jQuery('.banner_upload_1 .mobi_banner_upload_preview').empty().append("<div class='mobi_banner_preview'><img src='" + e.target.result + "' /></div>");
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            });
            jQuery('.banner_upload_2 input[name=mobi_banner_2]').change(function () {
                jQuery('.banner_upload_2 .mobi_banner_bg .alert').addClass('hidden');
                if(this.files[0].size > gks.maxSize) {
                    jQuery('.banner_upload_2 .mobi_banner_bg .alert').removeClass('hidden');
                    jQuery(this).val("");
                    jQuery('.banner_upload_2 .mobi_banner_upload_preview').empty();
                    return false;
                }

                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                    var filesAmount = input.files.length;

                    for (var i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            jQuery('.banner_upload_2 .mobi_banner_upload_preview').empty().append("<div class='mobi_banner_preview'><img src='" + e.target.result + "' /></div>");
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            });
            jQuery('.banner_upload_3 input[name=mobi_banner_3]').change(function () {
                jQuery('.banner_upload_3 .mobi_banner_bg .alert').addClass('hidden');
                if(this.files[0].size > gks.maxSize) {
                    jQuery('.banner_upload_3 .mobi_banner_bg .alert').removeClass('hidden');
                    jQuery(this).val("");
                    jQuery('.banner_upload_3 .mobi_banner_upload_preview').empty();
                    return false;
                }

                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                    var filesAmount = input.files.length;

                    for (var i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            jQuery('.banner_upload_3 .mobi_banner_upload_preview').empty().append("<div class='mobi_banner_preview'><img src='" + e.target.result + "' /></div>");
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            });
            jQuery('.banner_upload_4 input[name=mobi_banner_4]').change(function () {
                jQuery('.banner_upload_4 .mobi_banner_bg .alert').addClass('hidden');
                if(this.files[0].size > gks.maxSize) {
                    jQuery('.banner_upload_4 .mobi_banner_bg .alert').removeClass('hidden');
                    jQuery(this).val("");
                    jQuery('.banner_upload_4 .mobi_banner_upload_preview').empty();
                    return false;
                }

                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                    var filesAmount = input.files.length;

                    for (var i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            jQuery('.banner_upload_4 .mobi_banner_upload_preview').empty().append("<div class='mobi_banner_preview'><img src='" + e.target.result + "' /></div>");
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            });

            // cate
            jQuery('.cate_upload_1 input[type=file]').change(function () {
                jQuery('.cate_upload_1 .alert').addClass('hidden');
                if(this.files[0].size > gks.maxSize) {
                    jQuery('.cate_upload_1 .alert').removeClass('hidden');
                    jQuery(this).val("");
                    jQuery('.cate_upload_1 .cate_upload_preview').empty();
                    return false;
                }

                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                    var filesAmount = input.files.length;

                    for (var i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            jQuery('.cate_upload_1 .cate_upload_preview').empty().append("<div class='cate_preview'><img src='" + e.target.result + "' /></div>");
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            });
            jQuery('.cate_upload_2 input[type=file]').change(function () {
                jQuery('.cate_upload_2 .alert').addClass('hidden');
                if(this.files[0].size > gks.maxSize) {
                    jQuery('.cate_upload_2 .alert').removeClass('hidden');
                    jQuery(this).val("");
                    jQuery('.cate_upload_2 .cate_upload_preview').empty();
                    return false;
                }

                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                    var filesAmount = input.files.length;

                    for (var i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            jQuery('.cate_upload_2 .cate_upload_preview').empty().append("<div class='cate_preview'><img src='" + e.target.result + "' /></div>");
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            });
            jQuery('.cate_upload_3 input[type=file]').change(function () {
                jQuery('.cate_upload_3 .alert').addClass('hidden');
                if(this.files[0].size > gks.maxSize) {
                    jQuery('.cate_upload_3 .alert').removeClass('hidden');
                    jQuery(this).val("");
                    jQuery('.cate_upload_3 .cate_upload_preview').empty();
                    return false;
                }

                var input = this;
                var url = $(this).val();
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                    var filesAmount = input.files.length;

                    for (var i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            jQuery('.cate_upload_3 .cate_upload_preview').empty().append("<div class='cate_preview'><img src='" + e.target.result + "' /></div>");
                        }
                        reader.readAsDataURL(input.files[i]);
                    }
                }
            });
        });
    </script>
@stop
