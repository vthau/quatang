<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();

$viewer = $apiCore->getViewer();
?>

@extends('templates.be.master')

@section('content')

    <div>
        <div class="fade-in">
            <form action="{{url('admin/texts/save')}}" method="post" id="frm-add" enctype="multipart/form-data" >
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><strong>{{$pageTitle}}</strong></div>
                            <div class="card-body card-block">

                                <div class="col-md-12 kero-tab" id="kero-1">
                                    <div class="mb-3 card">
                                        <div class="card-header">
                                            <ul class="nav nav-justified">
                                                <li class="nav-item">
                                                    <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('text_sp')" class="nav-link text_sp active">
                                                        <span>sản phẩm</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('text_tk')" class="nav-link text_tk">
                                                        <span>tài khoản</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('text_dh')" class="nav-link text_dh">
                                                        <span>giỏ hàng</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('text_dn')" class="nav-link text_dn">
                                                        <span>đăng nhập</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('text_dk')" class="nav-link text_dk">
                                                        <span>đăng kí</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body mb__20">
                                            <div class="tab-content">
                                                <div class="tab-pane text_sp active" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">các sản phẩm trong gói combo</label>
                                                                <input value="{{$apiCore->getSetting('text_sp_combo')}}" name="text_sp_combo" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">nhóm sản phẩm</label>
                                                                <input value="{{$apiCore->getSetting('text_sp_nsp')}}" name="text_sp_nsp" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">thương hiệu</label>
                                                                <input value="{{$apiCore->getSetting('text_sp_th')}}" name="text_sp_th" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">xuất xứ</label>
                                                                <input value="{{$apiCore->getSetting('text_sp_xx')}}" name="text_sp_xx" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">kích thước</label>
                                                                <input value="{{$apiCore->getSetting('text_sp_kt')}}" name="text_sp_kt" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">thể tích (ml)</label>
                                                                <input value="{{$apiCore->getSetting('text_sp_tt')}}" name="text_sp_tt" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">khối lượng (kg)</label>
                                                                <input value="{{$apiCore->getSetting('text_sp_cn')}}" name="text_sp_cn" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane text_dn" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">đăng nhập tài khoản</label>
                                                                <input value="{{$apiCore->getSetting('text_dn_title')}}" name="text_dn_title" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">email</label>
                                                                <input value="{{$apiCore->getSetting('text_dn_email')}}" name="text_dn_email" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">mật khẩu</label>
                                                                <input value="{{$apiCore->getSetting('text_dn_mat_khau')}}" name="text_dn_mat_khau" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">xác nhận</label>
                                                                <input value="{{$apiCore->getSetting('text_dn_xac_nhan')}}" name="text_dn_xac_nhan" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">chưa có tài khoản?</label>
                                                                <input value="{{$apiCore->getSetting('text_dn_chua_co_tai_khoan')}}" name="text_dn_chua_co_tai_khoan" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">quên mật khẩu?</label>
                                                                <input value="{{$apiCore->getSetting('text_dn_quen_mat_khau')}}" name="text_dn_quen_mat_khau" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">đăng nhập với facebook</label>
                                                                <input value="{{$apiCore->getSetting('text_dn_facebook')}}" name="text_dn_facebook" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">đăng nhập với google</label>
                                                                <input value="{{$apiCore->getSetting('text_dn_google')}}" name="text_dn_google" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label style="text-transform: none !important;">Hãy nhập email hợp lệ</label>
                                                                <input value="{{$apiCore->getSetting('text_dn_loi_email')}}" name="text_dn_loi_email" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label style="text-transform: none !important;">Hãy nhập mật khẩu</label>
                                                                <input value="{{$apiCore->getSetting('text_dn_loi_mk')}}" name="text_dn_loi_mk" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane text_dk" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">đăng kí thành viên</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_dang_ki_thanh_vien')}}" name="text_dk_dang_ki_thanh_vien" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">họ tên</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_ho_ten')}}" name="text_dk_ho_ten" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">email</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_email')}}" name="text_dk_email" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">mật khẩu</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_mat_khau')}}" name="text_dk_mat_khau" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">xác nhận mật khẩu</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_xac_nhan_mat_khau')}}" name="text_dk_xac_nhan_mat_khau" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">điện thoại</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_dien_thoai')}}" name="text_dk_dien_thoai" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">địa chỉ</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_dia_chi')}}" name="text_dk_dia_chi" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">xác nhận</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_xac_nhan')}}" name="text_dk_xac_nhan" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">đăng kí với facebook</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_facebook')}}" name="text_dk_facebook" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">đăng kí với google</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_google')}}" name="text_dk_google" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label style="text-transform: none !important;">Tôi đã đọc và đồng ý các chính sách của công ty</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_toi_da_doc')}}" name="text_dk_toi_da_doc" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">trở về đăng nhập</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_tro_ve_dang_nhap')}}" name="text_dk_tro_ve_dang_nhap" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label style="text-transform: none !important;">Hãy nhập họ tên bạn</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_loi_ht')}}" name="text_dk_loi_ht" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label style="text-transform: none !important;">Hãy nhập email hợp lệ</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_loi_email')}}" name="text_dk_loi_email" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label style="text-transform: none !important;">Hãy nhập mật khẩu</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_loi_mk')}}" name="text_dk_loi_mk" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label style="text-transform: none !important;">[Xác Nhận Mật Khẩu] và [Mật Khẩu] không giống nhau</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_loi_xnmk')}}" name="text_dk_loi_xnmk" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label style="text-transform: none !important;">Hãy nhập số điện thoại (>= 10 số)</label>
                                                                <input value="{{$apiCore->getSetting('text_dk_loi_dt')}}" name="text_dk_loi_dt" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane text_tk" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">Thông Tin Tài Khoản</label>
                                                                <input value="{{$apiCore->getSetting('text_tk_title')}}" name="text_tk_title" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">Thông Tin Cá Nhân</label>
                                                                <input value="{{$apiCore->getSetting('text_tk_ttcn')}}" name="text_tk_ttcn" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">Đơn Hàng Đã Đặt</label>
                                                                <input value="{{$apiCore->getSetting('text_tk_dhdd')}}" name="text_tk_dhdd" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">Sản Phẩm Xem Gần Nhất</label>
                                                                <input value="{{$apiCore->getSetting('text_tk_spdx')}}" name="text_tk_spdx" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane text_dh" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">Xác Nhận Giỏ Hàng Tiêu Đề</label>
                                                                <input value="{{$apiCore->getSetting('text_dh_confirm_text_title')}}" name="text_dh_confirm_text_title" type="text" autocomplete="off" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label class="text-capitalize">Xác Nhận Giỏ Hàng Nội Dung</label>
                                                                <textarea name="text_dh_confirm_text_body" autocomplete="off" class="form-control" rows="5" cols="3">{{$apiCore->getSetting('text_dh_confirm_text_body')}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
        });
    </script>
@stop
