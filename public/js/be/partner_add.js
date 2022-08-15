jQuery(document).ready(function () {
    jQuery('#frm-add').on('submit', function(e){
        e.preventDefault();
        submitFrm();
    });

    jQuery('.max-size-text').text(gks.maxSizeText);

    $("#upload-avatar").change(function () {
        jQuery('#req-avatar .alert-danger').addClass('hidden');
        if(this.files[0].size > gks.maxSize) {
            jQuery('#req-avatar .alert-danger').removeClass('hidden');

            jQuery(this).val("");
            jQuery('#avatar-preview').empty();
            return false;
        }

        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#avatar-preview').empty().append("<div class='img-preview'><img src='" + e.target.result + "' ></div>");
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    });

});

function submitFrm() {
    if (isValidFrm()) {
        var frm = jQuery('#frm-add');
        var frmEmail = frm.find('input[name=email]').val().trim();
        var frmPhone = frm.find('input[name=phone]').val().trim();
        var frmId = frm.find('input[name=item_id]').val().trim();
        var frmCode = frm.find('input[name=ref_code]').val().trim();
        var frmDonVi = frm.find('select[name=don_vi]').val();

        //check email / phone
        jQuery.ajax({
            url: gks.baseURL + '/admin/staff/check-info',
            type: 'post',
            data: {
                id: frmId,
                email: frmEmail,
                phone: frmPhone,
                partner_code: frmCode,
                partner_donvi: frmDonVi,
                _token: gks.tempTK,
            },
            success: function (response) {
                if (response.VALID_EMAIL && response.VALID_PHONE && response.VALID_REFCODE) {
                    frm.find('button').hide();
                    frm[0].submit();
                } else {
                    if (!response.VALID_EMAIL) {
                        frm.find('#req-email .alert-danger').text("Địa chỉ email đã được đăng kí.").removeClass('hidden');
                    }
                    if (!response.VALID_PHONE) {
                        frm.find('#req-phone .alert-danger').text("Điện thoại đã được đăng kí.").removeClass('hidden');
                    }
                    if (!response.VALID_REFCODE) {
                        frm.find('#req-code .alert-danger').text("Mã giới thiệu đã được đăng kí.").removeClass('hidden');
                    }
                }
            }
        });
    }
    return false;
}

function isValidFrm() {
    var frm = jQuery('#frm-add');
    var regexEmail = new RegExp(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-z]{2,4})$/);
    var frmName = frm.find('input[name=name]').val().trim();
    var frmEmail = frm.find('input[name=email]').val().trim();
    var frmPhone = frm.find('input[name=phone]').val().trim();
    var frmCode = frm.find('input[name=ref_code]').val().trim();
    var frmDonVi = frm.find('select[name=don_vi]').val();
    var valid = true;

    frm.find('.alert').addClass('hidden');

    if (!frmName || frmName === '') {
        frm.find('#req-name .alert').removeClass('hidden');
        valid = false;
    }
    if (!frmEmail || !regexEmail.test(frmEmail)) {
        frm.find('#req-email .alert').text("Hãy nhập email hợp lệ.").removeClass('hidden');
        valid = false;
    }
    if (!frmPhone || frmPhone.length < 10) {
        frm.find('#req-phone .alert').removeClass('hidden');
        valid = false;
    }

    if (frmDonVi === 'to_chuc' && (!frmCode || frmCode === '')) {
        frm.find('#req-code .alert').removeClass('hidden').text("Hãy nhập mã giới thiệu cho tổ chức.");
        frm.find('#req-code label').addClass('required');
        valid = false;
    }

    if (!valid) {
        return false;
    }

    return valid;
}

function maGioiThieu() {

}





