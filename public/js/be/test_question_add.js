jQuery(document).ready(function () {
    jQuery('#frm-add').on('submit', function(e){
        e.preventDefault();
        itemSubmit();
    });
});

function itemSubmit() {
    var frm = jQuery('#frm-add');
    frm.find('.alert').addClass('hidden');
    var valid = true;

    var title = frm.find('input[name=title]').val().trim();
    var ans1 = frm.find('input[name=answer_1]').val().trim();
    var ans2 = frm.find('input[name=answer_2]').val().trim();
    var ans3 = frm.find('input[name=answer_3]').val().trim();
    var ans4 = frm.find('input[name=answer_4]').val().trim();
    var right = frm.find('input[name=right]:checked').val();

    if (!title || title === '') {
        frm.find('#req-title .alert').removeClass('hidden');
        valid = false;
    }

    if (!ans1 || ans1 === '') {
        frm.find('#req-answer-1 .alert').removeClass('hidden');
        valid = false;
    }

    if (!ans2 || ans2 === '') {
        frm.find('#req-answer-2 .alert').removeClass('hidden');
        valid = false;
    }

    if (!ans3 || ans3 === '') {
        frm.find('#req-answer-3 .alert').removeClass('hidden');
        valid = false;
    }

    if (!ans4 || ans4 === '') {
        frm.find('#req-answer-4 .alert').removeClass('hidden');
        valid = false;
    }

    if (!valid) {
        return false;
    }

    frm.find('button').hide();
    frm.find('button').after(gks.loadingIMG);
    frm[0].submit();

    return false;
}
