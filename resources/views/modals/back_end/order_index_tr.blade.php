<?php
$pageTitle = (isset($page_title)) ? $page_title : "";

$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();

$apiFE = new \App\Api\FE;
?>

<td>{{date('d-m-Y H:i:s', strtotime($order->created_at))}}</td>
<td>
    <div>
        <?php echo $order->toHTML(['href' => true]);?>
    </div>
    @if (!empty($order->ghn_code))
        <div>{{$order->ghn_code}}</div>
    @endif
</td>
<td>
    @if ($order->getOwner())
        <div class="text-capitalize">
            <?php echo $order->getOwner()->toHTML(['href' => true]);?>
        </div>
        <div>
            <div>ĐT: <a href="tel:{{$order->getOwner()->phone}}">{{$order->getOwner()->phone}}</a></div>
        </div>
    @else
        <div>
            @if (!empty($order->name))
                <div>{{$order->name}}</div>
            @endif
            <div>ĐT: <a href="tel:{{$order->phone}}">{{$order->phone}}</a></div>
            @if (!empty($order->email))
                <div><a class="text-lowercase" href="mailto:{{$order->email}}">{{$order->email}}</a></div>
            @endif
        </div>
    @endif
</td>
<td class="align-center font-weight-bold">{{$order->total_quantity}}</td>
<td class="align-center">
    <span class="money_format font-weight-bold">{{$order->total_cart}}</span>
    <span class="currency_format">₫</span>
</td>
<td class="align-center hidden">
    @if ($order->total_discount > 0)
        <div>
            <span class="money_format font-weight-bold">{{$order->total_percent}}</span>
            <span>%</span>
        </div>
        <div>
            <span class="money_format font-weight-bold">{{$order->total_discount}}</span>
            <span class="currency_format">₫</span>
        </div>
    @endif
</td>
<td class="align-center">
    <div>
        <span class="money_format font-weight-bold @if ($order->free_ship) line_through @endif">{{$order->total_ship}}</span>
        <span class="currency_format">₫</span>
    </div>
    @if ($order->free_ship)
        <div>
            <div class="badge badge-danger text-fff text-uppercase">miễn phí ship</div>
        </div>
    @endif
</td>
<td class="align-center">
    <span class="money_format font-weight-bold">{{$order->total_price}}</span>
    <span class="currency_format">₫</span>
</td>
<td class="align-center">
    <div>
        <div class="badge badge-primary text-fff text-uppercase">{{$order->getPaymentText()}}</div>
    <!--
        <select onchange="updateStatus(this, 'payment_by')" data-id="{{$order->id}}" class="form-control select2-single select2-hidden-accessible">
            <option <?php if ($order->payment_by == "cod"):?>selected="selected"<?php endif;?> value="cod">Tiền Mặt Khi Nhận Hàng</option>
            <option <?php if ($order->payment_by == "banking"):?>selected="selected"<?php endif;?> value="banking">Thanh Toán Online</option>
        </select>
        -->
    </div>
    <div class="mt-2">
    @if (!$order->confirm_success)
        <!--
            <div class="badge badge-warning text-fff text-uppercase">chưa thanh toán</div>
            -->
            <button onclick="confirmOrder({{$order->id}}, 'status', 1)" class="btn btn-warning fs-10 btn-sm font-weight-bold text-uppercase text-white">xác nhận thanh toán</button>

    @else
        <!--
            <div class="badge badge-success text-fff text-uppercase">đã thanh toán</div>
            -->
            <button onclick="confirmOrder({{$order->id}}, 'status', 0)" class="btn btn-success fs-10 btn-sm font-weight-bold text-uppercase text-white">đã thanh toán</button>

        @endif
    </div>
</td>
<td class="text-center">
    @if ($viewer->isAllowed('order_shipment'))
        <select name="shipping_status" class="form-control" onchange="updateStatus(this, {{$order->id}})">
            @foreach($apiFE->getShippingStatus() as $k => $v)
                <option @if($order->shipping_status == $k) selected="selected" @endif value="{{$k}}">{{$v}}</option>
            @endforeach
        </select>
    @else
        <div>
            <div class="badge badge-info text-fff text-uppercase">{{$order->getGhnStatus()}}</div>
        </div>
    @endif

    {{--    @if ($viewer->isAllowed('order_manual') && $order->shipping_status == 'delivering' && empty($order->ghn_code))--}}
    {{--    <div class="mt-2">--}}
    {{--        <button onclick="confirmShipped({{$order->id}})" class="btn btn-secondary fs-10 btn-sm font-weight-bold text-uppercase ">xác nhận giao hàng</button>--}}
    {{--    </div>--}}
    {{--    @endif--}}
</td>
<td>
    <div class="align-right">
        {{--        @if ($viewer->isAllowed('order_ghn'))--}}
        {{--            @if (empty($order->ghn_code) && !in_array($order->shipping_status, ['delivering', 'delivered']))--}}
        {{--                <button class="btn btn-warning"--}}
        {{--                        title="GHN" data-original-title="GHN"--}}
        {{--                        onclick="giaoHang('{{$order->id}}')"--}}
        {{--                >--}}
        {{--                    <i class="fa fa-truck"></i>--}}
        {{--                </button>--}}
        {{--            @endif--}}
        {{--        @endif--}}

        {{--        @if ($viewer->isAllowed('order_manual'))--}}
        {{--            @if (empty($order->ghn_code) || (in_array($order->shipping_status, ['cancel', 'returned'])))--}}
        {{--                <button class="btn btn-primary"--}}
        {{--                        title="Tự Vận Đơn" data-original-title="Tự Vận Đơn"--}}
        {{--                        data-fee="{{$order->total_ship}}"--}}
        {{--                        data-note="{{$order->note_manual}}"--}}
        {{--                        onclick="tuVanDon('{{$order->id}}', this)"--}}
        {{--                >--}}
        {{--                    <i class="fa fa-hand-paper"></i>--}}
        {{--                </button>--}}
        {{--            @endif--}}
        {{--        @endif--}}

        <button class="btn btn-info"
                title="PDF" data-original-title="PDF"
                onclick="gotoPage('{{url('/dh/pdf/' . $order->id)}}')"
        >
            <i class="fa fa-file-pdf"></i>
        </button>
        <button class="btn btn-success"
                title="Excel" data-original-title="Excel"
                onclick="gotoPage('{{url('/dh/excel/' . $order->id)}}')"
        >
            <i class="fa fa-file-excel"></i>
        </button>

        @if ($viewer->isAllowed('order_delete'))
            <button class="btn btn-danger"
                    title="Xóa" data-original-title="Xóa"
                    onclick="deleteItem({{$order->id}})"
            >
                <i class="fa fa-trash"></i>
            </button>
        @endif
    </div>
</td>
