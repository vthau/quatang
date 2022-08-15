jQuery(document).ready(function () {

});

function filterBy(value) {
    switch (value) {
        case 'name':
            jQuery('#btn-filter').text("Tiêu Đề");
            break;
        case 'mo_ta':
            jQuery('#btn-filter').text("Mô Tả");
            break;
    }
    jQuery('#filter-by').val(value);
}

function updateStatus(ele, col) {
    jQuery.ajax({
        url: gks.baseURL + '/admin/event/update-status',
        type: 'post',
        data: {
            item_id: jQuery(ele).attr('data-id'),
            value: jQuery(ele).val(),
            status: col,
            _token: gks.tempTK,
        },
        success: function (response) {

        },
    });
}

function itemFeatured(id, value) {
    jQuery.ajax({
        url: gks.baseURL + '/admin/event/featured',
        type: 'post',
        data: {
            item_id: id,
            value: value,
            _token: gks.tempTK,
        },
        success: function (response) {
            reloadPage();
        },
    });
}

function deleteItem(id) {
    var popup = jQuery('#modal_delete_item');
    popup.find('input[name=item_id]').val(id);
    popup.modal('show');
}

function jspopupdelete() {
    var popup = jQuery('#modal_delete_item');
    jQuery.ajax({
        url: gks.baseURL + '/admin/event/delete',
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
