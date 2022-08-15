//check
function cartSideUpdate(id) {
    var bind = jQuery('#cart_side_item_' + id);
    var total = 0;
    //js calculate
    var quantity = 0;
    if (bind.length) {
        quantity = parseInt(bind.find('input[name=quantity]').val());
    }

    //
    if (jQuery('#frm-cart_side .cart_side_item').length) {
        jQuery('#frm-cart_side .cart_side_item').each(function(pos, ele) {
            var q = parseInt(jQuery(ele).find('input[name=quantity]').val());
            var p = parseInt(jQuery(ele).find('input[name=price]').val());

            total += parseInt(p * q);
        });

        jQuery('#frm-cart_side .cart_tot_price .total_cart').text(total);
        jsbindmf();
    }

    //
    jQuery.ajax({
        url: gks.baseURL + '/sp/buy-side',
        type: 'post',
        data: {
            item_id: id,
            quantity: quantity,
            _token: gks.tempTK,
        },
        success: function (response) {
            jQuery('#count-cart-sp').text(response.COUNT);
        }
    });
}
function getProductsByBrand(bid) {
    if (curLoading) {
        return false;
    }
    curLoading = true;

    jQuery.ajax({
        url: gks.baseURL + '/sp/by-brand',
        type: 'post',
        data: {
            bid: bid,
            page: curPage + 1,
            _token: gks.tempTK,
        },
        success: function (response) {
            if (response.BODY && response.BODY != '') {
                jQuery('.products-wrapper .row').append(response.BODY);

                curLoading = false;
                curPage = curPage + 1;
            }
        }
    });
}
function getSlide(direction) {
    if (direction === 'prev') {
        photoCurrent = photoCurrent - 1;
    } else {
        //next
        photoCurrent = photoCurrent + 1;
    }
    if (photoCurrent <= 0) {
        photoCurrent = parseInt(photoMax);
    } else if (photoCurrent > parseInt(photoMax)) {
        photoCurrent = 1;
    }

    var count = photoCurrent;
    var temp = [];
    var arr = photoSlides;

    var i = photoSlides.length;
    for (i; i > 0; i--) {
        if (temp.length < 3 && count === i) {
            temp.push(arr[count - 1]);
            count--;
        }
    }
    if (photoCurrent === 1) {
        temp.push(arr[photoSlides.length - 1]);
        temp.push(arr[photoSlides.length - 2]);
    } else if (photoCurrent === 2) {
        temp.push(arr[photoSlides.length - 1]);
    }

    if (temp.length) {
        var c = 1;
        temp.reverse().forEach(function (ele, pos) {
            jQuery('.p-info-slides .thumb-' + c).removeAttr('style')
                .attr('style', 'background-image:url(\'' + ele.thumb + '\')')
                .removeAttr('href')
                .attr('href', ele.full);
            c++;
        });
    }
}

