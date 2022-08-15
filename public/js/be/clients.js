function changePassword(id) {
    jQuery('#change-password').val(id);
    jQuery('#modalPassword').modal('show');
}

function confirmChangePassword(value) {
    if (!value) {
        jQuery('#change-password').val("");
        jQuery('#modalPassword').modal('hide');
    } else {
        var password = jQuery('#frm-password').val().trim();
        jQuery('#modalPassword .alert-danger').addClass('hidden');
        if (!password) {
            jQuery('#modalPassword .alert-danger').removeClass('hidden');
            return false;
        }

        jQuery.ajax({
            url: gks.baseURL + '/admin/staff/change-password',
            type: 'post',
            data: {
                id: jQuery('#change-password').val().trim(),
                password: password,
                _token: gks.tempTK,
            },
            beforeSend: function () {
                jQuery('#modalPassword .modal-footer').hide();
                jQuery('#modalPassword .modal-footer').after(gks.loadingIMG);
            },
            success: function (response) {
                confirmChangePassword(0);
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
        case 'ref_code':
            jQuery('#btn-filter').text("Mã Giới Thiệu");
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

function updateDonVi(ele, id) {
    jQuery.ajax({
        url: gks.baseURL + '/admin/partner/update/dv',
        type: 'post',
        data: {
            id: id,
            don_vi: ele.value,
            _token: gks.tempTK,
        },
        success: function (response) {

        },
    });
}

function updateRef(id) {
    var popup = jQuery('#modal-ngt');
    var bind = jQuery('#ref_' + id);
    var refId = '';
    if (parseInt(bind.attr('data-ref'))) {
        refId = parseInt(bind.attr('data-ref'));
    }

    popup.find('input[name=item_id]').val(id);
    popup.find('select[name=ref_id]').val(refId).change();

    popup.modal('show');
}

function updateRefConfirm() {
    var popup = jQuery('#modal-ngt');
    var id = popup.find('input[name=item_id]').val();
    var ref_id = popup.find('select[name=ref_id]').val();
    var bind = jQuery('#ref_' + id);

    jQuery.ajax({
        url: gks.baseURL + '/admin/partner/update/ref',
        type: 'post',
        data: {
            id: id,
            ref_id: ref_id,
            _token: gks.tempTK,
        },
        beforeSend: function () {
            bind.removeAttr('data-ref')
                .attr('data-ref', popup.find('select[name=ref_id]').val());
            bind.empty();
            modalLoading('modal-ngt');
        },
        success: function (response) {
            if (response.VALID) {
                modalSuccess('modal-ngt', gks.successCHANGE);

                bind.append(response.BODY);
            } else {
                modalError('modal-ngt', gks.saveERR);
            }
        },
    });
}
