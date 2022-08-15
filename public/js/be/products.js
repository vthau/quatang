jQuery(document).ready(function () {
    jQuery('#modalImport form').on('submit', function(e){
        e.preventDefault();
        nhapTuExcelXacNhan();
    });

    jQuery('#modalSizeUpdate form').on('submit', function(e){
        e.preventDefault();
        sizeExcelConfirm();
    });
});

function filterBy(value) {
    switch (value) {
        case 'name':
            jQuery('#btn-filter').text("Tên");
            break;
        case 'mo_ta':
            jQuery('#btn-filter').text("Mô Tả");
            break;
        case 'cong_dung':
            jQuery('#btn-filter').text("Công Dụng");
            break;
        case 'thanh_phan':
            jQuery('#btn-filter').text("Thành Phần");
            break;
        case 'hdsd':
            jQuery('#btn-filter').text("HDSD");
            break;
    }
    jQuery('#filter-by').val(value);
}

function updateStatus(ele, col) {
    jQuery.ajax({
        url: gks.baseURL + '/admin/product/update-status',
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

function updateState(id, col, val) {
    jQuery.ajax({
        url: gks.baseURL + '/admin/product/update-state',
        type: 'post',
        data: {
            id: id,
            value: val,
            status: col,
            _token: gks.tempTK,
        },
        success: function (response) {
            window.location.reload(true);
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
            url: gks.baseURL + '/admin/product/delete',
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

function exportItems() {
    var frm = jQuery('#frm-search');
    var frmOrder = jQuery('#frm-order');
    var href = gks.baseURL + '/admin/product/export?p=1';

    var keyword = frm.find('input[name=keyword]').val().trim();
    var filter = frm.find('input[name=filter]').val().trim();
    var category = frm.find('select[name=category]').val();
    var brand = frm.find('select[name=brand]').val();
    var country = frm.find('select[name=country]').val();
    var status = frm.find('select[name=status]').val();
    var active = frm.find('select[name=active]').val();
    var state = frm.find('select[name=state]').val();
    var order = frmOrder.find('select[name=order]').val();
    var orderby = frmOrder.find('select[name=orderby]').val();

    href += '&keyword=' + keyword;
    href += '&filter=' + filter;
    href += '&category=' + category;
    href += '&brand=' + brand;
    href += '&country=' + country;
    href += '&status=' + status;
    href += '&active=' + active;
    href += '&state=' + state;
    href += '&order=' + order;
    href += '&orderby=' + orderby;

    gotoPage(href);
}

function kiemTraFileImport(sender) {
    var popup = jQuery('#modal_bhnt_nhap_excel');
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
    var popup = jQuery('#modalImport');

    popup.find('.error').addClass('hidden');
    popup.find('.modal-body input').val('');

    popup.modal('show');
}

function nhapTuExcelXacNhan() {
    var popup = jQuery('#modalImport');
    var file = popup.find('input[name=file]').val();
    if (!file || file === '') {
        return false;
    }

    modalLoading('modalImport');
    popup.find('form')[0].submit();
}

function sizeExcel() {
    var popup = jQuery('#modalSizeUpdate');

    popup.find('.error').addClass('hidden');
    popup.find('.modal-body input').val('');

    popup.modal('show');
}

function sizeExcelConfirm() {
    var popup = jQuery('#modalSizeUpdate');
    var file = popup.find('input[name=file]').val();
    if (!file || file === '') {
        return false;
    }

    modalLoading('modalSizeUpdate');
    popup.find('form')[0].submit();
}
