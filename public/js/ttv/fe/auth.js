jQuery(document).ready(function () {
    // if (jQuery('#frm-signup').length) {
    //     jQuery('#frm-signup').on('submit', function (e) {
    //         e.preventDefault();
    //         dtjsauthdk();
    //     });
    // }

    if (jQuery('#frm-reset').length) {
        jQuery('#frm-reset').on('submit', function (e) {
            e.preventDefault();
            dtjsauthrp();
        });
    }
});

//signup
function dtjsauthdk() {
    var frm = jQuery('#frm-signup');
    frm.find('.alert').addClass('hidden');
    var name = frm.find('input[name=name]').val().trim();
    var phone = frm.find('input[name=phone]').val().trim();
    var regexEmail = new RegExp(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-z]{2,4})$/);
    var email = frm.find('input[name=email]').val().trim();
    var password1 = frm.find('input[name=password]').val().trim();
    var password2 = frm.find('input[name=password_confirm]').val().trim();
    var valid = true;

    if (!name || name === '') {
        frm.find('.input-name .alert').removeClass('hidden');
        valid = false;
    }
    if (!email || !regexEmail.test(email)) {
        frm.find('.input-email .alert').removeClass('hidden').text('Hãy nhập email hợp lệ.');
        valid = false;
    }
    if (!phone || phone === '' || phone.length < 10) {
        frm.find('.input-phone .alert').removeClass('hidden').text('Hãy nhập số điện thoại hợp lệ (>= 10 số)');
        valid = false;
    }

    if (password1 !== password2) {
        frm.find('.input-password2 .alert').removeClass('hidden');
        valid = false;
    }

    if (!valid) {
        return false;
    }

    //check email
    jQuery.ajax({
        url: gks.baseURL + '/auth/ktra-email',
        type: 'post',
        data: {
            email: email,
            phone: phone,
            _token: gks.tempTK,
        },
        success: function (response) {
            if (response.VALID_EMAIL || response.VALID_PHONE) {
                if (response.VALID_EMAIL) {
                    frm.find('.input-email .alert').removeClass('hidden').text('Địa chỉ email này đã có người sử dụng.');
                }

                if (response.VALID_PHONE) {
                    frm.find('.input-phone .alert').removeClass('hidden').text('Số điện thoại này đã có người sử dụng.');
                }
            } else {
                frm.find('input[type=submit]').hide();
                frm[0].submit();
            }
        },
    });

    return false;
}

//forgot
function dtjsauthfp() {
    var frm = jQuery('#frm-forgot');
    var frmEmail = frm.find('input[name=email]').val().trim();
    var baseURL = frm.find('#frm-base-url').val().trim();

    if (jsvalidfp()) {
        //check email unique
        jQuery.ajax({
            url: baseURL + '/auth/ktra-email',
            type: 'post',
            data: {
                email: frmEmail,
                _token: gks.tempTK,
            },
            success: function (response) {
                if (response.VALID_EMAIL) {
                    jQuery.ajax({
                        url: baseURL + '/auth/quen-mat-khau',
                        type: 'post',
                        data: {
                            email: frmEmail,
                            _token: gks.tempTK,
                        },
                        beforeSend: function () {
                            frm.find('#err-email .alert-danger').text(gks.loading).removeClass('hidden');
                        },
                        success: function (response) {
                            if (response.VALID) {
                                frm.find('#err-email .alert-danger').text("Hệ thống đã gửi cho bạn 1 email. Vui lòng truy cập email và thực hiện theo các bước.").removeClass('hidden');
                            } else {
                                frm.find('#err-email .alert-danger').text(gks.saveERR).removeClass('hidden');
                            }

                        }
                    });
                } else {
                    frm.find('#err-email .alert-danger').text("Địa chỉ email không có trong hệ thống.").removeClass('hidden');
                }
            },
        });
        return false;
    }
    return false;
}

