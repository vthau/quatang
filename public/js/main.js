function frmOrder(ele) {
    jQuery('#search-order').val(jQuery(ele).val());
    jQuery('.frm-search .card-footer button[type=submit]').click();
}

function frmOrderBy(ele) {
    jQuery('#search-order-by').val(jQuery(ele).val());
    jQuery('.frm-search .card-footer button[type=submit]').click();
}

function frmFocus(frm) {
    frm.addClass('frm-focus');
    setTimeout(function () {
        frm.removeClass('frm-focus');

        setTimeout(function () {
            frm.addClass('frm-focus');

            setTimeout(function () {
                frm.removeClass('frm-focus');

                setTimeout(function () {
                    frm.addClass('frm-focus');

                    setTimeout(function () {
                        frm.removeClass('frm-focus');
                    }, 300);
                }, 300);
            }, 300);
        }, 300);
    }, 300);
}

function openPage(href) {
    parent.window.location.href = href;
}

function gotoPage(href) {
    window.open(href);
}

function isInputNumber(evt, obj) {
    if (/^0/.test(obj.value)) {
        obj.value = obj.value.replace(/^0/, "")
    }

    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function isInputPhone(evt, obj) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function isInputFloat(evt, obj) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var value = obj.value;
    var dotcontains = value.indexOf(".") != -1;
    if (dotcontains) {
        if (charCode == 46) {
            return false;
        }
        //2
        var dots = value.split(".");
        if (dots[1] && dots[1] > 10) {
            return false;
        }
    }
    if (charCode == 46) {
        return true;
    }
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function refreshTimeNotify(ele) {
    jQuery(ele).parent().addClass('show');
    jQuery('#ele-panel-notification').addClass('show');
    setTimeout(function () {
        jQuery(ele).parent().removeClass('show');
        jQuery('#ele-panel-notification').removeClass('show');
    }, 4500);

    jQuery.ajax({
        url: gks.baseURL + '/admin/notification/refresh',
        type: 'post',
        data: {
            _token: gks.tempTK,
        },
        beforeSend: function () {
            jQuery('#my-notification').remove();
        }
    });
}

function pressNoSpace(event) {
    if (event.which == 32) {
        event.preventDefault();
        return false;
    }
}

function setFocusAt(ele) {
    if (ele) {
        setTimeout(function () {
            ele.focus();
        }, gks.timeOutFocus);
    }
}

//modal
function modalLoading(ele) {
    var bind = jQuery('#' + ele);

    bind.find('.modal-footer').hide();
    bind.find('.modal-footer').after(gks.loadingIMG);
    bind.find('.js-loading').addClass('text-right');
}

function modalBack(ele) {
    var bind = jQuery('#' + ele);

    bind.find('.modal-footer').show();
    bind.find('.js-loading').remove();
    bind.find('.js_loading').remove();
    bind.find('.frm-message').remove();
    bind.find('.frm-message_error').remove();
}

function modalSuccess(ele, message) {
    var bind = jQuery('#' + ele);

    bind.find('.js-loading').remove();
    bind.find('.js_loading').remove();
    var html = '<div class="alert alert-success frm-message">' + message + '</div>';
    bind.find('.modal-footer').after(html);

    setTimeout(function () {
        modalRefresh(ele);
    }, 888);
}

function modalRefresh(ele) {
    var bind = jQuery('#' + ele);

    bind.find('.frm-message').remove();
    bind.find('.js-loading').remove();
    bind.find('.js_loading').remove();
    bind.find('.modal-footer').show();
    bind.modal('hide');
}

function modalError(ele, message) {
    var bind = jQuery('#' + ele);

    bind.find('.js-loading').remove();
    bind.find('.js_loading').remove();
    var html = '<div class="alert alert-danger frm-message_error">' + message + '</div>';
    bind.find('.modal-footer').after(html);

    setTimeout(function () {
        modalBack(ele);
    }, 2888);
}




