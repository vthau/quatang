<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$apiFE = new \App\Api\FE;
$provinces = $apiFE->getProvinces();
$districts = $apiFE->getDistrictsByProvinceId($user->province_id);
$wards = $apiFE->getWardsByDistrictId($user->district_id);

$code = base64_encode('c=' . $user->id);
$URL1 = url('gt?ref=' . $code);
$URL2 = url('gt?ref=' . $code . '&to=sp');
$URL3 = url('gt?ref=' . $code . '&dk=dt');

$validCount = 0;

$commission = $user->createCommission((int)date('m'), (int)date('Y'));
?>

@extends('templates.be.master')

@section('content')
    <style type="text/css">
        input#code_url_1,
        input#code_url_2,
        input#code_url_3 {
            width: 250px;
            padding-right: 88px;
        }

        button.code_url {
            margin-left: -90px;
            width: 88px;
            height: 27px;
            background: #4e97fd;
            color: white;
            border-radius: 3px;
            -webkit-appearance: none;
        }

        #ui-view a.c-href-item {
            position: relative;
            top: 10px;
        }
    </style>

    <div>
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>{{$pageTitle}}</strong>
                        </div>
                        <div class="card-body card-block">
                            <div class="card kero-tab" id="kero-1">
                                <div class="card-header">
                                    <ul class="nav nav-justified">
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('tab_ttin')" class="nav-link tab_ttin active">
                                                <span>thông tin</span>
                                            </a>
                                        </li>
                                        @if ($user->doiTacHopLe())
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('tab_dtac')" class="nav-link tab_dtac">
                                                <span>mã đối tác</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('tab_tke')" class="nav-link tab_tke">
                                                <span>thống kê hoa hồng</span>
                                            </a>
                                        </li>
                                        @endif
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('tab_dhang')" class="nav-link tab_dhang">
                                                <span>đơn hàng đã đặt</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('tab_spxem')" class="nav-link tab_spxem">
                                                <span>sản phẩm xem gần nhất</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane tab_ttin active" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="text-right">
                                                        @if ($viewer->id == $user->id)
                                                            {{-- tai khoan = sua / doi mat khau / upload avatar --}}
                                                            <button class="btn btn-info btn-sm mb-1"
                                                                    title="Sửa" data-original-title="Sửa"
                                                                    onclick="frmEdit()"
                                                            >
                                                                <i class="fa fa-edit"></i>
                                                            </button>

                                                            <button class="btn btn-warning btn-sm mb-1"
                                                                    title="Đổi Mật Khẩu" data-original-title="Đổi Mật Khẩu"
                                                                    onclick="changePassword({{$user->id}})"
                                                            >
                                                                <i class="fa fa-user-lock text-white"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 frm-upload">
                                                    @if ($viewer->id == $user->id)
                                                        <a href="javascript:void(0)" onclick="uploadAvatar()">
                                                            <form action="{{url('admin/staff/upload-avatar')}}" method="post" >
                                                                @csrf
                                                                <div class="c-account-avatar" style="background-image:url('{{$user->getAvatar('profile')}}')"></div>
                                                                <input name="photos[]" id="select_avatar" type="file" accept="image/*" class="hidden" />
                                                                <input type="hidden" name="item_id" value="{{$user->id}}" />
                                                                <input type="submit" class="hidden" />
                                                            </form>
                                                        </a>
                                                    @else
                                                        <div class="c-account-avatar" style="background-image:url('{{$user->getAvatar('profile')}}')"></div>
                                                    @endif
                                                </div>
                                                <div class="col-md-9">
                                                    <form method="post" action="{{url('admin/staff/update')}}" id="frm-add">
                                                        @csrf
                                                        <div class="form-group row">
                                                            <label class="col-md-3 frm-label">Quyền Truy Cập</label>
                                                            <div class="col-md-9">
                                                                <div class="text-uppercase">
                                                                    @if($user->level_id != 4)
                                                                        {{$user->getLevel() ? $user->getLevel()->getTitle() : ''}}
                                                                    @else
                                                                        khách hàng
                                                                    @endif

                                                                    @if($user->isDeleted())
                                                                        <span class="required">( đã xóa )</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-md-3 frm-label">Họ Tên</label>
                                                            <div class="col-md-9" id="req-name">
                                                                <div class="c-info">{{$user->name}}</div>
                                                                <div class="c-edit hidden">
                                                                    <input required value="{{$user->name}}" name="name" type="text" autocomplete="off" class="form-control" />
                                                                    <div class="alert alert-danger hidden mt-sm-1">Vui lòng nhập họ tên.</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-md-3 frm-label">Email</label>
                                                            <div class="col-md-9" id="req-email">
                                                                <div class="c-info">{{$user->email}}</div>
                                                                <div class="c-edit hidden">
                                                                    <input required value="{{$user->email}}" name="email" type="email" autocomplete="off" class="form-control" />
                                                                    <div class="alert alert-danger hidden mt-sm-1">Vui lòng nhập email hợp lệ.</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-md-3 frm-label">Điện Thoại</label>
                                                            <div class="col-md-9" id="req-phone">
                                                                <div class="c-info">{{$user->phone}}</div>
                                                                <div class="c-edit hidden">
                                                                    <input required value="{{$user->phone}}" name="phone" type="text" autocomplete="off" class="form-control"
                                                                           onkeypress="return isInputPhone(event, this)"
                                                                           oncopy="return false;" oncut="return false;" onpaste="return false;"
                                                                    />
                                                                    <div class="alert alert-danger hidden mt-sm-1">Vui lòng nhập số điện thoại (>= 10 số).</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-md-3 frm-label">Địa Chỉ</label>
                                                            <div class="col-md-9">
                                                                <div class="c-info">{{$user->getFullAddress()}}</div>
                                                                <div class="c-edit hidden">
                                                                    <div class="input" id="frm-address">
                                                                        <input name="address" value="{{$user->address}}" class="form-control" type="text" autocomplete="off" />

                                                                        <div class="mt-3">
                                                                            <select name="province_id" class="form-control" onchange="jscartaddressopts(this, 'district')">
                                                                                <option value="">Hãy chọn tỉnh / thành</option>
                                                                                @foreach($provinces as $ite)
                                                                                    <option @if($user->province_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="mt-3">
                                                                            <select name="district_id" class="form-control" onchange="jscartaddressopts(this, 'ward')">
                                                                                <option value="">Hãy chọn quận / huyện</option>
                                                                                @if (count($districts))
                                                                                    @foreach($districts as $ite)
                                                                                        <option @if($user->district_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>

                                                                        <div class="mt-3">
                                                                            <select name="ward_id" class="form-control">
                                                                                <option value="">Hãy chọn phường / xã</option>
                                                                                @if (count($wards))
                                                                                    @foreach($wards as $ite)
                                                                                        <option @if($user->ward_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row" >
                                                            <label class="col-md-3 frm-label">Ghi Chú</label>
                                                            <div class="col-md-9">
                                                                <div class="c-info"><?php echo nl2br($user->note)?></div>
                                                                <div class="c-edit hidden">
                                                                    <textarea class="form-control" name="note" rows="5" cols="3">{{$user->note}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row c-edit hidden float-right mr-lg-5">
                                                            <button type="submit" class="btn btn-primary btn-sm mb-1">
                                                                <i class="fa fa-check-circle mr-1"></i>
                                                                Xác Nhận
                                                            </button>

                                                            <input type="hidden" name="item_id" value="{{$user->id}}" />
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane tab_dtac" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2">
                                                            <label class="frm-label">{{$apiCore->getSetting('text_tk_qrcode')}}</label>
                                                        </div>
                                                        <div class="col-md-8 mb-2">
                                                            <div class="clearfix qr-wrapper">
                                                                {!! \QrCode::size(200)->generate($URL2); !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2">
                                                            <label class="frm-label">{{$apiCore->getSetting('text_tk_link_sp')}}</label>
                                                        </div>
                                                        <div class="col-md-8 mb-2">
                                                            <input type="text" value="{{$URL2}}" id="code_url_2" readonly />
                                                            <button class="button text-uppercase code_url" onclick="jskhcopyurl(2)">
                                                                copy
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2">
                                                            <label class="frm-label">{{$apiCore->getSetting('text_tk_link_dk')}}</label>
                                                        </div>
                                                        <div class="col-md-8 mb-2">
                                                            <input type="text" value="{{$URL1}}" id="code_url_1" readonly />
                                                            <button class="button text-uppercase code_url" onclick="jskhcopyurl(1)">
                                                                copy
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2">
                                                            <label class="frm-label">{{$apiCore->getSetting('text_tk_link_dt')}}</label>
                                                        </div>
                                                        <div class="col-md-8 mb-2">
                                                            <input type="text" value="{{$URL3}}" id="code_url_3" readonly />
                                                            <button class="button text-uppercase code_url" onclick="jskhcopyurl(3)">
                                                                copy
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-2">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2">
                                                            <label class="frm-label">{{$apiCore->getSetting('text_tk_dtcode')}}</label>
                                                        </div>
                                                        <div class="col-md-8 mb-2">
                                                            <div class="font-weight-bold text-success text-uppercase">{{$user->ref_code}}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane tab_tke" role="tabpanel">
                                            <div class="card kero-tab" id="kero-2">
                                                <div class="card-header">
                                                    <ul class="nav nav-justified">
                                                        <li class="nav-item">
                                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent2('dskh')" class="nav-link dskh active">
                                                                <span>{{$apiCore->getSetting('text_tk_ds_kh_care') . ' (' . count($user->dsChamSoc()) . ')'}}</span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent2('dsdt')" class="nav-link dsdt">
                                                                <span>{{$apiCore->getSetting('text_tk_ds_dt_care') . ' (' . count($user->doiTacCons()) . ')'}}</span>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent2('thht')" class="nav-link thht">
                                                                <span>{{$apiCore->getSetting('text_tk_hoa_hong_thang_title')}}</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="card-body">
                                                    <div class="tab-content">
                                                        <div class="tab-pane dskh active" role="tabpanel">
                                                            @if(count($user->dsChamSoc()))
                                                                <div class="main-card mb-3 card">
                                                                    <div class="card-body">
                                                                        <div class="dataTables_wrapper dt-bootstrap4">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <table id="exp1" style="width: 100%;" class="table kero_tables table-hover table-striped table-bordered dataTable dtr-inline" role="grid">
                                                                                        <thead>
                                                                                        <tr role="row">
                                                                                            <th >STT</th>
                                                                                            <th >Họ Tên</th>
                                                                                            <th >Liên Hệ</th>
                                                                                            <th >Địa Chỉ</th>
                                                                                            <th >tổng đơn hàng</th>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        <?php
                                                                                        $count = 0;
                                                                                        foreach($user->dsChamSoc() as $ite):
                                                                                        $count++;
                                                                                        ?>
                                                                                        <tr role="row" class="@if($count%2 != 0) odd @else even @endif">
                                                                                            <td class="text-center">{{$count}}</td>
                                                                                            <td>
                                                                                                <a target="_blank" href="{{$ite->getHref()}}">{{$ite->getTitle()}}</a>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                @if(!empty($ite->phone))
                                                                                                    <div>
                                                                                                        <a href="tel:{{$ite->phone}}">{{'ĐT: ' . $ite->phone}}</a>
                                                                                                    </div>
                                                                                                @endif
                                                                                                @if(!empty($ite->email))
                                                                                                    <div>
                                                                                                        <a href="mailto:{{$ite->email}}">{{$ite->email}}</a>
                                                                                                    </div>
                                                                                                @endif
                                                                                            </td>
                                                                                            <td>{{$ite->getFullAddress()}}</td>
                                                                                            <td class="text-center"><div class="number_format">{{$ite->tongDH()}}</div></td>
                                                                                        </tr>
                                                                                        <?php endforeach;?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="alert alert-info notfound"></div>
                                                            @endif
                                                        </div>

                                                        <div class="tab-pane dsdt" role="tabpanel">
                                                            @if(count($user->doiTacCons()))
                                                                <div class="main-card mb-3 card">
                                                                    <div class="card-body">
                                                                        <div class="dataTables_wrapper dt-bootstrap4">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <table id="exp2" style="width: 100%;" class="table kero_tables table-hover table-striped table-bordered dataTable dtr-inline" role="grid">
                                                                                        <thead>
                                                                                        <tr role="row">
                                                                                            <th >STT</th>
                                                                                            <th >Họ Tên</th>
                                                                                            <th >Liên Hệ</th>
                                                                                            <th >Địa Chỉ</th>
                                                                                            <th >tổng đơn hàng</th>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                        <?php
                                                                                        $count = 0;
                                                                                        foreach($user->doiTacCons() as $ite):
                                                                                        $count++;
                                                                                        ?>
                                                                                        <tr role="row" class="@if($count%2 != 0) odd @else even @endif">
                                                                                            <td class="text-center">{{$count}}</td>
                                                                                            <td>
                                                                                                <a target="_blank" href="{{$ite->getHref()}}">{{$ite->getTitle()}}</a>
                                                                                            </td>
                                                                                            <td class="text-center">
                                                                                                @if(!empty($ite->phone))
                                                                                                    <div>
                                                                                                        <a href="tel:{{$ite->phone}}">{{'ĐT: ' . $ite->phone}}</a>
                                                                                                    </div>
                                                                                                @endif
                                                                                                @if(!empty($ite->email))
                                                                                                    <div>
                                                                                                        <a href="mailto:{{$ite->email}}">{{$ite->email}}</a>
                                                                                                    </div>
                                                                                                @endif
                                                                                            </td>
                                                                                            <td>{{$ite->getFullAddress()}}</td>
                                                                                            <td class="text-center"><div class="number_format">{{$ite->tongDH()}}</div></td>
                                                                                        </tr>
                                                                                        <?php endforeach;?>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="alert alert-info notfound"></div>
                                                            @endif
                                                        </div>

                                                        <div class="tab-pane thht" role="tabpanel" id="body_thht">
                                                            <div class="main-card mb-3 card">
                                                                <div class="card-body">
                                                                    <div class="search_body">
                                                                        <input type="hidden" name="item_id" value="{{$user->id}}" />

                                                                        <div class="row">
                                                                            <div class="col-md-2 mb-2 text-center">
                                                                                <div class="form-group">
                                                                                    <label class="frm-label">tháng</label>
                                                                                    <div>
                                                                                        <select class="form-control" name="month" onchange="jskhsearchhht()">
                                                                                            @for($i=1;$i<=12;$i++)
                                                                                                <option @if($i == (int)date('m')) selected="selected" @endif value="{{$i}}">{{'Tháng ' . $i}}</option>
                                                                                            @endfor
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-2 mb-2 text-center">
                                                                                <div class="form-group">
                                                                                    <label class="frm-label">năm</label>
                                                                                    <div>
                                                                                        <select class="form-control" name="year" onchange="jskhsearchhht()">
                                                                                            @for($i=2020;$i<=(int)date('Y');$i++)
                                                                                                <option @if($i == (int)date('Y')) selected="selected" @endif value="{{$i}}">{{'Năm ' . $i}}</option>
                                                                                            @endfor
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-8 mb-4">
                                                                                <table class="table kero_tables table-hover table-striped table-bordered dataTable dtr-inline">
                                                                                    <tr>
                                                                                        <td class="text-uppercase text-center font-weight-bold">bảng tính</td>
                                                                                        <td class="text-uppercase text-center font-weight-bold">tạm tính</td>
                                                                                        <td class="text-uppercase text-center font-weight-bold">thực tế</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="text-uppercase">(1) hoa hồng</td>
                                                                                        <td class="text-right"><div class="number_format hh_tam_tinh">{{$commission->hht_tam_tinh}}</div></td>
                                                                                        <td class="text-right"><div class="number_format hh_thuc_te">{{$commission->hht_thuc_te}}</div></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="text-uppercase">(2) tổng doanh số NET bán trực tiếp</td>
                                                                                        <td class="text-right"><div class="number_format tds_net_tam_tinh">{{$commission->ds_net_tam_tinh}}</div></td>
                                                                                        <td class="text-right"><div class="number_format tds_net_thuc_te">{{$commission->ds_net_thuc_te}}</div></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="text-uppercase">(3) thưởng doanh số</td>
                                                                                        <td class="text-right"><div class="number_format tds_tam_tinh">{{$commission->tds_tam_tinh}}</div></td>
                                                                                        <td class="text-right"><div class="number_format tds_thuc_te">{{$commission->tds_thuc_te}}</div></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td class="text-uppercase font-weight-bold">tổng lãnh (1) + (3)</td>
                                                                                        <td class="text-right"><div class="number_format tc_tam_tinh font-weight-bold">{{$commission->tc_tam_tinh}}</div></td>
                                                                                        <td class="text-right"><div class="number_format tc_thuc_te font-weight-bold">{{$commission->tc_thuc_te}}</div></td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="dataTables_wrapper dt-bootstrap4">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <table id="exp3" style="width: 100%;" class="table kero_tables table-hover table-striped table-bordered dataTable dtr-inline" role="grid">
                                                                                    <thead>
                                                                                    <tr role="row">
                                                                                        <th >STT</th>
                                                                                        <th >thời gian</th>
                                                                                        <th >đơn hàng</th>
                                                                                        <th >tổng tiền hàng</th>
                                                                                        <th >tỉ lệ %</th>
                                                                                        <th >hoa hồng</th>
                                                                                        <th >loại</th>
                                                                                        <th >trạng thái</th>
                                                                                        <th >người mua</th>
                                                                                    </thead>
                                                                                    <tbody class="hh_body">

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane tab_dhang" role="tabpanel">
                                            @if (count($carts))
                                                <div class="row">
                                                    <div class="col-md-12 mb-4">
                                                        <div class="clearfix">
                                                            <span class="mr-4">
                                                                <span class="frm-label">Tổng tiền hàng: </span>
                                                                <b class="number_format text-warning font-weight-bold">{{$totalCart}}</b>
                                                                <span class="currency_format text-warning font-weight-bold">₫</span>
                                                            </span>
                                                            <span class="mr-4">
                                                                <span class="frm-label">Tổng Giảm Giá: </span>
                                                                <b class="number_format text-danger font-weight-bold">{{$totalDiscount}}</b>
                                                                <span class="currency_format text-danger font-weight-bold">₫</span>
                                                            </span>
                                                            <span>
                                                                <span class="frm-label">Tổng thanh toán: </span>
                                                                <b class="number_format text-success font-weight-bold">{{$totalPrice}}</b>
                                                                <span class="currency_format text-success font-weight-bold">₫</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table kero_tables table-hover table-striped table-bordered dataTable dtr-inline">
                                                            <thead>
                                                            <tr>
                                                                <th class="frm-label">stt</th>
                                                                <th class="frm-label">thời gian đặt</th>
                                                                <th class="frm-label">mã đơn hàng</th>
                                                                <th class="align-center frm-label">số lượng SP</th>
                                                                <th class="align-center frm-label">tổng tiền hàng</th>
                                                                <th class="align-center frm-label">giảm giá</th>
                                                                <th class="align-center frm-label">phí giao hàng</th>
                                                                <th class="align-center frm-label">tổng thanh toán</th>
                                                                <th class="align-center frm-label">phương thức</th>
                                                                <th class="align-center frm-label">trạng thái</th>
                                                                <th class="align-center"></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $stt = 0;
                                                            foreach($carts as $cart):
                                                            $stt++;
                                                            ?>
                                                            <tr>
                                                                <td class="text-center">{{$stt}}</td>
                                                                <td class="long_td_sm">{{$apiCore->timeToString($cart->created_at)}}</td>
                                                                <td class="long_td_mid"><?php echo $cart->toHTML(['href' => true]);?></td>
                                                                <td class="align-center long_td_sm">{{$cart->total_quantity}}</td>
                                                                <td class="align-center font-weight-bold text-primary long_td_mid">
                                                                    <span class="number_format">{{$cart->total_cart}}</span>
                                                                    <span class="currency_format">₫</span>
                                                                </td>
                                                                <td class="align-center font-weight-bold long_td_mid">
                                                                    @if ($cart->total_discount > 0)
                                                                        <span class="number_format">{{$cart->total_discount}}</span>
                                                                        <span class="currency_format">₫</span>
                                                                    @endif
                                                                </td>
                                                                <td class="align-center long_td_mid ">
                                                                    <div class="font-weight-bold @if($cart->free_ship) line_through @endif">
                                                                        <span class="number_format">{{$cart->total_ship ? $cart->total_ship : 0}}</span>
                                                                        <span class="currency_format">₫</span>
                                                                    </div>
                                                                    @if($cart->free_ship)
                                                                        <div>
                                                                            <div class="badge badge-danger font-weight-bold text-uppercase">miễn phí ship</div>
                                                                        </div>
                                                                    @endif

                                                                </td>
                                                                <td class="align-center text-danger font-weight-bold long_td_mid">
                                                                    <span class="number_format">{{$cart->total_price}}</span>
                                                                    <span class="currency_format">₫</span>
                                                                </td>
                                                                <td class="align-center frm-label long_td_mid">{{$cart->getPaymentText()}}</td>
                                                                <td class="align-center frm-label long_td_mid text-success">{{$cart->getGhnStatus()}}</td>
                                                                <td class="long_td_mid text-center">
                                                                    <div class="align-center">
                                                                        <button class="btn btn-warning btn-sm mb-1 text-uppercase"
                                                                                onclick="gotoPage('{{url('/dh/pdf/' . $cart->id)}}')"
                                                                        >
                                                                            <i class="fa fa-file-pdf"></i>
                                                                        </button>
                                                                        <button class="btn btn-success btn-sm mb-1 text-uppercase"
                                                                                onclick="gotoPage('{{url('/dh/excel/' . $cart->id)}}')"
                                                                        >
                                                                            <i class="fa fa-file-excel"></i>
                                                                        </button>
                                                                        <button class="btn btn-primary btn-sm mb-1 text-uppercase"
                                                                                onclick="openPage('{{$cart->getHref(true)}}')"
                                                                        >
                                                                            <i class="fa fa-eye"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach;?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-info notfound"></div>
                                            @endif
                                        </div>

                                        <div class="tab-pane tab_spxem" role="tabpanel">
                                            @if (count($views))
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table kero_tables table-hover table-striped table-bordered dataTable dtr-inline">
                                                        <thead>
                                                        <tr>
                                                            <th class="frm-label">stt</th>
                                                            <th class="frm-label">sản phẩm</th>
                                                            <th class="frm-label">giá</th>
                                                            <th class="align-center"></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $stt = 0;
                                                        foreach($views as $item):
                                                        $stt++;

                                                        $product = \App\Model\Product::find((int)$item->product_id);
                                                        ?>
                                                        <tr>
                                                            <td class="text-center">{{$stt}}</td>
                                                            <td class="long_td_mid"><?php echo $product->toHTML(['href' => true]);?></td>
                                                            <td class="align-center font-weight-bold text-primary long_td_mid">
                                                                <span class="number_format">{{$product->price_pay}}</span>
                                                                <span class="currency_format">₫</span>
                                                            </td>
                                                            <td class="long_td_mid text-center">
                                                                <div class="align-center">
                                                                    <button class="btn btn-primary btn-sm mb-1 text-uppercase"
                                                                            onclick="openPage('{{$product->getHref(true)}}')"
                                                                    >
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            @else
                                                <div class="alert alert-info notfound"></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.all')

    <script src="{{url('public/libraries/upload/jquery.ui.widget.js')}}" type="text/javascript"></script>
    <script src="{{url('public/libraries/upload/jquery.iframe-transport.js')}}" type="text/javascript"></script>
    <script src="{{url('public/libraries/upload/jquery.fileupload.js')}}" type="text/javascript"></script>

    <script type="text/javascript" src="{{url('public/js/be/user_info.js')}}"></script>

@stop
