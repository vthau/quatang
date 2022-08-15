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
    jQuery('#delete-item').val(id);
    jQuery('#modalDelete').modal('show');
}

function confirmDeleteItem(value) {
    if (!value) {
        jQuery('#delete-item').val("");
        jQuery('#modalDelete').modal('hide');
    } else {
        jQuery.ajax({
            url: gks.baseURL + '/admin/contact/delete',
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
