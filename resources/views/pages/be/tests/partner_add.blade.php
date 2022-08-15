<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();

$viewer = $apiCore->getViewer();
$oldPhotos = "";

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
            <form action="{{url('admin/partner/save')}}" method="post" enctype="multipart/form-data"
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
                                                <div class="alert alert-info">Mật khẩu mặc định cho đối tác là: dt123456</div>
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

                                        <div class="form-group alert alert-danger hidden">Hãy nhập họ tên.</div>
                                    </div>

                                    <div class="col-md-3" id="req-email">
                                        <div class="form-group">
                                            <label class="frm-label required">* Email</label>
                                            <input required value="{{$item ? $item->email : ""}}" name="email" type="email" autocomplete="off" class="form-control" />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập email hợp lệ.</div>
                                    </div>

                                    <div class="col-md-3" id="req-phone">
                                        <div class="form-group">
                                            <label class="frm-label required">* Điện Thoại</label>
                                            <input required value="{{$item ? $item->phone : ""}}" name="phone" type="text" autocomplete="off" class="form-control"
                                                   onkeypress="return isInputPhone(event, this)"
                                                   oncopy="return false;" oncut="return false;" onpaste="return false;"
                                            />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập điện thoại (>=10 số).</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3" id="req-code">
                                        <div class="form-group">
                                            <label class="frm-label required">mã đối tác</label>
                                            <input value="{{$item ? $item->ref_code : ""}}" name="ref_code" type="text" autocomplete="off" class="form-control text-uppercase"
                                                   onkeypress="return pressNoSpace(event, this)"
                                                   oncopy="return false;" oncut="return false;" onpaste="return false;"
                                            />
                                            <p>+ Đối Với Cá Nhân: [Chữ Cái Đầu Của Tên] + 6 số đầu Điện Thoại (nếu trùng 6 số Điện Thoại + 1)</p>
                                            <p>+ Đối Với Tổ Chức: Vui lòng tự định nghĩa</p>
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập mã đối tác cho tổ chức.</div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="frm-label">đơn vị</label>
                                            <select name="don_vi" class="form-control">
                                                <option @if($item && $item->don_vi == 'ca_nhan') selected="selected" @endif value="ca_nhan">Cá Nhân</option>
                                                <option @if($item && $item->don_vi == 'to_chuc') selected="selected" @endif value="to_chuc">Tổ Chức</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="frm-label">loại đối tác</label>
                                            <select name="loai_doi_tac" class="form-control">
                                                <option value="thanh_vien">Đối Tác Là Thành Viên</option>
                                                <option @if($item && $item->chuyenGia()) selected="selected" @endif value="chuyen_gia">Đối Tác Là Chuyên Gia</option>
                                                <option @if($item && $item->doiTacDacBiet()) selected="selected" @endif value="dac_biet">Đối Tác Đặc Biệt</option>
                                                <option @if($item && $item->chuyenGia() && $item->doiTacDacBiet()) selected="selected" @endif value="chuyen_gia_dac_biet">Chuyên Gia Đặc Biệt</option>
                                            </select>
                                        </div>
                                    </div>

                                    @if ($viewer->isSuperAdmin() || $viewer->isAdmin())
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="frm-label required">Người Giới Thiệu</label>
                                                <select name="ref_id" class="form-control">
                                                    <option value="">Không Có Người Giới Thiệu</option>
                                                    @if(count($ngts))
                                                        @foreach($ngts as $ite)
                                                            <option <?php if ($item && $item->ref_id == $ite->id):?> selected="selected" <?php endif;?> value="{{$ite->id}}">{{$ite->ref_code . ' - ' . $ite->getTitle()}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <p class="required font-weight-bold font-italic">Chỉ Admin mới có quyền gán / thay đổi người giới thiệu</p>
                                            </div>
                                        </div>
                                    @endif
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
                                            <select name="province_id" class="form-control select-css mt__10" onchange="jscartaddressopts(this, 'district')">
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
                                            <select name="district_id" class="form-control select-css mt__10" onchange="jscartaddressopts(this, 'ward')">
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
                                            <select name="ward_id" class="form-control select-css mt__10">
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
                                            <label class="frm-label">chứng chỉ hành nghề</label>
                                            <input value="{{$item ? $item->chung_chi_hanh_nghe : ""}}" name="chung_chi_hanh_nghe" type="text" autocomplete="off" class="form-control" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="frm-label">thông tin chuyển khoản</label>
                                            <textarea class="form-control" name="thong_tin_chuyen_khoan" rows="5" cols="3">{{$item ? $item->thong_tin_chuyen_khoan : ""}}</textarea>
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
                                <button type="submit" class="btn btn-primary btn-sm mb-1" >
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

    <script type="text/javascript" src="{{url('public/js/be/partner_add.js')}}"></script>

@stop
