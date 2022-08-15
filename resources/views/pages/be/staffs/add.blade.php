<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();

$apiFE = new \App\Api\FE;
$provinces = $apiFE->getProvinces();
$districts = [];
$wards = [];
if ($item) {
    $districts = $apiFE->getDistrictsByProvinceId($item->province_id);
    $wards = $apiFE->getWardsByDistrictId($item->district_id);
}
?>

@extends('templates.be.master')

@section('content')

    <div>
        <div class="fade-in">
            <form action="{{url('admin/staff/save')}}" method="post" enctype="multipart/form-data"
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
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            @if (!$item)
                                                <div class="alert alert-info">Mật khẩu mặc định cho nhân viên mới là: nv123456</div>
                                            @else
                                                <div class="alert alert-info">Sửa Thông Tin: {{$item->name}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3" id="req-name">
                                        <div class="form-group">
                                            <label class="frm-label required">* Họ Tên</label>
                                            <input required value="{{$item ? $item->name : ""}}" name="name" type="text" autocomplete="off" class="form-control" />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Vui lòng nhập họ tên.</div>
                                    </div>

                                    <div class="col-md-3" id="req-email">
                                        <div class="form-group">
                                            <label class="frm-label required">* Email</label>
                                            <input required value="{{$item ? $item->email : ""}}" name="email" type="email" autocomplete="off" class="form-control" />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Vui lòng nhập email hợp lệ.</div>
                                    </div>

                                    <div class="col-md-3" id="req-phone">
                                        <div class="form-group">
                                            <label class="frm-label required">* Điện Thoại</label>
                                            <input required value="{{$item ? $item->phone : ""}}" name="phone" type="text" autocomplete="off" class="form-control"
                                                   onkeypress="return isInputPhone(event, this)"
                                                   oncopy="return false;" oncut="return false;" onpaste="return false;"
                                            />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập điện thoại.</div>
                                    </div>

                                    <div class="col-md-3" id="req-level">
                                        <div class="form-group">
                                            <label class="frm-label required">* Quyền Truy Cập</label>
                                            <select required class="form-control" name="level_id" onchange="changeLevel(this)">
                                                @foreach($levels as $k => $v)
                                                    <option <?php if ($level_id == $k):?>selected="selected" <?php endif;?> value="{{$k}}">{{$v}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 @if ($item && $item->supplier_id) @else hidden @endif" id="req-brand">
                                        <div class="form-group">
                                            <label class="frm-label required">Thương hiệu</label>
                                            <select class="form-control" name="brand_id">
                                                <option value="">Hãy Chọn</option>
                                                @foreach($brands as $ite)
                                                    <option <?php if ($item && $item->supplier_id == $ite->id):?>selected="selected" <?php endif;?> value="{{$ite->id}}">{{$ite->getTitle()}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="frm-address">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="frm-label">Địa chỉ</label>
                                            <input value="{{$item ? $item->address : ""}}" name="address" type="text" autocomplete="off" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="frm-label">tỉnh / thành</label>
                                            <select name="province_id" class="form-control" onchange="jscartaddressopts(this, 'district')">
                                                <option value="">Hãy chọn tỉnh / thành</option>
                                                @foreach($provinces as $ite)
                                                    <option @if($item && $item->province_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="frm-label">quận / huyện</label>
                                            <select name="district_id" class="form-control" onchange="jscartaddressopts(this, 'ward')">
                                                <option value="">Hãy chọn quận / huyện</option>
                                                @if (count($districts))
                                                    @foreach($districts as $ite)
                                                        <option @if($item && $item->district_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="frm-label">phường / xã</label>
                                            <select name="ward_id" class="form-control">
                                                <option value="">Hãy chọn phường / xã</option>
                                                @if (count($wards))
                                                    @foreach($wards as $ite)
                                                        <option @if($item && $item->ward_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="frm-label">ghi chú</div>
                                            <textarea name="note" rows="5" cols="3" class="form-control">{{$item ? $item->note : ''}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12" id="req-avatar">
                                        <div class="form-group">
                                            <label class="frm-label">Ảnh Đại Diện (Recommended: square 128 x 128)</label>
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
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm mb-1">
                                    <i class="fa fa-check-circle mr-1"></i>
                                    Xác Nhận
                                </button>

                                <input type="hidden" name="item_id" value="{{$item ? $item->id : ""}}" />
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="{{url('public/js/be/staff_add.js')}}"></script>

@stop
