jQuery(document).ready(function () {

});

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
        case 'body':
            jQuery('#btn-filter').text("Nội Dung");
            break;
    }
    jQuery('#filter-by').val(value);
}

function deleteItem(id) {
    var popup = jQuery('#modal_delete_item');
    popup.find('input[name=item_id]').val(id);
    popup.modal('show');
}

function jspopupdelete() {
    var popup = jQuery('#modal_delete_item');
    jQuery.ajax({
        url: gks.baseURL + '/admin/contact/delete',
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
