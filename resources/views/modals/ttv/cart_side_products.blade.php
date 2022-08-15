<?php
$apiFE = new \App\Api\FE;
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile() ? 1 : 0;

if ($viewer) {
    $cart = $viewer->getCart();

    $products = $cart->getProducts();

} else {
    $cart = (Session::get('USR_CART'));

    $products = $cart;
}

$total = 0;
?>

<div class="mini_cart_content fixcl-scroll">
    <div class="fixcl-scroll-content">
        <div class="mini_cart_items lazyloaded">
            @if (count($products))
                <?php foreach ($products as $p):
                if ($viewer) {
                    $product = $apiCore->getItem('product', $p->product_id);

                    $quantity = $p->quantity;
                } else {
                    $product = $apiCore->getItem('product', (int)$p);

                    $quantity = 1;
                    $cartQty = (Session::get('USR_CART_QTY'));
                    if (count($cartQty)) {
                        if (isset($cartQty[(int)$p]) && $cartQty[(int)$p]) {
                            $quantity = (int)$cartQty[(int)$p];
                        }
                    }
                }

                if (!$product) {
                    continue;
                }

                $total += $product->price_pay * $quantity;

                ?>
                <div class="mini_cart_item flex al_center pr oh cart_side_item" id="cart_side_item_{{$product->id}}">
                    <div class="ld_cart_bar"></div>
                    <a href="{{$product->getAvatar('normal')}}" class="mini_cart_img" title="{{$product->getTitle()}}">
                        <img class="lz_op_ef w__100 lazyautosizes lazyloaded"
                             src="{{$product->getAvatar('normal')}}" data-widths="[120, 240]" data-sizes="auto" alt=""
                             data-srcset="{{$product->getAvatar('normal')}}" sizes="120px"
                             srcset="{{$product->getAvatar('normal')}}">
                    </a>
                    <div class="mini_cart_info">
                        <a href="{{$product->getHref(true)}}" title="{{$product->getTitle()}}" class="mini_cart_title truncate">{{$product->getTitle()}}</a>
                        <div class="mini_cart_meta">
                            <div class="cart_meta_price price">
                                <div class="cart_price">
                                    <span class="number_format">{{$product->price_pay}}</span>
                                    <span class="currency_format">₫</span>
                                </div>
                                <input type="hidden" name="price" value="{{$product->price_pay}}" />
                            </div>
                        </div>
                        <div class="mini_cart_actions">
                            <div class="quantity pr mr__10 qty__true" style="margin: 0 auto;">
                                <input type="number" class="input-text qty text tc"
                                       step="1" min="0" max="99" name="quantity"
                                       size="4" pattern="[0-9]*" inputmode="numeric"
                                       value="{{$quantity}}"  onkeyup="jscartdhsq({{$product->id}})"
                                >
                                <div class="qty tc fs__14">
                                    <a onclick="jscartdhsqu({{$product->id}})"
                                        class="plus  cb pa pr__15 tr r__0" href="javascript:void(0)">
                                        <i class="facl facl-plus"></i>
                                    </a>
                                    <a onclick="jscartdhsqd({{$product->id}})"
                                        class="minus  cb pa pl__15 tl l__0" href="javascript:void(0)">
                                        <i class="facl facl-minus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach;?>
            @endif
        </div>
    </div>
</div>
<div class="mini_cart_footer">
    <div class="total row fl_between al_center">
        <div class="col-auto"><strong class="text-uppercase">tổng cộng:</strong></div>
        <div class="col-auto tr">
            <div class="cart_tot_price text-bold" >
                <span class="number_format total_cart">{{$total}}</span>
                <span class="currency_format">₫</span>
            </div>
        </div>
    </div>
    <button type="button" onclick="openPage('{{url('gio-hang')}}');" class="button button_primary text-fff btn-cart mt__10 mb__10">chi tiết giỏ hàng</button>
</div>

