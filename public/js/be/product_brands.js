jQuery(document).ready(function () {
    jQuery('.max-size-text').text(gks.maxSizeText);

    $("#upload-avatar").change(function () {
        jQuery('#modal-avatar .alert-danger').addClass('hidden');
        if(this.files[0].size > gks.maxSize) {
            jQuery('#modal-avatar .alert-danger').removeClass('hidden');

            jQuery(this).val("");
            jQuery('#avatar-preview').empty();
            return false;
        }

        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#avatar-preview').empty().append("<div class='img-preview'><img src='" + e.target.result + "' ></div>");
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    });

    $("#upload-banner").change(function () {
        jQuery('#modal-banner .alert-danger').addClass('hidden');
        if(this.files[0].size > gks.maxSize) {
            jQuery('#modal-banner .alert-danger').removeClass('hidden');

            jQuery(this).val("");
            jQuery('#banner-preview').empty();
            return false;
        }

        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#banner-preview').empty().append("<div class='img-preview'><img src='" + e.target.result + "' ></div>");
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    });
});

function pressEnter(e) {
    if (e.which == 13 || e.keyCode == 13) {
        confirmUpdateItem(1);
        return false;
    }
}

function addItem() {
    jQuery('#update-item').val('');
    jQuery('#parent-item').val('');
    jQuery('#modalUpdate').modal('show');
    jQuery('#modalUpdate .alert').addClass('hidden');
    jQuery('#avatar-preview').empty(); //.append("<div class='img-preview'><img src='" + gks.baseURL + "/public/images/no_photo.jpg' ></div>");
    jQuery('#banner-preview').empty();
    jQuery('#frm-id').val('');

    jQuery('#frm-update-title').text("Tạo Thương Hiệu");
    jQuery('#frm-update-text').val('');
    setTimeout(function () {
        jQuery('#frm-update-text').focus();
    }, 500);
}

function editItem(id) {
    jQuery('#update-item').val(id);
    jQuery('#parent-item').val('');
    jQuery('#modalUpdate').modal('show');
    jQuery('#modalUpdate .alert').addClass('hidden');

    jQuery('#frm-update-title').text("Sửa Thương Hiệu");
    jQuery('#frm-update-text').val(jQuery('#row-name-' + id).attr('data-name'));
    setTimeout(function () {
        jQuery('#frm-update-text').focus();
    }, 500);

    jQuery('#frm-id').val(id);

    var avatarBG = jQuery('#row-name-' + id).attr('data-avatar');
    if (avatarBG) {
        jQuery('#avatar-preview').empty().append("<div class='img-preview'><img src='" + jQuery('#row-name-' + id).attr('data-avatar') + "' ></div>");
    } else {
        jQuery('#avatar-preview').empty();
    }

    var bannerBG = jQuery('#row-name-' + id).attr('data-banner');
    if (bannerBG) {
        jQuery('#banner-preview').empty().append("<div class='img-preview'><img src='" + jQuery('#row-name-' + id).attr('data-banner') + "' ></div>");
    } else {
        jQuery('#banner-preview').empty();
    }
}

function confirmUpdateItem(value) {
    if (!value) {
        jQuery('#modalUpdate').modal('hide');
    } else {
        var id = jQuery('#update-item').val().trim();
        var name = jQuery('#frm-update-text').val().trim();
        if (!name) {
            jQuery('#modalUpdate .alert').removeClass('hidden').text("Hãy nhập tên thương hiệu.");
            return false;
        }
        //check duplicate
        var duplicated = false;
        var stopped = false;
        jQuery('.row-tr').each(function (pos, ele) {
            var curSame = false;
            var bind = jQuery(ele);
            var curId = bind.attr('data-id');
            if (name.toLowerCase() == bind.find('.row-parent').attr('data-name').trim().toLowerCase()) {
                duplicated = true;
                curSame = true;
            }
            if (parseInt(id) && parseInt(id) == parseInt(curId) && curSame) {
                stopped = true;
            }
        });

        if (stopped) {
            //update img
            var curImg = jQuery('#row-name-' + id).attr('data-avatar');
            var newImg = jQuery('#avatar-preview img').attr('src');
            if (curImg !== newImg) {
                jQuery('#frm-add').removeAttr('onsubmit').submit();
            }
            confirmUpdateItem(0);
            return false;
        }
        if (duplicated) {
            jQuery('#modalUpdate .alert').removeClass('hidden').text("Tên thương hiệu đã có.");
            return false;
        }

        jQuery('#frm-add').removeAttr('onsubmit').submit();
        return false;
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
            url: gks.baseURL + '/admin/brand/delete',
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

function updateMenu(id, col, val) {
    jQuery.ajax({
        url: gks.baseURL + '/admin/brand/update-menu',
        type: 'post',
        data: {
            id: id,
            value: val,
            menu: col,
            _token: gks.tempTK,
        },
        success: function (response) {
            window.location.reload(true);
        },
    });
}

