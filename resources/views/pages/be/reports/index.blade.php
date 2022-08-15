<?php
$pageTitle = (isset($page_title)) ? $page_title : "";

$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();
?>

@extends('templates.be.master')

@section('content')

    <style type="text/css">
        .frm-search .form-group > div {
            float: left;
            margin-bottom: 20px;
        }

        #search_body a.c-href-item {
            position: relative;
            top: 10px;
        }
    </style>

    <div>
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-menu">
                        <button class="btn btn-success btn-sm mb-1" type="button" onclick="exportBaoCao('tong_hop')" >
                            <i class="fa fa-file-excel mr-1"></i>
                            báo cáo tổng hợp
                        </button>

                        <button class="btn btn-success btn-sm mb-1" type="button" onclick="exportBaoCao('chi_tiet_doi_tac')" >
                            <i class="fa fa-file-excel mr-1"></i>
                            báo cáo chi tiết đối tác tháng
                        </button>

                        <button class="btn btn-success btn-sm mb-1" type="button" onclick="exportBaoCao('hoa_hong')" >
                            <i class="fa fa-file-excel mr-1"></i>
                            báo cáo hoa hồng tháng
                        </button>

{{--                        <button class="btn btn-success btn-sm mb-1" type="button" onclick="exportBaoCao('doanh_thu_khach_hang')" >--}}
{{--                            <i class="fa fa-file-excel mr-1"></i>--}}
{{--                            báo cáo doanh thu khách hàng tháng--}}
{{--                        </button>--}}
                    </div>

                    <div class="frm-search">
                        <form action="" method="get" id="frm-search">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-1 text-center">
                                            <div class="form-group">
                                                <select class="form-control" name="month">
                                                    @for($i=1;$i<=12;$i++)
                                                        <option @if($i == (int)date('m')) selected="selected" @endif value="{{$i}}">{{'Tháng ' . $i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mb-1 text-center">
                                            <div class="form-group">
                                                <select class="form-control" name="year">
                                                    @for($i=2020;$i<=(int)date('Y');$i++)
                                                        <option @if($i == (int)date('Y')) selected="selected" @endif value="{{$i}}">{{'Năm ' . $i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mb-1 text-center">
                                            <div class="form-group">
                                                <select class="form-control" name="user_id">
                                                    <option value="">Tất Cả Đối Tác</option>
                                                    @foreach($dts as $ite)
                                                        <option value="{{$ite->id}}">{{$ite->ref_code . ' - ' . $ite->getTitle()}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mb-1 text-center">
                                            <button type="button" class="btn btn-primary btn-sm mb-1" onclick="baoCaoTimKiem();">
                                                <i class="fa fa-search fs-14 mr-1"></i>
                                                Tìm
                                            </button>

                                            <input value="{{(int)date('Y')}}" name="cur_year" type="hidden" />
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card">
                        <div class="card-body card-block">
                            <div class="card kero-tab" id="kero-1">
                                <div class="card-header">
                                    <ul class="nav nav-justified">
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('tab_thop')" class="nav-link tab_thop active">
                                                <span>báo cáo tổng hợp</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('tab_ctiet')" class="nav-link tab_ctiet">
                                                <span>báo cáo chi tiết đối tác tháng</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('tab_hhong')" class="nav-link tab_hhong">
                                                <span>báo cáo hoa hồng tháng</span>
                                            </a>
                                        </li>
{{--                                        <li class="nav-item">--}}
{{--                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('tab_dthu')" class="nav-link tab_dthu">--}}
{{--                                                <span>báo cáo doanh thu khách hàng tháng</span>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <div class="tab-content" id="search_body">
                                        <div class="tab-pane tab_thop active" role="tabpanel">

                                        </div>
                                        <div class="tab-pane tab_ctiet" role="tabpanel">

                                        </div>
                                        <div class="tab-pane tab_hhong" role="tabpanel">

                                        </div>
{{--                                        <div class="tab-pane tab_dthu" role="tabpanel">--}}

{{--                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{url('public/js/be/reports.js')}}"></script>
@stop
