jQuery(document).ready(function () {
    baoCaoTongHop();
    baoCaoChiTiet();
    baoCaoHoaHong();
    baoCaoDoanhThu();
});

function baoCaoTimKiem() {
    var frm = jQuery('#frm-search');
    var curYear = parseInt(frm.find('input[name=cur_year]').val());
    var selYear = parseInt(frm.find('select[name=year]').val());

    if (curYear !== selYear) {
        frm.find('input[name=cur_year]').val(frm.find('select[name=year]').val());

        baoCaoTongHop();
    }

    baoCaoChiTiet();
    baoCaoHoaHong();
    baoCaoDoanhThu();
}

function baoCaoTongHop() {
    var frm = jQuery('#frm-search');
    var body = jQuery('#search_body');

    jQuery.ajax({
        url: gks.baseURL + '/admin/report/search',
        type: 'post',
        data: {
            month: frm.find('select[name=month]').val(),
            year: frm.find('select[name=year]').val(),
            partner: frm.find('select[name=user_id]').val(),
            type: 'thop',
            _token: gks.tempTK,
        },
        beforeSend: function () {
            body.find('.tab_thop').empty().append(gks.loadingIMG);
        },
        success: function (response) {
            body.find('.tab_thop').empty().append(response.BODY);
        },
    });
}

function baoCaoChiTiet() {
    var frm = jQuery('#frm-search');
    var body = jQuery('#search_body');

    jQuery.ajax({
        url: gks.baseURL + '/admin/report/search',
        type: 'post',
        data: {
            month: frm.find('select[name=month]').val(),
            year: frm.find('select[name=year]').val(),
            partner: frm.find('select[name=user_id]').val(),
            type: 'ctiet',
            _token: gks.tempTK,
        },
        beforeSend: function () {
            body.find('.tab_ctiet').empty().append(gks.loadingIMG);
        },
        success: function (response) {
            body.find('.tab_ctiet').empty().append(response.BODY);
        },
    });
}

function baoCaoHoaHong() {
    var frm = jQuery('#frm-search');
    var body = jQuery('#search_body');

    jQuery.ajax({
        url: gks.baseURL + '/admin/report/search',
        type: 'post',
        data: {
            month: frm.find('select[name=month]').val(),
            year: frm.find('select[name=year]').val(),
            partner: frm.find('select[name=user_id]').val(),
            type: 'hhong',
            _token: gks.tempTK,
        },
        beforeSend: function () {
            body.find('.tab_hhong').empty().append(gks.loadingIMG);
        },
        success: function (response) {
            body.find('.tab_hhong').empty().append(response.BODY);
        },
    });
}

function baoCaoDoanhThu() {
    var frm = jQuery('#frm-search');
    var body = jQuery('#search_body');

    jQuery.ajax({
        url: gks.baseURL + '/admin/report/search',
        type: 'post',
        data: {
            month: frm.find('select[name=month]').val(),
            year: frm.find('select[name=year]').val(),
            partner: frm.find('select[name=user_id]').val(),
            type: 'dthu',
            _token: gks.tempTK,
        },
        beforeSend: function () {
            body.find('.tab_dthu').empty().append(gks.loadingIMG);
        },
        success: function (response) {
            body.find('.tab_dthu').empty().append(response.BODY);
        },
    });
}

function exportBaoCao(type) {
    var frm = jQuery('#frm-search');
    var href = gks.baseURL + '/excel/bao-cao-tong-hop';
    if (type === 'chi_tiet_doi_tac') {
        href = gks.baseURL + '/excel/bao-cao-chi-tiet-doi-tac';
    } else if (type === 'hoa_hong') {
        href = gks.baseURL + '/excel/bao-cao-hoa-hong';
    } else if (type === 'doanh_thu_khach_hang') {
        href = gks.baseURL + '/excel/bao-cao-doanh-thu-khach-hang';
    }

    showMessage("Vui lòng chờ trong giây lát!");

    href += '?month=' + frm.find('select[name=month]').val()
        + '&year=' + frm.find('select[name=year]').val()
        + '&user=' + frm.find('select[name=user_id]').val();
    gotoPage(href);
}

