<?php
$pageTitle = (isset($page_title)) ? $page_title : "";

$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();
?>

@extends('templates.be.master')

@section('content')

<style type="text/css">
    #ui-view a.c-href-item {
        position: relative;
        top: 10px;
    }

    .frm-search .form-group>div {
        float: left;
        margin-bottom: 20px;
    }

    .currency_format {
        font-size: 10px;
        position: relative;
        top: -3px;
        left: 2px;
    }

    .banner_preview img {
        width: 100%;
        max-width: 300px;
        height: 100px;
        border: 1px solid;
    }

    .mobi_banner_preview img {
        width: 210px !important;
        height: 200px !important;
    }
</style>

<div>
    <div class="fade-in">

        <div class="row">
            <div class="col-md-12">
            <div class="btn-menu">
                            <button class="btn btn-primary btn-sm mb-1" onclick="openPage('{{url('admin/banner/add')}}')" >
                                <i class="fa fa-plus-circle mr-1"></i>
                                Tạo Banner
                            </button>
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if (count($banners))
                <div class="card">
                    <div class="card-header">
                        <strong>{{$pageTitle}}</strong>

                        <div class="c-header-right font-weight-bold">
                            <span>Tổng Cộng: </span>
                            <span class="number_format">{{count($banners)}}</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                            <thead>
                                <tr>
                                    <!-- <th>STT</th> -->
                                    <th>Tiêu đề</th>
                                    <th>Liên kết</th>
                                    <th>Hình ảnh</th>
                                    <th>Hình ảnh (Mobile)</th>
                                    <th>trạng thái</th>

                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($banners as $banner) :
                                ?>
                                    <tr id="item-{{$banner->id}}" data-id="{{$banner->id}}">
                                        <td>
                                            <div>{{$banner->title}}</div>
                                        </td>
                                        <td>
                                            <div>{{$banner->href}}</div>
                                        </td>

                                        <td>
                                            <div class="form-group banner_upload_preview">
                                                @if (!empty($banner->img))
                                                <div class="banner_preview">
                                                    <img src="{{url('public/' . $banner->img)}}" />
                                                </div>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group mobi_banner_upload_preview">
                                                @if (!empty($banner->img_mobi))
                                                <div class="mobi_banner_preview">
                                                    <img src="{{url('public/' . $banner->img_mobi)}}" />
                                                </div>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            <div class="mb-1">
                                                @if ($banner->display == 1)
                                                <span class="badge badge-success text-uppercase">
                                                    <i class="fa fa-check text-white mr-1"></i>Hiển thị
                                                </span>
                                                @else
                                                <span class="badge badge-warning text-white text-uppercase">
                                                    <i class="fa fa-ban text-white mr-1"></i> Ẩn
                                                </span>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            <div class="align-right">

                                                <button class="btn btn-info btn-sm mb-1" title="Sửa" data-original-title="Sửa" onclick="openPage('{{url('admin/banner/add?id=' . $banner->id)}}')">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <button class="btn btn-danger btn-sm mb-1" title="Xóa" data-original-title="Xóa" onclick="deleteItem({{$banner->id}})">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                @else
                <div class="clearfix mb-4 mt-4">
                    <span class="alert alert-info notfound"></span>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="{{url('public/js/back_end/banners.js')}}"></script>

@stop
