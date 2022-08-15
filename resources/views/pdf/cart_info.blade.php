<?php
$apiCore = new \App\Api\Core();
$apiMobile = new \App\Api\Mobile();
$isMobile = $apiMobile->isMobile();

$siteTitle = $apiCore->getSetting('site_title');
$hotline = $apiCore->getSetting('site_hotline');
$siteAddress = $apiCore->getSetting('site_address');
$note = $apiCore->getSetting('receipt_note');
$siteLogo = $apiCore->getSetting('site_logo');

$viewer = $apiCore->getViewer();

$cartName = (!empty($cart->name)) ? $cart->name : $cart->phone;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ĐƠN HÀNG {{$cart->href}}</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link type="text/css" href="{{url('public/css/pdf.css')}}" rel="stylesheet">

    <style type="text/css">
        *{ font-family: DejaVu Sans !important; font-size: 11px;}

        table {
            border-collapse: collapse;
        }

        .table_main,
        .table_main td {
            border: 0.01em solid #000;
        }

        .table_sub,
        .table_sub td {
            border: none;
        }

        /*table {*/
        /*    border-left: 0.01em solid #000;*/
        /*    border-right: 0;*/
        /*    border-top: 0.01em solid #000;*/
        /*    border-bottom: 0;*/
        /*    border-collapse: collapse;*/
        /*}*/
        /*table td,*/
        /*table th {*/
        /*    border-left: 0;*/
        /*    border-right: 0.01em solid #000;*/
        /*    border-top: 0;*/
        /*    border-bottom: 0.01em solid #000;*/
        /*}*/
    </style>
</head>

