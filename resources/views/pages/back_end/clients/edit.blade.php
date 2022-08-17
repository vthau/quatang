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
            <form action="{{url('admin/client/update')}}" method="post" enctype="multipart/form-data"
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
                                    <div class="alert alert-info">Sửa Thông Tin Của Khách Hàng: {{$item->name}} --- Email: {{$item->email}}</div>
                                    </div>
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-md-4" id="req-category">
                                        <div class="form-group">
                                            <label class="required">* Nhóm khách hàng</label>
                                            <div class="select2-single-wrapper">
                                                <select required id="filter-category" name="user_category_id" class="form-control"></select>
                                            </div>
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy chọn nhóm khách hàng.</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Nhóm Khách Hàng Phụ</label>
                                            <select id="filter-category-others" class="form-control" name="cate_others[]" multiple="multiple"></select>
                                        </div>
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

    <script type="text/javascript" src="{{url('public/js/back_end/client_edit.js')}}"></script>


    <script type="text/javascript">
        jQuery(document).ready(function () {
            //categories
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
                    <?php if ($item && $item->user_category_id == $category->id):?>
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
                    <?php if ($item && $item->user_category_id == $sub->id):?>
                    selected: 1,
                    <?php endif;?>
                },
                    <?php if (count($childs)):?>
                    <?php foreach ($childs as $child):?>
                {
                    id: '{{$child->id}}',
                    text: "-----> {{$child->title}}",
                    level: 3,
                    <?php if ($item && $item->user_category_id == $child->id):?>
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


        });

    </script>
@stop
