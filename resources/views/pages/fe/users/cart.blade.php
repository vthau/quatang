<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile() ? 1 : 0;

$apiFE = new \App\Api\FE;
$provinces = $apiFE->getProvinces();
$districts = [];
$wards = [];

$paymentAccount = $apiCore->getSetting('payment_account');

$overFee = $apiCore->parseToInt($apiCore->getSetting('payment_ship_free_cart'));
$freeShip = false;

$priceAll = 0;
$totalQuantity = 0;

$ids = "";

$discount = 0;
$countCarts = 0;
if ($viewer) {
    $countCarts = count($viewer->getAllCarts());

    $districts = $apiFE->getDistrictsByProvinceId($viewer->province_id);
    $wards = $apiFE->getWardsByDistrictId($viewer->district_id);
}

$URLCart = $apiCore->getSetting('link_cart_agree');
if (empty($URLCart)) {
    $URLCart = url('chinh-sach-thanh-toan');
}


?>

@extends('templates.ttv.master')

@section('content')
    <style type="text/css">
        .mfp-ajax-holder .mfp-content, .mfp-inline-holder .mfp-content {
            width: auto;
        }
        @if ($isMobile)
        .mfp-content {
            position: absolute;
            left: 0;
            top: 50%;
            margin-top: -150px;
        }
        @endif

        .mini_cart_actions {
            margin-top: 0;
        }

        #shopify-section-cart-template input[type=email],
        #shopify-section-cart-template input[type=text] {
            height: 35px;
            border-radius: 5px !important;
            color: #000 !important;
        }

        textarea {
            border-radius: 5px !important;
            color: #000 !important;
        }

        .price .number_format {
            @if($isMobile)
            font-size: 16px;
            @else
            font-size: 18px;
            @endif
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

        .height_80px {
            height: 80px;
        }
    </style>

    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop')
        </div>
    </div>

    <div id="shopify-section-cart-template" class="shopify-section cart_page_section container mb__60">
        @include('modals.breadcrumb', [
           'text1' => 'giỏ hàng',
        ])

        @if (!count($products))
        <div class="empty_cart_page tc" style="margin-top: 20px !important;">
            @if ($saved)
                <?php $cart = $apiCore->getItem('cart', $saved);
                ?>
                <style type="text/css">
                    .empty_cart_page>i:after {
                        display: none;
                    }

                </style>
                <i class="fa fa-check text-success mb__30 fs__90"></i>
                <h4 class="cart_page_heading mg__0 mb__20 tu fs__30 text-success">đặt hàng thành công!!!</h4>
                @if (!empty($cart->expected_delivery_time))
                <div class="cart_page_txt frm-label fs-16 text-danger mb__10 text-bold">Thời gian giao hàng dự kiến: {{date('d/m/Y', strtotime($cart->expected_delivery_time))}}</div>
                @endif
                <div class="cart_page_txt text-bold">Cảm ơn bạn đã lựa chọn sản phẩm và tin tưởng chúng tôi.</div>
            @else
                <i class="las la-shopping-bag pr mb__30 fs__90"></i>
                <h4 class="cart_page_heading mg__0 mb__20 tu fs__30">Giỏ hàng trống!!!</h4>
                <div class="cart_page_txt">Hãy chọn những sản phẩm tốt nhất của chúng tôi với giá hợp lí nhất.</div>
                @if ($days)
                    <div class="cart_page_txt">Chúng tôi xin phép sẽ gửi thông báo nhắc đơn hàng tiếp theo đến bạn sau {{$days}} ngày nữa.</div>
                @endif
            @endif
            <div class="mt__30"></div>
            <p class="mb__15"><a class="button button_primary tu " href="{{url('/')}}">trở lại trang chủ</a>
            </p>
        </div>
        @else

        <form action="" method="post" class="frm_cart_page pr oh" autocomplete="off" id="frm-cart">
            @csrf
            <div class="cart_header">
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
                $sale = null;

                if ($viewer) {
                    $product = $apiCore->getItem('product', $p->product_id);

                    $quantity = $p->quantity;
                    $priceMain = $p->price_main;
                    $priceOne = $p->price_pay;
                    $priceDiscount = $p->discount;
                    if ($p->sale_id) {
                        $sale = \App\Model\ProductSale::find($p->sale_id);
                        $priceDiscount = $p->sale_discount;
                        if ($priceDiscount) {
                            $priceOne = $priceMain - (int)($priceMain * $priceDiscount / 100);
                        } else {
                            $priceOne = $priceMain;
                        }
                    }


                } else {
                    $product = $p;

                    $cartQty = (Session::get('USR_CART_QTY'));

                    $quantity = 1;
                    if ($cartQty && count($cartQty) && isset($cartQty[$product->id])) {
                        $quantity = (int)$cartQty[$product->id];
                    }

                    $priceMain = $product->price_main;
                    $priceOne = $product->price_pay;
                    $priceDiscount = $product->discount;
                }

                $priceRow = $priceOne * $quantity;
                $priceAll += $priceRow;
                $totalQuantity += $quantity;
                $ids .= $product->id . "_" . $quantity . ";";

                //shipping
                if ($overFee > 0 && $priceAll >= $overFee) {
                    //free
                    $freeShip = true;
                } else {
                    $priceAll = $priceAll + $shippingFee;
                }

                ?>
                @if ($isMobile)
                    <table class="table mb__10 cart_item c_ite" id="c_ite_{{$product->id}}" data-id="{{$product->id}}">
                        <tr>
                            <td colspan="3" class="clearfix pr">
                                <div class="float-left mr__10">
                                    <a href="{{$product->getHref(true)}}">
                                        <img class="width_height_80" src="{{$product->getAvatar()}}" />
                                    </a>
                                </div>
                                <div class="overflow-hidden height_80px pr">
                                    <a href="{{$product->getHref(true)}}">
                                        {{$product->getTitle()}}
                                    </a>
                                    <div class="pa" style="bottom: 0; right: 0;">
                                        <a href="javascript:void(0)" onclick="jscartdhx({{$product->id}});jscartdhvalid(false);"
                                           class="cart_ac_remove ttip_nt tooltip_top_right"
                                        >
                                            <span class="tt_txt">Xóa khỏi giỏ hàng</span>
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                                 stroke-width="2" fill="none" stroke-linecap="round"
                                                 stroke-linejoin="round" class="css-i6dzq1">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 33.33%;">
                                <div class="frm-label text-uppercase fs-12">đơn giá</div>
                            </td>
                            <td class="text-center" style="width: 33.33%;">
                                <div class="frm-label text-uppercase fs-12">số lượng</div>
                            </td>
                            <td class="text-center" style="width: 33.33%;">
                                <div class="frm-label text-uppercase fs-12">thành tiền</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 33.33%;">
                                <div class="cart_meta_prices price">
                                    <div class="cart_price">
                                        @if ($priceOne!= $priceMain)
                                            <del class="price_old">
                                                <span class="number_format">{{$priceMain}}</span>
                                                <span class="currency_format">₫</span>
                                            </del>
                                        @endif
                                        <strong class="fs-16">
                                            <span class="number_format">{{$priceOne}}</span>
                                            <span class="currency_format">₫</span>
                                        </strong>

                                        <input type="hidden" name="ite_one" value="{{$priceOne}}" />
                                    </div>
                                </div>
                            </td>
                            <td class="text-center mini_cart_actions" style="width: 33.33%;">
                                <div class="quantity pr mr__10 qty__true" style="margin: 0 auto;">
                                    <input type="number" class="input-text qty text tc qty_pr_js"
                                           step="1" min="1" max="9999" name="quantity"
                                           size="4" pattern="[0-9]*" inputmode="numeric"
                                           value="{{$quantity}}"  onkeyup="jscartdhmq({{$product->id}})"
                                    >
                                    <div class="qty tc fs__14">
                                        <a onclick="jscartdhmu({{$product->id}});jscartdhvalid(false);"
                                           class="plus  cb pa pr__15 tr r__0" href="javascript:void(0)">
                                            <i class="facl facl-plus"></i>
                                        </a>
                                        <a onclick="jscartdhmu({{$product->id}});jscartdhvalid(false);"
                                           class="minus  cb pa pl__15 tl l__0" href="javascript:void(0)">
                                            <i class="facl facl-minus"></i>
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center" style="width: 33.33%;">
                                <span class="cart-item-price price fwm cd">
                                    <strong class="color-money fs-16">
                                        <span class="number_format one_total">{{$priceRow}}</span>
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
                                            src="{{$product->getAvatar('profile')}}"
                                            data-widths="[120, 240]" data-sizes="auto" alt=""
                                            data-srcset="{{$product->getAvatar('profile')}}"
                                            sizes="120px"
                                            srcset="{{$product->getAvatar('profile')}}">
                                    </a>
                                    <div class="mini_cart_body ml__15">
                                        <h5 class="mini_cart_title mg__0 mb__5">
                                            <a href="{{$product->getHref(true)}}">{{$product->getTitle()}}</a>
                                        </h5>
                                        <div class="mini_cart_meta"></div>
                                        <div class="mini_cart_tool mt__10">
                                            <a href="javascript:void(0)" onclick="jscartdhx({{$product->id}});jscartdhvalid(false);"
                                                class="cart_ac_remove ttip_nt tooltip_top_right"
                                                >
                                                <span class="tt_txt">Xóa khỏi giỏ hàng</span>
                                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                                     stroke-width="2" fill="none" stroke-linecap="round"
                                                     stroke-linejoin="round" class="css-i6dzq1">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3 tc">
                                <div class="cart_meta_prices price">
                                    <div class="cart_price">
                                        @if ($priceOne!= $priceMain)
                                            <del class="price_old">
                                                <span class="number_format">{{$priceMain}}</span>
                                                <span class="currency_format">₫</span>
                                            </del>
                                        @endif
                                        <strong class="fs-16">
                                            <span class="number_format">{{$priceOne}}</span>
                                            <span class="currency_format">₫</span>
                                        </strong>

                                        <input type="hidden" name="ite_one" value="{{$priceOne}}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-2 tc mini_cart_actions">
                                <div class="quantity pr mr__10 qty__true" style="margin: 0 auto;">
                                    <input type="number" class="input-text qty text tc qty_pr_js"
                                           step="1" min="1" max="9999" name="quantity"
                                           size="4" pattern="[0-9]*" inputmode="numeric"
                                           value="{{$quantity}}"  onkeyup="jscartdhmq({{$product->id}})"
                                    >
                                    <div class="qty tc fs__14">
                                        <a onclick="jscartdhmu({{$product->id}});jscartdhvalid(false);"
                                           class="plus  cb pa pr__15 tr r__0" href="javascript:void(0)">
                                            <i class="facl facl-plus"></i>
                                        </a>
                                        <a onclick="jscartdhmu({{$product->id}});jscartdhvalid(false);"
                                           class="minus  cb pa pl__15 tl l__0" href="javascript:void(0)">
                                            <i class="facl facl-minus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-2 tc tr_lg">
                                <span class="cart-item-price price fwm cd">
                                    <strong class="color-money fs-16">
                                        <span class="number_format one_total">{{$priceRow}}</span>
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
                @if ($isMobile)
                <div class="row">
                    <div class="col-12 order-1">
                        <table class="table">
                            <tr>
                                <td>
                                    <label class="frm-label">tổng tiền hàng:</label>
                                </td>
                                <td class="text-right">
                                    <strong class="text-black fs-18">
                                        <span class="number_format" id="cart_all">{{$priceAll}}</span>
                                        <span class="currency_format">₫</span>
                                    </strong>
                                </td>
                            </tr>
                            <tr class="hidden ma_giam_gia">
                                <td>
                                    <label class="frm-label">giảm giá đặc biệt:</label>
                                </td>
                                <td class="text-right">
                                    <strong class="text-black fs-18">
                                        <span class="number_format" id="cart_discount">0</span>
                                        <span class="currency_format">₫</span>
                                    </strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="frm-label">phí giao hàng:</label>
                                </td>
                                <td class="text-right">
                                    <strong class="fs-18">
                                        <span class="number_format @if($freeShip) line_through @endif" id="cart_shipping">{{$shippingFee}}</span>
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
                                        <span class="number_format text-bold" id="cart_total">{{$priceAll}}</span>
                                        <span class="currency_format">₫</span>
                                    </strong>
                                </td>
                            </tr>
                        </table>

                        <table class="table">
                            <tr class="@if($viewer && $viewer->doiTacHopLe()) hidden @endif">
                                <td>
                                    <div>
                                        <label class="frm-label fs-11">Mã giảm giá đặc biệt</label>
                                    </div>
                                    <div id="ele-referer">
                                        <input class="text-center text-uppercase" type="text" name="ref_code" onkeyup="jscartdhrefer()" onkeypress="pressNoSpace(event)"
                                               @if(!empty($refCode)) value="{{$refCode}}" @endif
                                        />
                                    </div>
                                </td>
                            </tr>
                            @if (!$viewer)
                            <tr>
                                <td>
                                    <div>
                                        <label class="frm-label fs-11 required">Họ Tên <span class="required">*</span></label>
                                    </div>
                                    <div id="ele-name">
                                        <input class="text-center" required type="text" name="name" autocomplete="off" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <label class="frm-label fs-11 required">Điện Thoại <span class="required">*</span></label>
                                    </div>
                                    <div id="ele-phone">
                                        <input class="text-center" required type="text" name="phone" autocomplete="off"
                                               onkeypress="return isInputPhone(event, this)" onkeyup="jscartdhrefer()"
                                               oncopy="return false;" oncut="return false;" onpaste="return false;"
                                        />
                                    </div>
                                </td>
                            </tr>
                            <tr class="hidden">
                                <td>
                                    <div>
                                        <label class="frm-label fs-11 required">Địa chỉ email <span class="required">*</span></label>
                                    </div>
                                    <div id="ele-email">
                                        <input class="text-center" type="email" name="email" />
                                    </div>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>
                                    <div>
                                        <label class="frm-label fs-11 required">Địa chỉ nhận hàng <span class="required">*</span></label>
                                    </div>
                                    <div id="ele-address" class="mb__10">
                                        <input class="text-center" required type="text" name="address" value="{{$viewer ? $viewer->address : ''}}" />
                                    </div>
                                    <div id="frm-address">
                                        <div class="mb__10" id="ele-province">
                                            <select name="province_id" class="form-control select-css" onchange="jscartaddressopts(this, 'district');jscartdhvalid(false);jscartdhcal();">
                                                <option value="">Hãy chọn tỉnh / thành</option>
                                                @foreach($provinces as $ite)
                                                    <option @if($viewer && $viewer->province_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb__10" id="ele-district">
                                            <select name="district_id" class="form-control select-css" onchange="jscartaddressopts(this, 'ward');jscartdhvalid(false);jscartdhcal();">
                                                <option value="">Hãy chọn quận / huyện</option>
                                                @if (count($districts))
                                                    @foreach($districts as $ite)
                                                        <option @if($viewer && $viewer->district_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="mb__10" id="ele-ward">
                                            <select name="ward_id" class="form-control select-css" onchange="jscartdhvalid(false);jscartdhcal();">
                                                <option value="">Hãy chọn phường / xã</option>
                                                @if (count($wards))
                                                    @foreach($wards as $ite)
                                                        <option @if($viewer && $viewer->ward_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hidden">
                                <td>
                                    <div>
                                        <label class="frm-label fs-11">giao hàng</label>
                                    </div>
                                    <div class="mb__10">
                                        <div class="cart_ship gh_thuong active" onclick="jscartshipment('gh_thuong');jscartdhvalid(false);jscartdhcal();">
                                            <img src="{{url('public')}}/images/shipping.png" />
                                            <div>giao hàng<br/>thường</div>
                                        </div>
                                    </div>
                                    <div class="mb__10">
                                        <div class="cart_ship gh_nhanh" onclick="jscartshipment('gh_nhanh');jscartdhvalid(false);jscartdhcal();">
                                            <img src="{{url('public')}}/images/shipping_fast.png" />
                                            <div>giao hàng<br/>nhanh</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <label class="frm-label fs-11">phương thức thanh toán</label>
                                    </div>
                                    <div class=" mb__10">
                                        <div class="cart_payment cod active" onclick="jscartpttt('cod')">
                                            <img src="{{url('public')}}/images/cod.png" />
                                            <div>tiền mặt khi<br/>nhận hàng</div>
                                        </div>
                                    </div>
                                    <div class=" mb__10">
                                        <div class="cart_payment banking" onclick="jscartpttt('banking')">
                                            <img src="{{url('public')}}/images/banking.png" />
                                            <div>chuyển khoản<br/>ngân hàng</div>
                                        </div>
                                    </div>
                                    <div class=" mb__10">
                                        <div class="cart_payment vnpay" onclick="jscartpttt('vnpay')">
                                            <img src="{{url('public')}}/images/vnpay.jpg" />
                                            <div>thanh toán<br/>vnpay</div>
                                        </div>
                                    </div>
                                    <div class=" mb__10 @if(!$apiCore->testMode()) hidden @endif">
                                        <div class="cart_payment zalopay" onclick="jscartpttt('zalopay')">
                                            <img src="{{url('public')}}/images/zalopay.png" />
                                            <div>thanh toán<br/>zalopay</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        @include('modals.payment_account')
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <table class="table">
                            <tr>
                                <td class="clearfix">
                                    <div class="float-left mr__10">
                                        <input required type="checkbox" class="mr__5 width_height_20" style="margin: 0; cursor: pointer;">
                                    </div>
                                    <div class="overflow-hidden">
                                        <label for="cart_agree">
                                            <a class="text-site text-bold" href="{{$URLCart}}" target="_blank">
                                                Tôi đã đọc và đồng ý các chính sách của công ty.
                                            </a>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button type="submit" name="checkout" class="btn_checkout button button_primary tu mt__10 mb__10 width_full">
                                        xác nhận đặt hàng
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <textarea name="note" class="cart-note__input min_height_100px" placeholder="Ghi chú đơn hàng..."></textarea>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                @else
                <div class="row">
                    <div class="col-12 col-md-7 cart_actions tl_md tc order-md-2 order-2 mb__50">
                        <div class="row @if($viewer && $viewer->doiTacHopLe()) hidden @endif">
                            <div class="col-md-3 mt__15">
                                <label class="frm-label fs-11">Mã giảm giá đặc biệt</label>
                            </div>
                            <div class="col-md-9 mt__15" id="ele-referer">
                                <input class="text-center text-uppercase" type="text" name="ref_code" onkeyup="jscartdhrefer()" onkeypress="pressNoSpace(event)"
                                       @if(!empty($refCode)) value="{{$refCode}}" @endif
                                />
                            </div>
                        </div>
                        @if (!$viewer)
                            <div class="row">
                                <div class="col-md-3 mt__15">
                                    <label class="frm-label fs-11 required">Họ Tên <span class="required">*</span></label>
                                </div>
                                <div class="col-md-9 mt__15" id="ele-name">
                                    <input class="text-center" required type="text" name="name" autocomplete="off" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt__15">
                                    <label class="frm-label fs-11 required">Điện Thoại <span class="required">*</span></label>
                                </div>
                                <div class="col-md-9 mt__15" id="ele-phone">
                                    <input class="text-center" required type="text" name="phone" autocomplete="off"
                                           onkeypress="return isInputPhone(event, this)" onkeyup="jscartdhrefer()"
                                           oncopy="return false;" oncut="return false;" onpaste="return false;"
                                    />
                                </div>
                            </div>
                            <div class="row hidden">
                                <div class="col-md-3 mt__15">
                                    <label class="frm-label fs-11 required">Địa chỉ email <span class="required">*</span></label>
                                </div>
                                <div class="col-md-9 mt__15" id="ele-email">
                                    <input  class="text-center" type="email" name="email" />
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-3 mt__15">
                                <label class="frm-label fs-11 required">Địa chỉ nhận hàng <span class="required">*</span></label>
                            </div>
                            <div class="col-md-9 mt__15" id="ele-address">
                                <input class="text-center" required type="text" name="address" value="{{$viewer ? $viewer->address : ''}}" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mt__15"></div>
                            <div class="col-md-9 mt__15" id="frm-address">
                                <div class="row">
                                    <div class="col-md-4" id="ele-province">
                                        <select name="province_id" class="form-control select-css" onchange="jscartaddressopts(this, 'district');jscartdhvalid(false);jscartdhcal();">
                                            <option value="">Hãy chọn tỉnh / thành</option>
                                            @foreach($provinces as $ite)
                                                <option @if($viewer && $viewer->province_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4" id="ele-district">
                                        <select name="district_id" class="form-control select-css" onchange="jscartaddressopts(this, 'ward');jscartdhvalid(false);jscartdhcal();">
                                            <option value="">Hãy chọn quận / huyện</option>
                                            @if (count($districts))
                                                @foreach($districts as $ite)
                                                    <option @if($viewer && $viewer->district_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-4" id="ele-ward">
                                        <select name="ward_id" class="form-control select-css" onchange="jscartdhvalid(false);jscartdhcal();">
                                            <option value="">Hãy chọn phường / xã</option>
                                            @if (count($wards))
                                                @foreach($wards as $ite)
                                                    <option @if($viewer && $viewer->ward_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row hidden">
                            <div class="col-md-3 mt__15">
                                <label class="frm-label fs-11">giao hàng</label>
                            </div>
                            <div class="col-md-9 mt__15">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="cart_ship gh_thuong active" onclick="jscartshipment('gh_thuong');jscartdhvalid(false);jscartdhcal();">
                                            <img src="{{url('public')}}/images/shipping.png" />
                                            <div>giao hàng<br/>thường</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="cart_ship gh_nhanh" onclick="jscartshipment('gh_nhanh');jscartdhvalid(false);jscartdhcal();">
                                            <img src="{{url('public')}}/images/shipping_fast.png" />
                                            <div>giao hàng<br/>nhanh</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mt__15">
                                <label class="frm-label fs-11">phương thức thanh toán</label>
                            </div>
                            <div class="col-md-9 mt__15">
                                <div class="row">
                                    <div class="col-md-6 mb__10">
                                        <div class="cart_payment cod active" onclick="jscartpttt('cod')">
                                            <img src="{{url('public')}}/images/cod.png" />
                                            <div>tiền mặt khi<br/>nhận hàng</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb__10">
                                        <div class="cart_payment banking" onclick="jscartpttt('banking')">
                                            <img src="{{url('public')}}/images/banking.png" />
                                            <div>chuyển khoản<br/>ngân hàng</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb__10">
                                        <div class="cart_payment vnpay" onclick="jscartpttt('vnpay')">
                                            <img src="{{url('public')}}/images/vnpay.jpg" />
                                            <div>thanh toán<br/>vnpay</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb__10 @if(!$apiCore->testMode()) hidden @endif">
                                        <div class="cart_payment zalopay" onclick="jscartpttt('zalopay')">
                                            <img src="{{url('public')}}/images/zalopay.png" />
                                            <div>thanh toán<br/>zalopay</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt__15">
                            @include('modals.payment_account')
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 tr_md tc order-md-4 order-1 ">
                        <div class="row text-right">
                            <div class="col-md-7 mb__10 text-right">
                                <label class="frm-label">tổng tiền hàng:</label>
                            </div>
                            <div class="col-md-5 mb__10 text-right">
                                <strong class="text-black fs-18">
                                    <span class="number_format" id="cart_all">{{$priceAll}}</span>
                                    <span class="currency_format">₫</span>
                                </strong>
                            </div>
                        </div>
                        <div class="row hidden">
                            <div class="col-md-6 mb__10"></div>
                            <div class="col-md-6 mb__10" id="ele-coupon">
                                <input class="text-center text-uppercase" type="text" name="coupon" onkeyup="jscartdhcoupon()" onkeypress="pressNoSpace(event)" placeholder="nhập mã khuyến mãi" />
                            </div>
                        </div>
                        <div class="row text-right hidden ma_giam_gia">
                            <div class="col-md-7 mb__10 text-right">
                                <label class="frm-label">giảm giá đặc biệt:</label>
                            </div>
                            <div class="col-md-5 mb__10 text-right">
                                <strong class="text-black fs-18">
                                    <span class="number_format" id="cart_discount">0</span>
                                    <span class="currency_format">₫</span>
                                </strong>
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-md-7 mb__10 text-right">
                                <label class="frm-label">phí giao hàng:</label>
                            </div>
                            <div class="col-md-5 mb__10 text-right">
                                <strong class="fs-18">
                                    <span class="number_format @if($freeShip) line_through @endif" id="cart_shipping">{{$shippingFee}}</span>
                                    <span class="currency_format">₫</span>
                                </strong>
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-md-7 mb__10 text-right">
                                <label class="frm-label">tổng thanh toán:</label>
                            </div>
                            <div class="col-md-5 mb__10 text-right">
                                <strong class="color-money fs-18">
                                    <span class="number_format text-bold" id="cart_total">{{$priceAll}}</span>
                                    <span class="currency_format">₫</span>
                                </strong>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <p class="pr dib mb__5">
                            <input required type="checkbox" class="mr__5 width_height_20" style="margin: 0; cursor: pointer;">
                            <label for="cart_agree">
                                <a class="text-site text-bold" href="{{$URLCart}}" target="_blank">
                                    Tôi đã đọc và đồng ý các chính sách của công ty.
                                </a>
                            </label>
                        </p>
                        <div class="clearfix"></div>
                        <button type="submit" name="checkout" class="btn_checkout button button_primary tu mt__10 mb__10">
                            xác nhận đặt hàng
                        </button>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-4 mb__10"></div>
                            <div class="col-md-8 mb__10">
                                <textarea name="note" class="cart-note__input min_height_100px" placeholder="Ghi chú đơn hàng..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="hidden">
                <input type="hidden" name="ids" value="{{$ids}}" id="frm-ids" />
                <input type="hidden" name="total_order" value="{{$countCarts}}" />

                <input type="hidden" name="total_all" value="{{$priceAll}}" />
                <input type="hidden" name="total_paid" value="{{$priceAll}}" />
                <input type="hidden" name="total_paid_no_ship" value="{{$priceAll}}" />
                <input type="hidden" name="payment_by" value="cod" />

                <input type="hidden" name="percent_discount" value="0" />
                <input type="hidden" name="total_discount" value="0" />

                {{--                        giam gia--}}
                <input type="hidden" name="discount_gg" value="{{$discountGG}}" />
                {{--                        khuyen mai--}}
                <input type="hidden" name="discount_km" value="0" />

                <input type="hidden" name="over_cart" value="{{$overFee}}" />
                <input type="hidden" name="ghn_fee" value="{{$shippingFee}}" />
                <input type="hidden" name="free_city" value="{{$freeCity}}" />
                <input type="hidden" name="express" value="" />
            </div>
        </form>
        @endif
    </div>

    <div class="mfp-wrap mfp-close-btn-in mfp-auto-cursor mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_2 hidden" tabindex="-1" style="overflow: hidden auto;">
        <div class="mfp-container mfp-s-ready mfp-inline-holder">
            <div class="mfp-content">
                <div class="popup_gks">
                    <table class="table">
                        <thead>
                        <tr>
                            <td>
                                <div class="overflow-hidden text-uppercase text-bold">{{$apiCore->getSetting('text_dh_confirm_text_title')}}</div>
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <div class="popup_gks_body"><?php echo nl2br($apiCore->getSetting('text_dh_confirm_text_body'))?></div>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td class="text-right">
                                <button class="button text-uppercase" onclick="jsbindpopupclose()">không</button>
                                <button class="button button_primary text-uppercase" onclick="jscartdhpopupok()">xác nhận</button>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            @if(!empty($refCode))
            jscartdhrefer();
            @endif

            jscartdhcal();
        });
    </script>
@stop
