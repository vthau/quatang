jQuery(document).ready(function () {
    jQuery('.notfound').text("Không tìm thấy dữ liệu phù hợp.");
    //
    jskhsearchhht();

    //
    jQuery('#frm-add').on('submit', function(e){
        e.preventDefault();
        submitFrm();
    });

    //avatar
    jQuery('#select_avatar').fileupload({
        dataType: 'json',
        add: function (e, data) {
            jQuery('.c-account-avatar').removeAttr('style').attr('style', 'background-image:url(\'' + gks.loadingUPLOADPHOTO + '\')');
            data.submit();
        },
        done: function (e, data) {
            window.location.reload(true);
        }
    }).prop('disabled', !jQuery.support.fileInput)
        .parent().addClass(jQuery.support.fileInput ? undefined : 'disabled');
});
var ajHHT = null, ajDST = null;

function uploadAvatar() {
    jQuery('#select_avatar')[0].click();
}

function frmEdit() {
    if (jQuery('.c-edit').hasClass('hidden')) {
        jQuery('.c-edit').removeClass('hidden');
        jQuery('.c-info').addClass('hidden');
    } else {
        jQuery('.c-edit').addClass('hidden');
        jQuery('.c-info').removeClass('hidden');
    }
}
function isValidFrm() {
    var frm = jQuery('#frm-add');
    var regexEmail = new RegExp(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-z]{2,4})$/);
    // var regexLink = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/;
    var frmName = frm.find('input[name=name]').val().trim();
    var frmEmail = frm.find('input[name=email]').val().trim();
    var frmPhone = frm.find('input[name=phone]').val().trim();
    var valid = true;

    frm.find('.alert').addClass('hidden');

    if (!frmName || frmName === '') {
        frm.find('#req-name .alert-danger').removeClass('hidden');
        valid = false;
    }
    if (!frmEmail || !regexEmail.test(frmEmail)) {
        frm.find('#req-email .alert-danger').text("Vui lòng nhập email hợp lệ.").removeClass('hidden');
        valid = false;
    }
    if (!frmPhone || frmPhone === '' || frmPhone.length < 10) {
        frm.find('#req-phone .alert-danger').removeClass('hidden');
        valid = false;
    }

    return valid;
}
function submitFrm() {
    if (isValidFrm()) {
        var frm = jQuery('#frm-add');
        var frmEmail = frm.find('input[name=email]').val().trim();
        var frmPhone = frm.find('input[name=phone]').val().trim();
        var frmId = frm.find('input[name=item_id]').length ? frm.find('input[name=item_id]').val() : 0;

        //check email / phone
        jQuery.ajax({
            url: gks.baseURL + '/admin/staff/check-info',
            type: 'post',
            data: {
                id: frmId,
                email: frmEmail,
                phone: frmPhone,
                _token: gks.tempTK,
            },
            success: function (response) {
                if (response.VALID_EMAIL && response.VALID_PHONE) {
                    frm.find('button').hide().after(gks.loadingIMG);
                    frm[0].submit();

                } else {
                    if (!response.VALID_EMAIL) {
                        frm.find('#req-email .alert-danger').text("Địa chỉ email đã được đăng kí.").removeClass('hidden');
                    }
                    if (!response.VALID_PHONE) {
                        frm.find('#req-phone .alert-danger').text("Điện thoại đã được đăng kí.").removeClass('hidden');
                    }
                }
            }
        });
    }
    return false;
}

function changePassword(id) {
    jQuery('#change-password').val(id);
    jQuery('#modalPassword').modal('show');
}
function confirmChangePassword(value) {
    if (!value) {
        jQuery('#change-password').val("");
        jQuery('#modalPassword').modal('hide');
    } else {
        var password = jQuery('#frm-password').val().trim();
        jQuery('#modalPassword .alert-danger').addClass('hidden');
        if (!password) {
            jQuery('#modalPassword .alert-danger').removeClass('hidden');
            return false;
        }

        jQuery.ajax({
            url: gks.baseURL + '/admin/staff/change-password',
            type: 'post',
            data: {
                id: jQuery('#change-password').val().trim(),
                password: password,
                _token: gks.tempTK,
            },
            beforeSend: function () {
                jQuery('#modalPassword .modal-footer').hide();
                jQuery('#modalPassword .modal-footer').after(gks.loadingIMG);
            },
            success: function (response) {
                confirmChangePassword(0);
                window.location.reload(true);
            },
        });
    }
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
            url: gks.baseURL + '/admin/staff/delete',
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
                parent.window.location.href = gks.baseURL + '/admin/staffs';
            },
        });
    }
}

