jQuery(document).ready(function () {
    jQuery("#frm-add").on("submit", function (e) {
        e.preventDefault();
        submitFrm();
    });
});

function submitFrm() {
    var frm = jQuery("#frm-add");

    if (isValidFrm()) {
        frm.find(".card-footer").hide().after(gks.loadingIMG);

        frm[0].submit();
    }

    jQuery("html, body").animate(
        {
            scrollTop: frm.find("#req-product_limit").offset().top - 200,
        },
        1000
    );

    return false;
}

function isValidFrm() {
    var frm = jQuery("#frm-add");
    var frmProductLimit = frm.find("input[name=product_limit]").val().trim();
    var frmProductLine = frm.find("input[name=product_line]").val().trim();
    var valid = true;

    frm.find(".alert-danger").addClass("hidden");

    if (!frmProductLimit || parseInt(frmProductLimit) < 8) {
        frm.find("#req-product_limit .alert-danger").removeClass("hidden");
        valid = false;
    }
    if (!frmProductLine || parseInt(frmProductLine) < 2) {
        frm.find("#req-product_line .alert-danger").removeClass("hidden");
        valid = false;
    }

    return valid;
}
