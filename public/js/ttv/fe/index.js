jQuery(document).ready(function() {
    setTimeout(function () {
        jsbindmf();
    }, 333);

    setTimeout(function () {
        jsbindpsale();
    }, 100000);

    if (jQuery('#frm-test').length) {
        jQuery('#frm-test').on('submit', function(e){
            e.preventDefault();
            jskhtesthh();
        });
    }

    if (jQuery('#frm-cart').length) {
        jQuery('#frm-cart').on('submit', function (e) {
            e.preventDefault();
            jscartbs();
        });
    }

    if (jQuery('#frm-review').length) {
        jQuery('#frm-review').on('submit', function (e) {
            e.preventDefault();
            jsspdg();
        });
    }

    if (jQuery('#mau_thiep_tu_viet').length) {
        //upload
        $("#mau_thiep_tu_viet").change(function () {
            jQuery('#req-card .upload_preview').empty();
            jQuery('#req-card .alert-danger').addClass('hidden');

            if (this.files.length) {
                var i = 0;
                for (i; i<=99; i++) {
                    if (this.files[i] && this.files[i].size > gks.maxSize) {
                        jQuery('#req-card .alert-danger').removeClass('hidden');

                        jQuery(this).val("");
                        jQuery('#req-card .upload_preview').empty();
                        return false;
                    }
                }
            }

            var input = this;
            var url = $(this).val();
            var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
                var filesAmount = input.files.length;

                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        jQuery('#req-card .upload_preview').append("<div class='img-item'><div class='img-preview'><img src='" + e.target.result + "' ></div></div>");
                    }
                    reader.readAsDataURL(input.files[i]);
                }
            } else {
                jQuery(this).val("");
            }
        });
    }

    if (jQuery('#frm-person').length) {
        jQuery('#frm-person').on('submit', function (e) {
            e.preventDefault();
            jskhpopuppersonok();
        });
    }

    if (jQuery('#frm-date').length) {
        jQuery('#frm-date').on('submit', function (e) {
            e.preventDefault();
            jskhpopupdateok();
        });
    }

    jQuery('.max-size-text').text(gks.maxSizeText);
});
var ghnExpress = 0, ajHHT = null, ajDST = null;

//bind
function jsbindmf() {
    // jQuery('.number_format').simpleNumberFormat();

    jQuery('.number_format').each(function (index, el) {
        var elType = null; // input or other
        var value = null;
        // get value
        if ($(el).is('input') || $(el).is('textarea')) {
            value = $(el).val().replace(/\,/g, '');
            elType = 'input';
        } else {
            value = $(el).text().replace(/\,/g, '');
            elType = 'other';
        }
        // if value changes
        $(el).on('paste keyup', function (event) {
            value = $(el).val().replace(/\,/g, '');
            jsbindmfele(el, elType, value); // format element
        });
        jsbindmfele(el, elType, value); // format element
    });

    jQuery(".number_format").bind('keypress keyup blur paste', function (event) {
        $(this).val($(this).val().replace(/[^0-9\,]/g, ''));
        if (event.which < 48 || event.which > 57) {
            event.preventDefault();
        }
    });
}
function jsbindmfele(el, elType, value) {
    //custom
    value = value.replace(/\s/g, '');
    value = value.replace(/\n/g, '');

    var result = '';
    var valueArray = value.split('');
    var resultArray = [];
    var counter = 0;
    var temp = '';
    for (var i = valueArray.length - 1; i >= 0; i--) {
        temp += valueArray[i];
        counter++
        if (counter == 3) {
            resultArray.push(temp);
            counter = 0;
            temp = '';
        }
    }
    ;
    if (counter > 0) {
        resultArray.push(temp);
    }
    for (var i = resultArray.length - 1; i >= 0; i--) {
        var resTemp = resultArray[i].split('');
        for (var j = resTemp.length - 1; j >= 0; j--) {
            result += resTemp[j];
        }
        ;
        if (i > 0) {
            result += ','
        }
    }
    ;

    if (elType == 'input') {
        $(el).val(result);
    } else {
        $(el).empty().text(result);
    }

}
function jsbindlh() {
    var bind = jQuery('#frm-send');
    var contactName = bind.find('#frm-name').val().trim();
    var contactPhone = bind.find('#frm-phone').val().trim();
    var contactEmail = bind.find('#frm-email').val().trim();
    var contactBody = bind.find('#frm-body').val().trim();
    var valid = true;
    bind.find('.alert-danger').addClass('hidden');

    if (!contactName) {
        valid = false;
        jQuery('#req-name .alert-danger').removeClass('hidden');
    }

    if (!contactPhone) {
        valid = false;
        jQuery('#req-phone .alert-danger').removeClass('hidden');
    }

    if (!contactEmail) {
        valid = false;
        jQuery('#req-email .alert-danger').removeClass('hidden');
    }

    if (!contactBody) {
        valid = false;
        jQuery('#req-body .alert-danger').removeClass('hidden');
    }

    if (valid) {
        jQuery.ajax({
            url: gks.baseURL + '/lh/gui',
            type: 'post',
            data: {
                name: contactName,
                phone: contactPhone,
                email: contactEmail,
                body: contactBody,
                _token: gks.tempTK,
            },
            beforeSend: function () {
                bind.find('#req-button').hide()
                    .after(gks.loadingIMG);
            },
            success: function (response) {
                window.location.reload(true);
            }
        });
    }

}
function jsbindbrand(ele, key) {
    jQuery('.brand-sorts .brand-sort-item').removeClass('active');
    jQuery('#brand-sort-' + key).addClass('active');

    jQuery('.brand-chars .brand-char-item').removeClass('active');
    jQuery(ele).addClass('active');
}
function jsbindtab1(tab) {
    var bind = jQuery('#kero-1');
    var header = bind.find('.card-header').first();
    var body = bind.find('.card-body').first();
    var pane = body.find('.tab-content').first();

    header.find('li a').removeClass('active');
    pane.find('> .tab-pane').removeClass('active');

    header.find('li a.' + tab).addClass('active');
    pane.find('> .tab-pane.' + tab).addClass('active');

    // bind.find('.card-header li a').removeClass('active');
    // bind.find('.card-body .tab-pane').removeClass('active');
    //
    // bind.find('.card-header li a.' + tab).addClass('active');
    // bind.find('.card-body .tab-pane.' + tab).addClass('active');
}
function jsbindtab2(tab) {
    var bind = jQuery('#kero-2');

    bind.find('.card-header li a').removeClass('active');
    bind.find('.card-body .tab-pane').removeClass('active');

    bind.find('.card-header li a.' + tab).addClass('active');
    bind.find('.card-body .tab-pane.' + tab).addClass('active');
}
function jsbindpsale() {
    jQuery.ajax({
        url: gks.baseURL + '/sp/random',
        type: 'post',
        data: {
            _token: gks.tempTK,
        },
        success: function (response) {
            var bind = jQuery('#push-push-push');

            bind.find('.link_p_1').removeAttr('href')
                .attr('href', response.URL);
            bind.find('.link_p_1 img').removeAttr('src')
                .attr('src', response.IMG)
                .removeAttr('srcset')
                .attr('srcset', response.IMG);

            bind.find('.client_name').text(response.KH);

            bind.find('.client_action').text(response.DO);

            bind.find('.link_p_2').removeAttr('href')
                .attr('href', response.URL)
                .text(response.SP);

            bind.find('.pp_slpr_time').text(response.TIME + ' giây trước');

            bind.fadeIn(555);
            setTimeout(function () {
                bind.fadeOut(333);

                var timeOut = 100000;
                if (gks.isMobile) {
                    timeOut = 120000;
                }
                setTimeout(function () {
                    jsbindpsale();
                }, timeOut);
            }, 5000);
        }
    });
}
function jsbindhomevideo(vd) {
    var bind = jQuery('.vd_wrapper');
    bind.find('.media_thumbnail').removeClass('hidden');
    bind.find('.media_body').addClass('hidden');

    bind.find('.media_thumbnail.vd_thumb_' + vd).addClass('hidden');
    bind.find('.media_body.vd_' + vd).removeClass('hidden');

    bind.find('.media_body').children('iframe').attr('src', '');
    bind.find('.media_body.vd_' + vd).children('iframe').attr('src', bind.find('.media_body.vd_' + vd).attr('data-src'));

}
function jsbindpopupclose() {
    var body = jQuery('body');
    body.find('.overlay_bg_1').addClass('hidden');
    body.find('.overlay_bg_2').addClass('hidden');
}
function jsbindcateqr() {
    var body = jQuery('body');
    var html = '<div class="mfp-bg mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_1"></div>';

    body.prepend(jQuery('.overlay_bg_2'));
    body.find('.overlay_bg_2').removeClass('hidden');

    if (body.find('.overlay_bg_1').length) {
        body.find('.overlay_bg_1').removeClass('hidden');
    } else {
        body.prepend(html);
    }
}

