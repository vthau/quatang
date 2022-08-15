jQuery(document).ready(function () {
    jQuery('#modal_item_update form').on('submit', function(e){
        e.preventDefault();
        confirmUpdateItem();
    });

    //upload
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
        } else {
            jQuery(this).val("");
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
        } else {
            jQuery(this).val("");
        }
    });
});

function addItem() {
    var popup = jQuery('#modal_item_update');

    popup.find('.alert').addClass('hidden');
    popup.find('.modal-header .modal-title').text("Tạo Nhà Cung Cấp");

    popup.find('#avatar-preview').empty();
    popup.find('#banner-preview').empty();

    popup.find('input[name=item_id]').val('');
    popup.find('input[name=title]').val('');

    popup.modal('show');
    jsfocusat('modal_item_update', 'input[name=title]');
}

function editItem(id) {
    var popup = jQuery('#modal_item_update');
    var editElement = jQuery('#row-name-' + id);

    popup.find('.alert').addClass('hidden');
    popup.find('.modal-header .modal-title').text("Sửa Nhà Cung Cấp");

    popup.find('#avatar-preview').empty();
    popup.find('#banner-preview').empty();

    popup.find('input[name=item_id]').val(id);
    popup.find('input[name=title]').val(editElement.attr('data-name'));

    popup.modal('show');
    jsfocusat('modal_item_update', 'input[name=title]');

    var avatarBG = editElement.attr('data-avatar');
    if (avatarBG) {
        popup.find('#avatar-preview').empty()
            .append("<div class='img-preview'><img src='" + editElement.attr('data-avatar') + "' ></div>");
    } else {
        popup.find('#avatar-preview').empty();
    }

    var bannerBG = editElement.attr('data-banner');
    if (bannerBG) {
        popup.find('#banner-preview').empty()
            .append("<div class='img-preview'><img src='" + editElement.attr('data-banner') + "' ></div>");
    } else {
        popup.find('#banner-preview').empty();
    }
}

function confirmUpdateItem() {
    var popup = jQuery('#modal_item_update');
    var itemId = popup.find('input[name=item_id]').val();
    var name = popup.find('input[name=title]').val().trim();

    popup.find('.alert').addClass('hidden');

    if (!name || name === '') {
        popup.find('#req-title .alert').removeClass('hidden')
            .text("Hãy nhập tên nhà cung cấp.");
        return false;
    }

    //check duplicate
    var duplicated = false;
    jQuery('.row-tr').each(function (pos, ele) {
        var bind = jQuery(ele);
        var currentId = parseInt(bind.attr('data-id'));

        if (itemId && parseInt(itemId) === currentId) {
            //ko ktra
        } else {
            if (name.toLowerCase() === bind.find('.row-parent').attr('data-name').trim().toLowerCase()) {
                duplicated = true;
            }
        }
    });

    if (duplicated) {
        popup.find('#req-title .alert').removeClass('hidden')
            .text("Tên nhà cung cấp đã có.");
        return false;
    }

    jspopuploading('modal_item_update');
    popup.find('form')[0].submit();
    return false;
}

function deleteItem(id) {
    var popup = jQuery('#modal_delete_item');
    popup.find('input[name=item_id]').val(id);
    popup.modal('show');
}

function jspopupdelete() {
    var popup = jQuery('#modal_delete_item');
    jQuery.ajax({
        url: gks.baseURL + '/admin/supplier/delete',
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

