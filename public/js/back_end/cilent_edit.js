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
            scrollTop: frm.find("#req-title").offset().top - 200,
        },
        1000
    );

    return false;
}

function isValidFrm() {
    var frm = jQuery("#frm-add");
    var frmCategory = frm.find("select[name=user_category_id]").val();
    var valid = true;

    frm.find(".alert-danger").addClass("hidden");

    if (!frmCategory || !parseInt(frmCategory)) {
        frm.find("#req-category .alert-danger").removeClass("hidden");
        valid = false;
    }

    return valid;
}
