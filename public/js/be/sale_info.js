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
            url: gks.baseURL + '/admin/sale/delete',
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
                parent.window.location.href = gks.baseURL + '/admin/product-sales';
            },
        });
    }
}

function updateStatus(ele) {
    jQuery.ajax({
        url: gks.baseURL + '/admin/sale/update-status',
        type: 'post',
        data: {
            id: jQuery(ele).attr('data-id'),
            status: jQuery(ele).val(),
            _token: gks.tempTK,
        },
        success: function (response) {
            window.location.reload(true);
        },
    });
}
