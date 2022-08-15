jQuery(document).ready(function () {
    jQuery('#modal_item_update form').on('submit', function(e){
        e.preventDefault();
        confirmUpdateItem();
    });

    //upload
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
        } else {
            jQuery(this).val("");
        }
    });
});

function addItem() {
    var popup = jQuery('#modal_item_update');

    popup.find('.alert').addClass('hidden');
    popup.find('.modal-header .modal-title').text("Tạo Nhóm Sản Phẩm");

    popup.find('#banner-preview').empty();
    popup.find('#banner-preview-mobi').empty();

    popup.find('input[name=item_id]').val('');
    popup.find('input[name=parent_id]').val('');
    popup.find('input[name=title]').val('');

    popup.modal('show');
    jsfocusat('modal_item_update', 'input[name=title]');
}

function editItem(id) {
    var popup = jQuery('#modal_item_update');
    var editElement = jQuery('#title-' + id);

    popup.find('.alert').addClass('hidden');
    popup.find('.modal-header .modal-title').text("Sửa Nhóm Sản Phẩm");

    popup.find('#banner-preview').empty();
    popup.find('#banner-preview-mobi').empty();

    popup.find('input[name=item_id]').val(id);
    popup.find('input[name=parent_id]').val('');
    popup.find('input[name=title]').val(editElement.attr('data-name'));

    popup.modal('show');
    jsfocusat('modal_item_update', 'input[name=title]');

    var bannerBG = editElement.attr('data-banner');
    if (bannerBG) {
        jQuery('#banner-preview').empty().append("<div class='img-preview'><img src='" + editElement.attr('data-banner') + "' ></div>");
    } else {
        jQuery('#banner-preview').empty();
    }

    var bannerBGMobi = editElement.attr('data-banner-mobi');
    if (bannerBGMobi) {
        jQuery('#banner-preview-mobi').empty().append("<div class='img-preview'><img src='" + editElement.attr('data-banner-mobi') + "' ></div>");
    } else {
        jQuery('#banner-preview-mobi').empty();
    }
}

function confirmUpdateItem() {
    var popup = jQuery('#modal_item_update');
    var itemId = popup.find('input[name=item_id]').val();
    var parentId = popup.find('input[name=parent_id]').val();
    var name = popup.find('input[name=title]').val().trim();

    popup.find('.alert').addClass('hidden');

    if (!name || name === '') {
        popup.find('#req-title .alert').removeClass('hidden')
            .text("Hãy nhập tên nhóm sản phẩm.");
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
        url: gks.baseURL + '/admin/category/delete',
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

//child
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
    var popup = jQuery('#modal_item_update');

    popup.find('.alert').addClass('hidden');
    popup.find('.modal-header .modal-title').text("Tạo Nhóm Sản Phẩm Con");

    popup.find('#banner-preview').empty();
    popup.find('#banner-preview-mobi').empty();

    popup.find('input[name=item_id]').val('');
    popup.find('input[name=parent_id]').val(parent_id);
    popup.find('input[name=title]').val('');

    popup.modal('show');
    jsfocusat('modal_item_update', 'input[name=title]');
}

function editSubItem(id, parent_id) {
    var popup = jQuery('#modal_item_update');
    var editElement = jQuery('#title-' + id);

    popup.find('.alert').addClass('hidden');
    popup.find('.modal-header .modal-title').text("Sửa Nhóm Sản Phẩm");

    popup.find('#banner-preview').empty();
    popup.find('#banner-preview-mobi').empty();

    popup.find('input[name=item_id]').val(id);
    popup.find('input[name=parent_id]').val(parent_id);
    popup.find('input[name=title]').val(editElement.attr('data-name'));

    popup.modal('show');
    jsfocusat('modal_item_update', 'input[name=title]');

    var bannerBG = editElement.attr('data-banner');
    if (bannerBG) {
        jQuery('#banner-preview').empty().append("<div class='img-preview'><img src='" + editElement.attr('data-banner') + "' ></div>");
    } else {
        jQuery('#banner-preview').empty();
    }

    var bannerBGMobi = editElement.attr('data-banner-mobi');
    if (bannerBGMobi) {
        jQuery('#banner-preview-mobi').empty().append("<div class='img-preview'><img src='" + editElement.attr('data-banner-mobi') + "' ></div>");
    } else {
        jQuery('#banner-preview-mobi').empty();
    }
}
