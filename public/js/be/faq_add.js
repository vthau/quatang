jQuery(document).ready(function () {

});

function submitFrm() {
    if (isValidFrm()) {
        jQuery('#frm-add').removeAttr('onsubmit').submit();

        jQuery('#frm-add .card-footer').hide().after(gks.loadingIMG);
    }

    jQuery('html, body').animate({
        scrollTop: jQuery("#req-question").offset().top - 200
    }, 1000);

    return false;
}

function isValidFrm() {
    var frmQuestion = jQuery('#frm-question').val().trim();
    var frmAnswer = jQuery('#frm-answer').val().trim();
    var valid = true;

    jQuery('#frm-add .alert-danger').addClass('hidden');

    if (!frmQuestion || frmQuestion === '') {
        jQuery('#req-question .alert-danger').removeClass('hidden');
        valid = false;
    }

    if (!frmAnswer || frmAnswer === '') {
        jQuery('#req-answer .alert-danger').removeClass('hidden');
        valid = false;
    }

    return valid;
}