//popup
function jspopupaddress(ele, type) {
    var frm = jQuery('#frm_popup_address');

    jQuery.ajax({
        url: gks.baseURL + '/get-opts',
        type: 'post',
        data: {
            id: ele.value,
            type: type,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            if (type === 'district') {
                frm.find('select[name=district_id]').empty().append('<option value="">Hãy chọn quận / huyện</option>');
                frm.find('select[name=ward_id]').empty().append('<option value="">Hãy chọn phường / xã</option>');
            } else if (type === 'ward') {
                frm.find('select[name=ward_id]').empty().append('<option value="">Hãy chọn phường / xã</option>');
            }
        },
        success: function (response) {
            var html = '';

            if (response.VALID) {
                if (type === 'district') {
                    html += '<option value="">Hãy chọn quận / huyện</option>';

                } else if (type === 'ward') {
                    html += '<option value="">Hãy chọn phường / xã</option>';
                }

                response.ARR.forEach(function (ele, pos) {
                    html += '<option value="' + ele.id + '">' + ele.title + '</option>';
                });

                if (type === 'district') {
                    frm.find('select[name=district_id]').empty().append(html);

                } else if (type === 'ward') {
                    frm.find('select[name=ward_id]').empty().append(html);
                }
            }
        }
    });
}

//mobi
function jsbindmobimenu() {
    if (jQuery('#header-menu').hasClass('has-open')) {
        jQuery('#header-menu').removeClass('has-open');
    } else {
        jQuery('#header-menu').addClass('has-open');
    }
}
function jsbindmobimenucate(ele) {
    jQuery(ele).parent().toggleClass('open-submenu');
}
function jsbindmobimenuside(menu) {
    jQuery('.mb_cat_true .mb_nav_title').removeClass('active');
    jQuery('#nt_menu_canvas .mb_nav_tab').removeClass('active');

    jQuery('.mb_cat_true .' + menu).addClass('active');
    jQuery('#nt_menu_canvas .' + menu).addClass('active');
}
function jsbindmobifooter(ele) {
    var bind = jQuery(ele);
    if (bind.find('.widget_footer').hasClass('active')) {
        bind.find('.widget_footer').removeClass('active');
        bind.removeClass('footer_opened');
    } else {
        bind.find('.widget_footer').addClass('active');
        bind.addClass('footer_opened');
    }
}

