<?php
$apiCore = new \App\Api\Core();
$apiMobile = new \App\Api\Mobile();
$isMobile = $apiMobile->isMobile();

$viewer = $apiCore->getViewer();

?>

<table border="1">
    <tr>
        <th>Tên</th>
        <th>Điện thoại</th>
        <th>Email</th>
        <th>Thời gian đặt đơn hàng đầu tiên</th>
    </tr>
    @if (count($items))
    <?php foreach ($items as $client) :
    ?>
        <tr>
            <td>{{$client->name}}</td>
            <td>{{$client->phone}}</td>
            <td>{{$client->email}}</td>
            <td>{{$client->getFirstTimeCart()}}</td>
        </tr>
    <?php endforeach; ?>
    @endif
</table>
