<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();

$apiFE = new \App\Api\FE;
$provinces = $apiFE->getProvinces();
$districts = [];
$wards = [];
if ($user) {
    $districts = $apiFE->getDistrictsByProvinceId($user->province_id);
    $wards = $apiFE->getWardsByDistrictId($user->district_id);
}
?>

@extends('templates.be.master')

@section('content')

    <div>
        <div class="fade-in">
            <form action="{{url('admin/client/save')}}" method="post" enctype="multipart/form-data"
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
                                            @if (!$user)
                                                <div class="alert alert-info">Mật khẩu mặc định cho nhân viên mới là: kh123456</div>
                                            @else
                                                <div class="alert alert-info">Sửa Thông Tin: {{$user->name}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3" id="req-name">
                                        <div class="form-group">
                                            <label class="frm-label required">* Họ Tên</label>
                                            <input required value="{{$user ? $user->name : ""}}" name="name" type="text" autocomplete="off" class="form-control" />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Vui lòng nhập họ tên.</div>
                                    </div>

                                    <div class="col-md-3" id="req-email">
                                        <div class="form-group">
                                            <label class="frm-label required">* Email</label>
                                            <input required value="{{$user ? $user->email : ""}}" name="email" type="email" autocomplete="off" class="form-control" />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Vui lòng nhập email hợp lệ.</div>
                                    </div>

                                    <div class="col-md-3" id="req-phone">
                                        <div class="form-group">
                                            <label class="frm-label required">* Điện Thoại</label>
                                            <input required value="{{$user ? $user->phone : ""}}" name="phone" type="text" autocomplete="off" class="form-control"
                                                   onkeypress="return isInputPhone(event, this)"
                                                   oncopy="return false;" oncut="return false;" onpaste="return false;"
                                            />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập điện thoại.</div>
                                    </div>
                                </div>

                                <div class="row" id="frm-address">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="frm-label">Địa chỉ</label>
                                            <input value="{{$user ? $user->address : ""}}" name="address" type="text" autocomplete="off" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="frm-label">tỉnh / thành</label>
                                            <select name="province_id" class="form-control" onchange="jscartaddressopts(this, 'district')">
                                                <option value="">Hãy chọn tỉnh / thành</option>
                                                @foreach($provinces as $ite)
                                                    <option @if($user && $user->province_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
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
                                                        <option @if($user && $user->district_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
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
                                                        <option @if($user && $user->ward_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
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
                                            @if ($user && !empty($user->getAvatar()))
                                                <div class='img-preview'>
                                                    <img src="{{$user->getAvatar()}}" />
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

                                <input type="hidden" name="item_id" value="{{$user ? $user->id : ""}}" />
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="{{url('public/js/back_end/client_add.js')}}"></script>

@stop
