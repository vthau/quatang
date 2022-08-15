<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();

$viewer = $apiCore->getViewer();
?>

@extends('templates.be.master')

@section('content')

<style type="text/css">
    .frm-search .form-group>div {
        float: left;
    }
</style>

<div>
    <div class="fade-in">
        <div class="row">
            <div class="col-md-12">
                <div class="btn-menu">
                    @if ($viewer->isAllowed("client_add"))
                    <button class="btn btn-primary btn-sm mb-1" onclick="openPage('{{url('admin/client/add')}}')">
                        <i class="fa fa-plus-circle mr-1"></i>
                        Tạo Khách Hàng
                    </button>
                    @endif
                    @if ($viewer->isAllowed("product_excel_export") || $viewer->isSupplier())
                    <button class="btn btn-success btn-sm mb-1" onclick="exportItems()">
                        <i class="fa fa-file-excel mr-1"></i>
                        Xuất Excel
                    </button>
                    @endif
                </div>

                <div class="frm-search">
                    <form action="{{url('admin/clients')}}" method="get" id="frm-search">
                        <div class="card">
                            <div class="card-header">
                                <strong>Tìm Kiếm</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <div class="input-group-btn">
                                                <div class="btn-group">
                                                    <button id="btn-filter" type="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="true" class="dropdown-toggle btn btn-info">
                                                        @if (count($params) && isset($params['filter']))
                                                        @if ($params['filter'] == 'phone')
                                                        Điện Thoại
                                                        @elseif ($params['filter'] == 'email')
                                                        Email
                                                        @else
                                                        Họ Tên
                                                        @endif
                                                        @else
                                                        Họ Tên
                                                        @endif
                                                    </button>
                                                    <div tabindex="-1" aria-hidden="true" role="menu" class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -173px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        <button onclick="filterBy('name')" type="button" tabindex="0" class="dropdown-item">Họ Tên</button>
                                                        <button onclick="filterBy('phone')" type="button" tabindex="0" class="dropdown-item">Điện Thoại</button>
                                                        <button onclick="filterBy('email')" type="button" tabindex="0" class="dropdown-item">Email</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="text" id="filter-keyword" name="keyword" placeholder="Từ Khóa" class="form-control" value="{{count($params) && isset($params['keyword']) ? $params['keyword'] : " "}}" autocomplete="off" />
                                            <input type="hidden" id="filter-by" name="filter" value="{{count($params) && isset($params['filter']) ? $params['filter'] : "name"}}" />
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
                <div class="card-filter margin-bot-20" id="frm-order">
                    <div class="float-right">
                        <div class="float-left margin-right-5">
                            <select onchange="frmOrder(this)" class="form-control" name="order">
                                <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'newest') : ?>selected="selected" <?php endif; ?> value="newest">Mới Nhất</option>
                                <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'alphabet') : ?>selected="selected" <?php endif; ?> value="alphabet">Alphabet</option>
                            </select>
                        </div>

                        <div class="float-left">
                            <select onchange="frmOrderBy(this)" class="form-control" name="orderby">
                                <option <?php if (count($params) && isset($params['order-by']) && $params['order-by'] == 'desc') : ?>selected="selected" <?php endif; ?> value="desc">Giảm Dần</option>
                                <option <?php if (count($params) && isset($params['order-by']) && $params['order-by'] == 'asc') : ?>selected="selected" <?php endif; ?> value="asc">Tăng Dần</option>
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
                                    <th>khách hàng</th>
                                    <th>điện thoại</th>
                                    <th>email</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($items as $user) :

                                ?>
                                    <tr id="item-{{$user->id}}">
                                        <td class="text-capitalize"><?php echo $user->toHTML(['avatar' => false]); ?></td>
                                        <td>
                                            <a href="tel:{{$user->phone}}">{{$user->phone}}</a>
                                        </td>
                                        <td>
                                            <a class="text-lowercase" href="mailto:{{$user->email}}">{{$user->email}}</a>
                                        </td>
                                        <td>
                                            <div class="align-right">
                                                <button class="btn btn-primary btn-sm mb-1" title="Xem" data-original-title="Xem" onclick="gotoPage('{{$user->getHref()}}')">
                                                    <i class="fa fa-eye"></i>
                                                </button>

                                                @if ($viewer->isAllowed("client_reset_password"))
                                                <button class="btn btn-secondary btn-sm mb-1" title="Reset Mật Khẩu" data-original-title="Reset Mật Khẩu" onclick="changePassword({{$user->id}})">
                                                    <i class="fa fa-user-lock"></i>
                                                </button>
                                                @endif

                                                @if ($viewer->isAllowed("client_delete"))
                                                <button class="btn btn-danger btn-sm mb-1" title="Xóa" data-original-title="Xóa" onclick="deleteItem({{$user->id}})">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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

<div id="modal_change_password" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Đổi Mật Khẩu</h4>
            </div>
            <form action="{{url('admin/client/change-password')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="modal-password" id="req-pwd">
                        <input required type="password" name="pwd" autocomplete="new-password" class="form-control text-center" />
                        <div class="alert alert-danger hidden">Hãy nhập mật khẩu hợp lệ.</div>
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

<script type="text/javascript" src="{{url('public/js/back_end/clients.js')}}"></script>

<script type="text/javascript">
    jQuery(document).ready(function() {
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
