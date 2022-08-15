jQuery(document).ready(function () {
    //bind
    bindEvent();

    bindSelect2();
});

//required
function bindSelect2() {
    jQuery('body select').select2({});
}

function bindEvent() {
    bindNumber();
    bindMoney();
    bindSelect2();

    jQuery('.notfound').text(gks.notFound);
    jQuery('.delete_confirm').text(gks.deleteConfirm);
    jQuery('.max-size-text').text(gks.maxSizeText);
}

function bindNumber() {
    jQuery('.number_format').simpleNumberFormat();
    jQuery(".number_format").bind('keypress keyup blur paste', function (event) {
        $(this).val($(this).val().replace(/[^0-9\,]/g, ''));
        if (event.which < 48 || event.which > 57) {
            event.preventDefault();
        }
    });
}

function bindMoney() {
    jQuery('.money_format').simpleMoneyFormat();
    jQuery(".money_format").bind('keypress keyup blur paste', function (event) {
        $(this).val($(this).val().replace(/[^0-9\.\,]/g, ''));
        if ((event.which !== 46 || $(this).val().indexOf('.') !== -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
}

function showMessage(message) {
    var popup = jQuery('#modal_message');

    popup.find('#text_message').text(message);

    popup.modal('show');
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

function jskhcopyurl(value) {
    document.getElementById("code_url_" + value).select();
    document.execCommand('copy');
}

function viewContent1(tab) {
    var bind = jQuery('#kero-1');
    var header = bind.find('.card-header').first();
    var body = bind.find('.card-body').first();
    var pane = body.find('.tab-content').first();

    header.find('li a').removeClass('active');
    pane.find('> .tab-pane').removeClass('active');

    header.find('li a.' + tab).addClass('active');
    pane.find('> .tab-pane.' + tab).addClass('active');
}

function viewContent2(tab) {
    var bind = jQuery('#kero-2');
    var header = bind.find('.card-header').first();
    var body = bind.find('.card-body').first();
    var pane = body.find('.tab-content').first();

    header.find('li a').removeClass('active');
    pane.find('> .tab-pane').removeClass('active');

    header.find('li a.' + tab).addClass('active');
    pane.find('> .tab-pane.' + tab).addClass('active');
}

function frmPressEnter(e, functionElement) {
    if (e.which === 13 || e.keyCode === 13) {
        if (typeof functionElement === "function") {
            functionElement();
        }
    }
}

function reloadPage() {
    window.location.reload(true);
}

//modal
function jspopuploading(value) {
    var popup = jQuery('#' + value);
    popup.find('.modal-footer button').hide();
    popup.find('.modal-footer button').last().after(gks.loadingIMG);
}

function jspopupback(value) {
    var popup = jQuery('#' + value);
    popup.find('.modal-footer button').show();
    popup.find('.modal-footer .js_loading').remove();
}

function jsfocusat(value, ele) {
    var popup = jQuery('#' + value);

    setTimeout(function () {
        popup.find(ele).focus();
    }, gks.timeOutFocus);
}