function jsvalidfp() {
    var frm = jQuery('#frm-forgot');
    var regexEmail = new RegExp(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-z]{2,4})$/);
    var frmEmail = frm.find('input[name=email]').val().trim();
    var valid = true;

    frm.find('.alert-danger').addClass('hidden');

    if (!frmEmail || !regexEmail.test(frmEmail)) {
        frm.find('#err-email .alert-danger').text("Hãy nhập email hợp lệ.").removeClass('hidden');
        valid = false;
    }
    return valid;
}

//reset
function dtjsauthrp() {
    var frm = jQuery('#frm-reset');
    var frmPassword = jQuery('#frm-password').val().trim();
    var tokenKey = jQuery('#_key_tks').val().trim();
    var baseURL = jQuery('#frm-base-url').val().trim();

    if (jsvalidrp()) {
        jQuery.ajax({
            url: baseURL + '/auth/doi-mat-khau',
            type: 'post',
            data: {
                key: tokenKey,
                password: frmPassword,
                _token: gks.tempTK,
            },
            beforeSend: function() {
                if (frm.length) {
                    // frm.find('button').after(gks.loadingIMG);
                    frm.find('button').hide();
                }
            },
            success: function (response) {
                if (response.VALID) {
                    jQuery('#err-password .alert-danger').text(gks.successUPDATE).removeClass('hidden');
                    setTimeout(function () {
                        parent.window.location.href = baseURL + '/dang-nhap';
                    }, 1000);
                } else {
                    jQuery('#err-password .alert-danger').text(gks.saveERR).removeClass('hidden');

                    if (frm.length) {
                        // frm.find('.js-loading').remove();
                        frm.find('button').show();
                    }
                }
            },
        });
    }
    return false;
}

function jsvalidrp() {
    var frmPassword = jQuery('#frm-password').val().trim();
    var valid = true;

    jQuery('#frm-forgot .alert-danger').addClass('hidden');

    if (!frmPassword) {
        jQuery('#err-password .alert-danger').removeClass('hidden');
        valid = false;
    }
    return valid;
}

//ctv
function jsvaliddkctv() {
    var frm = jQuery('#frm-ctv');
    frm.find('.alert').addClass('hidden');
    var name = frm.find('input[name=name]').val().trim();
    var phone = frm.find('input[name=phone]').val().trim();
    var regexEmail = new RegExp(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-z]{2,4})$/);
    var email = frm.find('input[name=email]').val().trim();
    var password1 = frm.find('input[name=password]').val().trim();
    var password2 = frm.find('input[name=password_confirm]').val().trim();
    var valid = true;

    if (!name || name === '') {
        frm.find('.input-name .alert').removeClass('hidden');
        valid = false;
    }
    if (!email || !regexEmail.test(email)) {
        frm.find('.input-email .alert').removeClass('hidden').text('Hãy nhập email hợp lệ.');
        valid = false;
    }
    if (!phone || phone === '' || phone.length < 10) {
        frm.find('.input-phone .alert').removeClass('hidden').text('Hãy nhập số điện thoại hợp lệ (>= 10 số)');
        valid = false;
    }

    if (password1 !== password2) {
        frm.find('.input-password2 .alert').removeClass('hidden');
        valid = false;
    }

    if (!valid) {
        return false;
    }

    //check email
    jQuery.ajax({
        url: gks.baseURL + '/auth/ktra-email',
        type: 'post',
        data: {
            email: email,
            phone: phone,
            _token: gks.tempTK,
        },
        success: function (response) {
            if (response.VALID_EMAIL || response.VALID_PHONE) {
                if (response.VALID_EMAIL) {
                    frm.find('.input-email .alert').removeClass('hidden').text('Địa chỉ email này đã có người sử dụng.');
                }

                if (response.VALID_PHONE) {
                    frm.find('.input-phone .alert').removeClass('hidden').text('Số điện thoại này đã có người sử dụng.');
                }
            } else {
                // frm.find('#ele-verify').removeClass('hidden');

                //verify phone
                // verifySms('sign_up');

                frm.find('input[type=submit]').hide();
                frm[0].submit();
            }
        },
    });

    return false;
}



