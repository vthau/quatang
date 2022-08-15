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
            url: gks.baseURL + '/admin/staff/delete',
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

function blockItem(id) {
    jQuery('#block-item').val(id);
    jQuery('#modalBlock').modal('show');
}

function confirmBlockItem(value) {
    if (!value) {
        jQuery('#block-item').val("");
        jQuery('#modalBlock').modal('hide');
    } else {
        jQuery.ajax({
            url: gks.baseURL + '/admin/staff/block',
            type: 'post',
            data: {
                id: jQuery('#block-item').val().trim(),
                reason: jQuery('#modal-reason').length ? jQuery('#modal-reason').val().trim() : '',
                block: 1,
                _token: gks.tempTK,
            },
            beforeSend: function () {
                jQuery('#modalBlock .modal-footer').hide();
                jQuery('#modalBlock .modal-footer').after(gks.loadingIMG);
            },
            success: function (response) {
                confirmBlockItem(0);
                window.location.reload(true);
            },
        });
    }
}

function unblockItem(id) {
    jQuery('#unblock-item').val(id);
    jQuery('#modalUnblock').modal('show');
}

function confirmUnblockItem(value) {
    if (!value) {
        jQuery('#unblock-item').val("");
        jQuery('#modalUnblock').modal('hide');
    } else {
        jQuery.ajax({
            url: gks.baseURL + '/admin/staff/block',
            type: 'post',
            data: {
                id: jQuery('#unblock-item').val().trim(),
                _token: gks.tempTK,
            },
            beforeSend: function () {
                jQuery('#modalUnblock .modal-footer').hide();
                jQuery('#modalUnblock .modal-footer').after(gks.loadingIMG);
            },
            success: function (response) {
                confirmUnblockItem(0);
                window.location.reload(true);
            },
        });
    }
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
