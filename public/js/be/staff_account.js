jQuery(document).ready(function () {
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

function uploadAvatar() {
    jQuery('#select_avatar')[0].click();
}

function toggleEdit() {
    if (jQuery('.c-edit').hasClass('hidden')) {
        jQuery('.c-edit').removeClass('hidden');
        jQuery('.c-info').addClass('hidden');
    } else {
        jQuery('.c-edit').addClass('hidden');
        jQuery('.c-info').removeClass('hidden');
    }
}

function submitFrm() {
    var frmEmail = jQuery('#frm-email').val().trim();
    var frmPhone = jQuery('#frm-phone').val().trim();
    var frmId = jQuery('#frm-id').val().trim();

    if (isValidFrm()) {
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
                    jQuery('#frm-add').removeAttr('onsubmit').submit();

                    jQuery('#frm-add .btn').hide().after(gks.loadingIMG);
                } else {
                    if (!response.VALID_EMAIL) {
                        jQuery('#req-email .alert-danger').text("Địa chỉ email đã được đăng kí.").removeClass('hidden');
                    }
                    if (!response.VALID_PHONE) {
                        jQuery('#req-phone .alert-danger').text("Điện thoại đã được đăng kí.").removeClass('hidden');
                    }
                }
            }
        });
    }
    return false;
}

function isValidFrm() {
    var regexEmail = new RegExp(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-z]{2,4})$/);
    var regexLink = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/;
    var frmEmail = jQuery('#frm-email').val().trim();
    var frmName = jQuery('#frm-name').val().trim();
    var frmPhone = jQuery('#frm-phone').val().trim();
    var valid = true;

    jQuery('#frm-add .alert-danger').addClass('hidden');

    if (!frmEmail || !regexEmail.test(frmEmail)) {
        jQuery('#req-email .alert-danger').text("Hãy nhập email hợp lệ.").removeClass('hidden');
        valid = false;
    }
    if (!frmName || frmName === '') {
        jQuery('#req-name .alert-danger').removeClass('hidden');
        valid = false;
    }
    if (!frmPhone || frmPhone === '') {
        jQuery('#req-phone .alert-danger').removeClass('hidden');
        valid = false;
    }

    return valid;
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
