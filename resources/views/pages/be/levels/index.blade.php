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
                            <button class="btn btn-danger btn-sm mb-1" onclick="parent.window.location.href = '{{url('admin/level/manage')}}'">
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
                                        <td class="row-name" id="row-name-{{$item->id}}">{{$item->title}}</td>
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
    <div id="modalUpdate"  class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="frm-update-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input onkeypress="return pressEnter(event)" type="text" id="frm-update-text" autocomplete="off" class="form-control" />
                        <div class="alert alert-danger hidden">Hãy nhập quyền truy cập.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                    <button type="button" class="btn btn-primary" onclick="confirmUpdateItem(1)">Có</button>
                </div>

                <div class="hidden">
                    <input type="hidden" id="update-item" />
                </div>
            </div>
        </div>
    </div>
    <div id="modalDelete"  class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Xác Nhận</h4>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc muốn xóa quyền truy cập này không?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                    <button type="button" class="btn btn-primary" onclick="confirmDeleteItem(1)">Có</button>
                </div>

                <div class="hidden">
                    <input type="hidden" id="delete-item" />
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{url('public/js/be/levels.js')}}"></script>
@stop
