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

function updateStatus(ele, col, val) {
    var tr = jQuery(ele).closest('tr');

    jQuery.ajax({
        url: gks.baseURL + '/admin/product/update-status',
        type: 'post',
        data: {
            item_id: tr.attr('data-id'),
            value: val,
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
        url: gks.baseURL + '/admin/product/delete',
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
