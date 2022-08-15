jQuery(document).ready(function () {

    jQuery('#modal-import-excel form').on('submit', function(e){
        e.preventDefault();
        nhapTuExcelXacNhan();
    });
});

function kiemTraFileImport(sender) {
    var popup = jQuery('#modal-import-excel');
    var validExts = new Array(".xlsx", ".xls");
    var fileExt = sender.value;
    popup.find('.error').addClass('hidden');
    fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
    if (validExts.indexOf(fileExt) < 0) {

        popup.find('.error').text(gks.importExcelOnly).removeClass('hidden');
        popup.find('.modal-body input').val('');

        return false;
    }

    return true;
}

function nhapTuExcel() {
    var popup = jQuery('#modal-import-excel');

    popup.find('.error').addClass('hidden');
    popup.find('.modal-body input').val('');

    popup.modal('show');
}

function nhapTuExcelXacNhan() {
    var popup = jQuery('#modal-import-excel');
    var file = popup.find('input[name=file]').val();
    if (!file || file === '') {
        return false;
    }

    modalLoading('modal-import-excel');
    popup.find('form')[0].submit();
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
            url: gks.baseURL + '/admin/test/question/delete',
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
                window.location.reload(true);
            },
        });
    }
}