//cart
function jscartdh(pid) {
    var quantity = 1;
    if (jQuery('#product_buy_quantity').length) {
        quantity = parseInt(jQuery('#product_buy_quantity').val());

        if (!quantity || quantity < 1) {
            quantity = 1;
            jQuery('#product_buy_quantity').val(1);
        }
    }

    jQuery.ajax({
        url: gks.baseURL + '/sp/buy',
        type: 'post',
        data: {
            pid: pid,
            quantity: quantity,
            _token: gks.tempTK,
        },
        success: function (response) {
            var cart = jQuery('#frm-cart');

            if (!cart.length) {
                jQuery('#frm-cart_side').empty().append(response.BODY);

                jsbindmf();

                jQuery('#cart_side_open')[0].click();
            }

            jQuery('#count-cart-sp').text(response.COUNT);
        }
    });
}
function jscartdhcombo(pid) {
    var ele = jQuery('#cart_combo_' + pid);
    var quantity = 1;
    var temp = parseInt(ele.find('input[name=combo_quantity]').val());

    if (!temp || temp < 1) {
        ele.find('input[name=combo_quantity]').val(1);
    } else {
        quantity = temp;
    }

    jQuery.ajax({
        url: gks.baseURL + '/sp/buy',
        type: 'post',
        data: {
            pid: pid,
            quantity: quantity,
            _token: gks.tempTK,
        },
        success: function (response) {
            var cart = jQuery('#frm-cart');

            if (!cart.length) {
                jQuery('#frm-cart_side').empty().append(response.BODY);

                jsbindmf();

                jQuery('#cart_side_open')[0].click();
            }

            jQuery('#count-cart-sp').text(response.COUNT);
        }
    });

}
function jscartdhx(ite) {
    jQuery('#c_ite_' + ite).remove();

    jQuery.ajax({
        url: gks.baseURL + '/sp/remove',
        type: 'post',
        data: {
            ite: ite,
            _token: gks.tempTK,
        },
        success: function (response) {
            jQuery('#count-love-sp').text(response.count);

            if (!jQuery('.cart_items .c_ite').length) {
                parent.window.location.href = gks.baseURL + '/';
            }
        }
    });

    jscartdhcal();
}
function jscartdhmqvalid(ele) {
    var bind = jQuery(ele);
    var value = parseInt(bind.val());

    if (value <= 0 || !value || value === '') {
        value = '';
    } else if (value >= 100) {
        value = 99;
    }

    bind.val(value);
}
function jscartdhmq(ite) {
    var bind = jQuery('#c_ite_' + ite);

    //prevent
    var value = parseInt(bind.find('input[name=quantity]').val());

    if (value <= 0 || !value || value === '') {
        value = 1;
    } else if (value >= 100) {
        value = 99;
    }

    bind.find('input[name=quantity]').val(value);

    //cal
    var iteOne = parseInt(bind.find('input[name=ite_one]').val());
    bind.find('.one_total').text(iteOne * value);
    jsbindmf();
    jscartdhcal();
}
function jscartdhmu(ite) {
    setTimeout(function () {
        jscartdhmq(ite);
    }, 333);
}
function jscartdhvalid(valid) {
    var frm = jQuery('#frm-cart');

    if (valid) {
        frm.find('.btn_checkout').removeAttr('disabled');
    } else {
        frm.find('.btn_checkout').attr('disabled', 'disabled');
    }
}
function jscartdhcal() {
    var frm = jQuery('#frm-cart');
    var ids = '';
    var totalAll = 0;
    var totalPaid = 0;
    var totalPaidNoShip = 0;
    var totalDiscount = 0;
    var percentDiscount = parseFloat(frm.find('input[name=percent_discount]').val());
    var overCart = parseInt(frm.find('input[name=over_cart]').val());
    var totalShip = parseInt(frm.find('input[name=ghn_fee]').val());
    var freeCity = parseInt(frm.find('input[name=free_city]').val());
    var freeShip = false;
    frm.find('#cart_shipping').removeClass('line_through');

    var percentDiscountGG = parseFloat(frm.find('input[name=discount_gg]').val());

    frm.find('.cart_items .c_ite').each(function (pos, ele) {
        var bind = jQuery(ele);
        var iteId = parseInt(bind.attr('data-id'));
        var iteOne = parseInt(bind.find('input[name=ite_one]').val());
        var iteQua = bind.find('input[name=quantity]').val() !== '' ? parseInt(bind.find('input[name=quantity]').val()) : 0;
        totalAll += parseInt(iteQua * iteOne);

        ids += iteId + '_' + iteQua + ';';
    });

    if (percentDiscountGG > 0) {
        totalDiscount = parseInt(totalAll * percentDiscountGG / 100);
        jQuery('.ma_giam_gia').removeClass('hidden');
    }

    totalPaidNoShip = totalAll - totalDiscount;

    //shipping fee
    if (overCart > 0 && totalPaidNoShip >= overCart && freeCity) {
        //free
        totalPaid = totalPaidNoShip;
        jscartdhvalid(true);
        freeShip = true;
        frm.find('#cart_shipping').addClass('line_through');
    } else {
        //must update again
        totalPaid = totalPaidNoShip + totalShip;
    }

    frm.find('#cart_all').text(totalAll);
    frm.find('#cart_discount').text(totalDiscount);
    frm.find('#cart_total').text(totalPaid);

    frm.find('#frm-ids').val(ids);
    frm.find('input[name=total_all]').val(totalAll);
    frm.find('input[name=total_discount]').val(totalDiscount);
    frm.find('input[name=total_paid]').val(totalPaid);
    frm.find('input[name=total_paid_no_ship]').val(totalPaidNoShip);

    jsbindmf();

    jscartdhship();
}
function jscartdhcalsp() {
    var frm = jQuery('#frm-cart');
    var ids = '';
    var totalAll = 0;
    var totalPaid = 0;
    var totalPaidNoShip = 0;
    var totalDiscount = 0;
    var percentDiscount = parseFloat(frm.find('input[name=percent_discount]').val());
    var overCart = parseInt(frm.find('input[name=over_cart]').val());
    var totalShip = parseInt(frm.find('input[name=ghn_fee]').val());
    var freeCity = parseInt(frm.find('input[name=free_city]').val());
    var freeShip = false;
    frm.find('#cart_shipping').removeClass('line_through');

    var percentDiscountGG = parseFloat(frm.find('input[name=discount_gg]').val());

    frm.find('.cart_items .c_ite').each(function (pos, ele) {
        var bind = jQuery(ele);
        var iteId = parseInt(bind.attr('data-id'));
        var iteOne = parseInt(bind.find('input[name=ite_one]').val());
        var iteQua = bind.find('input[name=quantity]').val() !== '' ? parseInt(bind.find('input[name=quantity]').val()) : 0;
        totalAll += parseInt(iteQua * iteOne);

        ids += iteId + '_' + iteQua + ';';
    });

    if (percentDiscountGG > 0) {
        totalDiscount = parseInt(totalAll * percentDiscountGG / 100);
        jQuery('.ma_giam_gia').removeClass('hidden');
    }

    totalPaidNoShip = totalAll - totalDiscount;

    //shipping fee
    if (overCart > 0 && totalPaidNoShip >= overCart && freeCity) {
        //free
        totalPaid = totalPaidNoShip;
        freeShip = true;
        frm.find('#cart_shipping').addClass('line_through');
    } else {
        //no update
        totalPaid = totalPaidNoShip + totalShip;
    }

    frm.find('#cart_all').text(totalAll);
    frm.find('#cart_discount').text(totalDiscount);
    frm.find('#cart_total').text(totalPaid);

    frm.find('#frm-ids').val(ids);
    frm.find('input[name=total_all]').val(totalAll);
    frm.find('input[name=total_discount]').val(totalDiscount);
    frm.find('input[name=total_paid]').val(totalPaid);
    frm.find('input[name=total_paid_no_ship]').val(totalPaidNoShip);

    jsbindmf();

    jscartdhvalid(true);
}
function jscartdhship() {
    var frm = jQuery('#frm-cart');

    jQuery.ajax({
        url: gks.baseURL + '/dh/pgh',
        type: 'post',
        data: {
            ids: frm.find('#frm-ids').val(),
            // express: ghnExpress,
            province_id: frm.find('select[name=province_id]').val(),
            district_id: frm.find('select[name=district_id]').val(),
            ward_id: frm.find('select[name=ward_id]').val(),
            _token: gks.tempTK,
        },
        success: function (response) {
            if (response.VALID) {
                frm.find('input[name=free_city]').val(response.FREE_CITY);
                // frm.find('input[name=ghn_fee]').val(response.FEE);
                // frm.find('#cart_shipping').text(response.FEE);
                // frm.find('#cart_delivery_time').text(response.TIME);

                jscartdhcalsp();
            } else {
                //ghn error or not enough info
            }
        },
    });
}
function jscartbs() {
    var frm = jQuery('#frm-cart');
    var valid = true;
    var html = '';
    frm.find('div[id^=ele-] .alert').remove();

    var address = frm.find('input[name=address]').val().trim();
    var province = frm.find('select[name=province_id]').val();
    var district = frm.find('select[name=district_id]').val();
    var ward = frm.find('select[name=ward_id]').val();

    if (!parseInt(gks.user)) {
        var name = frm.find('input[name=name]').val().trim();

        if (!name || name === '') {
            frmFocus(frm.find('input[name=name]'));
            setFocusAt(frm.find('input[name=name]'));

            html = '<div class="alert alert-danger mt__10 mb__10">Vui lòng nhập họ tên.</div>';
            frm.find('#ele-name .alert').remove();
            frm.find('#ele-name input').after(html);

            valid = false;
        }

        var phone = frm.find('input[name=phone]').val().trim();

        if (!phone || phone === '' || phone.length < 10) {
            // frm.find('input[name=phone]').val('');
            frmFocus(frm.find('input[name=phone]'));
            setFocusAt(frm.find('input[name=phone]'));

            html = '<div class="alert alert-danger mt__10 mb__10">Vui lòng nhập số điện thoại (>= 10 số).</div>';
            frm.find('#ele-phone .alert').remove();
            frm.find('#ele-phone input').after(html);

            valid = false;
        }

        /*
        var email = frm.find('input[name=email]').val().trim();
        var regexEmail = new RegExp(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-z]{2,4})$/);

        if (!email || !regexEmail.test(email)) {
            frmFocus(frm.find('input[name=email]'));
            setFocusAt(frm.find('input[name=email]'));

            html = '<div class="alert alert-danger mt__10 mb__10">Vui lòng nhập email hợp lệ.</div>';
            frm.find('#ele-email .alert').remove();
            frm.find('#ele-email input').after(html);

            valid = false;
        }
        */
    }

    if (!address || address === '') {
        frm.find('input[name=address]').val('');
        frmFocus(frm.find('input[name=address]'));
        setFocusAt(frm.find('input[name=address]'));

        html = '<div class="alert alert-danger mt__10 mb__10">Vui lòng nhập rõ địa chỉ.</div>';
        frm.find('#ele-address .alert').remove();
        frm.find('#ele-address input').after(html);

        valid = false;
    }

    if (!province) {
        html = '<div class="alert alert-danger mt__10 mb__10">Chọn tỉnh / thành.</div>';
        frm.find('#ele-province .alert').remove();
        frm.find('select[name=province_id]').after(html);

        valid = false;
    }

    if (!district) {
        html = '<div class="alert alert-danger mt__10 mb__10">Chọn quận / huyện.</div>';
        frm.find('#ele-district .alert').remove();
        frm.find('select[name=district_id]').after(html);

        valid = false;
    }

    if (!ward) {
        html = '<div class="alert alert-danger mt__10 mb__10">Chọn phường / xã.</div>';
        frm.find('#ele-ward .alert').remove();
        frm.find('select[name=ward_id]').after(html);

        valid = false;
    }

    if (!valid) {
        return false;
    }

    frm.find('input[name=express]').val(ghnExpress);

    //xac nhan
    jscartdhpopup();

    return false;
}
function jscartdhdl() {
    var frm = jQuery('#frm-cart');

    var formData = new FormData();

    var data = {
        _token: gks.tempTK,
        ids: frm.find('input[name=ids]').val(),
        payment_by: frm.find('input[name=payment_by]').val(),
        note: frm.find('textarea[name=note]').val(),
        name: frm.find('input[name=name]').length ? frm.find('input[name=name]').val() : '',
        phone: frm.find('input[name=phone]').length ? frm.find('input[name=phone]').val() : '',
        email: frm.find('input[name=email]').length ? frm.find('input[name=email]').val() : '',
        address: frm.find('input[name=address]').val(),
        province_id: frm.find('select[name=province_id]').val(),
        district_id: frm.find('select[name=district_id]').val(),
        ward_id: frm.find('select[name=ward_id]').val(),
        total_all: frm.find('input[name=total_all]').val(),
        total_paid: frm.find('input[name=total_paid]').val(),
        total_paid_no_ship: frm.find('input[name=total_paid_no_ship]').val(),
        total_discount: frm.find('input[name=total_discount]').val(),
        discount_gg: frm.find('input[name=discount_gg]').val(),
        over_cart: frm.find('input[name=over_cart]').val(),
        ghn_fee: frm.find('input[name=ghn_fee]').val(),
        express: frm.find('input[name=express]').val(),
        coupon: frm.find('input[name=coupon]').val(),
        ref_code: frm.find('input[name=ref_code]').val(),

        cau_chuc_co_san: frm.find('input[name=cau_chuc_co_san]').is(':checked') ? 1 : 0,
        cau_chuc_tu_viet: frm.find('textarea[name=cau_chuc_tu_viet]').val(),
        cau_chuc_id: frm.find('select[name=cate_wish_id]').val(),
        mau_thiep_co_san: frm.find('input[name=mau_thiep_co_san]').is(':checked') ? 1 : 0,
        mau_thiep_id: frm.find('select[name=cate_card_id]').val(),

        person_id: frm.find('select[name=person_id]').val(),
        date_id: frm.find('select[name=date_id]').val(),
        address_id: frm.find('input[name=dia_chi_1]').is(':checked') ? 1 : 0,
    };

    // Read selected files
    var totalfiles = document.getElementById('mau_thiep_tu_viet').files.length;
    for (var index = 0; index < totalfiles; index++) {
        formData.append("files[]", document.getElementById('mau_thiep_tu_viet').files[index]);
    }

    formData.append("_token", gks.tempTK);
    formData.append("data", JSON.stringify(data));

    return formData;
}
function jscartdhxntt1() {
    jQuery.ajax({
        url: gks.baseURL + '/dh/cod',
        type: 'post',
        contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
        processData: false, // NEEDED, DON'T OMIT THIS
        data: jscartdhdl(),
        success: function (response) {
            location.href = response.URL;
        }
    });
}
function jscartdhxntt2() {
    jQuery.ajax({
        url: gks.baseURL + '/dh/vnpay',
        type: 'post',
        data: jscartdhdl(),
        success: function (response) {
            location.href = response.URL;
        }
    });
}
function jscartdhxntt3() {
    jQuery.ajax({
        url: gks.baseURL + '/dh/zalopay',
        type: 'post',
        data: jscartdhdl(),
        success: function (response) {
            location.href = response.URL;
        }
    });
}
function jscartpttt(value) {
    var frm = jQuery('#frm-cart');
    frm.find('input[name=payment_by]').val(value);

    frm.find('.cart_payment').removeClass('active');
    frm.find('.cart_payment.' + value).addClass('active');
}
function jscartshipment(value) {
    var frm = jQuery('#frm-cart');

    frm.find('.cart_ship').removeClass('active');
    frm.find('.cart_ship.' + value).addClass('active');

    ghnExpress = 0;
    if (value === 'gh_nhanh') {
        ghnExpress = 1;
    }
}
function jscartaddressopts(ele, type) {
    var frm = jQuery('#frm-signup');
    if (jQuery('#frm-ctv').length) {
        frm = jQuery('#frm-ctv');
    }
    if (jQuery('#frm-address').length) {
        frm = jQuery('#frm-address');
    }

    jQuery.ajax({
        url: gks.baseURL + '/get-opts',
        type: 'post',
        data: {
            id: ele.value,
            type: type,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            if (type === 'district') {
                frm.find('select[name=district_id]').empty().append('<option value="">Hãy chọn quận / huyện</option>');
                frm.find('select[name=ward_id]').empty().append('<option value="">Hãy chọn phường / xã</option>');
            } else if (type === 'ward') {
                frm.find('select[name=ward_id]').empty().append('<option value="">Hãy chọn phường / xã</option>');
            }
        },
        success: function (response) {
            var html = '';

            if (response.VALID) {
                if (type === 'district') {
                    html += '<option value="">Hãy chọn quận / huyện</option>';

                } else if (type === 'ward') {
                    html += '<option value="">Hãy chọn phường / xã</option>';
                }

                response.ARR.forEach(function (ele, pos) {
                    html += '<option value="' + ele.id + '">' + ele.title + '</option>';
                });

                if (type === 'district') {
                    frm.find('select[name=district_id]').empty().append(html);

                } else if (type === 'ward') {
                    frm.find('select[name=ward_id]').empty().append(html);
                }
            }
        }
    });
}
function jscartdhpopup() {
    var body = jQuery('body');
    var html = '<div class="mfp-bg mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_1"></div>';

    body.prepend(jQuery('.overlay_bg_2'));
    body.find('.overlay_bg_2').removeClass('hidden');

    if (body.find('.overlay_bg_1').length) {
        body.find('.overlay_bg_1').removeClass('hidden');
    } else {
        body.prepend(html);
    }
}
function jscartdhpopupok() {
    var frm = jQuery('#frm-cart');
    var popup = jQuery('.popup_gks');
    var phuongThuc = frm.find('input[name=payment_by]').val();

    popup.find('button').hide();
    popup.find('tfoot td').append(gks.loadingIMG);
    popup.find('.js-loading').addClass('text-right').addClass('mr__10');

    // ktra
    if (phuongThuc === 'cod' || phuongThuc === 'banking') {
        //xac thuc qua dt sms

        jscartdhxntt1();
    } else if (phuongThuc === 'vnpay') {
        //online
        jscartdhvalid(false);
        jscartdhxntt2();
    } else if (phuongThuc === 'zalopay') {
        //online
        jscartdhvalid(false);
        jscartdhxntt3();
    }
}
function jscartcc(ele) {
    var frm = jQuery('#req-wish');
    var bind = jQuery(ele);
    if (bind.is(':checked')) {
        frm.find('.tu_viet').addClass('hidden');
        frm.find('.co_san').removeClass('hidden');
    } else {
        frm.find('.tu_viet').removeClass('hidden');
        frm.find('.co_san').addClass('hidden');
    }
}
function jscartcscc(ele) {
    var bind = jQuery('#req-wish');
    jQuery.ajax({
        url: gks.baseURL + '/get-opts-wish',
        type: 'post',
        data: {
            id: ele.value,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            bind.find('select[name=cate_wish_id]').empty().append('<option value="">Hãy chọn câu chúc</option>');
        },
        success: function (response) {
            var html = '<option value="">Hãy chọn câu chúc</option>';

            if (response.VALID) {
                response.ARR.forEach(function (ele, pos) {
                    html += '<option value="' + ele.id + '">' + ele.title + '</option>';
                });

                bind.find('select[name=cate_wish_id]').empty().append(html);
            }
        }
    });
}
function jscartcsccget(ele) {
    var bind = jQuery('#req-wish');
    jQuery.ajax({
        url: gks.baseURL + '/get-wish',
        type: 'post',
        data: {
            id: ele.value,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            bind.find('.cau_chuc_temp').empty();
        },
        success: function (response) {
            if (response.VALID) {
                bind.find('.cau_chuc_temp').append(response.BODY);
            }
        }
    });
}
function jscartmt(ele) {
    var frm = jQuery('#req-card');
    var bind = jQuery(ele);
    if (bind.is(':checked')) {
        frm.find('.tu_viet').addClass('hidden');
        frm.find('.co_san').removeClass('hidden');
    } else {
        frm.find('.tu_viet').removeClass('hidden');
        frm.find('.co_san').addClass('hidden');
    }
}
function jscartcsmt(ele) {
    var bind = jQuery('#req-card');
    jQuery.ajax({
        url: gks.baseURL + '/get-opts-card',
        type: 'post',
        data: {
            id: ele.value,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            bind.find('select[name=cate_card_id]').empty().append('<option value="">Hãy chọn mẫu thiệp</option>');
        },
        success: function (response) {
            var html = '<option value="">Hãy chọn mẫu thiệp</option>';

            if (response.VALID) {
                response.ARR.forEach(function (ele, pos) {
                    html += '<option value="' + ele.id + '">' + ele.title + '</option>';
                });

                bind.find('select[name=cate_card_id]').empty().append(html);
            }
        }
    });
}
function jscartcsmtget(ele) {
    var bind = jQuery('#req-card');
    jQuery.ajax({
        url: gks.baseURL + '/get-card',
        type: 'post',
        data: {
            id: ele.value,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            bind.find('.mau_thiep_temp').empty();
        },
        success: function (response) {
            if (response.VALID) {
                if (response.ARR.length) {
                    var html = '';

                    response.ARR.forEach(function (ele, pos) {
                        if (ele !== '') {
                            html += '<div class="img-item">';
                            html += '<div class="img-preview">';
                            html += '<img src="' + ele + '" />';
                            html += '</div>';
                            html += '</div>';
                        }
                    });

                    bind.find('.mau_thiep_temp').append(html);
                }
            }
        }
    });
}
function jscartgetdate(ele) {
    var frm = jQuery('#frm-cart');
    var person = ele.value;
    jQuery.ajax({
        url: gks.baseURL + '/dh/g-d',
        type: 'post',
        data: {
            person_id: person,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            frm.find('select[name=date_id]').addClass('hidden');

            frm.find('input[name=address_2]').val('');
            frm.find('input[name=ward_id_2]').val('');
            frm.find('input[name=district_id_2]').val('');
            frm.find('input[name=province_id_2]').val('');

            if (!person || person === '') {
                if (!frm.find('input[name=dia_chi_1]').is(':checked')) {
                    jscartdcnh(1);
                }
            }
        },
        success: function (response) {
            var html = '<option value="">Không tìm thấy sự kiện nào.</option>';
            if (response.ARR && response.ARR.length) {
                html = '<option value="">Hãy chọn sự kiện</option>';
                response.ARR.forEach(function (ele, pos) {
                    html += '<option value="' + ele.id + '">' + ele.title + '</option>';
                });
            }

            frm.find('select[name=date_id]').empty()
                .append(html)
                .removeClass('hidden');

            if (response.DC) {
                frm.find('input[name=address_2]').val(response.DC.address);
                frm.find('input[name=ward_id_2]').val(response.DC.ward_id);
                frm.find('input[name=district_id_2]').val(response.DC.district_id);
                frm.find('input[name=province_id_2]').val(response.DC.province_id);
            }

            frm.find('input[name=quan_huyen_2]').val(response.DISTRICTS);
            frm.find('input[name=phuong_xa_2]').val(response.WARDS);

            if (!frm.find('input[name=dia_chi_1]').is(':checked')) {
                jscartdcnh(2);
            }
        }
    });
}
function jscartdcnh(to) {
    var frm = jQuery('#frm-cart');
    var arr = [];
    var html = '';
    if (to === 1) {
        frm.find('input[name=dia_chi_1]').prop('checked', true);
        frm.find('input[name=dia_chi_2]').prop('checked', false);

        //opts
        arr = frm.find('input[name=quan_huyen_1]').val() !== '' ? JSON.parse(frm.find('input[name=quan_huyen_1]').val()) : [];
        if (arr.length) {
            html = '<option value="">Hãy chọn quận / huyện</option>';
            arr.forEach(function (ele, pos) {
                html += '<option value="' + ele.id + '">' + ele.title + '</option>';
            });
            frm.find('select[name=district_id]').empty()
                .append(html);
        }
        arr = frm.find('input[name=phuong_xa_1]').val() !== '' ? JSON.parse(frm.find('input[name=phuong_xa_1]').val()) : [];
        if (arr.length) {
            html = '<option value="">Hãy chọn phường / xã</option>';
            arr.forEach(function (ele, pos) {
                html += '<option value="' + ele.id + '">' + ele.title + '</option>';
            });
            frm.find('select[name=ward_id]').empty()
                .append(html);
        }

        frm.find('input[name=address]').val(frm.find('input[name=address_1]').val());
        frm.find('select[name=ward_id]').val(frm.find('input[name=ward_id_1]').val());
        frm.find('select[name=district_id]').val(frm.find('input[name=district_id_1]').val());
        frm.find('select[name=province_id]').val(frm.find('input[name=province_id_1]').val());
    } else {
        frm.find('input[name=dia_chi_2]').prop('checked', true);
        frm.find('input[name=dia_chi_1]').prop('checked', false);

        //opts
        arr = frm.find('input[name=quan_huyen_2]').val() !== '' ? JSON.parse(frm.find('input[name=quan_huyen_2]').val()) : [];
        if (arr.length) {
            html = '<option value="">Hãy chọn quận / huyện</option>';
            arr.forEach(function (ele, pos) {
                html += '<option value="' + ele.id + '">' + ele.title + '</option>';
            });
            frm.find('select[name=district_id]').empty()
                .append(html);
        }
        arr = frm.find('input[name=phuong_xa_2]').val() !== '' ? JSON.parse(frm.find('input[name=phuong_xa_2]').val()) : [];
        if (arr.length) {
            html = '<option value="">Hãy chọn phường / xã</option>';
            arr.forEach(function (ele, pos) {
                html += '<option value="' + ele.id + '">' + ele.title + '</option>';
            });
            frm.find('select[name=ward_id]').empty()
                .append(html);
        }

        frm.find('input[name=address]').val(frm.find('input[name=address_2]').val());
        frm.find('select[name=ward_id]').val(frm.find('input[name=ward_id_2]').val());
        frm.find('select[name=district_id]').val(frm.find('input[name=district_id_2]').val());
        frm.find('select[name=province_id]').val(frm.find('input[name=province_id_2]').val());
    }

    jscartdhship();
}

