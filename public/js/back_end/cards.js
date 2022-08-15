jQuery(document).ready(function () {
    jQuery('#modal_item_update form').on('submit', function(e){
        e.preventDefault();
        confirmUpdateItem();
    });

    //upload
    $("#upload-slides").change(function () {
        jQuery('#req-slides .alert-danger').addClass('hidden');
        if (this.files.length) {
            var i = 0;
            for (i; i<=99; i++) {
                if (this.files[i] && this.files[i].size > gks.maxSize) {
                    jQuery('#req-slides .alert-danger').removeClass('hidden');

                    jQuery(this).val("");
                    jQuery('#slides-preview').empty();
                    return false;
                }
            }
        }

        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#slides-preview').append("<div class='img-item'><div class='img-preview'><img src='" + e.target.result + "' ></div></div>");
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
    popup.find('.modal-header .modal-title').text("Tạo Mẫu Thiệp");

    popup.find('#upload-slides').val('');
    popup.find('#slides-preview').empty();

    popup.find('input[name=old_photos]').val('');
    popup.find('input[name=item_id]').val('');
    popup.find('input[name=title]').val('');
    popup.find('select[name=system_category_id]').val('0').change();

    popup.modal('show');
    jsfocusat('modal_item_update', 'input[name=title]');
}

function editItem(id) {
    var popup = jQuery('#modal_item_update');
    var editElement = jQuery('#row-name-' + id);
    var links = editElement.attr('data-url');

    popup.find('.alert').addClass('hidden');
    popup.find('.modal-header .modal-title').text("Sửa Mẫu Thiệp");

    popup.find('#upload-slides').val('');
    popup.find('#slides-preview').empty();

    popup.find('input[name=old_photos]').val('');
    popup.find('input[name=item_id]').val(id);
    popup.find('input[name=title]').val(editElement.attr('data-name'));
    popup.find('select[name=system_category_id]').val(editElement.attr('data-category')).change();

    var html = '';
    if (links) {
        links = links.split(';;');
        if (links.length) {
            links.forEach(function (ele, pos) {
                if (ele !== '') {
                    var arr = ele.split('__');
                    if (arr.length > 0) {
                        html += '<div class="img-item" id="photo_' + arr[0] + '">';
                        html += '<div class="img-preview">';
                        html += '<i class="fa fa-times required" onclick="removePhoto(' + arr[0] + ')"></i>';
                        html += '<img src="' + arr[1] + '" />';
                        html += '</div>';
                        html += '</div>';
                    }
                }
            });
        }
    }
    if (html !== '') {
        jQuery('#slides-preview').append(html);
    }

    popup.modal('show');
    jsfocusat('modal_item_update', 'input[name=title]');
}

function removePhoto(id) {
    var popup = jQuery('#modal_item_update');
    jQuery('#photo_' + id).remove();
    popup.find('input[name=old_photos]').val(popup.find('input[name=old_photos]').val() + 'p_' + id + ';');
}

function confirmUpdateItem() {
    var popup = jQuery('#modal_item_update');
    var itemId = popup.find('input[name=item_id]').val();
    var name = popup.find('input[name=title]').val().trim();

    popup.find('.alert').addClass('hidden');

    if (!name || name === '') {
        popup.find('#req-title .alert').removeClass('hidden')
            .text("Hãy nhập tên mẫu thiệp.");
        return false;
    }

    jspopuploading('modal_item_update');
    popup.find('form')[0].submit();
    return false;
}

function updateStatus(id, col, val) {
    jQuery.ajax({
        url: gks.baseURL + '/admin/card/update-status',
        type: 'post',
        data: {
            item_id: id,
            value: col === 'category' ? val.value : val,
            column: col,
            _token: gks.tempTK,
        },
        success: function (response) {
            reloadPage();
        },
    });
}

function deleteItem(id) {
    var popup = jQuery('#modal_delete_item');
    popup.find('input[name=item_id]').val(id);
    popup.modal('show');
}

function jspopupdelete() {
    var popup = jQuery('#modal_delete_item');
    jQuery.ajax({
        url: gks.baseURL + '/admin/card/delete',
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