function cartChangeQuantity(ele) {
    var bind = jQuery(ele);
    var quantity = parseInt(bind.val().trim()) ? parseInt(bind.val().trim()) : 0;
    var parent = bind.parent().parent();
    var priceOne = parseInt(parent.attr('data-price'));
    var price = quantity * priceOne;

    parent.find('.price-total').text(price);

    reCalculateCart();
}
function reCalculateCart() {
    cartCalculate();
    cartShip();

    jsbindmf();
}
function cartShip() {
    var currentShip = parseInt(jQuery('#cart-ship-default').val().trim());
    var maxShipCart = parseInt(jQuery('#cart-ship-free-price').val().trim()) ? parseInt(jQuery('#cart-ship-free-price').val().trim()) : 0;
    var maxShipQuantity = parseInt(jQuery('#cart-ship-free-quantity').val().trim()) ? parseInt(jQuery('#cart-ship-free-quantity').val().trim()) : 0;
    var currentTotalPrice = parseInt(jQuery('#cart-total').val().trim());
    var currentTotalQuantity = parseInt(jQuery('#cart-quantity').val().trim()) ? parseInt(jQuery('#cart-quantity').val().trim()) : 0;
    var freeShip = false;

    if (maxShipCart || maxShipQuantity) {
        if (maxShipCart
            && currentTotalPrice >= maxShipCart
        ) {
            freeShip = true;
        }

        if (maxShipQuantity
            && !freeShip
            && currentTotalQuantity >= maxShipQuantity
        ) {
            freeShip = true;
        }
    }

    if (freeShip) {
        currentShip = 0;
    }
    jQuery('.price-cart-ship').text(currentShip);

    jQuery('.price-cart-all').text(currentTotalPrice + currentShip);
}
function cartCalculate() {
    var priceTotal = 0;
    var totalQuantity = 0;
    var ids = '';
    jQuery('.cart-table tbody tr.cart-p-item.bought').each(function (pos, ele) {
        var bind = jQuery(ele);
        var priceID = parseInt(bind.attr('data-id'));
        var priceOne = parseInt(bind.attr('data-price'));
        var quantity = parseInt(bind.find('.quantity').val().trim()) ? parseInt(bind.find('.quantity').val().trim()) : 0;
        priceTotal += quantity * priceOne;
        totalQuantity += quantity;
        ids += priceID + '_' + quantity + ';';
    });
    jQuery('.price-cart-total').text(priceTotal);

    jQuery('#frm-ids').val(ids);
    jQuery('#cart-total').val(priceTotal);
    jQuery('#cart-quantity').val(totalQuantity);
    jQuery('.total-quantity').text(totalQuantity);
}
function cartRow(ele, active) {
    var bind = jQuery(ele);
    var parent = bind.parent().parent();

    parent.find('.action a').removeClass('active');

    if (active) {
        parent.find('.action .action_delete').addClass('active');
        parent.addClass('bought');
        parent.find('.quantity').val('1').removeAttr('disabled');
    } else {
        parent.find('.action .action_recover').addClass('active');
        parent.removeClass('bought');
        parent.find('.quantity').val('0').attr('disabled', 'disabled');
    }

    reCalculateCart();
}

function cartPayment(method) {
    jQuery('.cart-wrapper .cart-payment i').removeClass('checked');
    jQuery('.cart-wrapper .cart-payment .' + method).addClass('checked');
    jQuery('#payment_by').val(method);

    jQuery('.cart-wrapper #req-slides').addClass('hidden');
    if (method === 'banking') {
        jQuery('.cart-wrapper #req-slides').removeClass('hidden');
    }
}
function cartAddress(ele) {
    var bind = jQuery(ele);
    jQuery('.cart-wrapper .cart-address').removeClass('checked');
    jQuery('.cart-wrapper .cart-address i').removeClass('checked');

    bind.addClass('checked');
    bind.parent().addClass('checked');

    jQuery('#address-id').val(bind.parent().attr('data-id'));
}

function hopTacXacNhan() {
    var frm = jQuery('#frm-hop_tac');
    var cmnd = frm.find('input[name=cmnd]').val().trim();
    var cmndAddress = frm.find('input[name=cmnd_address]').val().trim();
    var chungChiHanhNghe = frm.find('input[name=chung_chi_hanh_nghe]').val().trim();
    var valid = true;
    frm.find('.alert').addClass('hidden');

    if (!cmnd || cmnd === '' || cmnd.length < 9) {
        frm.find('#ele-cmnd .alert').removeClass('hidden');
        valid = false;
    }

    // if (!cmndAddress || cmndAddress === '') {
    //     frm.find('#ele-cmnd_address .alert').removeClass('hidden');
    //     valid = false;
    // }
    //
    // if (!chungChiHanhNghe || chungChiHanhNghe === '') {
    //     frm.find('#ele-chung_chi_hanh_nghe .alert').removeClass('hidden');
    //     valid = false;
    // }

    if (!valid) {
        return false;
    }

    frm.find('button').hide();
    frm.find('button').after(gks.loadingIMG);
    frm[0].submit();

    return false;
}



