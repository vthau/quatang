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
                        <button class="btn btn-primary btn-sm mb-1" onclick="parent.window.location.href = '{{url('admin/news/add')}}'" >
                            <i class="fa fa-plus-circle mr-1"></i>
                            Tạo Bài
                        </button>
                    </div>

                    <div class="frm-search">
                        <form action="{{url('admin/news')}}" method="get" >
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
                                                        <button id="btn-filter" type="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="true" class="dropdown-toggle btn btn-info">
                                                            @if (count($params) && isset($params['filter']))
                                                                @if ($params['filter'] == 'mo_ta')
                                                                    Mô Tả
                                                                @else
                                                                    Tiêu Đề
                                                                @endif
                                                            @else
                                                                Tiêu Đề
                                                            @endif
                                                        </button>
                                                        <div tabindex="-1" aria-hidden="true" role="menu" class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -173px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                            <button onclick="filterBy('name')" type="button" tabindex="0" class="dropdown-item">Tiêu Đề</button>
                                                            <button onclick="filterBy('mo_ta')" type="button" tabindex="0" class="dropdown-item">Mô Tả</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="text" id="filter-keyword" name="keyword" placeholder="Từ Khóa" class="form-control" value="{{count($params) && isset($params['keyword']) ? $params['keyword'] : ""}}" autocomplete="off" />
                                                <input type="hidden" id="filter-by" name="filter" value="{{count($params) && isset($params['filter']) ? $params['filter'] : "name"}}" />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <select id="filter-active" name="active" class="form-control">
                                                <option value="">Tất Cả Trạng Thái</option>
                                                <option <?php if (count($params) && isset($params['active']) && (int)$params['active'] == 1):?>selected="selected"<?php endif;?> value="1">Cho Xem</option>
                                                <option <?php if (count($params) && isset($params['active']) && (int)$params['active'] == 2):?>selected="selected"<?php endif;?> value="2">Tắt Xem</option>
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
                                    Tổng Cộng: {{$items->total()}}
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-responsive-sm table-striped">
                                    <thead>
                                    <tr>
                                        <th>tiêu đề</th>
                                        <th>lượt xem</th>
                                        <th>trạng thái</th>
                                        <th></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php foreach($items as $item):

                                    ?>
                                    <tr id="item-{{$item->id}}">
                                        <td class="text-capitalize"><?php echo $item->toHTML(['avatar' => true, 'fe' => true]);?></td>

                                        <td>{{$item->view_count}}</td>

                                        <td>
                                            <select class="form-control" onchange="updateStatus(this, 'active')" data-id="{{$item->id}}">
                                                <option <?php if ($item->active):?>selected="selected"<?php endif;?> value="1">Cho Xem</option>
                                                <option <?php if (!$item->active):?>selected="selected"<?php endif;?> value="0">Tắt Xem</option>
                                            </select>
                                        </td>

                                        <td>
                                            <div class="align-right">
                                                <button class="btn btn-info btn-sm mb-1"
                                                        title="Sửa" data-original-title="Sửa"
                                                        onclick="gotoPage('{{url('admin/news/add?id=' . $item->id)}}')"
                                                >
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <button class="btn btn-danger btn-sm mb-1"
                                                        title="Xóa" data-original-title="Xóa"
                                                        onclick="deleteItem({{$item->id}})"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>
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

    <script type="text/javascript" src="{{url('public/js/be/news.js')}}"></script>
@stop
