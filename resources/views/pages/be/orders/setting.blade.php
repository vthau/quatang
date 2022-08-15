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

    @if ($saved)
        <div class="alert alert-success">Thông tin đã được lưu thành công.</div>
    @endif

    <div>
        <div class="fade-in">
            <form action="{{url('admin/receipt-settings/save')}}" method="post" id="frm-add" enctype="multipart/form-data" >
                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><strong>{{$pageTitle}}</strong></div>
                            <div class="card-body card-block">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="frm-label">Tổng Giá Trị Đơn Hàng (₫)</label>
                                            <div><p>(Miễn phí giao hàng nếu bằng hoặc vượt qua)</p></div>
                                            <input value="{{$apiCore->getSetting('payment_ship_free_cart')}}" name="payment_ship_free_cart" type="text" autocomplete="off"
                                                   class="form-control number_format mb-2"
                                            />

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

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="frm-label">Thông báo khi có đơn đặt hàng</label>
                                            <input name="cart_booked_notify_to" value="{{$apiCore->getSetting('cart_booked_notify_to')}}" class="form-control" type="text" />
                                            <p>Các địa chỉ email cách nhau bởi dấu chấm phẩy [;]</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="frm-label">link chính sách tại trang giỏ hàng</label>
                                            <input name="link_cart_agree" value="{{$apiCore->getSetting('link_cart_agree')}}" class="form-control" type="text" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="frm-label">Lưu ý khi mua hàng</label>
                                            <textarea class="form-control" name="payment_account" rows="5" cols="5">{{$apiCore->getSetting('payment_account')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="frm-label">Ghi chú hóa đơn</label>
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

@stop