<body>
<table width="100%" style="width:100%" class="table_main">
    <tr>
        <td>
            <table width="100%" style="width:100%;" class="table_sub">
                <tr>
                    <td style="width: 33.33%; border-right: 0.01em solid #000;" rowspan="2">
                        <div style="float: left; margin-right: 10px;"><img width="35" src="{{$siteLogo}}" /></div>
                        <div style="float: left; text-transform: uppercase;text-align: center; font-weight: bold;">thông tin <br/> đơn hàng</div>
                    </td>
                    <td style="width: 33.33%; border-right: 0.01em solid #000; border-bottom: 0.01em solid #000;">Đơn hàng</td>
                    <td style="width: 33.33%; border-bottom: 0.01em solid #000;">{{$cart->href}}</td>
                </tr>
                <tr>
                    <td style="width: 33.33%; border-right: 0.01em solid #000;">Ngày</td>
                    <td style="width: 33.33%;">{{date('d/m/Y', strtotime($cart->created_at))}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" style="width:100%" class="table_sub">
                <tr>
                    <td style="width: 50%; border-bottom: 0.01em solid #000; border-right: 0.01em solid #000;">
                        <div style="text-transform: uppercase;text-align: center; font-weight: bold;">thông tin người bán</div>
                    </td>
                    <td style="width: 50%; border-bottom: 0.01em solid #000;">
                        <div style="text-transform: uppercase;text-align: center; font-weight: bold;">thông tin người mua</div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%; border-right: 0.01em solid #000;">
                        <div>Nhà Cung Cấp: {{$siteTitle}}</div>
                    </td>
                    <td style="width: 50%">
                        <div>Họ Tên: {{$cart->getOwner() ? $cart->getOwner()->name : $cartName}}</div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%; border-right: 0.01em solid #000;">
                        <div>Địa Chỉ: {{$siteAddress}}</div>
                    </td>
                    <td style="width: 50%">
                        <div>Địa Chỉ: {{$cart->getFullAddress()}}</div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%; border-right: 0.01em solid #000;">
                        <div>Điện Thoại: {{$hotline}}</div>
                    </td>
                    <td style="width: 50%">
                        <div>Điện Thoại: {{$cart->getOwner() ? $cart->getOwner()->phone : $cart->phone}}</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" style="width:100%" class="table_sub">
                <tr>
                    <td style="vertical-align: middle; border-right: 0.01em solid #000; border-bottom: 0.01em solid #000;">
                        <div style="text-align: center; font-weight: bold; text-transform: uppercase;">STT</div>
                    </td>
                    <td style="vertical-align: middle; border-right: 0.01em solid #000; border-bottom: 0.01em solid #000;">
                        <div style="text-align: center; font-weight: bold; text-transform: uppercase;">Tên hàng</div>
                    </td>
                    <td style="vertical-align: middle; border-right: 0.01em solid #000; border-bottom: 0.01em solid #000;">
                        <div style="text-align: center; font-weight: bold; text-transform: uppercase;">Số lượng</div>
                    </td>
                    <td style="vertical-align: middle; border-right: 0.01em solid #000; border-bottom: 0.01em solid #000;">
                        <div style="text-align: center; font-weight: bold; text-transform: uppercase;">Giá</div>
                    </td>
                    <td style="vertical-align: middle;border-bottom: 0.01em solid #000;">
                        <div style="text-align: center; font-weight: bold; text-transform: uppercase;">Thành tiền</div>
                    </td>
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
                    <td style="vertical-align: middle; border-right: 0.01em solid #000; @if($stt != count($cart->getProducts())) border-bottom: 0.01em solid #000; @endif">
                        <div style="text-align: center;">{{$stt}}</div>
                    </td>
                    <td style="vertical-align: middle; border-right: 0.01em solid #000; @if($stt != count($cart->getProducts())) border-bottom: 0.01em solid #000; @endif">
                        <div>{{$product->title}}</div>
                    </td>
                    <td style="vertical-align: middle; text-align: center; border-right: 0.01em solid #000; @if($stt != count($cart->getProducts())) border-bottom: 0.01em solid #000; @endif">
                        <div>{{$apiCore->numberCurrency($ite->quantity)}}</div>
                    </td>
                    <td style="vertical-align: middle; text-align: right; border-right: 0.01em solid #000; @if($stt != count($cart->getProducts())) border-bottom: 0.01em solid #000; @endif">
                        <div>{{$apiCore->numberCurrency($ite->price_pay)}}</div>
                    </td>
                    <td style="vertical-align: middle; text-align: right; @if($stt != count($cart->getProducts())) border-bottom: 0.01em solid #000; @endif">
                        <div>{{$apiCore->numberCurrency($ite->price_pay * $ite->quantity)}}</div>
                    </td>
                </tr>
                <?php endforeach;?>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" style="width:100%" class="table_sub">
                <tr>
                    <td style="width: 33.33%; vertical-align: top; border-right: 0.01em solid #000;" @if($cart->total_discount > 0) rowspan="6" @else rowspan="5" @endif>
                        <div>Ghi Chú</div>
                        <div><?php echo nl2br($cart->note)?></div>
                    </td>
                    <td style="width: 33.33%; border-right: 0.01em solid #000; border-bottom: 0.01em solid #000;">
                        <div>Tổng tiền hàng:</div>
                    </td>
                    <td style="width: 33.33%; text-align: right; border-bottom: 0.01em solid #000;">
                        <div>{{$apiCore->numberCurrency($cart->total_cart)}}</div>
                    </td>
                </tr>
                @if($cart->total_discount > 0)
                <tr>
                    <td style="width: 33.33%; border-right: 0.01em solid #000; border-bottom: 0.01em solid #000;">
                        <div>Giảm giá đặc biệt:</div>
                    </td>
                    <td style="width: 33.33%; text-align: right; border-bottom: 0.01em solid #000;">
                        <div>{{$apiCore->numberCurrency($cart->total_discount)}}</div>
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="width: 33.33%; border-right: 0.01em solid #000; border-bottom: 0.01em solid #000;">
                        <div>Phí giao hàng:</div>
                    </td>
                    <td style="width: 33.33%; text-align: right; border-bottom: 0.01em solid #000;">
                        <div>{{$cart->free_ship ? 0 : $apiCore->numberCurrency($cart->total_ship)}}</div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 33.33%; border-right: 0.01em solid #000; border-bottom: 0.01em solid #000;">
                        <div>Tổng thanh toán:</div>
                    </td>
                    <td style="width: 33.33%; text-align: right; border-bottom: 0.01em solid #000;">
                        <div><b>{{$apiCore->numberCurrency($cart->total_price)}}</b></div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 33.33%; border-right: 0.01em solid #000; border-bottom: 0.01em solid #000;">
                        <div>Phương thức thanh toán:</div>
                    </td>
                    <td style="width: 33.33%; text-align: right; border-bottom: 0.01em solid #000;">
                        <div style="text-transform: uppercase; font-weight: bold; font-size: 9px;">{{$cart->getPaymentText()}}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>(*) Giá trên đã bao gồm thuế GTGT</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @if (!empty($note))
        <tr>
            <td><?php echo nl2br($note)?></td>
        </tr>
    @endif
</table>
</body>
</html>
