<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$apiFE = new \App\Api\FE;

$stt = 0;
foreach($carts as $cart):
$stt++;

$dates = $apiFE->getDates(['person_id' => $cart->user_person_id]);
?>
<tr id="cart_{{$cart->id}}">
    <td class="text-center">{{$stt}}</td>
    <td class="long_td_sm">
        <div>{{date('d/m/Y', strtotime($cart->created_at))}}</div>
        <div>{{date('H:i:s', strtotime($cart->created_at))}}</div>
    </td>
    <td class="long_td_mid"><?php echo $cart->toHTML(['href' => true]);?></td>
    <td class="align-center long_td_sm">{{$cart->total_quantity}}</td>
    <td class="align-center text-bold text-primary long_td_mid">
        <span class="number_format">{{$cart->total_cart}}</span>
        <span class="currency_format">₫</span>
    </td>
    <td class="align-center text-bold long_td_mid hidden">
        @if ($cart->total_discount > 0)
            <span class="number_format">{{$cart->total_discount}}</span>
            <span class="currency_format">₫</span>
        @endif
    </td>
    <td class="align-center long_td_mid ">
        <div class="text-bold @if($cart->free_ship) line_through @endif">
            <span class="number_format">{{$cart->total_ship ? $cart->total_ship : 0}}</span>
            <span class="currency_format">₫</span>
        </div>
        @if($cart->free_ship)
            <div>
                <span class="badge badge-danger text-uppercase">free ship</span>
            </div>
        @endif

    </td>
    <td class="align-center text-danger text-bold long_td_mid">
        <span class="number_format">{{$cart->total_price}}</span>
        <span class="currency_format">₫</span>
    </td>
    <td class="align-center frm-label long_td_mid">{{$cart->getPaymentText()}}</td>
    <td class="align-center frm-label long_td_mid text-success">{{$cart->getGhnStatus()}}</td>
    <td>
        @if($cart->getPerson() && $cart->getPerson()->isDeleted())
            <div>{{$cart->getPerson()->getTitle()}}</div>
            @if($cart->getPersonDate())
                <div>{{$cart->getPersonDate()->getTitle()}}</div>
            @endif
        @elseif($viewer->id == $cart->user_id && count($persons))
            <div class="">
                <select class="form-control" name="person_id" onchange="jskhcartchangeperson(this, {{$cart->id}})">
                    <option value="0">Không Tặng Cho Ai</option>
                    @if(count($persons))
                        @foreach($persons as $ite)
                            <option @if($cart->user_person_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->getRelationship() ? $ite->getRelationship()->getTitle() . ": " . $ite->getTitle() : $ite->getTitle()}}</option>
                        @endforeach
                    @endif
                </select>
                <select class="form-control mt__10 @if(!$cart->getPersonDate()) hidden @endif" name="date_id" onchange="jskhcartchangepersondate(this, {{$cart->id}})">
                    @if(count($dates))
                        @foreach($dates as $ite)
                            <option @if($cart->user_person_date == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->getTitle() . ' - ' . $ite->day . ' / ' . $ite->month}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        @else

        @endif
    </td>
    <td class="long_td_mid text-center table_item ">
        <div class="align-center">
            <button class="btn btn-danger mb-1"
                    onclick="gotoPage('{{url('/dh/pdf/' . $cart->id)}}')"
            >
                <i class="fa fa-file-pdf"></i>
            </button>
            <button class="btn btn-success mb-1"
                    onclick="gotoPage('{{url('/dh/excel/' . $cart->id)}}')"
            >
                <i class="fa fa-file-excel"></i>
            </button>
        </div>
    </td>
</tr>
<?php endforeach;?>
