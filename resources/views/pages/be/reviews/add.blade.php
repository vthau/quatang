<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();

$viewer = $apiCore->getViewer();
$oldPhotos = "";
?>

@extends('templates.be.master')

@section('content')

    <div>
        <div class="fade-in">
            <form action="{{url('admin/review/save')}}" method="post" enctype="multipart/form-data"
                  id="frm-add" accept-charset="UTF-8" autocomplete="off"
            >
                @csrf

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>{{$pageTitle}}</strong>
                            </div>

                            <div class="card-body">

                                <div class="row" >
                                    <div class="col-md-6" id="req-title">
                                        <div class="form-group">
                                            <label class="required">* Tiêu Đề</label>
                                            <input required id="frm-title" value="{{$item ? $item->title : ""}}" name="title" type="text" autocomplete="off" class="form-control" />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập tiêu đề.</div>
                                    </div>
                                    <div class="col-md-6" id="req-title">
                                        <div class="form-group">
                                            <label class="required">* Điểm</label>
                                            <select name="star" class="form-control">
                                                <option @if($item && $item->star == 5) selected="selected" @endif value="5">5 sao</option>
                                                <option @if($item && $item->star == 4.5) selected="selected" @endif value="4.5">4.5 sao</option>
                                                <option @if($item && $item->star == 4) selected="selected" @endif value="4">4 sao</option>
                                                <option @if($item && $item->star == 3.5) selected="selected" @endif value="3.5">3.5 sao</option>
                                                <option @if($item && $item->star == 3) selected="selected" @endif value="3">3 sao</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6" id="req-name">
                                        <div class="form-group">
                                            <label class="required">* người đánh giá</label>
                                            <input required id="frm-name" value="{{$item ? $item->name : ""}}" name="name" type="text" autocomplete="off" class="form-control" />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập tên người đánh giá.</div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="">chức vụ / chức danh</label>
                                            <input value="{{$item ? $item->position : ""}}" name="position" type="text" autocomplete="off" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12" id="req-avatar">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Ảnh Đại Diện</label>
                                            <div>
                                                <input name="avatar" id="upload-avatar" type="file" accept="image/*" />

                                                <div class="alert alert-danger hidden mt-3">Vui lòng không upload hình lớn hơn <b class="max-size-text"></b>.</div>
                                            </div>
                                        </div>

                                        <div class="form-group" id="avatar-preview">
                                            @if ($item && !empty($item->getAvatar()))
                                                <div class='img-preview'>
                                                    <img src="{{$item->getAvatar()}}" />
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12" id="req-mota">
                                        <div class="form-group">
                                            <label class="font-weight-bold required">* Nội Dung</label>
                                            <textarea id="frm-mota" name="mo_ta" class="c-tinymce" rows="5">{{$item ? $item->mo_ta : ""}}</textarea>
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập nội dung.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm mb-1">
                                    <i class="fa fa-check-circle mr-1"></i>
                                    Xác Nhận
                                </button>

                                <input type="hidden" id="frm-id" name="item_id" value="{{$item ? $item->id : ""}}" />
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="{{url('public/js/be/review_add.js')}}"></script>

    <script src="https://cdn.tiny.cloud/1/{{$apiCore->getKey('tinymce')}}/tinymce/5/tinymce.min.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function () {
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
