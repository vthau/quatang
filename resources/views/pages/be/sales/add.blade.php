<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();

$viewer = $apiCore->getViewer();


?>

@extends('templates.be.master')

@section('content')

    <style type="text/css">
        .width_80px {
            width: 80px !important;
        }

        .width_150px {
            width: 150px !important;
        }
    </style>

    <div>
        <div class="fade-in">
            <form action="{{url('admin/sale/save')}}" method="post" enctype="multipart/form-data"
                  id="frm-add" accept-charset="UTF-8" autocomplete="off"
            >
                <input type="hidden" name="_token" value="{{csrf_token()}}" />

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>{{$pageTitle}}</strong>
                            </div>

                            <div class="card-body">

                                <div class="row" >
                                    <div class="col-md-12" id="req-title">
                                        <div class="form-group">
                                            <label class="required">* Tên</label>
                                            <input required id="frm-title" value="{{$sale ? $sale->title : ""}}" name="title" type="text" autocomplete="off" class="form-control" />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập tên chương trình.</div>
                                    </div>
                                </div>

                                <div class="row" >
                                    <div class="col-md-6" id="req-date_start">
                                        <div class="form-group">
                                            <label class="required">* Ngày Bắt Đầu</label>
                                            <input required value="{{$sale ? $sale->date_start : ""}}" id="frm-date_start" class="form-control" name="date_start" type="date" />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập ngày bắt đầu.</div>
                                    </div>

                                    <div class="col-md-6" id="req-date_end">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Ngày Kết Thúc</label>
                                            <input value="{{$sale ? $sale->date_end : ""}}" id="frm-date_end" class="form-control" name="date_end" type="date" />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu.</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="required">* Áp dụng cho</label>
                                        </div>
                                        <div class="form-group mt-1">
                                            <div class="row">
                                                <div class="col-md-1 mb-1">
                                                    <label class="c-switch c-switch-label c-switch-pill c-switch-danger float-left">
                                                        <input name="sale_all" class="c-switch-input" type="checkbox" />
                                                        <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                    </label>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <input name="sale_all_percent" type="text" class="form-control money_format text-center" placeholder="%" />
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <span>Áp dụng cho tất cả đơn hàng</span>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-1 mb-1">
                                                    <label class="c-switch c-switch-label c-switch-pill c-switch-danger float-left">
                                                        <input name="sale_first" class="c-switch-input" type="checkbox" />
                                                        <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                    </label>
                                                </div>
                                                <div class="col-md-1 mb-1">
                                                    <input name="sale_first_percent" type="text" class="form-control money_format text-center" placeholder="%" />
                                                </div>
                                                <div class="col-md-10 mb-1">
                                                    <span>Áp dụng cho đơn hàng đầu tiên sau khi đăng kí thành viên</span>
                                                </div>
                                            </div>

                                            <div id="sale_dk">
                                                <div class="row">
                                                    <div class="col-md-1 mb-1">
                                                        <label class="c-switch c-switch-label c-switch-pill c-switch-danger float-left">
                                                            <input name="sale_dk_1_checkbox" class="c-switch-input" type="checkbox" />
                                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <input name="sale_dk_1_percent" type="text" class="form-control money_format text-center" placeholder="%" />
                                                    </div>
                                                    <div class="col-md-9 mb-1">
                                                        <span>Áp dụng cho đơn hàng thứ <input type="text" class="text-center number_format width_80px" name="sale_dk_1_input" /> sau khi đăng kí thành viên</span>
                                                    </div>
                                                    <div class="col-md-1 mb-1 text-center">
                                                        <button type="button" class="btn btn-info" onclick="themDKThu()">
                                                            <i class="fa fa-plus-circle mr-1"></i> thêm
                                                        </button>

                                                        <input type="hidden" name="sale_dk_total" value="1" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="sale_gt">
                                                <div class="row">
                                                    <div class="col-md-1 mb-1">
                                                        <label class="c-switch c-switch-label c-switch-pill c-switch-danger float-left">
                                                            <input name="sale_gt_1_checkbox" class="c-switch-input" type="checkbox" />
                                                            <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-1 mb-1">
                                                        <input name="sale_gt_1_percent" type="text" class="form-control money_format text-center" placeholder="%" />
                                                    </div>
                                                    <div class="col-md-9 mb-1">
                                                        <span>Áp dụng cho đơn hàng có giá trị từ <input type="text" class="text-center money_format width_150px" name="sale_gt_1_from" /> đến <input type="text" class="text-center money_format width_150px" name="sale_gt_1_to" /> </span>
                                                    </div>
                                                    <div class="col-md-1 mb-1 text-center">
                                                        <button type="button" class="btn btn-info" onclick="themDKGT()">
                                                            <i class="fa fa-plus-circle mr-1"></i> thêm
                                                        </button>

                                                        <input type="hidden" name="sale_gt_total" value="1" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="alert alert-danger hidden err_sale_apply">Vui lòng chọn ít nhất 1 loại khuyến mãi áp dụng</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row hidden">
                                    <div class="col-md-12" id="req-avatar">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Ảnh Đại Diện</label>
                                            <div>
                                                <input name="avatar" id="upload-avatar" type="file" accept="image/*" />

                                                <div class="alert alert-danger hidden mt-3">Vui lòng không upload hình lớn hơn <b class="max-size-text"></b>.</div>
                                            </div>
                                        </div>

                                        <div class="form-group" id="avatar-preview">
                                            @if ($sale && !empty($sale->getAvatar()))
                                                <div class='img-preview'>
                                                    <img src="{{$sale->getAvatar()}}" />
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row hidden">
                                    <div class="col-md-12" id="req-banner">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Ảnh Banner</label>
                                            <div>
                                                <input name="banner" id="upload-banner" type="file" accept="image/*" />

                                                <div class="alert alert-danger hidden mt-3">Vui lòng không upload hình lớn hơn <b class="max-size-text"></b>.</div>
                                            </div>
                                        </div>

                                        <div class="form-group" id="banner-preview">
                                            @if ($sale && !empty($sale->getBanner()))
                                                <div class='banner-preview'>
                                                    <img src="{{$sale->getBanner()}}" />
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row hidden">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Mô Tả</label>
                                            <textarea name="mo_ta" class="c-tinymce" rows="5">{{$sale ? $sale->mo_ta : ""}}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm mb-1">
                                    <i class="fa fa-check-circle mr-1"></i>
                                    Xác Nhận
                                </button>

                                <input type="submit" class="hidden" />
                                <input type="hidden" id="frm-id" name="item-id" value="{{$sale ? $sale->id : ""}}" />
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{--modal--}}
    <div id="modalAddTo"  class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Áp Dụng Cho</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group" id="modal-add_to">
                        <select class="form-control" id="select-add_to" onchange="changeAddTo(this)">
                            <option value="">Hãy Chọn</option>
                            <option value="group">Nhóm Sản Phẩm</option>
                            <option value="product">Sản Phẩm</option>
                            <option value="brand">Thương Hiệu</option>
                        </select>
                    </div>

                    <div class="form-group hidden select2-single-wrapper" id="modal-apply_to">
                        <select class="form-control select2-single select2-hidden-accessible" id="select-apply_to">

                        </select>
                    </div>

                    <div class="alert alert-danger hidden"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                    <button type="button" class="btn btn-primary" onclick="confirmAddTo(1)">Có</button>
                </div>

                <div class="hidden">

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{url('public/js/be/sale_add.js')}}"></script>

    <script src="https://cdn.tiny.cloud/1/{{$apiCore->getKey('tinymce')}}/tinymce/5/tinymce.min.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            //apply
            @if ($sale && count($toItems))
                @foreach ($toItems as $toItem)
                    addApplyTo('{{$toItem['item_type']}}', '{{$toItem['item_id']}}', '{{$toItem['item_title']}}', '{{$toItem['discount']}}');
                @endforeach
            @endif

            //tinymce
            tinymce.init({
                selector: 'textarea.c-tinymce',
                plugins: 'code print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
                toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen | link image media | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
                image_advtab: true,
                height: 400,
                //local upload
                images_upload_handler: function (blobInfo, success, failure) {
                    var xhr, formData;

                    xhr = new XMLHttpRequest();
                    xhr.withCredentials = true;
                    xhr.open('POST', '{{url('admin/tinymce/upload')}}');

                    xhr.onload = function() {
                        var json;

                        if (xhr.status != 200) {
                            failure('HTTP Error: ' + xhr.status);
                            return;
                        }

                        json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.location != 'string') {
                            failure('Invalid JSON: ' + xhr.responseText);
                            return;
                        }

                        success(json.location);
                    };

                    formData = new FormData();
                    formData.append('_token', '{{csrf_token()}}');
                    formData.append('file', blobInfo.blob(), blobInfo.filename());

                    xhr.send(formData);
                },
            });
        });

    </script>
@stop
