<?php
$apiCore = new \App\Api\Core();
$viewer = $viewer ? $viewer : $apiCore->getViewer();

$count = 0;

foreach($carts as $cart):
$count++;

$tiLe = ($viewer->id == $cart->refer_id) ? $cart->refer_percent + 0 : $cart->parent_refer_percent + 0;
$hoaHong = ($viewer->id == $cart->refer_id) ? $cart->refer_money : $cart->parent_refer_money;
$loaiHH = ($viewer->id == $cart->refer_id) ? 'tư vấn' : 'phát triển hệ thống';

$trangThai = ($cart->status == 'da_thanh_toan') ? 'đã thanh toán' : 'chờ xác nhận';
?>
<tr role="row" class="@if($count%2 != 0) odd @else even @endif">
    <td class="text-center">{{$count}}</td>
    <td class="text-center">{{date('d/m/Y H:i:s', strtotime($cart->created_at))}}</td>
    <td class="text-center">
        <a href="{{$cart->getHref(true)}}">{{$cart->href}}</a>
    </td>
    <td class="text-center">
        <div class="number_format">{{$cart->total_cart}}</div>
    </td>
    <td class="text-center">
        <div class="money_format">{{$tiLe}}</div>
    </td>
    <td class="text-center">
        <div class="number_format">{{$hoaHong}}</div>
    </td>
    <td class="text-center">
        <div class="text-uppercase fs-12">{{$loaiHH}}</div>
    </td>
    <td class="text-center">
        <div class="text-uppercase fs-12">{{$trangThai}}</div>
    </td>
    <td class="text-center">
        @if ($cart->getOwner())
            <div>{{$cart->getOwner()->getTitle()}}</div>
            @if (!empty($cart->getOwner()->phone))
                <div>
                    <a href="tel:{{$cart->getOwner()->phone}}">{{'ĐT: ' . $cart->getOwner()->phone}}</a>
                </div>
            @endif
            @if (!empty($cart->getOwner()->email))
                <div>
                    <a href="mailto:{{$cart->getOwner()->email}}">{{$cart->getOwner()->email}}</a>
                </div>
            @endif
        @else
            @if (!empty($cart->name))
                <div>{{$cart->name}}</div>
            @endif
                @if (!empty($cart->phone))
                    <div>
                        <a href="tel:{{$cart->phone}}">{{'ĐT: ' . $cart->phone}}</a>
                    </div>
                @endif
            @if (!empty($cart->email))
                <div>
                    <a href="mailto:{{$cart->email}}">{{$cart->email}}</a>
                </div>
            @endif
        @endif
    </td>
</tr>
<?php endforeach;?>
