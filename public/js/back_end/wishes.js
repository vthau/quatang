jQuery(document).ready(function () {
    jQuery('#modal_item_update form').on('submit', function(e){
        e.preventDefault();
        confirmUpdateItem();
    });
});

function addItem() {
    var popup = jQuery('#modal_item_update');

    popup.find('.alert').addClass('hidden');
    popup.find('.modal-header .modal-title').text("Tạo Câu Chúc");

    popup.find('input[name=item_id]').val('');
    popup.find('select[name=system_category_id]').val('0').change();
    popup.find('textarea[name=title]').val('');

    popup.modal('show');
    jsfocusat('modal_item_update', 'textarea[name=title]');
}

function editItem(id) {
    var popup = jQuery('#modal_item_update');
    var editElement = jQuery('#row-name-' + id);

    popup.find('.alert').addClass('hidden');
    popup.find('.modal-header .modal-title').text("Sửa Câu Chúc");

    popup.find('input[name=item_id]').val(id);
    popup.find('select[name=system_category_id]').val(editElement.attr('data-category')).change();
    popup.find('textarea[name=title]').val(editElement.attr('data-name'));

    popup.modal('show');
    jsfocusat('modal_item_update', 'textarea[name=title]');
}

function confirmUpdateItem() {
    var popup = jQuery('#modal_item_update');
    var itemId = popup.find('input[name=item_id]').val();
    var name = popup.find('textarea[name=title]').val().trim();

    popup.find('.alert').addClass('hidden');

    if (!name || name === '') {
        popup.find('#req-title .alert').removeClass('hidden')
            .text("Hãy nhập câu chúc.");
        return false;
    }

    jspopuploading('modal_item_update');
    popup.find('form')[0].submit();
    return false;
}

function updateStatus(id, col, val) {
    jQuery.ajax({
        url: gks.baseURL + '/admin/wish/update-status',
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
        url: gks.baseURL + '/admin/wish/delete',
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
