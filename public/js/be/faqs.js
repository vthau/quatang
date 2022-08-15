jQuery(document).ready(function () {

});

function filterBy(value) {
    switch (value) {
        case 'question':
            jQuery('#btn-filter').text("Câu Hỏi");
            break;
        case 'answer':
            jQuery('#btn-filter').text("Trả Lời");
            break;
    }
    jQuery('#filter-by').val(value);
}

function updateStatus(ele, col) {
    jQuery.ajax({
        url: gks.baseURL + '/admin/faq/update-status',
        type: 'post',
        data: {
            id: jQuery(ele).attr('data-id'),
            value: jQuery(ele).val(),
            status: col,
            _token: gks.tempTK,
        },
        success: function (response) {

        },
    });
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
            url: gks.baseURL + '/admin/faq/delete',
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
