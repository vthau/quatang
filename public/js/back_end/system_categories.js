jQuery(document).ready(function () {
    jQuery('#modal_item_update form').on('submit', function(e){
        e.preventDefault();
        confirmUpdateItem();
    });

});

function addItem() {
    var popup = jQuery('#modal_item_update');

    popup.find('.alert').addClass('hidden');
    popup.find('.modal-header .modal-title').text("Tạo Nhóm Chủ Đề");

    popup.find('input[name=item_id]').val('');
    popup.find('input[name=title]').val('');

    popup.modal('show');
    jsfocusat('modal_item_update', 'input[name=title]');
}

function editItem(id) {
    var popup = jQuery('#modal_item_update');
    var editElement = jQuery('#row-name-' + id);

    popup.find('.alert').addClass('hidden');
    popup.find('.modal-header .modal-title').text("Sửa Nhóm Chủ Đề");

    popup.find('input[name=item_id]').val(id);
    popup.find('input[name=title]').val(editElement.attr('data-name'));

    popup.modal('show');
    jsfocusat('modal_item_update', 'input[name=title]');

}

function confirmUpdateItem() {
    var popup = jQuery('#modal_item_update');
    var itemId = popup.find('input[name=item_id]').val();
    var name = popup.find('input[name=title]').val().trim();

    popup.find('.alert').addClass('hidden');

    if (!name || name === '') {
        popup.find('#req-title .alert').removeClass('hidden')
            .text("Hãy nhập tên nhóm chủ đề.");
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
            .text("Nhóm chủ đề đã có.");
        return false;
    }

    jspopuploading('modal_item_update');
    popup.find('form')[0].submit();
    return false;
}

function updateStatus(id, col, val) {
    jQuery.ajax({
        url: gks.baseURL + '/admin/system-category/update-status',
        type: 'post',
        data: {
            item_id: id,
            value: val,
            menu: col,
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
        url: gks.baseURL + '/admin/system-category/delete',
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
