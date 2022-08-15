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
            <form action="{{url('admin/product/save')}}" method="post" enctype="multipart/form-data"
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
                                            <label class="required">* Tên</label>
                                            <input required value="{{$item ? $item->title : ""}}" name="title" type="text" autocomplete="off" class="form-control" />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập tên sản phẩm.</div>
                                    </div>

                                    <div class="col-md-6" id="req-category">
                                        <div class="form-group">
                                            <label class="required">* {{$apiCore->getSetting('text_sp_nsp')}}</label>
                                            <div class="select2-single-wrapper">
                                                <select required id="filter-category" name="product_category_id" class="form-control"></select>
                                            </div>
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy chọn nhóm sản phẩm.</div>
                                    </div>
                                </div>

                                <div class="row" >
                                    <div class="col-md-6" id="req-brand">
                                        <div class="form-group">
                                            <label class="required">* {{$apiCore->getSetting('text_sp_th')}}</label>
                                            <div class="select2-single-wrapper">
                                                <select required name="product_brand_id" class="form-control select2-single select2-hidden-accessible">
                                                    @if (!$viewer->isSupplier())
                                                    <option value="">Hãy Chọn</option>
                                                    @endif
                                                    @foreach ($brands as $brand)
                                                        <option <?php if ($item && $item->product_brand_id == $brand['id']):?> selected="selected" <?php endif;?> value="{{$brand['id']}}">{{$brand['title']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy chọn thương hiệu.</div>
                                    </div>

                                    <div class="col-md-6" id="req-price_main">
                                        <div class="form-group">
                                            <label class="required">* Giá (VND)</label>
                                            <input required value="{{$item ? $item->price_main : ""}}" name="price_main" type="text" autocomplete="off"
                                                   class="form-control money_format"
                                                   onkeyup="calculatePrice()"
                                            />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập giá.</div>
                                    </div>
                                </div>

                                <div class="row " >
                                    <div class="col-md-6" id="req-discount">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Giảm Giá (%)</label>
                                            <input value="{{$item ? $item->discount : ""}}" name="discount" type="text" autocomplete="off"
                                                   class="form-control number_format"
                                                   onkeyup="calculatePrice()"
                                            />
                                        </div>

                                    </div>

                                    <div class="col-md-6" id="req-price_pay">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Giá Sau Giảm (VND)</label>
                                            <input value="{{$item ? $item->price_pay : ""}}" name="price_pay" type="text" autocomplete="off"
                                                   class="form-control money_format"
                                            />
                                        </div>

                                    </div>
                                </div>

                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="mb-3 font-weight-bold">Trạng Thái</label>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="align-center">
                                                        <label class="float-left" style="margin-right: 5px; width: 25%; position:relative; top: 3px;">Mở Bán</label>
                                                        <label class="c-switch c-switch-label c-switch-pill c-switch-danger float-left">
                                                            <input name="active" class="c-switch-input" type="checkbox" @if ($item && $item->active) checked="checked" @endif /><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="align-center">
                                                        <label class="float-left" style="margin-right: 5px; width: 25%; position:relative; top: 3px;">Sản Phẩm Mới</label>
                                                        <label class="c-switch c-switch-label c-switch-pill c-switch-danger float-left">
                                                            <input name="is_new" class="c-switch-input" type="checkbox" @if ($item && $item->is_new) checked="checked" @endif /><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="align-center">
                                                        <label class="float-left" style="margin-right: 5px; width: 25%; position:relative; top: 3px;">Sản Phẩm Bán Chạy</label>
                                                        <label class="c-switch c-switch-label c-switch-pill c-switch-danger float-left">
                                                            <input name="is_best_seller" class="c-switch-input" type="checkbox" @if ($item && $item->is_best_seller) checked="checked" @endif /><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Tình Trạng</label>
                                            <div class="c-custom-radio">
                                                <label class="radio">
                                                    <input type="radio" @if (!$item || ($item && $item->status == 'con_hang')) checked="checked" @endif value="con_hang" name="status" />
                                                    <span class="check"></span>
                                                    <span class="txt">Còn Hàng</span>
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" @if ($item && $item->status == 'het_hang') checked="checked" @endif name="status" value="het_hang" />
                                                    <span class="check"></span>
                                                    <span class="txt">Hết Hàng</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="req-quantity">
                                        <div class="form-group">
                                            <label class="float-left font-weight-bold" style="margin-right: 15px;">Số Lượng</label>
                                            <div class="float-left" style="width: 40%;">
                                                <label class="float-left" style="margin-right: 15px;">Unlimited</label>
                                                <label class="c-switch c-switch-label c-switch-pill c-switch-danger float-left">
                                                    <input name="unlimited" class="c-switch-input" type="checkbox" @if (!$item || ($item && $item->unlimited)) checked="checked" @endif /><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </div>
                                            <input id="frm-quantity" value="{{$item && $item->quantity >= 0 ? $item->quantity : ""}}" name="quantity" type="text" autocomplete="off"
                                                   class="form-control number_format"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3" id="req-country">
                                        <div class="form-group">
                                            <label class="font-weight-bold">{{$apiCore->getSetting('text_sp_xx')}}</label>
                                            <div class="select2-single-wrapper">
                                                <select id="filter-country" name="made_in" class="form-control select2-single select2-hidden-accessible">
                                                    <option value="">Hãy Chọn</option>
                                                    @foreach ($apiCore->listCountries() as $k => $v)
                                                        <option <?php if ($item && $item->made_in == $k):?> selected="selected" <?php endif;?> value="{{$k}}">{{$v}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group alert alert-danger hidden">Hãy chọn xuất xứ.</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">{{$apiCore->getSetting('text_sp_cn')}}</label>
                                            <input value="{{$item ? $item->can_nang : ""}}" name="can_nang" type="text" autocomplete="off" class="form-control " />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">{{$apiCore->getSetting('text_sp_tt')}}</label>
                                            <input value="{{$item ? $item->the_tich : ""}}" name="the_tich" type="text" autocomplete="off" class="form-control " />
                                        </div>
                                    </div>
                                    <div class="col-md-3" >
                                        <div class="form-group">
                                            <label class="font-weight-bold">{{$apiCore->getSetting('text_sp_kt')}}</label>
                                            <input value="{{$item ? $item->kich_thuoc : ""}}" name="kich_thuoc" type="text" autocomplete="off" class="form-control " />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Nhóm Sản Phẩm Phụ</label>
                                            <select id="filter-category-others" class="form-control" name="cate_others[]" multiple="multiple"></select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">{{$apiCore->getSetting('text_sp_combo')}}</label>
                                            <select class="form-control" name="combo[]" multiple="multiple">
                                                @foreach($others as $ite)
                                                    <option @if($item && count($comboIds) && in_array($ite->id, $comboIds)) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Từ Khóa SEO</label>
                                            <input value="{{$item ? $item->tu_khoa_seo : ""}}" name="tu_khoa_seo" type="text" autocomplete="off" class="form-control " />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Mô Tả Ngắn (30-50 chữ)</label>
                                            <input value="{{$item ? $item->mo_ta_ngan : ""}}" name="mo_ta_ngan" autocomplete="off" type="text" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Video URL</label>
                                            <input value="{{$item ? $item->video_link : ""}}" name="video_link" autocomplete="off" type="text" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Mô Tả</label>
                                            <textarea class="c-tinymce" name="mo_ta" rows="5">{{$item ? $item->mo_ta : ""}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Công Dụng</label>
                                            <textarea class="c-tinymce" name="cong_dung" rows="5">{{$item ? $item->cong_dung : ""}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Thành Phần</label>
                                            <textarea class="c-tinymce" name="thanh_phan" rows="5">{{$item ? $item->thanh_phan : ""}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Hướng Dẫn Sử Dụng</label>
                                            <textarea class="c-tinymce" name="huong_dan_su_dung" rows="5">{{$item ? $item->huong_dan_su_dung : ""}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12" id="req-avatar">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Ảnh Đại Diện</label>
                                            <p class="font-weight-bold text-success">Vui lòng upload hình vuông hoặc thiết kế cùng 1 kích cỡ cho tất cả sản phẩm</p>
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
                                    <div class="col-md-12" id="req-slides">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Ảnh Slideshow</label>
                                            <p class="font-weight-bold text-success">Vui lòng upload hình cùng kích thước với Ảnh Đại Diện</p>
                                            <div>
                                                <input name="slides[]" id="upload-slides" type="file" accept="image/*" multiple="multiple" />

                                                <div class="alert alert-danger hidden mt-3">Vui lòng không upload hình lớn hơn <b class="max-size-text"></b>.</div>
                                            </div>
                                        </div>

                                        <div class="form-group" id="old-preview">
                                            @if ($item && count($photos))
                                                <?php foreach ($photos as $photo):
                                                $oldPhotos .= "p_" . $photo->id . ";";
                                                ?>
                                                <div class="img-item" id="photo-{{$photo->id}}" style="position:relative;">
                                                    <img class="c-btn-img remove-img" onclick="removeSlides({{$photo->id}})" src="{{url('public/images/icons/ic_minus.png')}}" />

                                                    <div class="img-preview">
                                                        <img src="{{$photo->getPhoto()}}" />
                                                    </div>
                                                </div>
                                                <?php endforeach;?>
                                            @endif
                                        </div>
                                        <div class="form-group" id="slides-preview">

                                        </div>

                                        <input type="hidden" value="{{$oldPhotos}}" id="old-photos" name="old_photos" />
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

    <script type="text/javascript" src="{{url('public/js/be/product_add.js')}}"></script>

    <script src="https://cdn.tiny.cloud/1/{{$apiCore->getKey('tinymce')}}/tinymce/5/tinymce.min.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            //select2
            var data = [
                {
                    id: "0",
                    text: "Hãy Chọn",
                    level: 1
                },
                    <?php foreach ($categories as $category):
                    $subs = $category->getSubCategories();
                    ?>
                {
                    id: '{{$category->id}}',
                    text: "> {{$category->title}}",
                    level: 1,
                    <?php if ($item && $item->product_category_id == $category->id):?>
                    selected: 1,
                    <?php endif;?>
                },
                    <?php if (count($subs)):?>
                    <?php foreach ($subs as $sub):
                    $childs = $sub->getSubCategories();
                    ?>
                {
                    id: '{{$sub->id}}',
                    text: "---> {{$sub->title}}",
                    level: 2,
                    <?php if ($item && $item->product_category_id == $sub->id):?>
                    selected: 1,
                    <?php endif;?>
                },
                    <?php if (count($childs)):?>
                    <?php foreach ($childs as $child):?>
                {
                    id: '{{$child->id}}',
                    text: "-----> {{$child->title}}",
                    level: 3,
                    <?php if ($item && $item->product_category_id == $child->id):?>
                    selected: 1,
                    <?php endif;?>
                },
                <?php endforeach;?>
                <?php endif;?>
                <?php endforeach;?>
                <?php endif;?>
                <?php endforeach;?>
            ];

            var dataOthers = [
                {
                    id: "0",
                    text: "Hãy Chọn",
                    level: 1
                },
                    <?php foreach ($categories as $category):
                    $subs = $category->getSubCategories();
                    ?>
                {
                    id: '{{$category->id}}',
                    text: "> {{$category->title}}",
                    level: 1,
                    <?php if ($item && count($categoriesIds) && in_array($category->id, $categoriesIds)):?>
                    selected: 1,
                    <?php endif;?>
                },
                    <?php if (count($subs)):?>
                    <?php foreach ($subs as $sub):
                    $childs = $sub->getSubCategories();
                    ?>
                {
                    id: '{{$sub->id}}',
                    text: "---> {{$sub->title}}",
                    level: 2,
                    <?php if ($item && count($categoriesIds) && in_array($sub->id, $categoriesIds)):?>
                    selected: 1,
                    <?php endif;?>
                },
                    <?php if (count($childs)):?>
                    <?php foreach ($childs as $child):?>
                {
                    id: '{{$child->id}}',
                    text: "-----> {{$child->title}}",
                    level: 3,
                    <?php if ($item && count($categoriesIds) && in_array($child->id, $categoriesIds)):?>
                    selected: 1,
                    <?php endif;?>
                },
                <?php endforeach;?>
                <?php endif;?>
                <?php endforeach;?>
                <?php endif;?>
                <?php endforeach;?>
            ];

            // console.log(data);
            function formatResult(node) {
                var $result = $('<span style="padding-left:' + (20 * node.level) + 'px;">' + node.text + '</span>');
                return $result;
            };

            jQuery("#filter-category").select2({
                data: data,
                formatSelection: function (item) {
                    return item.text
                },
                formatResult: function (item) {
                    return item.text
                },
                templateResult: formatResult,
            });

            jQuery("#filter-category-others").select2({
                data: dataOthers,
                formatSelection: function (item) {
                    return item.text
                },
                formatResult: function (item) {
                    return item.text
                },
                templateResult: formatResult,
            });

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
