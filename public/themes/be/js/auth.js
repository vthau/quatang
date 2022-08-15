//login
function validateLogin() {
    jQuery('.alert').addClass('hidden');
    var email = jQuery('#frm-email').val().trim();
    var pass = jQuery('#frm-password').val().trim();
    if (!email) {
        jQuery('#err-email .alert').removeClass('hidden');
        return false;
    }
    if (!pass) {
        jQuery('#err-password .alert').removeClass('hidden');
        return false;
    }
}

//forgot
function doForgot() {
    var frmEmail = jQuery('#frm-email').val().trim();
    var baseURL = jQuery('#frm-base-url').val().trim();

    if (isValidForgot()) {
        //check email unique
        jQuery.ajax({
            url: baseURL + '/auth/check-email',
            type: 'post',
            data: {
                email: frmEmail,
                _token: gks.tempTK,
            },
            success: function (response) {
                if (response.VALID) {
                    jQuery.ajax({
                        url: baseURL + '/auth/do-forgot',
                        type: 'post',
                        data: {
                            email: frmEmail,
                            _token: gks.tempTK,
                        },
                        beforeSend: function () {
                            jQuery('#err-email .alert-danger').text("Đang xử lí...").removeClass('hidden');
                        },
                        success: function (response) {
                            if (response.VALID) {
                                jQuery('#err-email .alert-danger').text("Hệ thống đã gửi cho bạn 1 email. Vui lòng truy cập email và thực hiện theo các bước.").removeClass('hidden');
                            } else {
                                jQuery('#err-email .alert-danger').text("Đã có lỗi xảy ra. Vui lòng thử lại sau vài phút.").removeClass('hidden');
                            }

                        }
                    });
                } else {
                    jQuery('#err-email .alert-danger').text("Địa chỉ email không có trong hệ thống.").removeClass('hidden');
                }
            },
        });
        return false;
    }
    return false;
}

function isValidForgot() {
    var regexEmail = new RegExp(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-z]{2,4})$/);
    var frmEmail = jQuery('#frm-email').val().trim();
    var valid = true;

    jQuery('#frm-forgot .alert-danger').addClass('hidden');

    if (!frmEmail || !regexEmail.test(frmEmail)) {
        jQuery('#err-email .alert-danger').text("Hãy nhập email hợp lệ.").removeClass('hidden');
        valid = false;
    }
    return valid;
}

//reset
function doReset() {
    var frmPassword = jQuery('#frm-password').val().trim();
    var tokenKey = jQuery('#_key_tks').val().trim();
    var baseURL = jQuery('#frm-base-url').val().trim();

    if (isValidReset()) {
        jQuery.ajax({
            url: baseURL + '/auth/reset-password',
            type: 'post',
            data: {
                key: tokenKey,
                password: frmPassword,
                _token: gks.tempTK,
            },
            success: function (response) {
                if (response.VALID) {
                    jQuery('#err-password .alert-danger').text("Thay đổi thành công.").removeClass('hidden');
                    setTimeout(function () {
                        parent.window.location.href = baseURL + '/login';
                    }, 1000);
                } else {
                    jQuery('#err-password .alert-danger').text("Đã có lỗi xảy ra. Vui lòng thử lại sau vài phút.").removeClass('hidden');
                }
            },
        });
    }
    return false;
}

function isValidReset() {
    var frmPassword = jQuery('#frm-password').val().trim();
    var valid = true;

    jQuery('#frm-forgot .alert-danger').addClass('hidden');

    if (!frmPassword) {
        jQuery('#err-password .alert-danger').removeClass('hidden');
        valid = false;
    }
    return valid;
}

