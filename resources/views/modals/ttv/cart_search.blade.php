<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiFE = new \App\Api\FE();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

if (!isset($cart) || !$cart) {
    return;
}
?>
<div class="pr overflow-hidden mb__20" data-id="{{$cart->id}}">
    <table class="table margin_auto" @if(!$isMobile) style="width: 50%;" @endif>
        <tr>
            <td colspan="2" class="text-center">
                <div>
                    <a class="frm-label text-uppercase" href="{{$cart->getHref(true)}}">{{'đơn hàng ' . $cart->href}}</a>
                    <p class="text-italic">(click vào mã đơn hàng để xem chi tiết)</p>
                </div>
            </td>
        </tr>
        <tr>
            <td class="width-25 frm-label">khách hàng</td>
            <td class="pr clearfix">
                <div class="float-right ml__10">
                    @if ($cart->getOwner())
                        @if ($cart->getOwner()->doiTacHopLe())
                            @if ($cart->getOwner()->doi_tac_dac_biet)
                                <img width="20" src="{{url('public/images/star_full.png')}}" />
                                <img width="20" src="{{url('public/images/star_full.png')}}" />
                                <img width="20" src="{{url('public/images/star_full.png')}}" />
                            @else
                                <img width="20" src="{{url('public/images/star_full.png')}}" />
                                <img width="20" src="{{url('public/images/star_full.png')}}" />
                                <img width="20" src="{{url('public/images/star_empty.png')}}" />
                            @endif
                        @else
                            <img width="20" src="{{url('public/images/star_full.png')}}" />
                            <img width="20" src="{{url('public/images/star_empty.png')}}" />
                            <img width="20" src="{{url('public/images/star_empty.png')}}" />
                        @endif
                    @else
                        <img width="20" src="{{url('public/images/star_empty.png')}}" />
                        <img width="20" src="{{url('public/images/star_empty.png')}}" />
                        <img width="20" src="{{url('public/images/star_empty.png')}}" />
                    @endif
                </div>
                <div class="overflow-hidden">
                    @if ($cart->getOwner())
                        <div>
                            <div class="text-uppercase">{{$cart->getOwner()->getTitle()}}</div>
                            <div>ĐT: <a href="tel:{{$cart->getOwner()->phone}}">{{$cart->getOwner()->phone}}</a></div>
                        </div>
                    @else
                        <div>
                            @if (!empty($cart->name))
                                <div>{{$cart->name}}</div>
                            @endif
                            <div>ĐT: <a href="tel:{{$cart->phone}}">{{$cart->phone}}</a></div>
                            @if (!empty($cart->email))
                                <div><a href="mailto:{{$cart->email}}">{{$cart->email}}</a></div>
                            @endif
                        </div>
                    @endif
                </div>
            </td>
        </tr>
        <tr>
            <td class="frm-label">địa chỉ nhận hàng</td>
            <td>
                <div>{{$cart->getFullAddress()}}</div>
            </td>
        </tr>
        <tr>
            <td class="frm-label">phương thức thanh toán</td>
            <td>
                <div class="text-uppercase">{{$cart->getPaymentText()}}</div>
            </td>
        </tr>
        @if(!empty($cart->ghn_code))
            <tr>
                <td class="frm-label">mã vận đơn</td>
                <td>
                    <div class="text-uppercase">{{$cart->ghn_code}}</div>
                </td>
            </tr>
        @endif
        @if(!empty($cart->expected_delivery_time))
            <tr>
                <td class="frm-label">dự kiến giao hàng</td>
                <td>
                    <div class="text-danger text-bold">{{date('d/m/Y', strtotime($cart->expected_delivery_time))}}</div>
                </td>
            </tr>
        @endif
        <tr>
            <td class="frm-label">trạng thái</td>
            <td>
                <div class="text-success text-bold text-uppercase">{{$cart->getGhnStatus()}}</div>
            </td>
        </tr>
        <tr>
            <td class="frm-label">ghi chú</td>
            <td>
                <div><?php echo nl2br($cart->note)?></div>
            </td>
        </tr>
    </table>
</div>
