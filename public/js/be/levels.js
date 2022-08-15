function pressEnter(e) {
    if (e.which == 13 || e.keyCode == 13) {
        confirmUpdateItem(1);
    }
}

function addItem() {
    jQuery('#update-item').val('');
    jQuery('#modalUpdate').modal('show');
    jQuery('#modalUpdate .alert').addClass('hidden');

    jQuery('#frm-update-title').text("Thêm Quyền Truy Cập");
    jQuery('#frm-update-text').val('');
    setTimeout(function () {
        jQuery('#frm-update-text').focus();
    }, 500);
}

function editItem(id) {
    jQuery('#update-item').val(id);
    jQuery('#modalUpdate').modal('show');
    jQuery('#modalUpdate .alert').addClass('hidden');

    jQuery('#frm-update-title').text("Sửa Quyền Truy Cập");
    jQuery('#frm-update-text').val(jQuery('#row-name-' + id).text());
    setTimeout(function () {
        jQuery('#frm-update-text').focus();
    }, 500);
}

function confirmUpdateItem(value) {
    if (!value) {
        jQuery('#modalUpdate').modal('hide');
    } else {
        var id = jQuery('#update-item').val().trim();
        var name = jQuery('#frm-update-text').val().trim();
        if (!name) {
            jQuery('#modalUpdate .alert').removeClass('hidden').text("Hãy nhập tên quyền truy cập.");
            return false;
        }
        //check duplicate
        var duplicated = false;
        var stopped = false;
        jQuery('.row-tr').each(function (pos, ele) {
            var curSame = false;
            var bind = jQuery(ele);
            var curId = bind.attr('data-id');
            if (name.toLowerCase() == bind.find('.row-name').text().trim().toLowerCase()) {
                duplicated = true;
                curSame = true;
            }
            if (parseInt(id) && parseInt(id) == parseInt(curId) && curSame) {
                stopped = true;
            }
        });
        if (stopped) {
            confirmUpdateItem(0);
            return false;
        }
        if (duplicated) {
            jQuery('#modalUpdate .alert').removeClass('hidden').text("Quyền truy cập đã tồn tại.");
            return false;
        }

        jQuery.ajax({
            url: gks.baseURL + '/admin/level/save',
            type: 'post',
            data: {
                id: id,
                name: name,
                _token: gks.tempTK,
            },
            beforeSend: function () {
                jQuery('#modalUpdate .modal-footer').hide();
                jQuery('#modalUpdate .modal-footer').after(gks.loadingIMG);
            },
            success: function (response) {
                confirmDeleteItem(0);
                window.location.reload(true);
            },
        });
    }
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
            url: gks.baseURL + '/admin/level/delete',
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