//side
function jscartdhsq(id) {
    var bind = jQuery('#cart_side_item_' + id);

    //prevent
    var value = parseInt(bind.find('input[name=quantity]').val());

    if (value <= 0 || !value || value === '') {
        value = 1;
    } else if (value >= 100) {
        value = 99;
    }

    bind.find('input[name=quantity]').val(value);

    setTimeout(function() {
        cartSideUpdate(id);
    }, 111);
}
function jscartdhsqd(id) {
    var bind = jQuery('#cart_side_item_' + id);

    var value = parseInt(bind.find('input[name=quantity]').val()) - 1;

    if (!value || value <= 0) {
        bind.remove();
    }

    if (!jQuery('#frm-cart_side .cart_side_item').length) {
        jQuery('#nt_cart_canvas .close_pp')[0].click();
    }

    setTimeout(function() {
        cartSideUpdate(id);
    }, 111);
}
function jscartdhsqu(id) {
    var bind = jQuery('#cart_side_item_' + id);

    setTimeout(function() {
        cartSideUpdate(id);
    }, 111);
}

//sp
function jssplove(ele, pid) {
    var user = parseInt(gks.user);
    // if (!user) {
    //     parent.window.location.href = gks.baseURL + '/dang-nhap';
    //     return false;
    // }

    if (jQuery(ele).find('i').hasClass('active')) {
        jQuery('.sp-love-' + pid).find('i').removeClass('active').removeAttr('title').attr('title', 'Thêm SP Yêu Thích');
    } else {
        jQuery('.sp-love-' + pid).find('i').addClass('active').removeAttr('title').attr('title', 'Đã Yêu Thích SP');

        jQuery('#add-to-love').fadeIn(333);
        // jQuery('#modalCartAdded').toggleClass('show');

        setTimeout(function () {
            jQuery('#add-to-love').fadeOut(333);
            // jQuery('#modalCartAdded').toggleClass('show');
        }, 888);
    }

    jQuery.ajax({
        url: gks.baseURL + '/sp/love',
        type: 'post',
        data: {
            pid: pid,
            _token: gks.tempTK,
        },
        success: function (response) {
            jQuery('#count-love-sp').text(response.count);
        }
    });


}
function jssptab(tab) {
    jQuery('.tab_product_info li').removeClass('active');
    jQuery('.p-body .p-body-content').addClass('hidden');

    jQuery('.tab_product_info li.' + tab).addClass('active');
    jQuery('.p-body .p-body-content.' + tab).removeClass('hidden');
}
function jsspdg() {
    var frm = jQuery('#frm-review');
    var user = parseInt(gks.user);
    var valid = true;
    var phone = '';
    var email = '';
    var note = '';
    var star = 0;
    frm.find('.card-body .alert').addClass('hidden');
    frm.find('.card-footer .alert').addClass('hidden');

    if (!user) {
        phone = frm.find('input[name=phone]').val().trim();
        email = frm.find('input[name=email]').val().trim();
        var regexEmail = new RegExp(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-z]{2,4})$/);

        if (!phone || phone === '' || phone.length < 10) {
            frmFocus(frm.find('input[name=phone]'));
            setFocusAt(frm.find('input[name=phone]'));

            frm.find('#ele-phone .alert').removeClass('hidden');
            valid = false;
        }

        // if (!email || !regexEmail.test(email)) {
        //     frmFocus(frm.find('input[name=email]'));
        //     setFocusAt(frm.find('input[name=email]'));
        //
        //     frm.find('#ele-email .alert').removeClass('hidden');
        //
        //     valid = false;
        // }
    }

    note = frm.find('textarea[name=note]').val().trim();
    if (!note || note === '') {
        frmFocus(frm.find('textarea[name=note]'));
        setFocusAt(frm.find('textarea[name=note]'));

        frm.find('#ele-note .alert').removeClass('hidden');
        valid = false;
    }

    if (!valid) {
        return false;
    }

    star = parseInt(frm.find('input[name=staring]:checked').val());
    if (!star || star <= 0) {
        frm.find('.card-footer .alert').addClass('alert-danger').removeClass('alert-success');
        frm.find('.card-footer .alert').removeClass('hidden').text("Vui lòng đánh giá sao cho sản phẩm");
        return false;
    }

    jQuery.ajax({
        url: gks.baseURL + '/sp/review',
        type: 'post',
        data: {
            item_id: frm.find('input[name=item_id]').val(),
            phone: phone,
            email: email,
            note: note,
            star: star,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            frm.find('.card-footer button').hide();
            frm.find('.card-footer button').after(gks.loadingIMG);
        },
        success: function (response) {
            frm.find('.card-footer .alert').removeClass('hidden');
            frm.find('.card-footer .js_loading').remove();

            if (response.VALID) {
                jsspdgreset();

                if (response.BODY && response.BODY !== '') {
                    jQuery('#count_rev_' + star).text(response.COUNT);

                    jQuery('.review_wrapper').prepend(response.BODY);

                    frm.find('.card-footer .alert').removeClass('alert-danger')
                        .addClass('alert-success').text("Cảm ơn đánh giá của bạn về sản phẩm!");
                } else {
                    frm.find('.card-footer .alert').removeClass('alert-danger')
                        .addClass('alert-success').text("Cảm ơn đánh giá của bạn về sản phẩm! Chúng tôi cần kiểm duyệt vài thông tin trước khi công khai đánh giá của bạn.");
                }
            } else {
                frm.find('.card-footer .alert').addClass('alert-danger').removeClass('alert-success');

                if (response.ERR === 'EXIST') {
                    frm.find('.card-footer .alert').text("Bạn đã đánh giá sản phẩm này!");
                } else if (response.ERR === 'NONE') {
                    frm.find('.card-footer .alert').text("Bạn chưa đặt mua thành công sản phẩm này!");
                } else {
                    frm.find('.card-footer .alert').text(gks.saveERR);
                }
            }

            setTimeout(function () {
                frm.find('.card-footer button').show();
                frm.find('.card-footer .js-loading').remove();
            }, 1888);
        }
    });
}
function jsspdgreset() {
    var frm = jQuery('#frm-review');
    var user = parseInt(gks.user);

    if (!user) {
        frm.find('input[name=phone]').val('');
        frm.find('input[name=email]').val('');
    }

    frm.find('textarea[name=note]').val('');
}
function jsspdgmore() {
    var frm = jQuery('#frm-review');
    jQuery.ajax({
        url: gks.baseURL + '/sp/review-more',
        type: 'post',
        data: {
            item_id: frm.find('input[name=item_id]').val(),
            max: jQuery('.review_wrapper .review_item').last().attr('data-id'),
            _token: gks.tempTK,
        },
        beforeSend: function () {
            jQuery('.review_more').hide();
        },
        success: function (response) {
            if (response.VALID) {
                jQuery('.review_wrapper').append(response.BODY);

                jQuery('.review_more').show();
            }
        }
    });
}
function jsspvideo(URL) {
    var body = jQuery('body');
    var html = '<div class="mfp-bg mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_1"></div>';

    body.prepend(jQuery('.overlay_bg_2'));
    body.find('.overlay_bg_2').removeClass('hidden');

    if (body.find('.overlay_bg_1').length) {
        body.find('.overlay_bg_1').removeClass('hidden');
    } else {
        body.prepend(html);
    }
}
function jsspvideoclose() {
    var body = jQuery('body');
    body.find('.overlay_bg_1').addClass('hidden');
    body.find('.overlay_bg_2').addClass('hidden');
}
function jsspttct() {
    jQuery('#ttct_button').toggleClass('hidden');
    jQuery('#ttct_body').toggleClass('hidden');
}
function jsspttkh(item_type, item_id) {
    jQuery('#ttct_button_' + item_type + '_' + item_id).toggleClass('hidden');
    jQuery('#ttct_body_' + item_type + '_' + item_id).toggleClass('hidden');
    jQuery('#ttct_mtn_' + item_type + '_' + item_id).toggleClass('hidden');
}
function jsspdgcg(item_type, item_id) {
    jQuery('#dgcg_button_' + item_type + '_' + item_id).toggleClass('hidden');
    jQuery('#dgcg_body_' + item_type + '_' + item_id).toggleClass('hidden');
    jQuery('#dgcg_mtn_' + item_type + '_' + item_id).toggleClass('hidden');
}

