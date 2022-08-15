<?php
$pageTitle = (isset($page_title)) ? $page_title : "";

$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();
?>

@extends('templates.be.master')

@section('content')
    <div>
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-menu">
                        @if ($viewer->isAllowed('staff_level_add'))
                            <button class="btn btn-primary btn-sm mb-1" onclick="addItem()">
                                <i class="fa fa-plus-circle mr-1"></i>
                                Thêm Quyền Truy Cập
                            </button>
                        @endif
                        @if ($viewer->fullPermissions())
                            <button class="btn btn-danger btn-sm mb-1" onclick="openPage('{{url('admin/level/manage')}}')">
                                <i class="fa fa-user-lock mr-1"></i>
                                Tùy Chỉnh Quyền Truy Cập
                            </button>
                        @endif
                    </div>

                    <div class="card">
                        <div class="card-header"><strong>{{$pageTitle}}</strong></div>
                        <div class="card-body">
                            @if (count($items))
                                <table class="table table-responsive-sm table-striped">
                                    <tbody>
                                    <?php foreach($items as $item):

                                    ?>
                                    <tr class="row-tr" data-id="{{$item->id}}">
                                        <td class="row-name row-parent" id="row-name-{{$item->id}}" data-name="{{$item->title}}">{{$item->title}}</td>
                                        <td>
                                            <div class="align-right">
                                                @if ($viewer->isAllowed('staff_level_edit') && $item->id > 5)
                                                    <button class="btn btn-info btn-sm mb-1"
                                                            title="Sửa" data-original-title="Sửa"
                                                            onclick="editItem({{$item->id}})"
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endif
                                                @if ($viewer->isAllowed('staff_level_delete') && $item->id > 5)
                                                    <button class="btn btn-danger btn-sm mb-1"
                                                            title="Xóa" data-original-title="Xóa"
                                                            onclick="deleteItem({{$item->id}})"
                                                    >
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            @else
                                <div class="clearfix mb-4 mt-4">
                                    <span class="alert alert-info notfound"></span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{--modal--}}
    <div id="modal_item_update"  class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <form action="{{url('admin/level/save')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group" id="req-title">
                            <input required type="text" name="title" autocomplete="off" class="form-control" />
                            <div class="alert alert-danger hidden">Hãy nhập quyền truy cập.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                        <button type="submit" class="btn btn-primary">Xác Nhận</button>

                        <input type="hidden" name="item_id" />
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{url('public/js/back_end/levels.js')}}"></script>

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
