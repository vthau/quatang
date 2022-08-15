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

    $("#upload-slides").change(function () {
        jQuery('#req-slides .alert-danger').addClass('hidden');
        if (this.files.length) {
            var i = 0;
            for (i; i<=99; i++) {
                if (this.files[i] && this.files[i].size > gks.maxSize) {
                    jQuery('#req-slides .alert-danger').removeClass('hidden');

                    jQuery(this).val("");
                    jQuery('#slides-preview').empty();
                    return false;
                }
            }
        }

        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0] && (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#slides-preview').append("<div class='img-item'><div class='img-preview'><img src='" + e.target.result + "' ></div></div>");
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    });
});

function calculatePrice() {
    var frm = jQuery('#frm-add');
    var price = frm.find('input[name=price_main]').val().trim();
    var discount = frm.find('input[name=discount]').val().trim();

    price = price.replace(/,/g, '');
    discount = discount.replace(/,/g, '');

    frm.find('input[name=price_pay]').val('');

    if (price > 0) {
        if (discount > 0) {
            price = Math.round(price - (price * discount / 100));
        }
        if (price > 0) {
            frm.find('input[name=price_pay]').val(price).simpleMoneyFormat();
        }
    }
}

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
    var frm = jQuery('#frm-add');
    var frmTitle = frm.find('input[name=title]').val().trim();
    var frmCategory = frm.find('select[name=product_category_id]').val();
    var frmBrand = frm.find('select[name=product_brand_id]').val();
    var frmPrice = frm.find('input[name=price_main]').val().trim();
    var valid = true;

    frm.find('.alert-danger').addClass('hidden');

    if (!frmTitle || frmTitle === '') {
        frm.find('#req-title .alert-danger').removeClass('hidden');
        valid = false;
    }
    if (!frmCategory || !parseInt(frmCategory)) {
        frm.find('#req-category .alert-danger').removeClass('hidden');
        valid = false;
    }
    if (!frmBrand || !parseInt(frmBrand)) {
        frm.find('#req-brand .alert-danger').removeClass('hidden');
        valid = false;
    }
    if (!frmPrice || parseInt(frmPrice) <= 0) {
        frm.find('#req-price_main .alert-danger').removeClass('hidden');
        valid = false;
    }

    return valid;
}

function removeSlides(id) {
    jQuery('#photo-' + id).remove();
    jQuery('#old-photos').val(jQuery('#old-photos').val().replace('p_' + id + ';', ''));
}
