<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();

$apiFE = new \App\Api\FE;
$provinces = $apiFE->getProvinces();
$freeShipCities = [];
$settingCities = \App\Model\Setting::where('title', 'payment_ship_free_city')->first();
if ($settingCities && !empty($settingCities->value)) {
    $freeShipCities = (array)json_decode($settingCities->value);
}
?>

@extends('templates.be.master')

@section('content')

    <div>
        <div class="fade-in">
            <form action="{{url('admin/order-settings/save')}}" method="post" id="frm-add" enctype="multipart/form-data" >
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><strong>{{$pageTitle}}</strong></div>
                            <div class="card-body card-block">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="frm-label">Phí Giao hàng (₫)</label>
                                            <div><p>(Áp dụng cho tất cả đơn hàng)</p></div>
                                            <input value="{{$apiCore->getSetting('payment_ship_fee')}}" name="payment_ship_fee" type="text" autocomplete="off"
                                                   class="form-control number_format mb-2"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="frm-label">miễn phí giao hàng</label>
                                            <div><p>(Nếu TỔNG giá trị đơn hàng bằng hoặc vượt qua)</p></div>
                                            <input value="{{$apiCore->getSetting('payment_ship_free_cart')}}" name="payment_ship_free_cart" type="text" autocomplete="off"
                                                   class="form-control number_format mb-2"
                                            />
                                            <div><p>(Chỉ áp dụng cho các tỉnh / thành bên dưới --- Nếu để trống = áp dụng cho tất cả)</p></div>
                                            <select name="payment_ship_free_city[]" class="form-control" multiple>
                                                @if(count($provinces))
                                                    @foreach($provinces as $ite)
                                                        <option @if(count($freeShipCities) && in_array($ite->id, $freeShipCities)) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row hidden">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="frm-label">Thông báo khi có đơn đặt hàng</label>
                                            <div><p>(Khi có đơn đặt hàng, hệ thống gửi thông báo cho tất cả nhân viên)</p></div>
                                            <input name="cart_booked_notify_to" value="{{$apiCore->getSetting('cart_booked_notify_to')}}" class="form-control" type="text"
                                                   placeholder="Ví dụ: nhanvien1@gmail.com;nhanvien2@gmail.com;..."
                                            />
                                            <p>Các địa chỉ email cách nhau bởi dấu chấm phẩy ;</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="frm-label">Lưu ý khi mua hàng</label>
                                            <div><p>(Hiển thị tại giỏ hàng)</p></div>
                                            <textarea class="form-control" name="payment_account" rows="5" cols="5">{{$apiCore->getSetting('payment_account')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="frm-label">Ghi chú hóa đơn</label>
                                            <div><p>(Hiển thị tại hóa đơn)</p></div>
                                            <textarea class="form-control" name="receipt_note" rows="5" cols="5">{{$apiCore->getSetting('receipt_note')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm mb-1">
                                    <i class="fa fa-check-circle mr-1"></i>
                                    Xác Nhận
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
