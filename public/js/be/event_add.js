jQuery(document).ready(function () {
    jQuery('.max-size-text').text(gks.maxSizeText);

    jQuery('#frm-add').on('submit', function(e){
        e.preventDefault();
        submitFrm();
    });

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

    $("#upload-banner").change(function () {
        jQuery('#modal-banner .alert-danger').addClass('hidden');
        if(this.files[0].size > gks.maxSize) {
            jQuery('#modal-banner .alert-danger').removeClass('hidden');

            jQuery(this).val("");
            jQuery('#banner-preview').empty();
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
                    $('#banner-preview').empty().append("<div class='img-preview'><img src='" + e.target.result + "' ></div>");
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    });
});

function submitFrm() {
    if (isValidFrm()) {
        jQuery('#frm-add')[0].submit();

        jQuery('#frm-add .card-footer').hide().after(gks.loadingIMG);
    }

    jQuery('html, body').animate({
        scrollTop: jQuery("#req-title").offset().top - 200
    }, 1000);

    return false;
}

function isValidFrm() {
    var frmTitle = jQuery('#frm-title').val().trim();
    var frmMota = tinymce.get('frm-mota').getContent().toString();
    var valid = true;

    jQuery('#frm-add .alert-danger').addClass('hidden');

    if (!frmTitle || frmTitle === '') {
        jQuery('#req-title .alert-danger').removeClass('hidden');
        valid = false;
    }

    frmMota = frmMota.replace(/<[^>]*>/g, '');
    frmMota = frmMota.replace(/&nbsp;/gi, '');
    frmMota = frmMota.replace(/(?:^(?:&nbsp;)+)|(?:(?:&nbsp;)+$)/g, '');
    frmMota = frmMota.replace(/\s/g, '');
    if (!frmMota || frmMota === '') {
        jQuery('#req-mota .alert-danger').removeClass('hidden');
        valid = false;
    }

    return valid;
}
