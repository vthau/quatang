<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();

$viewer = $apiCore->getViewer();
?>

@extends('templates.be.master')

@section('content')

    <style type="text/css">
        .frm-search .form-group > div {
            float: left;
        }
    </style>

    <div>
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    @if ($viewer->isAllowed("staff_user_add"))
                    <div class="btn-menu">
                        <button class="btn btn-primary btn-sm mb-1" onclick="openPage('{{url('admin/staff/add')}}')" >
                            <i class="fa fa-plus-circle mr-1"></i>
                            Tạo Nhân Viên
                        </button>
                    </div>
                    @endif

                    <div class="frm-search">
                        <form action="{{url('admin/staffs')}}" method="get" >
                            <div class="card">
                                <div class="card-header">
                                    <strong>Tìm Kiếm</strong>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="col-md-6">
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
                                                <input type="text" id="filter-keyword" name="keyword" placeholder="Từ Khóa" class="form-control" value="{{count($params) && isset($params['keyword']) ? $params['keyword'] : ""}}" autocomplete="off" />
                                                <input type="hidden" id="filter-by" name="filter" value="{{count($params) && isset($params['filter']) ? $params['filter'] : "name"}}" />
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <select id="filter-level" name="level" class="form-control">
                                                <option value="">Tất Cả</option>
                                                @foreach ($levels as $k => $v)
                                                    <option <?php if (count($params) && isset($params['level']) && $params['level'] == $k):?>selected="selected"<?php endif;?> value="{{$k}}">{{$v}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <select id="filter-block" name="blocked" class="form-control">
                                                <option value="">Tất Cả</option>
                                                <option <?php if (count($params) && isset($params['blocked']) && (int)$params['blocked'] == 1):?>selected="selected"<?php endif;?> value="1">Đã Chặn</option>
                                                <option <?php if (count($params) && isset($params['blocked']) && (int)$params['blocked'] == 2):?>selected="selected"<?php endif;?> value="2">Chưa Chặn</option>
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
                                    <span>Tổng Cộng: </span>
                                    <span class="number_format">{{$items->total()}}</span>
                                </div>
                            </div>

                            <div class="card-body">
                                <table class="table table-responsive-sm table-striped">
                                    <thead>
                                    <tr>
                                        <th>nhân viên</th>
                                        <th>quyền truy cập</th>
                                        <th>điện thoại</th>
                                        <th>email</th>
                                        <th>chặn truy cập</th>
                                        <th></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php foreach($items as $item):

                                    ?>
                                    <tr id="item-{{$item->id}}">
                                        <td class="text-capitalize"><?php echo $item->toHTML(['avatar' => true]);?></td>
                                        <td>{{$item->getLevel() ? $item->getLevel()->getTitle() : ''}}</td>
                                        <td>
                                            <a href="tel:{{$item->phone}}">{{$item->phone}}</a>
                                        </td>
                                        <td>
                                            <a class="text-lowercase" href="mailto:{{$item->email}}">{{$item->email}}</a>
                                        </td>
                                        <td>
                                            @if ($item->isBlocked())
                                                <img class="c-btn-img" src="{{url('public/images/icons/ic_tick_red.png')}}" />
                                                @if (!empty($item->blockReason()))
                                                    <div><?php echo nl2br($item->blockReason());?></div>
                                                @endif
                                            @else
                                                <img class="c-btn-img" src="{{url('public/images/icons/ic_tick_dark.png')}}" />
                                            @endif
                                        </td>
                                        <td>
                                            <div class="align-right">
                                                <button class="btn btn-primary btn-sm mb-1"
                                                        title="Xem" data-original-title="Xem"
                                                        onclick="gotoPage('{{$item->getHref()}}')"
                                                >
                                                    <i class="fa fa-eye text-white"></i>
                                                </button>

                                                @if (!$item->isBlocked() && $viewer->isAllowed("staff_user_block"))
                                                    <button class="btn btn-warning btn-sm mb-1"
                                                            title="Chặn Truy Cập" data-original-title="Chặn Truy Cập"
                                                            onclick="blockItem({{$item->id}})"
                                                    >
                                                        <i class="fa fa-lock text-white"></i>
                                                    </button>
                                                @endif

                                                @if ($item->isBlocked() && $viewer->isAllowed("staff_user_unblock"))
                                                    <button class="btn btn-success btn-sm mb-1"
                                                            title="Mở Chặn Truy Cập" data-original-title="Mở Chặn Truy Cập"
                                                            onclick="unblockItem({{$item->id}})"
                                                    >
                                                        <i class="fa fa-lock-open text-white"></i>
                                                    </button>
                                                @endif

                                                @if ($viewer->isAllowed("staff_user_edit"))
                                                    <button class="btn btn-info btn-sm mb-1"
                                                            title="Sửa" data-original-title="Sửa"
                                                            onclick="openPage('{{url('admin/staff/add?id=' . $item->id)}}')"
                                                    >
                                                        <i class="fa fa-edit text-white"></i>
                                                    </button>
                                                @endif

                                                @if ($viewer->isAllowed("staff_user_delete"))
                                                    <button class="btn btn-danger btn-sm mb-1"
                                                            title="Xóa" data-original-title="Xóa"
                                                            onclick="deleteItem({{$item->id}})"
                                                    >
                                                        <i class="fa fa-trash text-white"></i>
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

    <div id="modal_item_block" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Xác Nhận</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">Bạn có chắc muốn CHẶN truy cập không?</div>

                    <div class="modal-textarea">
                        <textarea name="reason" class="form-control" rows="5" cols="5" placeholder="Lí do"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                    <button type="button" class="btn btn-primary" onclick="confirmBlockItem()">Xác Nhận</button>

                    <input type="hidden" name="item_id" />
                </div>
            </div>
        </div>
    </div>

    <div id="modal_item_unblock"  class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Xác Nhận</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">Bạn có chắc muốn BỎ CHẶN truy cập không?</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                    <button type="button" class="btn btn-primary" onclick="confirmUnblockItem()">Xác Nhận</button>

                    <input type="hidden" name="item_id" />
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{url('public/js/back_end/staffs.js')}}"></script>

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
