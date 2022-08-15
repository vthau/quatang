<?php
$apiCore = new \App\Api\Core();
$apiMobile = new \App\Api\Mobile();
$isMobile = $apiMobile->isMobile();

$viewer = $apiCore->getViewer();

?>

<table border="1">
    <tr>
        <th style="width: 5px;"><b>STT</b></th>
        <th style="width: 15px;"><b>THỜI GIAN</b></th>
        <th style="width: 15px;"><b>MÃ<br />ĐƠN HÀNG</b></th>
        <th style="width: 25px;"><b>TÊN KHÁCH HÀNG</b></th>
        <th style="width: 25px;"><b>ĐIỆN THOẠI</b></th>
        <th style="width: 25px;"><b>ĐỊA CHỈ NHẬN HÀNG</b></th>
        <th style="width: 25px;"><b>GHI CHÚ</b></th>
        <th style="width: 25px;"><b>CÂU CHÚC</b></th>
        <th style="width: 15px;"><b>TỔNG<br />TIỀN HÀNG</b></th>
        <th style="width: 15px;"><b>PHÍ<br />GIAO HÀNG</b></th>
        <th style="width: 15px;"><b>TỔNG<br />THANH TOÁN</b></th>
        <th style="width: 25px;"><b>PHƯƠNG THỨC<br />THANH TOÁN</b></th>
        <th style="width: 25px;"><b>TRẠNG THÁI<br />ĐƠN HÀNG</b></th>

    </tr>
    @if (count($items))
    <?php
    $stt = 0;
    foreach ($items as $order) :
        $stt++;
    ?>
        <tr>
            <td style="text-align: center;">{{$stt}}</td>
            <td>
                <div>{{date('d-m-Y', strtotime($order->created_at))}}</div>
                <br />
                <div>{{date('H:i:s', strtotime($order->created_at))}}</div>
            </td>
            <td>{{$order->href}}</td>
            <td>
                @if ($order->getOwner())
                {{$order->getOwner()->getTitle()}}
                @endif
            </td>
            <td>
                @if ($order->getOwner())
                {{$order->getOwner()->phone}}
                @endif
            </td>
            <td>
                {{$order->getFullAddress()}}
            </td>
            <td>{{$order->note}}</td>
            <td>{{$order->text_wish}}</td>
            <td style="text-align: right;">{{$apiCore->numberToExcel($order->total_cart)}}</td>
            <td style="text-align: right;">{{$apiCore->numberToExcel($order->total_ship)}}</td>
            <td style="text-align: right;">{{$apiCore->numberToExcel($order->total_price)}}</td>
            <td>{{$order->getPaymentText()}}</td>
            <td>{{$order->getStatus()}}</td>
        </tr>
    <?php endforeach; ?>
    @endif
</table>
