function deleteItem(id) {
    var popup = jQuery('#modal_delete_item');
    popup.find('input[name=item_id]').val(id);
    popup.modal('show');
}
function jspopupdelete() {
    var popup = jQuery('#modal_delete_item');
    jQuery.ajax({
        url: gks.baseURL + '/admin/staff/delete',
        type: 'post',
        data: {
            item_id: popup.find('input[name=item_id]').val(),
            _token: gks.tempTK,
        },
        beforeSend: function () {
            jspopuploading('modal_delete_item');
        },
        success: function (response) {
            reloadPage();
        },
    });
}

function blockItem(id) {
    var popup = jQuery('#modal_item_block');
    popup.find('input[name=item_id]').val(id);
    popup.modal('show');
}
function confirmBlockItem() {
    var popup = jQuery('#modal_item_block');
    jQuery.ajax({
        url: gks.baseURL + '/admin/staff/block',
        type: 'post',
        data: {
            item_id: popup.find('input[name=item_id]').val(),
            reason: popup.find('textarea[name=reason]').val(),
            block: 1,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            jspopuploading('modal_item_block');
        },
        success: function (response) {
            reloadPage();
        },
    });
}

function unblockItem(id) {
    var popup = jQuery('#modal_item_unblock');
    popup.find('input[name=item_id]').val(id);
    popup.modal('show');
}
function confirmUnblockItem() {
    var popup = jQuery('#modal_item_unblock');
    jQuery.ajax({
        url: gks.baseURL + '/admin/staff/block',
        type: 'post',
        data: {
            item_id: popup.find('input[name=item_id]').val(),
            _token: gks.tempTK,
        },
        beforeSend: function () {
            jspopuploading('modal_item_unblock');
        },
        success: function (response) {
            reloadPage();
        },
    });
}

function filterBy(value) {
    switch (value) {
        case 'name':
            jQuery('#btn-filter').text("Họ Tên");
            break;
        case 'phone':
            jQuery('#btn-filter').text("Điện Thoại");
            break;
        case 'email':
            jQuery('#btn-filter').text("Email");
            break;
    }
    jQuery('#filter-by').val(value);
}
