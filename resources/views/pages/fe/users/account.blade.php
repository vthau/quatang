<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$apiFE = new \App\Api\FE;
$provinces = $apiFE->getProvinces();
$districts = $apiFE->getDistrictsByProvinceId($viewer->province_id);
$wards = $apiFE->getWardsByDistrictId($viewer->district_id);

$code = base64_encode('c=' . $viewer->id);
$URL1 = url('gt?ref=' . $code);
$URL2 = url('gt?ref=' . $code . '&to=sp');
$URL3 = url('gt?ref=' . $code . '&dk=dt');

$validCount = 0;

$commission = $viewer->createCommission((int)date('m'), (int)date('Y'));
?>

@extends('templates.ttv.master')

@section('content')
    <style type="text/css">
        input#code_url_1,
        input#code_url_2,
        input#code_url_3 {
            width: 250px;
            padding-right: 88px;
        }

        button.code_url {
            margin-left: -50px;
            width: 88px;
            background: #4e97fd;
            color: white;
            border: 0;
            -webkit-appearance: none;
        }

        .policy-wrapper .content {
            max-height: 400px;
            overflow: scroll;
        }

        .policy-wrapper,
        #frm-hop_tac .card {
            margin: 15px auto;
            max-width: 600px;
            border: 1px solid #aaa;
            border-radius: 3px;
            padding: 20px;
        }

        .policy-wrapper .h-title,
        #frm-hop_tac .card .card-header {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }
    </style>

    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop')
        </div>
    </div>

    <div class="container mb__100">
        @include('modals.breadcrumb', [
           'text1' => $apiCore->getSetting('text_tk_title'),
        ])

        <div class="row">
            <div class="col-md-12 kero-tab" id="kero-1">
                <div class="mb-3 card">
                    <div class="card-header">
                        <ul class="nav nav-justified">
                            <li class="nav-item">
                                <a data-toggle="tab" href="javascript:void(0)" onclick="jsbindtab1('ttcn')" class="nav-link ttcn active">
                                    <span>{{$apiCore->getSetting('text_tk_ttcn')}}</span>
                                </a>
                            </li>

                            @if ($viewer->doiTacHopLe())
                            <li class="nav-item">
                                <a data-toggle="tab" href="javascript:void(0)" onclick="jsbindtab1('ttdt')" class="nav-link ttdt">
                                    <span>{{$apiCore->getSetting('text_tk_mdt')}}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a data-toggle="tab" href="javascript:void(0)" onclick="jsbindtab1('tkhh')" class="nav-link tkhh">
                                    <span>{{$apiCore->getSetting('text_tk_tkhh')}}</span>
                                </a>
                            </li>
                            @endif

                            @if (!$viewer->doiTacHopLe() || ($viewer->doiTacHopLe() && count($viewer->getMyTests())))
                            <li class="nav-item">
                                <a data-toggle="tab" href="javascript:void(0)" onclick="jsbindtab1('dtkd')" class="nav-link dtkd">
                                    <span>{{$apiCore->getSetting('text_tk_ttdt')}}</span>
                                </a>
                            </li>
                            @endif

                            <li class="nav-item">
                                <a data-toggle="tab" href="javascript:void(0)" onclick="jsbindtab1('dhdd')" class="nav-link dhdd">
                                    <span>{{$apiCore->getSetting('text_tk_dhdd')}}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a data-toggle="tab" href="javascript:void(0)" onclick="jsbindtab1('spdx')" class="nav-link spdx">
                                    <span>{{$apiCore->getSetting('text_tk_spdx')}}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body mt__20 mb__20">
                        <div class="tab-content">
                            <div class="tab-pane ttcn active" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div id="frm-account">
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label class="frm-label">Họ Tên</label>
                                                </div>
                                                <div class="col-md-9" id="frm-name">
                                                    <button onclick="jskhinfoedit('name')" type="button" class="button button_edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>

                                                    <button onclick="jskhinfono('name')" type="button" class="button button_hide">
                                                        <i class="fas fa-times"></i>
                                                    </button>

                                                    <button onclick="jskhinfook('name')" type="button" class="button button_primary button_save">
                                                        <i class="fas fa-check"></i>
                                                    </button>

                                                    <div class="txt">{{$viewer->name}}</div>

                                                    <div class="input">
                                                        <input id="input-name" value="{{$viewer->name}}" class="form-control text-center" type="text" autocomplete="off" />
                                                        <div class="alert alert-danger hidden">Vui lòng nhập họ tên.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label class="frm-label">Email</label>
                                                </div>
                                                <div class="col-md-9" id="frm-email">
                                                    <button onclick="jskhinfoedit('email')" type="button" class="button button_edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>

                                                    <button onclick="jskhinfono('email')" type="button" class="button button_hide">
                                                        <i class="fas fa-times"></i>
                                                    </button>

                                                    <button onclick="jskhinfook('email')" type="button" class="button button_primary button_save">
                                                        <i class="fas fa-check"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-danger hidden">
                                                        <i class="fas fa-spinner"></i>
                                                    </button>

                                                    <div class="txt">{{$viewer->email}}</div>

                                                    <div class="input">
                                                        <input id="input-email" value="{{$viewer->email}}" class="form-control text-center" type="email" autocomplete="off" />
                                                        <div class="alert alert-danger hidden">Vui lòng nhập email hợp lệ.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label class="frm-label">Mật khẩu</label>
                                                </div>
                                                <div class="col-md-9" id="frm-password">
                                                    <button onclick="jskhinfoedit('password')" type="button" class="button button_edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>

                                                    <button onclick="jskhinfono('password')" type="button" class="button button_hide">
                                                        <i class="fas fa-times"></i>
                                                    </button>

                                                    <button onclick="jskhinfook('password')" type="button" class="button button_primary button_save">
                                                        <i class="fas fa-check"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-danger hidden">
                                                        <i class="fas fa-spinner"></i>
                                                    </button>

                                                    <div class="txt">********</div>

                                                    <div class="input">
                                                        <input id="input-password" class="form-control text-center" type="password" autocomplete="off" placeholder="hãy nhập mật khẩu mới..." />
                                                        <div class="alert alert-danger hidden">Vui lòng nhập mật khẩu.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label class="frm-label">Điện Thoại</label>
                                                </div>
                                                <div class="col-md-9" id="frm-phone">
                                                    <button onclick="jskhinfoedit('phone')" type="button" class="button button_edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>

                                                    <button onclick="jskhinfono('phone')" type="button" class="button button_hide">
                                                        <i class="fas fa-times"></i>
                                                    </button>

                                                    <button onclick="jskhinfook('phone')" type="button" class="button button_primary button_save">
                                                        <i class="fas fa-check"></i>
                                                    </button>

                                                    <div class="txt">{{$viewer->phone}}</div>

                                                    <div class="input">
                                                        <input id="input-phone" value="{{$viewer->phone}}" class="form-control text-center" type="number" autocomplete="off" />
                                                        <div class="alert alert-danger hidden">Vui lòng nhập điện thoại.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label class="frm-label">Địa Chỉ</label>
                                                </div>
                                                <div class="col-md-9" id="frm-dia_chi_nha">
                                                    <button onclick="jskhinfoedit('dia_chi_nha')" type="button" class="button button_edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>

                                                    <button onclick="jskhinfono('dia_chi_nha')" type="button" class="button button_hide">
                                                        <i class="fas fa-times"></i>
                                                    </button>

                                                    <button onclick="jskhinfook('dia_chi_nha')" type="button" class="button button_primary button_save">
                                                        <i class="fas fa-check"></i>
                                                    </button>

                                                    <div class="txt">{{$viewer->getFullAddress()}}</div>

                                                    <div class="input" id="frm-address">
                                                        <input id="input-dia_chi_nha" value="{{$viewer->address}}" class="form-control text-center" type="text" autocomplete="off" />

                                                        <select name="province_id" class="form-control select-css mt__10" onchange="jscartaddressopts(this, 'district')">
                                                            <option value="">Hãy chọn tỉnh / thành</option>
                                                            @foreach($provinces as $ite)
                                                                <option @if($viewer->province_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                            @endforeach
                                                        </select>

                                                        <select name="district_id" class="form-control select-css mt__10" onchange="jscartaddressopts(this, 'ward')">
                                                            <option value="">Hãy chọn quận / huyện</option>
                                                            @if (count($districts))
                                                                @foreach($districts as $ite)
                                                                    <option @if($viewer->district_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>

                                                        <select name="ward_id" class="form-control select-css mt__10">
                                                            <option value="">Hãy chọn phường / xã</option>
                                                            @if (count($wards))
                                                                @foreach($wards as $ite)
                                                                    <option @if($viewer->ward_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($viewer->hop_tac)

                                                <div class="form-group row">
                                                    <div class="col-md-3">
                                                        <label class="frm-label">chứng chỉ hành nghề</label>
                                                    </div>
                                                    <div class="col-md-9" id="frm-chung_chi_hanh_nghe">
                                                        <button onclick="jskhinfoedit('chung_chi_hanh_nghe')" type="button" class="button button_edit">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>

                                                        <button onclick="jskhinfono('chung_chi_hanh_nghe')" type="button" class="button button_hide">
                                                            <i class="fas fa-times"></i>
                                                        </button>

                                                        <button onclick="jskhinfook('chung_chi_hanh_nghe')" type="button" class="button button_primary button_save">
                                                            <i class="fas fa-check"></i>
                                                        </button>

                                                        <div class="txt">{{$viewer->chung_chi_hanh_nghe}}</div>

                                                        <div class="input">
                                                            <input id="input-chung_chi_hanh_nghe" value="{{$viewer->chung_chi_hanh_nghe}}" class="form-control text-center" type="text" autocomplete="off" />
                                                            <div class="alert alert-danger hidden">Vui lòng nhập chứng chỉ hành nghề.</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-md-3">
                                                        <label class="frm-label">thông tin chuyển khoản</label>
                                                    </div>
                                                    <div class="col-md-9" id="frm-thong_tin_chuyen_khoan">
                                                        <button onclick="jskhinfoedit('thong_tin_chuyen_khoan')" type="button" class="button button_edit">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>

                                                        <button onclick="jskhinfono('thong_tin_chuyen_khoan')" type="button" class="button button_hide">
                                                            <i class="fas fa-times"></i>
                                                        </button>

                                                        <button onclick="jskhinfook('thong_tin_chuyen_khoan')" type="button" class="button button_primary button_save">
                                                            <i class="fas fa-check"></i>
                                                        </button>

                                                        <div class="txt pre_wrap"><?php echo $viewer->thong_tin_chuyen_khoan;?></div>

                                                        <div class="input">
                                                            <textarea id="input-thong_tin_chuyen_khoan" class="form-control min_height_100px" >{{$viewer->thong_tin_chuyen_khoan}}</textarea>
{{--                                                            <input id="input-thong_tin_chuyen_khoan" value="{{$viewer->thong_tin_chuyen_khoan}}" class="form-control text-center" type="text" autocomplete="off" />--}}
{{--                                                            <div class="alert alert-danger hidden">Vui lòng nhập họ tên.</div>--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane ttdt" role="tabpanel">
                                @if ($hopTac)
                                    <div class="alert alert-success mb__10">
                                        <i class="fa fa-check text-success mr__5"></i> Chúc mừng bạn đã đăng ký thành công!
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6 mb__20">
                                        <div class="row">
                                            <div class="col-md-4 mb__20">
                                                <label class="frm-label">{{$apiCore->getSetting('text_tk_qrcode')}}</label>
                                            </div>
                                            <div class="col-md-8 mb__20">
                                                <div class="clearfix qr-wrapper">
                                                    {!! \QrCode::size(200)->generate($URL2); !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb__20">
                                        <div class="row">
                                            <div class="col-md-4 mb__20">
                                                <label class="frm-label">{{$apiCore->getSetting('text_tk_link_sp')}}</label>
                                            </div>
                                            <div class="col-md-8 mb__20">
                                                <input type="text" value="{{$URL2}}" id="code_url_2" readonly />
                                                <button class="button text-uppercase code_url" onclick="jskhcopyurl(2)">
                                                    copy
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb__20">
                                        <div class="row">
                                            <div class="col-md-4 mb__20">
                                                <label class="frm-label">{{$apiCore->getSetting('text_tk_link_dk')}}</label>
                                            </div>
                                            <div class="col-md-8 mb__20">
                                                <input type="text" value="{{$URL1}}" id="code_url_1" readonly />
                                                <button class="button text-uppercase code_url" onclick="jskhcopyurl(1)">
                                                    copy
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb__20">
                                        <div class="row">
                                            <div class="col-md-4 mb__20">
                                                <label class="frm-label">{{$apiCore->getSetting('text_tk_dtcode')}}</label>
                                            </div>
                                            <div class="col-md-8 mb__20">
                                                <div class="text-bold text-success text-uppercase">{{$viewer->ref_code}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tkhh" role="tabpanel">
                                @if(!empty($apiCore->getSetting('text_tk_tkhh_text')))
                                <div class="row">
                                    <div class="col-md-12 mb__20">
                                        <label class="frm-label text-uppercase">Ghi chú</label>
                                        <div>
                                            <?php echo nl2br($apiCore->getSetting('text_tk_tkhh_text'));?>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card mb__20 card-bordered">
                                            <div class="card-body">
                                                <div class="kero-tab" id="kero-2">
                                                    <div class="card-header">
                                                        <ul class="nav nav-justified">
                                                            <li class="nav-item">
                                                                <a data-toggle="tab" href="javascript:void(0)" onclick="jsbindtab2('dskh')" class="nav-link dskh active">
                                                                    <span>{{$apiCore->getSetting('text_tk_ds_kh_care') . ' (' . count($viewer->dsChamSoc()) . ')'}}</span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a data-toggle="tab" href="javascript:void(0)" onclick="jsbindtab2('dsdt')" class="nav-link dsdt">
                                                                    <span>{{$apiCore->getSetting('text_tk_ds_dt_care') . ' (' . count($viewer->doiTacCons()) . ')'}}</span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a data-toggle="tab" href="javascript:void(0)" onclick="jsbindtab2('thht')" class="nav-link thht">
                                                                    <span>{{$apiCore->getSetting('text_tk_hoa_hong_thang_title')}}</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="tab-content">
                                                            <div class="tab-pane dskh active" role="tabpanel">
                                                                @if(count($viewer->dsChamSoc()))
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
                                                                                        foreach($viewer->dsChamSoc() as $ite):
                                                                                        $count++;
                                                                                        ?>
                                                                                        <tr role="row" class="@if($count%2 != 0) odd @else even @endif">
                                                                                            <td class="text-center">{{$count}}</td>
                                                                                            <td>{{$ite->getTitle()}}</td>
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
                                                                @if(count($viewer->doiTacCons()))
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
                                                                                            foreach($viewer->doiTacCons() as $ite):
                                                                                            $count++;
                                                                                            ?>
                                                                                            <tr role="row" class="@if($count%2 != 0) odd @else even @endif">
                                                                                                <td class="text-center">{{$count}}</td>
                                                                                                <td>{{$ite->getTitle()}}</td>
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
                                                                            <div class="row">
                                                                                <div class="col-md-2 mb__5 text-center">
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
                                                                                <div class="col-md-2 mb__5 text-center">
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
                                                                                <div class="col-md-8 mb__20">
                                                                                    <table class="table kero_tables table-hover table-striped table-bordered dataTable dtr-inline">
                                                                                        <tr>
                                                                                            <td class="text-uppercase text-center text-bold">bảng tính</td>
                                                                                            <td class="text-uppercase text-center text-bold">tạm tính</td>
                                                                                            <td class="text-uppercase text-center text-bold">thực tế</td>
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
                                                                                            <td class="text-uppercase text-bold">tổng lãnh (1) + (3)</td>
                                                                                            <td class="text-right"><div class="number_format tc_tam_tinh text-bold">{{$commission->tc_tam_tinh}}</div></td>
                                                                                            <td class="text-right"><div class="number_format tc_thuc_te text-bold">{{$commission->tc_thuc_te}}</div></td>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane dhdd" role="tabpanel">
                                @if (count($carts))
                                <div class="row">
                                    <div class="col-md-12 mb__20">
                                        <div class="clearfix">
                                            <span class="mr__10">
                                                <span class="frm-label">Tổng tiền hàng: </span>
                                                <b class="number_format text-warning text-bold">{{$totalCart}}</b>
                                                <span class="currency_format text-warning text-bold">₫</span>
                                            </span>
                                            <span class="mr__10">
                                                <span class="frm-label">Tổng Giảm Giá: </span>
                                                <b class="number_format text-danger text-bold">{{$totalDiscount}}</b>
                                                <span class="currency_format text-danger text-bold">₫</span>
                                            </span>
                                            <span>
                                                <span class="frm-label">Tổng thanh toán: </span>
                                                <b class="number_format text-success text-bold">{{$totalPrice}}</b>
                                                <span class="currency_format text-success text-bold">₫</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="cart-table width_full">
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
                                                <td class="align-center text-bold text-primary long_td_mid">
                                                    <span class="number_format">{{$cart->total_cart}}</span>
                                                    <span class="currency_format">₫</span>
                                                </td>
                                                <td class="align-center text-bold long_td_mid">
                                                    @if ($cart->total_discount > 0)
                                                    <span class="number_format">{{$cart->total_discount}}</span>
                                                    <span class="currency_format">₫</span>
                                                    @endif
                                                </td>
                                                <td class="align-center long_td_mid ">
                                                    <div class="text-bold @if($cart->free_ship) line_through @endif">
                                                        <span class="number_format">{{$cart->total_ship ? $cart->total_ship : 0}}</span>
                                                        <span class="currency_format">₫</span>
                                                    </div>
                                                    @if($cart->free_ship)
                                                    <div>
                                                        <div class="badge badge-danger text-bold text-uppercase">miễn phí ship</div>
                                                    </div>
                                                    @endif

                                                </td>
                                                <td class="align-center text-danger text-bold long_td_mid">
                                                    <span class="number_format">{{$cart->total_price}}</span>
                                                    <span class="currency_format">₫</span>
                                                </td>
                                                <td class="align-center frm-label long_td_mid">{{$cart->getPaymentText()}}</td>
                                                <td class="align-center frm-label long_td_mid text-success">{{$cart->getGhnStatus()}}</td>
                                                <td class="long_td_mid text-center">
                                                    <div class="align-center">
                                                        <button class="button mb-1 text-uppercase"
                                                                onclick="gotoPage('{{url('/dh/pdf/' . $cart->id)}}')"
                                                        >
                                                            <i class="fa fa-file-pdf"></i>
                                                        </button>
                                                        <button class="button mb-1 text-uppercase"
                                                                onclick="gotoPage('{{url('/dh/excel/' . $cart->id)}}')"
                                                        >
                                                            <i class="fa fa-file-excel"></i>
                                                        </button>
                                                        <button class="button mb-1 text-uppercase"
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
                            <div class="tab-pane spdx" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if (count($views))
                                            <div class="container container_cat pop_default cat_default mb__60">
                                                <div class="cat_toolbar row fl_center al_center mt__30">
                                                    <div class="cat_view col-auto hidden">
                                                        <div class="dn dev_desktop dev_view_cat">
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="dk" data-col="6"
                                                               class="pr mr__10 cat_view_page view_6"></a>
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="dk" data-col="4"
                                                               class="pr mr__10 cat_view_page view_4"></a>
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="dk" data-col="3"
                                                               class="pr mr__10 cat_view_page view_3"></a><a rel="nofollow" data-no-instant="" href="#"
                                                                                                             data-dev="dk" data-col="15"
                                                                                                             class="pr mr__10 cat_view_page view_15"></a><a
                                                                rel="nofollow" data-no-instant="" href="#" data-dev="dk" data-col="2"
                                                                class="pr cat_view_page view_2"></a></div>
                                                        <div class="dn dev_tablet dev_view_cat">
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="tb" data-col="6"
                                                               class="pr mr__10 cat_view_page view_6"></a>
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="tb" data-col="4"
                                                               class="pr mr__10 cat_view_page view_4"></a>
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="tb" data-col="3"
                                                               class="pr cat_view_page view_3"></a>
                                                        </div>
                                                        <div class="flex dev_mobile dev_view_cat">
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="mb" data-col="12"
                                                               class="pr mr__10 cat_view_page view_12"></a>
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="mb" data-col="6"
                                                               class="pr cat_view_page view_6"></a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-12 col-12">
                                                        <div id="shopify-section-collection_page" class="shopify-section tp_se_cdt">
                                                            <div class="nt_svg_loader dn"></div>
                                                            <div class="products nt_products_holder row fl_center row_pr_1 cdt_des_1 round_cd_false nt_cover ratio_nt position_8 space_30 nt_default">
                                                                <?php foreach ($views as $item):
                                                                $product = \App\Model\Product::find((int)$item->product_id);
                                                                ?>
                                                                <div
                                                                    class="col-lg-3 col-md-3 col-6 pr_animated done mt__30 pr_grid_item product nt_pr desgin__1">
                                                                    <div class="product-inner pr">
                                                                        <div class="product-image pr oh lazyloaded product-custom" >
                                                                            <span class="tc nt_labels pa pe_none cw"></span>
                                                                            <a class="db" href="{{$product->getHref(true)}}">
                                                                                <div class="pr_lazy_img main-img nt_img_ratio nt_bg_lz lazyloaded"
                                                                                     data-id="14246008717451"
                                                                                     data-bgset="{{$product->getAvatar()}}"
                                                                                     data-parent-fit="width" data-wiis="" data-ratio="0.7837837837837838"
                                                                                     style="padding-top: 127.586%; background-image: url('{{$product->getAvatar()}}');">
                                                                                    <picture style="display: none;">
                                                                                        <source
                                                                                            data-srcset="{{$product->getAvatar()}}"
                                                                                            sizes="270px"
                                                                                            srcset="{{$product->getAvatar()}}">
                                                                                        <img alt="" class="lazyautosizes lazyloaded" data-sizes="auto"
                                                                                             data-ratio="0.7837837837837838" sizes="270px"></picture>
                                                                                </div>
                                                                            </a>
                                                                            <div class="hover_img pa pe_none t__0 l__0 r__0 b__0 op__0">
                                                                                <div class="pr_lazy_img back-img pa nt_bg_lz lazyloaded"
                                                                                     data-id="14246008750219"
                                                                                     data-bgset="{{$product->getAvatar()}}"
                                                                                     data-parent-fit="width" data-wiis="" data-ratio="0.7837837837837838"
                                                                                     style="padding-top: 127.586%; background-image: url('{{$product->getAvatar()}}');">
                                                                                    <picture style="display: none;">
                                                                                        <source
                                                                                            data-srcset="{{$product->getAvatar()}}"
                                                                                            sizes="270px"
                                                                                            srcset="{{$product->getAvatar()}}">
                                                                                        <img alt="" class="lazyautosizes lazyloaded" data-sizes="auto"
                                                                                             data-ratio="0.7837837837837838" sizes="270px"></picture>
                                                                                </div>
                                                                            </div>
                                                                            @if($product->is_new || $product->is_best_seller)
                                                                                <div class="hot_best ts__03 pa">
                                                                                    @if($product->is_new)
                                                                                        <div class="hot_best_text is_new">mới</div>
                                                                                    @endif
                                                                                    @if($product->is_best_seller)
                                                                                        <div class="hot_best_text is_hot">bán chạy</div>
                                                                                    @endif
                                                                                </div>
                                                                            @endif

                                                                            @if ($product->price_main != $product->price_pay)
                                                                                <div class="discount_percent ts__03 pa">
                                                                                    <div class="discount_percent_text">giảm {{$product->discount}}%</div>
                                                                                </div>
                                                                            @endif
                                                                            <div class="nt_add_w ts__03 pa ">
                                                                                <div class="product-love sp-love-{{$product->id}}" onclick="jssplove(this, {{$product->id}})">
                                                                                    @if ($product->isLoved())
                                                                                        <i class="fas fa-heart active" title="Đã Yêu Thích SP"></i>
                                                                                    @else
                                                                                        <i class="fas fa-heart" title="Thêm SP Yêu Thích"></i>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="hover_button op__0 tc pa flex column ts__03">
                                                                                <a href="javascript:void(0)" data-id="4540696920203" onclick="jscartdh({{$product->id}})"
                                                                                   class="pr pr_atc cd br__40 bgw tc dib cb chp ttip_nt tooltip_top_left"
                                                                                   rel="nofollow"><span class="tt_txt text-capitalize">thêm vào giỏ</span><i
                                                                                        class="iccl iccl-cart"></i><span class="text-capitalize">thêm vào giỏ</span>
                                                                                </a>
                                                                            </div>

                                                                        </div>
                                                                        <div class="product-info mt__15">
                                                                            <h3 class="product-title pr fs__14 mg__0 fwm"><a
                                                                                    class="cd chp" href="{{$product->getHref(true)}}">{{$product->getTitle()}}</a></h3>
                                                                            <span class="price dib mb__5">
                                                                                @if ($product->price_main != $product->price_pay)
                                                                                    <del class="price_old">
                                                                                    <span class="number_format">{{$product->price_main}}</span>
                                                                                    <span class="currency_format">₫</span>
                                                                                </del>
                                                                                @endif
                                                                                <ins>
                                                                                    <span class="number_format">{{$product->price_pay}}</span>
                                                                                    <span class="currency_format">₫</span>
                                                                                </ins>

                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php endforeach;?>
                                                            </div>
                                                            <style>
                                                                .dev_view_cat.dev_desktop a.view_3 {
                                                                    border-color: #222
                                                                }

                                                                .dev_view_cat.dev_desktop a.view_3:before {
                                                                    background: #222;
                                                                    box-shadow: 13px 0 0 #222, 26px 0 0 #222, 39px 0 0 #222
                                                                }

                                                                .dev_view_cat.dev_tablet a.view_3 {
                                                                    border-color: #222
                                                                }

                                                                .dev_view_cat.dev_tablet a.view_3:before {
                                                                    background: #222;
                                                                    box-shadow: 13px 0 0 #222, 26px 0 0 #222, 39px 0 0 #222
                                                                }

                                                                .dev_view_cat.dev_mobile a.view_6 {
                                                                    border-color: #222
                                                                }

                                                                .dev_view_cat.dev_mobile a.view_6:before {
                                                                    background: #222;
                                                                    box-shadow: 13px 0 0 #222, 13px 0 0 #222
                                                                }
                                                            </style>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-info notfound"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane dtkd" role="tabpanel">
                                @if ($viewer->hop_tac)
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if ($hopTac)
                                                <div class="alert alert-success mb__10">
                                                    <i class="fa fa-check text-success mr__5"></i> {{$apiCore->getSetting('text_tk_dk_hop_tac')}}
                                                </div>
                                            @endif
                                            <div class="alert alert-info mb__10">
                                                <?php echo nl2br($apiCore->getSetting('text_tk_ttdt_text'))?>
                                            </div>

                                            <table class="table table-bordered">
                                                <tbody>
                                                <tr>
                                                    <td class="frm-label text-center">chứng chỉ</td>
                                                    <td class="frm-label text-center">trạng thái</td>
                                                    <td class="frm-label text-center">kết quả bài test gần nhất</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-uppercase  vertical-align-middle">đối tác tư vấn trực tiếp</td>
                                                    <td class="text-uppercase text-center vertical-align-middle">
                                                        @if ($viewer->chung_chi_truc_tiep)
                                                            đã hoàn thành
                                                        @else
                                                            @if ($apiCore->canMakeTest('truc_tiep'))
                                                                <button onclick="openPage('{{url('/kh/dt/test/tt')}}')" type="button" class="button text-uppercase">làm bài test</button>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="text-uppercase text-center">
                                                        @if($viewer->getLastTest('truc_tiep'))
                                                            <span class="mr__10">KQ: <b>{{$viewer->getLastTest('truc_tiep')->total_scores . '/' . $viewer->getLastTest('truc_tiep')->total_questions}}</b> - <b class="text-uppercase text-danger">{{$viewer->getLastTest('truc_tiep')->getStatusText()}}</b></span>
                                                            <button type="button" class="button button_primary text-uppercase" onclick="openPage('{{$viewer->getLastTest('truc_tiep')->getHref()}}')">
                                                                <i class="fa fa-eye mr-1"></i> xem
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <div class="row step_1">
                                        <div class="col-md-12">
                                            <div class="policy-wrapper">
                                                <div class="text-uppercase fs-16 text-bold h-title">
                                                    chính sách và điều khoản trở thành Đối Tác
                                                </div>
                                                <div class="content">
                                                    <?php echo $apiCore->getSetting('partner_policy');?>
                                                </div>
                                                <div class="text-bold mt__30 mb__30">
                                                    <input name="agree" class="width_height_20" type="checkbox" style="position:relative; top: -3px; right: 5px;" />
                                                    Tôi đã đọc và đồng ý các <a class="text-site text-bold" href="{{url('chinh-sach-bao-mat')}}">chính sách</a> của công ty.
                                                </div>
                                                <div class="text-center">
                                                    <button onclick="jskhhoptac()" class="button button_primary text-uppercase" type="button">xác nhận</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row step_2 hidden">
                                        <div class="col-md-12">
                                            <form action="{{url('/kh/dt/dk')}}" method="post" id="frm-hop_tac">
                                                @csrf
                                                <div class="card">
                                                    <div class="card-header mb-3">
                                                        <strong class="text-uppercase">thông tin đăng kí Đối Tác</strong>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row hidden">
                                                            <div class="col-md-12" id="ele-cmnd">
                                                                <label class="frm-label required">* CMND</label>
                                                                <input  name="cmnd" type="number" class="form-control" autocomplete="off" />

                                                                <div class="alert alert-danger mt__10 hidden">Vui lòng nhập CMND hợp lệ (9-12 số).</div>
                                                            </div>
                                                        </div>
                                                        <div class="row hidden">
                                                            <div class="col-md-12">
                                                                <label class="frm-label required">* Ngày Cấp</label>
                                                                <input  name="cmnd_date" type="date" class="form-control" />
                                                            </div>
                                                        </div>
                                                        <div class="row hidden">
                                                            <div class="col-md-12" id="ele-cmnd_address">
                                                                <label class="frm-label required">* Nơi Cấp</label>
                                                                <input  name="cmnd_address" type="text" class="form-control" autocomplete="off" />

                                                                <div class="alert alert-danger mt__10 hidden">Vui lòng nhập nơi cấp CMND.</div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12" id="ele-chung_chi_hanh_nghe">
                                                                <label class="frm-label">Chứng Chỉ Hành Nghề</label>
                                                                <input  name="chung_chi_hanh_nghe" type="text" class="form-control" autocomplete="off" />

                                                                <div class="alert alert-danger mt__10 hidden">Vui lòng nhập chứng chỉ hành nghề.</div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="frm-label">Thông Tin Chuyển Khoản</label>
                                                                <textarea rows="3" cols="3" name="thong_tin_chuyen_khoan" class="form-control" autocomplete="off"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer mt__20 text-center">
                                                        <button type="submit" class="button button_primary text-uppercase">xác nhận</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.notfound').text("Không tìm thấy dữ liệu phù hợp.");

            @if (count($params) && isset($params['t']))
            jsbindtab1('{{$params['t']}}')
            @endif

            //
            jskhsearchhht();

        });

    </script>
@stop

