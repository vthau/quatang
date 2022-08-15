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
    </style>

    <div>
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-menu">
                        @if ($viewer->isAllowed('partner_test_add'))
                            <button class="btn btn-primary btn-sm mb-1" onclick="parent.window.location.href = '{{url('admin/test/question/add')}}'" >
                                <i class="fa fa-plus-circle mr-1"></i>
                                Tạo Câu Hỏi
                            </button>
                        @endif
                        @if ($viewer->isAllowed('partner_test_excel_import'))
                            <button class="btn btn-success btn-sm mb-1" onclick="nhapTuExcel()" >
                                <i class="fa fa-file-excel mr-1"></i>
                                Nhập Từ Excel
                            </button>
                        @endif
                    </div>

                    <div class="frm-search">
                        <form action="{{url('admin/test/questions')}}" method="get" >
                            <div class="card">
                                <div class="card-header">
                                    <strong>Tìm Kiếm</strong>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <div class="btn-group">
                                                        <button id="btn-filter" type="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="true" class="btn btn-info">
                                                            Câu Hỏi
                                                        </button>
                                                    </div>
                                                </div>
                                                <input type="text" id="filter-keyword" name="keyword" placeholder="Từ Khóa" class="form-control" value="{{count($params) && isset($params['keyword']) ? $params['keyword'] : ""}}" autocomplete="off" />
                                                <input type="hidden" id="filter-by" name="filter" value="{{count($params) && isset($params['filter']) ? $params['filter'] : "name"}}" />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <select name="type" class="form-control">
                                                <option value="">Tất Cả Loại Câu Hỏi</option>
                                                <option <?php if (count($params) && isset($params['type']) && $params['type'] == 'truc_tiep'):?>selected="selected"<?php endif;?> value="truc_tiep">Câu Hỏi TV Trực Tiếp</option>
                                                <option <?php if (count($params) && isset($params['type']) && $params['type'] == 'gian_tiep'):?>selected="selected"<?php endif;?> value="gian_tiep">Câu Hỏi TV Gián Tiếp</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary btn-sm mb-1">
                                        <i class="fa fa-search fs-14 mr-1"></i>
                                        Tìm
                                    </button>

                                    <input type="hidden" id="search-order" name="order" value="{{count($params) && isset($params['order']) ? $params['order'] : ""}}" />
                                    <input type="hidden" id="search-order-by" name="order-by" value="{{count($params) && isset($params['order-by']) ? $params['order-by'] : ""}}" />
                                </div>
                            </div>
                        </form>
                    </div>

                    @if (count($items))
                        <div class="card-filter margin-bot-20">
                            <div class="float-right">
                                <div class="float-left margin-right-5">
                                    <select onchange="frmOrder(this)" class="form-control">
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'newest'):?>selected="selected"<?php endif;?> value="newest">Mới Nhất</option>
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'alphabet'):?>selected="selected"<?php endif;?> value="alphabet">Alphabet</option>
                                    </select>
                                </div>

                                <div class="float-left">
                                    <select onchange="frmOrderBy(this)" class="form-control">
                                        <option <?php if (count($params) && isset($params['order-by']) && $params['order-by'] == 'desc'):?>selected="selected"<?php endif;?> value="desc">Giảm Dần</option>
                                        <option <?php if (count($params) && isset($params['order-by']) && $params['order-by'] == 'asc'):?>selected="selected"<?php endif;?> value="asc">Tăng Dần</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <strong>{{$pageTitle}}</strong>

                                <div class="c-header-right font-weight-bold">
                                    Tổng Cộng: {{$items->total()}}
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-responsive-sm table-striped">
                                    <thead>
                                    <tr>
                                        <th>câu hỏi</th>
                                        <th>đáp án</th>
                                        <th>loại</th>
                                        <th></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php foreach($items as $item):

                                    ?>
                                    <tr id="item-{{$item->id}}">
                                        <td class="text-capitalize">{{$item->getTitle()}}</td>
                                        <td class="text-capitalize">{{$item->getRightAnswer() ? $item->getRightAnswer()->getTitle() : ''}}</td>
                                        <td>{{$item->getTypeText()}}</td>
                                        <td>
                                            <div class="align-right">
                                                @if ($viewer->isAllowed('partner_test_edit'))
                                                    <button class="btn btn-info btn-sm mb-1"
                                                            title="Sửa" data-original-title="Sửa"
                                                            onclick="gotoPage('{{url('admin/test/question/add?id=' . $item->id)}}')"
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endif

                                                @if ($viewer->isAllowed('partner_test_delete'))
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
                            </div>
                        </div>

                        <div class="gks_pagination">
                            {{$items->appends(request()->query())->links()}}
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

    {{--modal--}}
    @include('modals.all')

    {{--    modal--}}
    <div id="modal-import-excel" class="modal fade bd-example-modal-lg in" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{url('admin/test/question/import')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase">nhập từ excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row form-group mb-3">
                                    <div class="col-md-12">
                                        <a href="{{url('public/import_questions.xlsx')}}"
                                           class="form-control font-weight-bold text-uppercase text-danger" download="">Tải về file mẫu</a>
                                    </div>
                                </div>

                                <div class="row form-group mb-4">
                                    <div class="col-md-12">
                                        <select name="type" class="form-control">
                                            <option value="truc_tiep">Câu Hỏi TV Trực Tiếp</option>
                                            <option value="gian_tiep">Câu Hỏi TV Gián Tiếp</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="clearfix">
                                    <input type="file" name="file" id="file-upload" required
                                           accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                           onchange="kiemTraFileImport(this)">
                                </div>
                                <div class="alert alert-danger mt-2 hidden error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary " data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary ">Xác Nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{url('public/js/be/test_questions.js')}}"></script>
@stop