//kh
function jskhinfoedit(value) {
    var bind = jQuery('#frm-' + value);
    bind.addClass('edit');
    bind.find('.input .alert').addClass('hidden');
    bind.find('.button_hide').removeClass('hidden');

    jQuery('#input-password').val('');

    setFocusAt(bind.find('.input input'));
}
function jskhinfono(value) {
    var bind = jQuery('#frm-' + value);
    bind.removeClass('edit');
}
function jskhinfook(value) {
    var bind = jQuery('#frm-' + value);
    var input = jQuery('#input-' + value).val().trim();
    if (value === 'password') {
        input = jQuery('#input-' + value).val();
    }

    //address
    var frm = jQuery('#frm-address');
    var provinceId = frm.find('select[name=province_id]').val();
    var districtId = frm.find('select[name=district_id]').val();
    var wardId = frm.find('select[name=ward_id]').val();

    if ((!input || input === '') && (value === 'email' || value === 'name' || value === 'password')) {
        bind.find('.input .alert').removeClass('hidden');
        return false;
    }

    if (value === 'email') {
        var regexEmail = new RegExp(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-z]{2,4})$/);
        if (!regexEmail.test(input)) {
            bind.find('.input .alert').removeClass('hidden').text("Vui lòng nhập email hợp lệ.");
            return false;
        }
    } else if(value === 'phone') {
        if (!input || input.length < 10) {
            bind.find('.input .alert').removeClass('hidden').text("Vui lòng nhập số điện thoại (>= 10 số).");
            return false;
        }
    } else if(value === 'cmnd') {
        if (!input || input.length < 9) {
            bind.find('.input .alert').removeClass('hidden');
            return false;
        }
    }

    jQuery.ajax({
        url: gks.baseURL + '/kh/tt/cn',
        type: 'post',
        data: {
            key: value,
            value: input,
            province_id: provinceId,
            district_id: districtId,
            ward_id: wardId,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            bind.find('.button').addClass('hidden');
            bind.find('.button_hide').removeClass('hidden');
        },
        success: function (response) {
            if (response.VALID) {
                if (value === 'password') {
                    input = '********';
                }

                if (value === 'thong_tin_chuyen_khoan') {
                    bind.find('.txt').empty().append(input);
                } else {
                    bind.find('.txt').text(input);
                    if (value === 'dia_chi_nha') {
                        bind.find('.txt').text(response.ADDRESS);
                    }
                }

                bind.removeClass('edit');

                pushMessage(gks.successUPDATE);

                if (value === 'phone') {
                    window.location.reload(true);
                }
            } else {
                bind.find('.input .alert').removeClass('hidden').text("Email này đã có người sử dụng.");
            }

            bind.find('.button').removeClass('hidden');
            bind.find('.button_hide').addClass('hidden');
        }
    });
}
function jskhpopupperson1() {
    var body = jQuery('body');
    var html = '<div class="mfp-bg mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_1"></div>';
    var popup = jQuery('#popup_person');

    body.prepend(popup);
    popup.removeClass('hidden');

    if (body.find('.overlay_bg_1').length) {
        body.find('.overlay_bg_1').removeClass('hidden');
    } else {
        body.prepend(html);
    }

    popup.find('tfoot').show();
    popup.find('.js_loading').remove();

    popup.find('input[name=item_id]').val('');
    popup.find('input[name=title]').val('');
    popup.find('input[name=address]').val('');
    popup.find('input[name=phone]').val('');
    popup.find('input[name=relationship]').val('');
    popup.find('input[name=address]').val('');
    popup.find('textarea[name=note]').val('');
    popup.find('select[name=relationship_id]').val('');
    popup.find('select[name=ward_id]').val('');
    popup.find('select[name=province_id]').val('');
    popup.find('select[name=district_id]').val('');

    popup.find('.table_title').text('thêm người');
    setFocusAt(popup.find('input[name=title]'));
}
function jskhpopupperson2(id) {
    var body = jQuery('body');
    var html = '<div class="mfp-bg mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_1"></div>';
    var popup = jQuery('#popup_person');
    var bind = jQuery('#person_' + id);
    var arr = [];

    body.prepend(popup);
    popup.removeClass('hidden');

    if (body.find('.overlay_bg_1').length) {
        body.find('.overlay_bg_1').removeClass('hidden');
    } else {
        body.prepend(html);
    }

    popup.find('tfoot').show();
    popup.find('.js_loading').remove();

    //opts
    arr = bind.attr('data-json-district') !== '' ? JSON.parse(bind.attr('data-json-district')) : [];
    if (arr.length) {
        html = '<option value="">Hãy chọn quận / huyện</option>';
        arr.forEach(function (ele, pos) {
            html += '<option value="' + ele.id + '">' + ele.title + '</option>';
        });
        popup.find('select[name=district_id]').empty()
            .append(html);
    }
    arr = bind.attr('data-json-ward') !== '' ? JSON.parse(bind.attr('data-json-ward')) : [];
    if (arr.length) {
        html = '<option value="">Hãy chọn phường / xã</option>';
        arr.forEach(function (ele, pos) {
            html += '<option value="' + ele.id + '">' + ele.title + '</option>';
        });
        popup.find('select[name=ward_id]').empty()
            .append(html);
    }

    popup.find('input[name=item_id]').val(id);
    popup.find('input[name=title]').val(bind.attr('data-title'));
    popup.find('input[name=address]').val(bind.attr('data-address'));
    popup.find('input[name=phone]').val(bind.attr('data-phone'));
    popup.find('input[name=relationship]').val('');
    popup.find('textarea[name=note]').val(bind.attr('data-note'));
    popup.find('select[name=relationship_id]').val(bind.attr('data-relationship'));
    popup.find('select[name=ward_id]').val(bind.attr('data-ward'));
    popup.find('select[name=province_id]').val(bind.attr('data-province'));
    popup.find('select[name=district_id]').val(bind.attr('data-district'));

    popup.find('.table_title').text('sửa thông tin');
    setFocusAt(popup.find('input[name=title]'));
}
function jskhpopuppersonok() {
    var frm = jQuery('#frm-person');
    frm.find('.alert-danger').addClass('hidden');

    var title = frm.find('input[name=title]').val().trim();
    if (!title || title === '') {
        frm.find('#req-title .alert-danger').removeClass('hidden');
        return false;
    }

    jQuery.ajax({
        url: gks.baseURL + '/kh/ds/p',
        type: 'post',
        data: {
            item_id: frm.find('input[name=item_id]').val().trim(),
            title: frm.find('input[name=title]').val().trim(),
            phone: frm.find('input[name=phone]').val().trim(),
            address: frm.find('input[name=address]').val().trim(),
            relationship: frm.find('input[name=relationship]').val().trim(),
            note: frm.find('textarea[name=note]').val().trim(),
            relationship_id: frm.find('select[name=relationship_id]').val(),
            ward_id: frm.find('select[name=ward_id]').val(),
            province_id: frm.find('select[name=province_id]').val(),
            district_id: frm.find('select[name=district_id]').val(),
            _token: gks.tempTK,
        },
        beforeSend: function () {
            frm.find('tfoot').hide();
            frm.find('tfoot').after(gks.loadingIMG);
        },
        success: function (response) {
            jsbindpopupclose();
            jskhsearchperson();
        }
    });

    return false;
}
function jskhsearchperson() {
    var frm = jQuery('.dsct .table_search');
    var body = jQuery('.dsct .table_body');
    jQuery.ajax({
        url: gks.baseURL + '/kh/ds/g-p',
        type: 'post',
        data: {
            keyword: frm.find('input[name=keyword]').val().trim(),
            month: frm.find('select[name=month]').val().trim(),
            relationship_id: frm.find('select[name=relationship_id]').val(),
            _token: gks.tempTK,
        },
        beforeSend: function () {
            body.empty().append(gks.loadingIMG);
        },
        success: function (response) {
            body.empty().append(response.BODY);
            jsbindmf();
        }
    });
}
function jskhpopuppersondel(id) {
    var body = jQuery('body');
    var html = '<div class="mfp-bg mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_1"></div>';
    var popup = jQuery('#popup_person_delete');

    body.prepend(popup);
    popup.removeClass('hidden');

    if (body.find('.overlay_bg_1').length) {
        body.find('.overlay_bg_1').removeClass('hidden');
    } else {
        body.prepend(html);
    }

    popup.find('tfoot').show();
    popup.find('.js_loading').remove();

    popup.find('input[name=item_id]').val(id);
}
function jskhpopuppersondelok() {
    var popup = jQuery('#popup_person_delete');
    var id = popup.find('input[name=item_id]').val();

    jQuery.ajax({
        url: gks.baseURL + '/kh/ds/d-p',
        type: 'post',
        data: {
            item_id: id,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            popup.find('tfoot').hide();
            popup.find('tfoot').after(gks.loadingIMG);

            jQuery('#person_' + id).remove();
        },
        success: function (response) {
            jsbindpopupclose();
        }
    });

    return false;
}
function jskhpopupdate1(id) {
    var body = jQuery('body');
    var html = '<div class="mfp-bg mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_1"></div>';
    var popup = jQuery('#popup_date');

    body.prepend(popup);
    popup.removeClass('hidden');

    if (body.find('.overlay_bg_1').length) {
        body.find('.overlay_bg_1').removeClass('hidden');
    } else {
        body.prepend(html);
    }

    popup.find('tfoot').show();
    popup.find('.js_loading').remove();

    popup.find('input[name=person_id]').val(id);
    popup.find('input[name=item_id]').val('');
    popup.find('input[name=title]').val('');
    popup.find('select[name=day]').val('');
    popup.find('select[name=month]').val('');
    popup.find('input[name=budget]').val('');
    popup.find('textarea[name=note]').val('');

    popup.find('.table_title').text('thêm sự kiện');
    setFocusAt(popup.find('input[name=title]'));
}
function jskhpopupdate2(id) {
    var body = jQuery('body');
    var html = '<div class="mfp-bg mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_1"></div>';
    var popup = jQuery('#popup_date');
    var bind = jQuery('#date_' + id);

    body.prepend(popup);
    popup.removeClass('hidden');

    if (body.find('.overlay_bg_1').length) {
        body.find('.overlay_bg_1').removeClass('hidden');
    } else {
        body.prepend(html);
    }

    popup.find('tfoot').show();
    popup.find('.js_loading').remove();

    popup.find('input[name=item_id]').val(id);
    popup.find('input[name=person_id]').val('');
    popup.find('input[name=title]').val(bind.attr('data-title'));
    popup.find('input[name=budget]').val(bind.attr('data-budget'));
    popup.find('textarea[name=note]').val(bind.attr('data-note'));
    popup.find('select[name=day]').val(bind.attr('data-day'));
    popup.find('select[name=month]').val(bind.attr('data-month'));
    jsbindmf();

    popup.find('.table_title').text('sửa thông tin');
    setFocusAt(popup.find('input[name=title]'));
}
function jskhpopupdateok() {
    var frm = jQuery('#frm-date');
    var personId = frm.find('input[name=person_id]').val();
    var id = frm.find('input[name=item_id]').val();
    frm.find('.alert-danger').addClass('hidden');

    var title = frm.find('input[name=title]').val().trim();
    if (!title || title === '') {
        frm.find('#req-title .alert-danger').removeClass('hidden');
        return false;
    }

    jQuery.ajax({
        url: gks.baseURL + '/kh/ds/pd',
        type: 'post',
        data: {
            item_id: id,
            person_id: personId,
            title: frm.find('input[name=title]').val().trim(),
            day: frm.find('select[name=day]').val().trim(),
            month: frm.find('select[name=month]').val().trim(),
            budget: frm.find('input[name=budget]').val().trim(),
            note: frm.find('textarea[name=note]').val().trim(),
            _token: gks.tempTK,
        },
        beforeSend: function () {
            frm.find('tfoot').hide();
            frm.find('tfoot').after(gks.loadingIMG);
        },
        success: function (response) {
            jsbindpopupclose();
            jskhgetdate(response.PERSON);
        }
    });

    return false;
}
function jskhgetdate(id) {
    var body = jQuery('#person_' + id);
    jQuery.ajax({
        url: gks.baseURL + '/kh/ds/g-pd',
        type: 'post',
        data: {
            person_id: id,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            body.empty().append(gks.loadingIMG);
        },
        success: function (response) {
            body.empty().append(response.BODY);
            jsbindmf();
        }
    });
}
function jskhpopupdatedel(id) {
    var body = jQuery('body');
    var html = '<div class="mfp-bg mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_1"></div>';
    var popup = jQuery('#popup_date_delete');

    body.prepend(popup);
    popup.removeClass('hidden');

    if (body.find('.overlay_bg_1').length) {
        body.find('.overlay_bg_1').removeClass('hidden');
    } else {
        body.prepend(html);
    }

    popup.find('tfoot').show();
    popup.find('.js_loading').remove();

    popup.find('input[name=item_id]').val(id);
}
function jskhpopupdatedelok() {
    var popup = jQuery('#popup_date_delete');
    var id = popup.find('input[name=item_id]').val();

    jQuery.ajax({
        url: gks.baseURL + '/kh/ds/d-pd',
        type: 'post',
        data: {
            item_id: id,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            popup.find('tfoot').hide();
            popup.find('tfoot').after(gks.loadingIMG);
        },
        success: function (response) {
            jsbindpopupclose();

            var body = jQuery('#person_' + response.PERSON);
            body.empty().append(response.BODY);

            jsbindmf();
        }
    });

    return false;
}
function jskhcart() {
    var frm = jQuery('.dhdd .table_search');
    var body = jQuery('#cart_found');
    jQuery.ajax({
        url: gks.baseURL + '/kh/dh/g-c',
        type: 'post',
        data: {
            person_id: frm.find('select[name=person_id]').val(),
            relationship_id: frm.find('select[name=relationship_id]').val(),
            month: frm.find('select[name=month]').val(),
            year: frm.find('select[name=year]').val(),
            _token: gks.tempTK,
        },
        beforeSend: function () {
            body.empty().append(gks.loadingIMG);
        },
        success: function (response) {
            body.empty().append(response.BODY);

            jQuery('#tong_tien_hang').text(response.CART);
            jQuery('#tong_thanh_toan').text(response.PRICE);

            jsbindmf();
        }
    });
}
function jskhcartchangeperson(ele, cart) {
    var frm = jQuery('#cart_' + cart);
    jQuery.ajax({
        url: gks.baseURL + '/kh/dh/u-p',
        type: 'post',
        data: {
            cart_id: cart,
            value: ele.value,
            type: 'person',
            _token: gks.tempTK,
        },
        beforeSend: function () {
            frm.find('select[name=date_id]').addClass('hidden');
        },
        success: function (response) {
            if (response.VALID) {
                var html = '<option value="">Không tìm thấy sự kiện nào.</option>';
                if (response.ARR && response.ARR.length) {
                    html = '<option value="">Hãy chọn sự kiện</option>';
                    response.ARR.forEach(function (ele, pos) {
                        html += '<option value="' + ele.id + '">' + ele.title + '</option>';
                    });
                }

                frm.find('select[name=date_id]').empty()
                    .append(html)
                    .removeClass('hidden');
            }
        }
    });
}
function jskhcartchangepersondate(ele, cart) {
    var frm = jQuery('#cart_' + cart);
    jQuery.ajax({
        url: gks.baseURL + '/kh/dh/u-p',
        type: 'post',
        data: {
            cart_id: cart,
            value: ele.value,
            type: 'date',
            _token: gks.tempTK,
        },
        success: function (response) {

        }
    });
}

//kero
function jskhkerotable() {
    jQuery.noConflict();
    jQuery('table.kero_tables').DataTable({
        responsive: true,
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        "language": {
            "sProcessing":   "Đang xử lý...",
            "sLengthMenu":   "Xem _MENU_ mục",
            "sZeroRecords":  "Không tìm thấy dòng nào phù hợp",
            "sInfo":         "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
            "sInfoEmpty":    "Đang xem 0 đến 0 trong tổng số 0 mục",
            "sInfoFiltered": "(được lọc từ _MAX_ mục)",
            "sInfoPostFix":  "",
            "sSearch":       "Tìm:",
            "sUrl":          "",
            "oPaginate": {
                "sFirst":    "Đầu",
                "sPrevious": "Trước",
                "sNext":     "Tiếp",
                "sLast":     "Cuối"
            }
        }
    });
}
