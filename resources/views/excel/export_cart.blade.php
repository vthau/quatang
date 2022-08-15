<?php
$apiCore = new \App\Api\Core();
$apiMobile = new \App\Api\Mobile();
$isMobile = $apiMobile->isMobile();

$siteTitle = $apiCore->getSetting('site_title');
$hotline = $apiCore->getSetting('site_hotline');
$siteAddress = $apiCore->getSetting('site_address');
$note = $apiCore->getSetting('receipt_note');

$viewer = $apiCore->getViewer();

$cartName = (!empty($cart->name)) ? $cart->name : $cart->phone;
?>

<table>
    <tr>
        <td style="border: 1px solid #000000;text-align: center;" colspan="4" rowspan="2">THÔNG TIN ĐƠN HÀNG</td>
        <td style="border: 1px solid #000000;" colspan="4">Đơn hàng</td>
        <td style="border: 1px solid #000000;" colspan="4">{{$cart->href}}</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000000;" colspan="4">Ngày</td>
        <td style="border: 1px solid #000000;" colspan="4">{{date('d/m/Y', strtotime($cart->created_at))}}</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000000;text-align: center;text-transform: uppercase;" colspan="6">THÔNG TIN NGƯỜI BÁN</td>
        <td style="border: 1px solid #000000;text-align: center;text-transform: uppercase;" colspan="6">THÔNG TIN NGƯỜI MUA</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000000;" colspan="6">Nhà Cung Cấp: {{$siteTitle}}</td>
        <td style="border: 1px solid #000000;" colspan="6">Họ Tên: {{$cart->getOwner() ? $cart->getOwner()->name : $cart->phone}}</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000000;" colspan="6">Địa Chỉ: {{$siteAddress}}</td>
        <td style="border: 1px solid #000000;" colspan="6">Địa Chỉ: {{$cart->getFullAddress()}}</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000000;" colspan="6">Điện Thoại: {{$hotline}}</td>
        <td style="border: 1px solid #000000;" colspan="6">Điện Thoại: {{$cart->getOwner() ? $cart->getOwner()->phone : $cart->phone}}</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000000;text-align: center; font-weight: bold; text-transform: uppercase;" colspan="2">STT</td>
        <td style="border: 1px solid #000000;text-align: center; font-weight: bold; text-transform: uppercase;" colspan="4">Tên hàng</td>
        <td style="border: 1px solid #000000;text-align: center; font-weight: bold; text-transform: uppercase;" colspan="2">Số lượng</td>
        <td style="border: 1px solid #000000;text-align: center; font-weight: bold; text-transform: uppercase;" colspan="2">Giá</td>
        <td style="border: 1px solid #000000;text-align: center; font-weight: bold; text-transform: uppercase;" colspan="2">Thành tiền</td>
    </tr>
    <?php
    $stt = 0;
    $totalQuantity = 0;
    foreach($cart->getProducts() as $ite):
        $stt++;
        $product = $apiCore->getItem('product', $ite->product_id);
        $totalQuantity += $ite->quantity;
        ?>
    <tr>
        <td style="border: 1px solid #000000;text-align: center;" colspan="2">{{$stt}}</td>
        <td style="border: 1px solid #000000;" colspan="4">{{$product->title}}</td>
        <td style="border: 1px solid #000000;text-align: right;" colspan="2">{{$apiCore->numberCurrency($ite->quantity)}}</td>
        <td style="border: 1px solid #000000;text-align: right;" colspan="2">{{$apiCore->numberCurrency($ite->price_pay)}}</td>
        <td style="border: 1px solid #000000;text-align: right;" colspan="2">{{$apiCore->numberCurrency($ite->price_pay * $ite->quantity)}}</td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td style="border: 1px solid #000000;text-align: center;" colspan="6">Tổng tiền hàng:</td>
        <td style="border: 1px solid #000000;text-align: right;" colspan="6">{{$apiCore->numberCurrency($cart->total_cart)}}</td>
    </tr>
    @if($cart->total_discount > 0)
    <tr>
        <td style="border: 1px solid #000000;text-align: center;" colspan="6">Giảm giá:</td>
        <td style="border: 1px solid #000000;text-align: right;" colspan="6">{{$apiCore->numberCurrency($cart->total_discount)}}</td>
    </tr>
    @endif
    <tr>
        <td style="border: 1px solid #000000;text-align: center;" colspan="6">Phí giao hàng:</td>
        <td style="border: 1px solid #000000;text-align: right;" colspan="6">@if($cart->free_ship) 0 @else {{$apiCore->numberCurrency($cart->total_ship)}} @endif</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000000;text-align: center;" colspan="6">Tổng thanh toán:</td>
        <td style="border: 1px solid #000000;text-align: right;font-weight: bold;" colspan="6">{{$apiCore->numberCurrency($cart->total_price)}}</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000000;text-align: center;" colspan="6">Phương thức thanh toán:</td>
        <td style="border: 1px solid #000000;text-align: right;" colspan="6">{{$cart->getPaymentText()}}</td>
    </tr>
    <tr>
        <td style="border: 1px solid #000000;" colspan="12"><?php echo "Ghi Chú: " . nl2br($cart->note)?></td>
    </tr>
    @if (!empty($note))
        <tr>
            <td style="border: 1px solid #000000;" colspan="12"><?php echo nl2br($note)?></td>
        </tr>
    @endif
</table>

