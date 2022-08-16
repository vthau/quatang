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

    .bg-move-admin {
        padding: 24px;
        background-color: #ffffcc;
        border: 1px dotted #ccc;
        cursor: pointer;
        margin-top: 12px;
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
<link rel="stylesheet" type="text/css" href="{{url('public/js/jquery.dataTables.min.css')}}" />
<div>
    <div class="fade-in">
        <div class="row">
            <div class="col-md-12">
                @if (count($elements))
                <div class="card">
                    <div class="card-header">
                        <strong>{{$pageTitle}}</strong>

                        <div class="c-header-right font-weight-bold">
                            <span>Tổng Cộng: </span>
                            <span class="number_format">{{count($elements)}}</span>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Thành phần</th>
                                    <th>Trạng thái</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="element-list">
                                <?php foreach ($elements as $element) :
                                ?>
                                    <tr id="{{$element->id}}" style="cursor:pointer;" data-id="{{$element->id}}">
                                        <td>
                                            <div>{{$element->title}}</div>
                                        </td>
                                        <td>
                                            <label class="c-switch c-switch-label c-switch-pill c-switch-success float-left mr-2">
                                                <input name="display" class="c-switch-input display-element" data-id="{{$element->id}}" type="checkbox" @if ($element && $element->display == 1) checked="checked" @endif /><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                            </label>
                                            <div>Hiển thị</div>
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                @if ($element->display == 1)
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


<script type="text/javascript" src="{{url('public/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{url('public/js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{url('public/js/back_end/elements.js')}}"></script>

@stop
