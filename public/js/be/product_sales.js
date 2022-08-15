jQuery(document).ready(function () {

});

function filterBy(value) {
    switch (value) {
        case 'name':
            jQuery('#btn-filter').text("Tên KM");
            break;
        case 'code':
            jQuery('#btn-filter').text("Code KM");
            break;
    }
    jQuery('#filter-by').val(value);
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
                window.location.reload(true);
            },
        });
    }
}
