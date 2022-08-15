<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile() ? 1 : 0;

$paymentAccount = $apiCore->getSetting('payment_account');

$pdfLink = url('/dh/pdf/' . $cart->id);
$excelLink = url('/dh/excel/' . $cart->id);
?>

@extends('templates.ttv.master')

@section('content')
    <style type="text/css">
        .price .number_format {
            font-size: 18px;
        }

        .cart_countdown {
            display: inline-block;
            margin-bottom: 30px;
            background-color: #fcb800;
            font-size: 15px;
            font-weight: 500;
            border-radius: 4px;
        }

        .cart_countdown.dn {
            display: none !important;
        }
    </style>

    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop')
        </div>
    </div>

    <div id="shopify-section-cart-template" class="shopify-section cart_page_section container mb__60">
        @include('modals.breadcrumb', [
            'text1' => 'đơn hàng ' . $cart->href,
            'pdfLink' => $pdfLink,
            'excelLink' => $excelLink,
        ])

        <div class="cart_header mb__20">
            <div class="row al_center">
                <div class="col-5 text-uppercase frm-label">sản phẩm</div>
                @if (!$isMobile)
                    <div class="col-3 text-uppercase frm-label tc">giá</div>
                    <div class="col-2 text-uppercase frm-label tc">số lượng</div>
                    <div class="col-2 text-uppercase frm-label tc tr_md">thành tiền</div>
                @endif
            </div>
        </div>

        <div class="cart_items">
            <?php
            foreach ($products as $p):
            $product = $apiCore->getItem('product', $p->product_id);
            ?>

            @if ($isMobile)
                <table class="table">
                    <tr>
                        <td colspan="3" class="clearfix pr">
                            <div class="float-left mr__10">
                                <a href="{{$product->getHref(true)}}">
                                    <img class="width_height_80" src="{{$product->getAvatar()}}" />
                                </a>
                            </div>
                            <div class="overflow-hidden">
                                <a href="{{$product->getHref(true)}}">
                                    {{$product->getTitle()}}
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <div class="frm-label text-uppercase fs-12">đơn giá</div>
                        </td>
                        <td class="text-center">
                            <div class="frm-label text-uppercase fs-12">số lượng</div>
                        </td>
                        <td class="text-center">
                            <div class="frm-label text-uppercase fs-12">thành tiền</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <div class="cart_meta_prices price">
                                <div class="cart_price">
                                    @if ($p->price_pay!= $p->price_main)
                                        <del class="price_old">
                                            <span class="number_format">{{$p->price_main}}</span>
                                            <span class="currency_format">₫</span>
                                        </del>
                                    @endif
                                    <strong class="fs-16">
                                        <span class="number_format">{{$p->price_pay}}</span>
                                        <span class="currency_format">₫</span>
                                    </strong>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="fs-18 text-bold">{{$p->quantity}}</div>
                        </td>
                        <td class="text-center">
                            <span class="cart-item-price price fwm cd">
                                <strong class="color-money fs-16">
                                    <span class="number_format one_total">{{$p->quantity*$p->price_pay}}</span>
                                    <span class="currency_format">₫</span>
                                </strong>
                            </span>
                        </td>
                    </tr>
                </table>
            @else
                <div class="cart_item cart_item_32289063698513 c_ite" id="c_ite_{{$product->id}}" data-id="{{$product->id}}">
                    <div class="ld_cart_bar"></div>
                    <div class="row al_center mb__10">
                        <div class="col-12 col-md-12 col-lg-5">
                            <div class="page_cart_info flex al_center">
                                <a href="{{$product->getHref(true)}}">
                                    <img
                                        class="w__100 lz_op_ef lazyautosizes lazyloaded width_height_80"
                                        src="{{$product->getAvatar()}}"
                                        data-widths="[120, 240]" data-sizes="auto" alt=""
                                        data-srcset="{{$product->getAvatar()}}"
                                        sizes="120px"
                                        srcset="{{$product->getAvatar()}}">
                                </a>
                                <div class="mini_cart_body ml__15">
                                    <h5 class="mini_cart_title mg__0 mb__5">
                                        <a href="{{$product->getHref(true)}}">{{$product->getTitle()}}</a>
                                    </h5>
                                    <div class="mini_cart_meta"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3 tc">
                            <div class="cart_meta_prices price">
                                <div class="cart_price">
                                    @if ($p->price_pay!= $p->price_main)
                                        <del class="price_old">
                                            <span class="number_format">{{$p->price_main}}</span>
                                            <span class="currency_format">₫</span>
                                        </del>
                                    @endif
                                    <strong class="fs-16">
                                        <span class="number_format">{{$p->price_pay}}</span>
                                        <span class="currency_format">₫</span>
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-2 tc fs-15">
                            <b class="fs-18">{{$p->quantity}}</b>
                        </div>
                        <div class="col-12 col-md-4 col-lg-2 tc tr_lg">
                        <span class="cart-item-price price fwm cd">
                            <strong class="color-money fs-16">
                                <span class="number_format one_total">{{$p->quantity*$p->price_pay}}</span>
                                <span class="currency_format">₫</span>
                            </strong>
                        </span>
                        </div>
                    </div>
                </div>
            @endif
            <?php endforeach; ?>
        </div>

        <div class="cart__footer mt__20 mb__80">
            <div class="row">
                <div class="col-12 col-md-6 tc order-md-2 order-2 mb__10">
                    <table class="table margin-0">
                        <thead>
                        <tr>
                            <th colspan="2" class="frm-label text-center">thông tin đơn hàng</th>
                        </tr>
                        </thead>
                        <tbody>
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
                                        <div>ĐT: <a href="tel:{{$cart->phone}}">{{$cart->phone}}</a></div>
                                        @if ($cart->email)
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
                        </tbody>
                    </table>
                </div>

                @if ($isMobile)
                    <div class="col-12 col-md-6 tc order-md-4 order-1 mb__10">
                        <table class="table">
                            <tr>
                                <td>
                                    <label class="frm-label">tổng tiền hàng:</label>
                                </td>
                                <td class="text-right">
                                    <strong class="text-black fs-18">
                                        <span class="number_format">{{$cart->total_cart}}</span>
                                        <span class="currency_format">₫</span>
                                    </strong>
                                </td>
                            </tr>
                            @if($cart->total_discount > 0)
                            <tr>
                                <td>
                                    <label class="frm-label">giảm giá:</label>
                                </td>
                                <td class="text-right">
                                    <strong class="text-black fs-18">
                                        <span class="number_format">{{$cart->total_discount}}</span>
                                        <span class="currency_format">₫</span>
                                    </strong>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>
                                    <label class="frm-label">phí giao hàng:</label>
                                </td>
                                <td class="text-right">
                                    <strong class="text-black fs-18">
                                        <span class="number_format @if($cart->free_ship) line_through @endif">{{$cart->total_ship}}</span>
                                        <span class="currency_format">₫</span>
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="frm-label">tổng thanh toán:</label>
                                </td>
                                <td class="text-right">
                                    <strong class="color-money fs-18">
                                        <span class="number_format text-bold">{{$cart->total_price}}</span>
                                        <span class="currency_format">₫</span>
                                    </strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                @else
                    <div class="col-12 col-md-6 tc order-md-4 order-1 mb__10">
                        <div class="row text-right">
                            <div class="col-md-9 mb__10 text-right">
                                <label class="frm-label">tổng tiền hàng:</label>
                            </div>
                            <div class="col-md-3 mb__10 text-right">
                                <strong class="text-black fs-18">
                                    <span class="number_format">{{$cart->total_cart}}</span>
                                    <span class="currency_format">₫</span>
                                </strong>
                            </div>
                        </div>
                        @if($cart->total_discount > 0)
                        <div class="row text-right">
                            <div class="col-md-9 mb__10 text-right">
                                <label class="frm-label">giảm giá:</label>
                            </div>
                            <div class="col-md-3 mb__10 text-right">
                                <strong class="text-black fs-18">
                                    <span class="number_format">{{$cart->total_discount}}</span>
                                    <span class="currency_format">₫</span>
                                </strong>
                            </div>
                        </div>
                        @endif
                        <div class="row text-right">
                            <div class="col-md-9 mb__10 text-right">
                                <label class="frm-label">phí giao hàng:</label>
                            </div>
                            <div class="col-md-3 mb__10 text-right">
                                <strong class="text-black fs-18">
                                    <span class="number_format @if($cart->free_ship) line_through @endif">{{$cart->total_ship}}</span>
                                    <span class="currency_format">₫</span>
                                </strong>
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-md-9 mb__10 text-right">
                                <label class="frm-label">tổng thanh toán:</label>
                            </div>
                            <div class="col-md-3 mb__10 text-right">
                                <strong class="color-money fs-18">
                                    <span class="number_format text-bold">{{$cart->total_price}}</span>
                                    <span class="currency_format">₫</span>
                                </strong>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@stop
