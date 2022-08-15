<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();
$apiFE = new \App\Api\FE();

$viewer = $apiCore->getViewer();
?>

@extends('templates.be.master')

@section('content')

    <style type="text/css">
        .frm-search .form-group > div {
            float: left;
            margin-bottom: 20px;
        }

        .c-href-item .c-title-href {
            top: 0 !important;
        }
    </style>

    <div>
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-menu">
                        <button class="btn btn-success btn-sm mb-1" onclick="exportItems()" >
                            <i class="fa fa-file-excel mr-1"></i>
                            xuất excel
                        </button>
                    </div>

                    <div class="frm-search">
                        <form action="{{url('admin/receipts')}}" method="get" id="frm-search">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Tìm Kiếm</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <label class="frm-label">Từ khóa</label>
                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <div class="btn-group">
                                                        <button id="btn-filter" type="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="true" class="dropdown-toggle btn btn-info">
                                                            @if (count($params) && isset($params['filter']))
                                                                @if ($params['filter'] == 'phone')
                                                                    Điện Thoại
                                                                @else
                                                                    Mã Đơn Hàng
                                                                @endif
                                                            @else
                                                                Điện Thoại
                                                            @endif
                                                        </button>
                                                        <div tabindex="-1" aria-hidden="true" role="menu" class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -173px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                            <button onclick="filterBy('name')" type="button" tabindex="0" class="dropdown-item">Mã Đơn Hàng</button>
                                                            <button onclick="filterBy('phone')" type="button" tabindex="0" class="dropdown-item">Điện Thoại</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="text" id="filter-keyword" name="keyword" placeholder="Gợi ý..." class="form-control" value="{{count($params) && isset($params['keyword']) ? $params['keyword'] : ""}}" autocomplete="off" />
                                                <input type="hidden" id="filter-by" name="filter" value="{{count($params) && isset($params['filter']) ? $params['filter'] : "phone"}}" />
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="frm-label">phương thức thanh toán</label>
                                            <select id="filter-payment" name="payment" class="form-control select2-single select2-hidden-accessible">
                                                <option value="">Tất Cả</option>
                                                <option <?php if (count($params) && isset($params['payment']) && $params['payment'] == 'cod'):?>selected="selected"<?php endif;?> value="cod">Tiền Mặt Khi Nhận Hàng</option>
                                                <option <?php if (count($params) && isset($params['payment']) && $params['payment'] == 'banking'):?>selected="selected"<?php endif;?> value="banking">Chuyển Khoản Ngân Hàng</option>
                                                <option <?php if (count($params) && isset($params['payment']) && $params['payment'] == 'vnpay'):?>selected="selected"<?php endif;?> value="vnpay">Thanh Toán VNPAY</option>
                                                <option <?php if (count($params) && isset($params['payment']) && $params['payment'] == 'zalopay'):?>selected="selected"<?php endif;?> value="zalopay">Thanh Toán ZALOPAY</option>
                                                <option <?php if (count($params) && isset($params['payment']) && $params['payment'] == 'momo'):?>selected="selected"<?php endif;?> value="momo">Thanh Toán MOMO</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3 select2-single-wrapper">
                                            <label class="frm-label">Sản phẩm</label>
                                            <select id="filter-product" name="product" class="form-control select2-single select2-hidden-accessible">
                                                <option value="">Tất Cả</option>
                                                @foreach ($products as $product)
                                                    <option <?php if (count($params) && isset($params['product']) && (int)$params['product'] == $product['id']):?>selected="selected"<?php endif;?> value="{{$product['id']}}">{{$product['title']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="frm-label">trạng thái thanh toán</label>
                                            <select id="filter-status" name="status" class="form-control select2-single select2-hidden-accessible">
                                                <option value="">Tất Cả</option>
                                                <option <?php if (count($params) && isset($params['status']) && $params['status'] == 'chua_thanh_toan'):?>selected="selected"<?php endif;?> value="chua_thanh_toan">Chưa Thanh Toán</option>
                                                <option <?php if (count($params) && isset($params['status']) && $params['status'] == 'da_thanh_toan'):?>selected="selected"<?php endif;?> value="da_thanh_toan">Đã Thanh Toán</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 select2-single-wrapper">
                                            <label class="frm-label">Khách Hàng</label>
                                            <select id="filter-user" name="user" class="form-control select2-single select2-hidden-accessible">
                                                <option value="">Tất Cả</option>
                                                <option <?php if (count($params) && isset($params['user']) && $params['user'] == 'out'):?>selected="selected"<?php endif;?> value="out">Khách hàng không có tài khoản</option>
                                                @foreach ($users as $user)
                                                    <option <?php if (count($params) && isset($params['user']) && (int)$params['user'] == $user['id']):?>selected="selected"<?php endif;?> value="{{$user['id']}}">{{$user['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3 select2-single-wrapper">
                                            <label class="frm-label">Người Giới Thiệu</label>
                                            <select id="filter-ref" name="ref" class="form-control select2-single select2-hidden-accessible">
                                                <option value="">Tất Cả</option>
                                                @foreach ($refers as $user)
                                                    <option <?php if (count($params) && isset($params['ref']) && (int)$params['ref'] == $user['id']):?>selected="selected"<?php endif;?> value="{{$user['id']}}">{{$user['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="frm-label">Ngày đơn hàng từ</label>
                                            <input type="date" class="form-control" name="date_from" value="{{$dateFrom}}" />
                                        </div>

                                        <div class="col-md-3">
                                            <label class="frm-label">Ngày đơn hàng đến</label>
                                            <input type="date" class="form-control" name="date_to" value="{{$dateTo}}" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 select2-single-wrapper">
                                            <label class="frm-label">Trạng Thái Giao Hàng</label>
                                            <select name="shipping_status" class="form-control select2-single select2-hidden-accessible">
                                                <option value="">Tất Cả Trạng Thái</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'ready_to_pick'):?>selected="selected"<?php endif;?> value="ready_to_pick">{{$apiFE->getGhnStatus('ready_to_pick')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'picking'):?>selected="selected"<?php endif;?> value="picking">{{$apiFE->getGhnStatus('picking')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'cancel'):?>selected="selected"<?php endif;?> value="cancel">{{$apiFE->getGhnStatus('cancel')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'money_collect_picking'):?>selected="selected"<?php endif;?> value="money_collect_picking">{{$apiFE->getGhnStatus('money_collect_picking')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'picked'):?>selected="selected"<?php endif;?> value="picked">{{$apiFE->getGhnStatus('picked')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'storing'):?>selected="selected"<?php endif;?> value="storing">{{$apiFE->getGhnStatus('storing')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'transporting'):?>selected="selected"<?php endif;?> value="transporting">{{$apiFE->getGhnStatus('transporting')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'sorting'):?>selected="selected"<?php endif;?> value="sorting">{{$apiFE->getGhnStatus('sorting')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'delivering'):?>selected="selected"<?php endif;?> value="delivering">{{$apiFE->getGhnStatus('delivering')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'money_collect_delivering'):?>selected="selected"<?php endif;?> value="money_collect_delivering">{{$apiFE->getGhnStatus('money_collect_delivering')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'delivered'):?>selected="selected"<?php endif;?> value="delivered">{{$apiFE->getGhnStatus('delivered')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'delivery_fail'):?>selected="selected"<?php endif;?> value="delivery_fail">{{$apiFE->getGhnStatus('delivery_fail')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'waiting_to_return'):?>selected="selected"<?php endif;?> value="waiting_to_return">{{$apiFE->getGhnStatus('waiting_to_return')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'return'):?>selected="selected"<?php endif;?> value="return">{{$apiFE->getGhnStatus('return')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'return_transporting'):?>selected="selected"<?php endif;?> value="return_transporting">{{$apiFE->getGhnStatus('return_transporting')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'return_sorting'):?>selected="selected"<?php endif;?> value="return_sorting">{{$apiFE->getGhnStatus('return_sorting')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'returning'):?>selected="selected"<?php endif;?> value="returning">{{$apiFE->getGhnStatus('returning')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'return_fail'):?>selected="selected"<?php endif;?> value="return_fail">{{$apiFE->getGhnStatus('return_fail')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'returned'):?>selected="selected"<?php endif;?> value="returned">{{$apiFE->getGhnStatus('returned')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'exception'):?>selected="selected"<?php endif;?> value="exception">{{$apiFE->getGhnStatus('exception')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'damage'):?>selected="selected"<?php endif;?> value="damage">{{$apiFE->getGhnStatus('damage')}}</option>
                                                <option <?php if (count($params) && isset($params['shipping_status']) && $params['shipping_status'] == 'lost'):?>selected="selected"<?php endif;?> value="lost">{{$apiFE->getGhnStatus('lost')}}</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3 select2-single-wrapper">
                                            <label class="frm-label">Phương Thức Giao Hàng</label>
                                            <select name="shipping" class="form-control select2-single select2-hidden-accessible">
                                                <option value="">Tất Cả Phương Thức</option>
                                                <option <?php if (count($params) && isset($params['shipping']) && $params['shipping'] == 'ghn'):?>selected="selected"<?php endif;?> value="ghn">GHN</option>
                                                <option <?php if (count($params) && isset($params['shipping']) && $params['shipping'] == 'manual'):?>selected="selected"<?php endif;?> value="manual">Tự Vận Đơn</option>
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
                            <div class="float-left">
                                <div class="sum-stats">
                                    <div>
                                        <span class="text-uppercase">TỔNG TIỀN HÀNG: </span>
                                        <span class="money_format">{{$totalCart}}</span>
                                        <span class="currency_format">₫</span>
                                    </div>
                                    <div>
                                        <span class="text-uppercase">Tổng Giảm Giá: </span>
                                        <span class="money_format text-warning">{{$totalDiscount}}</span>
                                        <span class="currency_format text-warning">₫</span>
                                    </div>
                                    <div>
                                        <span class="text-uppercase">Tổng Phí Giao Hàng: </span>
                                        <span class="money_format text-primary">{{$totalShip}}</span>
                                        <span class="currency_format text-primary">₫</span>
                                    </div>
                                    <div>
                                        <span class="text-uppercase">TỔNG THANH TOÁN: </span>
                                        <span class="money_format text-success">{{$totalPrice}}</span>
                                        <span class="currency_format text-success">₫</span>
                                    </div>
                                </div>
                            </div>

                            <div class="float-right" id="frm-order">
                                <div class="float-left margin-right-5">
                                    <select name="order" onchange="frmOrder(this)" class="form-control">
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'newest'):?>selected="selected"<?php endif;?> value="newest">Mới Nhất</option>
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'total_quantity'):?>selected="selected"<?php endif;?> value="total_quantity">Số Lượng</option>
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'total_cart'):?>selected="selected"<?php endif;?> value="total_cart">Giá Trị</option>
                                        {{--                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'total_ship'):?>selected="selected"<?php endif;?> value="total_ship">Phí Giao Hàng</option>--}}
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'total_price'):?>selected="selected"<?php endif;?> value="total_price">Tổng Thu</option>
                                    </select>
                                </div>

                                <div class="float-left">
                                    <select name="orderby" onchange="frmOrderBy(this)" class="form-control">
                                        <option <?php if (count($params) && isset($params['order-by']) && $params['order-by'] == 'desc'):?>selected="selected"<?php endif;?> value="desc">Giảm Dần</option>
                                        <option <?php if (count($params) && isset($params['order-by']) && $params['order-by'] == 'asc'):?>selected="selected"<?php endif;?> value="asc">Tăng Dần</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <strong>{{$pageTitle}}</strong>

                                <div class="c-header-right">
                                    Tổng Cộng: {{$items->total()}}
                                </div>
                            </div>

                            <div class="card-body">
                                <table class="table table-responsive-sm table-striped">
                                    <thead>
                                    <tr>
                                        <th class="frm-label">thời gian</th>
                                        <th class="frm-label">mã đơn hàng</th>
                                        <th class="frm-label">khách hàng</th>
                                        <th class="frm-label">người giới thiệu</th>
                                        <th class="frm-label align-center">tổng số lượng SP</th>
                                        <th class="frm-label align-center">tổng tiền hàng</th>
                                        <th class="frm-label align-center">Giảm Giá</th>
                                        <th class="frm-label align-center">phí giao hàng</th>
                                        <th class="frm-label align-center">tổng thanh toán</th>
                                        <th class="frm-label align-center">phương thức</th>
                                        <th class="frm-label align-center">trạng thái</th>
                                        <th></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php foreach($items as $order):

                                    ?>
                                    <tr id="order_{{$order->id}}">
                                        @include('modals.be.order_index_tr')
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

    <div id="modal-ghn-confirm"  class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Xác Nhận</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">Bạn có chắc muốn tạo đơn hàng trên hệ thống GHN?</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                    <button type="button" class="btn btn-primary" onclick="giaoHangXacNhan()">Có</button>

                    <input type="hidden" name="item_id" />
                </div>

            </div>
        </div>
    </div>

    <div id="modal-tu-van-don"  class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tự Vận Đơn</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-label required">Phí Giao Hàng</label>
                        <input type="text" class="form-control money_format" name="shipping_fee" />
                    </div>
                    <div class="form-group">
                        <label class="text-label">Ghi Chú</label>
                        <textarea name="note" class="form-control" rows="3" cols="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                    <button type="button" class="btn btn-primary" onclick="tuVanDonXacNhan()">Có</button>

                    <input type="hidden" name="item_id" />
                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{url('public/js/be/payments.js')}}"></script>


@stop