function jskhsearchhht() {
    if (ajHHT) {
        ajHHT.abort();
    }
    var bind = jQuery('#body_thht');

    ajHHT = jQuery.ajax({
        url: gks.baseURL + '/kh/dt/hht',
        type: 'post',
        data: {
            month: bind.find('select[name=month]').val(),
            year: bind.find('select[name=year]').val(),
            item_id: bind.find('input[name=item_id]').val(),
            _token: gks.tempTK,
        },
        beforeSend: function () {
            bind.find('.hh_tam_tinh').text('0');
            bind.find('.hh_thuc_te').text('0');
            bind.find('.tds_net_tam_tinh').text('0');
            bind.find('.tds_net_thuc_te').text('0');
            bind.find('.tds_tam_tinh').text('0');
            bind.find('.tds_thuc_te').text('0');
            bind.find('.tc_tam_tinh').text('0');
            bind.find('.tc_thuc_te').text('0');

            bind.find('.hh_body').empty()
                .append(gks.loadingIMG);
            bind.find('.js-loading')
                .attr('style', 'padding: 10px;');
        },
        success: function (response) {
            bind.find('.hh_body').empty();

            if (response.VALID) {
                bind.find('.hh_tam_tinh').text(response.DATA.hht_tam_tinh);
                bind.find('.hh_thuc_te').text(response.DATA.hht_thuc_te);
                bind.find('.tds_net_tam_tinh').text(response.DATA.ds_net_tam_tinh);
                bind.find('.tds_net_thuc_te').text(response.DATA.ds_net_thuc_te);
                bind.find('.tds_tam_tinh').text(response.DATA.tds_tam_tinh);
                bind.find('.tds_thuc_te').text(response.DATA.tds_thuc_te);
                bind.find('.tc_tam_tinh').text(response.DATA.tc_tam_tinh);
                bind.find('.tc_thuc_te').text(response.DATA.tc_thuc_te);

                bind.find('.hh_body').append(response.BODY);
            }

            bindEvent();
        }
    });
}
function jskhsearchdst() {
    if (ajDST) {
        ajDST.abort();
    }
    var bind = jQuery('#body_tdst');

    ajDST = jQuery.ajax({
        url: gks.baseURL + '/kh/dt/dst',
        type: 'post',
        data: {
            month: bind.find('select[name=month]').val(),
            year: bind.find('select[name=year]').val(),
            item_id: bind.find('input[name=item_id]').val(),
            _token: gks.tempTK,
        },
        beforeSend: function () {
            bind.find('.ds_tam_tinh').text('0');
            bind.find('.tds_tam_tinh .tien_thuong').text('0');
            bind.find('.tds_tam_tinh .phan_tram').empty();

            bind.find('.ds_thuc_te').text('0');
            bind.find('.tds_thuc_te .tien_thuong').text('0');
            bind.find('.tds_thuc_te .phan_tram').empty();

            bind.find('.ds_body').empty()
                .append(gks.loadingIMG);
            bind.find('.js-loading')
                .attr('style', 'padding: 10px;');
        },
        success: function (response) {
            bind.find('.ds_body').empty();

            if (response.VALID) {
                bind.find('.ds_tam_tinh').text(response.TAM_TINH_DS);
                bind.find('.tds_tam_tinh .tien_thuong').text(response.TAM_TINH_TT);
                bind.find('.tds_tam_tinh .phan_tram').text('(' + response.TAM_TINH_PT + '%)');

                bind.find('.ds_thuc_te').text(response.THUC_TE_DS);
                bind.find('.tds_thuc_te .tien_thuong').text(response.THUC_TE_TT);
                bind.find('.tds_thuc_te .phan_tram').text('(' + response.THUC_TE_PT + '%)');

                bind.find('.ds_body').append(response.BODY);
            }

            bindEvent();
        }
    });
}
