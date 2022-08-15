jQuery(document).ready(function () {
    jQuery('.max-size-text').text(gks.maxSizeText);

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

    $("#upload-banner-mobi").change(function () {
        jQuery('#modal-banner-mobi .alert-danger').addClass('hidden');
        if(this.files[0].size > gks.maxSize) {
            jQuery('#modal-banner-mobi .alert-danger').removeClass('hidden');

            jQuery(this).val("");
            jQuery('#banner-preview-mobi').empty();
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
                    $('#banner-preview-mobi').empty().append("<div class='img-preview'><img src='" + e.target.result + "' ></div>");
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    });
});

function pressEnter(e) {
    if (e.which == 13 || e.keyCode == 13) {
        confirmUpdateItem(1);
    }
}

function toggleItem(id, sub) {
    if (!sub) {
        if (jQuery('.sub-' + id + '').hasClass('hidden')) {
            jQuery('.sub-' + id + '').removeClass('hidden');
        } else {
            jQuery('.sub-' + id + '').addClass('hidden');
        }
    } else {
        if (jQuery('.sub-' + id + '.child-' + sub + '').hasClass('hidden')) {
            jQuery('.sub-' + id + '.child-' + sub + '').removeClass('hidden');
        } else {
            jQuery('.sub-' + id + '.child-' + sub + '').addClass('hidden');
        }
    }
}

function addSubItem(parent_id) {
    jQuery('#update-item').val('');
    jQuery('#parent-item').val(parent_id);
    jQuery('#modalUpdate').modal('show');
    jQuery('#modalUpdate .alert').addClass('hidden');

    jQuery('#frm-update-title').text("Tạo Nhóm SP Con");
    jQuery('#frm-update-text').val('');
    setTimeout(function () {
        jQuery('#frm-update-text').focus();
    }, 500);

    jQuery('#banner-preview').empty();
}

function editSubItem(id, parent_id) {
    jQuery('#update-item').val(id);
    jQuery('#parent-item').val(parent_id);
    jQuery('#modalUpdate').modal('show');
    jQuery('#modalUpdate .alert').addClass('hidden');

    jQuery('#frm-update-title').text("Sửa Nhóm Sản Phẩm");
    jQuery('#frm-update-text').val(jQuery('#title-' + id).attr('data-name'));
    setTimeout(function () {
        jQuery('#frm-update-text').focus();
    }, 500);

    var bannerBG = jQuery('#title-' + id).attr('data-banner');
    if (bannerBG) {
        jQuery('#banner-preview').empty().append("<div class='img-preview'><img src='" + jQuery('#title-' + id).attr('data-banner') + "' ></div>");
    } else {
        jQuery('#banner-preview').empty();
    }

    var bannerBGMobi = jQuery('#title-' + id).attr('data-banner-mobi');
    if (bannerBGMobi) {
        jQuery('#banner-preview-mobi').empty().append("<div class='img-preview'><img src='" + jQuery('#title-' + id).attr('data-banner-mobi') + "' ></div>");
    } else {
        jQuery('#banner-preview-mobi').empty();
    }
}

function addItem() {
    jQuery('#update-item').val('');
    jQuery('#parent-item').val('');
    jQuery('#modalUpdate').modal('show');
    jQuery('#modalUpdate .alert').addClass('hidden');

    jQuery('#frm-update-title').text("Tạo Nhóm Sản Phẩm");
    jQuery('#frm-update-text').val('');
    setTimeout(function () {
        jQuery('#frm-update-text').focus();
    }, 500);

    jQuery('#banner-preview').empty();
}

function editItem(id) {
    jQuery('#update-item').val(id);
    jQuery('#parent-item').val('');
    jQuery('#modalUpdate').modal('show');
    jQuery('#modalUpdate .alert').addClass('hidden');

    jQuery('#frm-update-title').text("Sửa Nhóm Sản Phẩm");
    jQuery('#frm-update-text').val(jQuery('#title-' + id).attr('data-name'));
    setTimeout(function () {
        jQuery('#frm-update-text').focus();
    }, 500);

    var bannerBG = jQuery('#title-' + id).attr('data-banner');
    if (bannerBG) {
        jQuery('#banner-preview').empty().append("<div class='img-preview'><img src='" + jQuery('#title-' + id).attr('data-banner') + "' ></div>");
    } else {
        jQuery('#banner-preview').empty();
    }

    var bannerBGMobi = jQuery('#title-' + id).attr('data-banner-mobi');
    if (bannerBGMobi) {
        jQuery('#banner-preview-mobi').empty().append("<div class='img-preview'><img src='" + jQuery('#title-' + id).attr('data-banner-mobi') + "' ></div>");
    } else {
        jQuery('#banner-preview-mobi').empty();
    }
}

function confirmUpdateItem(value) {
    if (!value) {
        jQuery('#modalUpdate').modal('hide');
    } else {
        var parent_id = jQuery('#parent-item').val().trim();
        var id = jQuery('#update-item').val().trim();
        var name = jQuery('#frm-update-text').val().trim();
        if (!name) {
            jQuery('#modalUpdate .alert').removeClass('hidden').text("Hãy nhập tên nhóm SP.");
            return false;
        }

        jQuery('#frm-add').removeAttr('onsubmit').submit();
        return false;

        jQuery.ajax({
            url: gks.baseURL + '/admin/category/save',
            type: 'post',
            data: {
                parent_id: parent_id,
                id: id,
                title: name,
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
            url: gks.baseURL + '/admin/category/delete',
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
        url: gks.baseURL + '/admin/category/update-menu',
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

