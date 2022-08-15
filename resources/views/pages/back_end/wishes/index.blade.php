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
                        @if ($viewer->isAllowed('wish_add'))
                        <button class="btn btn-primary btn-sm mb-1" onclick="addItem()">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Tạo Câu Chúc
                        </button>
                        @endif
                    </div>

                    <div class="clearfix mb-4 mt-4">
                        <span class="alert alert-warning">Câu chúc được ACTIVE sẽ được hiển thị ngoài trang chính.</span>
                    </div>

                    <div class="frm-search">
                        <form action="{{url('admin/wishes')}}" method="get" >
                            <div class="card">
                                <div class="card-header">
                                    <strong>Tìm Kiếm</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <div class="btn-group">
                                                        <button id="btn-filter" type="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="true" class=" btn btn-info">
                                                            Câu Chúc
                                                        </button>
                                                    </div>
                                                </div>
                                                <input type="text" id="filter-keyword" name="keyword" placeholder="Từ Khóa" class="form-control" value="{{count($params) && isset($params['keyword']) ? $params['keyword'] : ""}}" autocomplete="off" />
                                            </div>
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
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'view_count'):?>selected="selected"<?php endif;?> value="view_count">Lượt Xem</option>
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
                                    <span>Tổng Cộng: </span>
                                    <span class="number_format">{{$items->total()}}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-responsive-sm table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: 50%;">câu chúc</th>
                                        <th>trạng thái</th>
                                        <th>nhóm chủ đề</th>
                                        <th></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php foreach($items as $item):

                                    ?>
                                    <tr class="row-tr" data-id="{{$item->id}}">
                                        <td id="row-name-{{$item->id}}"
                                            data-name="{{$item->title}}" data-category="{{$item->system_category_id}}">
                                            <?php echo nl2br($item->title)?>
                                        </td>

                                        <td>
                                            @if ($item->active)
                                                @if ($viewer->isAllowed('wish_edit'))
                                                    <a class="badge badge-success text-uppercase" onclick="updateStatus({{$item->id}}, 'active', 0)" href="javascript:void(0)">
                                                        <i class="fa fa-check text-white mr-1"></i> active
                                                    </a>
                                                @else
                                                    <div class="badge badge-success text-uppercase">
                                                        <i class="fa fa-check text-white mr-1"></i> active
                                                    </div>
                                                @endif
                                            @else
                                                @if ($viewer->isAllowed('wish_edit'))
                                                    <a class="badge badge-secondary text-uppercase text-black-50" onclick="updateStatus({{$item->id}}, 'active', 1)" href="javascript:void(0)">
                                                        <i class="fa fa-check text-black-50 mr-1"></i> inactive
                                                    </a>
                                                @else
                                                    <div class="badge badge-secondary text-uppercase text-black-50">
                                                        <i class="fa fa-check text-black-50 mr-1"></i> inactive
                                                    </div>
                                                @endif
                                            @endif
                                        </td>

                                        <td>
                                            @if ($viewer->isAllowed('wish_edit'))
                                                <select class="form-control" onchange="updateStatus({{$item->id}}, 'category', this)">
                                                    <option @if(!$item->system_category_id) selected="selected" @endif value="0">Khác</option>
                                                    @if(count($categories))
                                                        @foreach($categories as $ite)
                                                            <option @if($item->system_category_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->getTitle()}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            @else
                                                <div>{{$item->getCategory() ? $item->getCategory()->getTitle() : 'Khác'}}</div>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="align-right">
                                                @if ($viewer->isAllowed('wish_edit'))
                                                <button class="btn btn-info btn-sm mb-1"
                                                        title="Sửa" data-original-title="Sửa"
                                                        onclick="editItem({{$item->id}})"
                                                >
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                @endif

                                                @if ($viewer->isAllowed('wish_delete'))
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
    <div id="modal_item_update"  class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <form action="{{url('admin/wish/save')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="frm-label">Nhóm Chủ Đề</label>
                            <select name="system_category_id" class="form-control">
                                <option value="0">Khác</option>
                                @if(count($categories))
                                    @foreach($categories as $ite)
                                        <option value="{{$ite->id}}">{{$ite->getTitle()}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group" id="req-title">
                            <label class="frm-label required">* Câu Chúc</label>
                            <textarea required name="title" rows="5" cols="5" class="form-control"></textarea>
                            <div class="alert alert-danger hidden"></div>
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

    <script type="text/javascript" src="{{url('public/js/back_end/wishes.js')}}"></script>

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
