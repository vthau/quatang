jQuery(document).ready(function () {


});

function tuVanDon(id, ele) {
    var popup = jQuery('#modal-tu-van-don');
    var bind = jQuery(ele);

    popup.find('input[name=item_id]').val(id);
    popup.find('textarea[name=note]').val(bind.attr('data-note'));
    popup.find('input[name=shipping_fee]').val('');
    if (parseInt(bind.attr('data-fee'))) {
        popup.find('input[name=shipping_fee]').val(bind.attr('data-fee'));
        bindMoney();
    }

    popup.modal('show');
}

function tuVanDonXacNhan() {
    var popup = jQuery('#modal-tu-van-don');
    jQuery.ajax({
        url: gks.baseURL + '/admin/order/ship-manual',
        type: 'post',
        data: {
            id: popup.find('input[name=item_id]').val(),
            note: popup.find('textarea[name=note]').val().trim(),
            shipping_fee: popup.find('input[name=shipping_fee]').val(),
            _token: gks.tempTK,
        },
        beforeSend: function () {
            modalLoading('modal-tu-van-don');
        },
        success: function (response) {
            modalSuccess('modal-tu-van-don', gks.successUPDATE);
            window.location.reload(true);
        },
    });
}

function giaoHang(id) {
    var popup = jQuery('#modal-ghn-confirm');
    popup.find('input[name=item_id]').val(id);
    popup.modal('show');
}

function giaoHangXacNhan() {
    var popup = jQuery('#modal-ghn-confirm');
    jQuery.ajax({
        url: gks.baseURL + '/ghn/create-order',
        type: 'post',
        data: {
            id: popup.find('input[name=item_id]').val(),
            _token: gks.tempTK,
        },
        beforeSend: function () {
            modalLoading('modal-ghn-confirm');
        },
        success: function (response) {
            modalSuccess('modal-ghn-confirm', gks.successUPDATE);
            window.location.reload(true);
        },
    });
}

function filterBy(value) {
    switch (value) {
        case 'name':
            jQuery('#btn-filter').text("Mã Đơn Hàng");
            break;
        case 'phone':
            jQuery('#btn-filter').text("Điện Thoại");
            break;
    }
    jQuery('#filter-by').val(value);
}

function deleteItem(id) {
    jQuery('#delete-item').val(id);
    jQuery('#modalDelete').modal('show');
}

function confirmDeleteItem(value) {
    if (!value) {
        jQuery('#delete-item').val("");
        jQuery('#modalDelete').modal('hide');
    } else {
        jQuery.ajax({
            url: gks.baseURL + '/admin/receipt/delete',
            type: 'post',
            data: {
                id: jQuery('#delete-item').val().trim(),
                _token: gks.tempTK,
            },
            beforeSend: function () {
                jQuery('#modalDelete .modal-footer').hide();
                jQuery('#modalDelete .modal-footer').after(gks.loadingIMG);
            },
            success: function (response) {
                confirmDeleteItem(0);
                window.location.reload(true);
            },
        });
    }
}

function confirmOrder(id, key, value) {
    var modal = jQuery('#modal-order-confirm');

    modal.find('#item_id').val(id);
    modal.find('#item_key').val(key);
    modal.find('#item_value').val(value);

    //message
    var message = '';
    if (key === 'status') {
        message = 'Bạn có chắc muốn xác nhận đơn hàng này đã CHƯA ĐƯỢC thanh toán không?';
        if (parseInt(value)) {
            message = 'Bạn có chắc muốn xác nhận đơn hàng này ĐÃ ĐƯỢC thanh toán không?';
        }
    }

    modal.find('.message').text(message);

    modal.modal('show');
}

function confirmOrderConfirm() {
    var modal = jQuery('#modal-order-confirm');
    var itemId = modal.find('#item_id').val();
    var itemKey = modal.find('#item_key').val();
    var itemValue = modal.find('#item_value').val();

    jQuery.ajax({
        url: gks.baseURL + '/admin/order/update-status',
        type: 'post',
        data: {
            item_id: itemId,
            item_key: itemKey,
            item_value: itemValue,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            modalLoading('modal-order-confirm');
        },
        success: function (response) {
            if (response.BODY) {
                jQuery('#order_' + itemId).empty().append(response.BODY);
            }

            modalSuccess('modal-order-confirm', gks.successUPDATE);
            bindEvent();
        },
    });
}

function confirmShipped(id) {
    jQuery.ajax({
        url: gks.baseURL + '/admin/order/update-shipped',
        type: 'post',
        data: {
            item_id: id,
            _token: gks.tempTK,
        },
        success: function (response) {
            window.location.reload(true);
        },
    });
}

function exportItems() {
    var frm = jQuery('#frm-search');
    var frmOrder = jQuery('#frm-order');
    var href = gks.baseURL + '/admin/receipt/export?p=1';

    var keyword = frm.find('input[name=keyword]').val().trim();
    var filter = frm.find('input[name=filter]').val().trim();
    var payment = frm.find('select[name=payment]').val();
    var product = frm.find('select[name=product]').val();
    var status = frm.find('select[name=status]').val();
    var user = frm.find('select[name=user]').val();
    var ref = frm.find('select[name=ref]').val();
    var shipping_status = frm.find('select[name=shipping_status]').val();
    var shipping = frm.find('select[name=shipping]').val();
    var date_from = frm.find('input[name=date_from]').val();
    var date_to = frm.find('input[name=date_to]').val();
    var order = frmOrder.find('select[name=order]').val();
    var orderby = frmOrder.find('select[name=orderby]').val();

    href += '&keyword=' + keyword;
    href += '&filter=' + filter;
    href += '&payment=' + payment;
    href += '&product=' + product;
    href += '&user=' + user;
    href += '&status=' + status;
    href += '&ref=' + ref;
    href += '&shipping_status=' + shipping_status;
    href += '&shipping=' + shipping;
    href += '&date_from=' + date_from;
    href += '&date_to=' + date_to;
    href += '&order=' + order;
    href += '&orderby=' + orderby;

    gotoPage(href);
}
