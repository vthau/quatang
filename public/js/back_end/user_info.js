jQuery(document).ready(function () {
    jQuery('#frm-add').on('submit', function(e){
        e.preventDefault();
        submitFrm();
    });

    jQuery('#modal_change_password form').on('submit', function(e){
        e.preventDefault();
        changePasswordConfirm();
    });

    //upload
    jQuery('#select_avatar').fileupload({
        dataType: 'json',
        add: function (e, data) {
            jQuery('.c-account-avatar').removeAttr('style').attr('style', 'background-image:url(\'' + gks.loadingUPLOADPHOTO + '\')');
            data.submit();
        },
        done: function (e, data) {
            reloadPage();
        }
    }).prop('disabled', !jQuery.support.fileInput)
        .parent().addClass(jQuery.support.fileInput ? undefined : 'disabled');
});

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
    var popup = jQuery('#modal_change_password');
    popup.find('input[name=item_id]').val(id);
    popup.modal('show');
    jsfocusat('modal_change_password', 'input[name=pwd]');
}

function changePasswordConfirm() {
    var popup = jQuery('#modal_change_password');
    var pwd = popup.find('input[name=pwd]').val().trim();

    popup.find('.alert').addClass('hidden');
    if (!pwd || pwd === '') {
        popup.find('.alert').removeClass('hidden');
        return false;
    }

    jspopuploading('modal_change_password');
    popup.find('form')[0].submit();
}

