<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();

$viewer = $apiCore->getViewer();

$URL = url('tro-thanh-cong-tac-vien');
?>

@extends('templates.be.master')

@section('content')

    <style type="text/css">
        input#code_url {
            padding-right: 60px;
        }

        button.code_url {
            position: absolute;
            top: 0;
            right: 15px;
        }
    </style>

    @if ($saved)
        <div class="alert alert-success">Thông tin đã được lưu thành công.</div>
    @endif

    <div>
        <div class="fade-in">
            <form action="{{url('admin/partner-settings/update')}}" method="post" id="frm-add" enctype="multipart/form-data" >
                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><strong>{{$pageTitle}}</strong></div>
                            <div class="card-body card-block">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label class="frm-label required">link đăng kí đối tác dành cho người đã có kinh nghiệm</label>
                                            <div class="row">
                                                <div class="col-md-4 position-relative">
                                                    <input class="form-control" type="text" value="{{$URL}}" id="code_url" readonly />
                                                    <button type="button" class="btn btn-success text-uppercase code_url" onclick="copyToClipboard()">
                                                        copy
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="frm-label required">tỉ lệ % hoa hồng tư vấn</label>
                                            <input value="{{$apiCore->getSetting('percent_tvtt')}}" name="percent_tvtt" type="text" class="form-control money_format" placeholder="% tư vấn trực tiếp" autocomplete="off" />
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="frm-label required">tỉ lệ % hoa hồng phát triển hệ thống</label>
                                            <input value="{{$apiCore->getSetting('percent_tvgt')}}" name="percent_tvgt" type="text" class="form-control money_format" placeholder="% tư vấn gián tiếp" autocomplete="off" />
                                        </div>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <div class="form-group">
                                            <label class="frm-label required">tỉ lệ % giảm giá cho người dùng khi nhập mã giảm giá lần đầu tiên</label>
                                            <input value="{{$apiCore->getSetting('percent_first_code')}}" name="percent_first_code" type="text" class="form-control money_format" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label class="frm-label">Đề Test đối tác tư vấn</label>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <input value="{{$apiCore->getSetting('truc_tiep_total')}}" name="truc_tiep_total" type="number" class="form-control" placeholder="Tổng số câu hỏi" autocomplete="off" />
                                                </div>
                                                <div class="col-md-4">
                                                    <input value="{{$apiCore->getSetting('truc_tiep_passed')}}" name="truc_tiep_passed" type="number" class="form-control" placeholder="Số câu đúng cần đạt" autocomplete="off" />
                                                </div>
                                                <div class="col-md-4">
                                                    <input value="{{$apiCore->getSetting('truc_tiep_time')}}" name="truc_tiep_time" type="number" class="form-control" placeholder="Số phút thực hiện" autocomplete="off" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row hidden">
                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label class="frm-label ">Đề Test ĐTTV Gián Tiếp</label>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <input value="{{$apiCore->getSetting('gian_tiep_total')}}" name="gian_tiep_total" type="number" class="form-control" placeholder="Tổng số câu hỏi" autocomplete="off" />
                                                </div>
                                                <div class="col-md-4">
                                                    <input value="{{$apiCore->getSetting('gian_tiep_passed')}}" name="gian_tiep_passed" type="number" class="form-control" placeholder="Số câu đúng cần đạt" autocomplete="off" />
                                                </div>
                                                <div class="col-md-4">
                                                    <input value="{{$apiCore->getSetting('gian_tiep_time')}}" name="gian_tiep_time" type="number" class="form-control" placeholder="Số phút thực hiện" autocomplete="off" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label class="frm-label required">thưởng doanh số - doanh số cần đạt</label>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="frm-label">mức 1</label>
                                                    <input value="{{$apiCore->getSetting('doanh_so_gold')}}" name="doanh_so_gold" type="text" class="form-control number_format" autocomplete="off" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="frm-label">mức 2</label>
                                                    <input value="{{$apiCore->getSetting('doanh_so_diamond')}}" name="doanh_so_diamond" type="text" class="form-control number_format" autocomplete="off" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="frm-label">mức 3</label>
                                                    <input value="{{$apiCore->getSetting('doanh_so_platinum')}}" name="doanh_so_platinum" type="text" class="form-control number_format" autocomplete="off" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div class="form-group">
                                            <label class="frm-label required">thưởng doanh số - tỉ lệ %</label>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="frm-label">mức 1</label>
                                                    <input value="{{$apiCore->getSetting('percent_gold')}}" name="percent_gold" type="text" class="form-control money_format" autocomplete="off" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="frm-label">mức 2</label>
                                                    <input value="{{$apiCore->getSetting('percent_diamond')}}" name="percent_diamond" type="text" class="form-control money_format" autocomplete="off" />
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="frm-label">mức 3</label>
                                                    <input value="{{$apiCore->getSetting('percent_platinum')}}" name="percent_platinum" type="text" class="form-control money_format" autocomplete="off" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="frm-label">Chính Sách trở thành đối tác</label>
                                            <textarea class="c-tinymce" name="partner_policy" >{{$apiCore->getSetting('partner_policy')}}</textarea>
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

    <script src="https://cdn.tiny.cloud/1/{{$apiCore->getKey('tinymce')}}/tinymce/5/tinymce.min.js"></script>

    <script type="text/javascript">
        tinymce.init({
            selector: 'textarea.c-tinymce',
            plugins: 'code print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
            toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen | link image media | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
            image_advtab: true,
            height: 700,
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

        function copyToClipboard() {
            document.getElementById("code_url").select();
            document.execCommand('copy');
        }
    </script>
@stop
