jQuery(document).ready(function () {
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

    $("#upload-banner").change(function () {
        jQuery('#req-banner .alert-danger').addClass('hidden');
        if(this.files[0].size > gks.maxSize) {
            jQuery('#req-banner .alert-danger').removeClass('hidden');

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
                    $('#banner-preview').empty().append("<div class='banner-preview'><img src='" + e.target.result + "' ></div>");
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    });

    jQuery('#frm-add').on('submit', function(e){
        e.preventDefault();
        submitFrm();
    });

});

function submitFrm() {
    var frm = jQuery('#frm-add');
    if (isValidFrm()) {
        frm.find('.card-footer').hide().after(gks.loadingIMG);

        frm[0].submit();
    }

    jQuery('html, body').animate({
        scrollTop: frm.find("#req-title").offset().top - 200
    }, 1000);

    return false;
}

function isValidFrm() {
    var frm = jQuery('#frm-add');
    var frmTitle = frm.find('#frm-title').val().trim();
    var frmStart = frm.find('#frm-date_start').val().trim();
    var frmEnd = frm.find('#frm-date_end').val().trim();
    var valid = true;

    frm.find('.alert-danger').addClass('hidden');

    if (!frmTitle || frmTitle === '') {
        frm.find('#req-title .alert-danger').removeClass('hidden');
        valid = false;
    }
    if (!frmStart) {
        frm.find('#req-date_start .alert-danger').removeClass('hidden');
        valid = false;
    }
    if (frmEnd && frmEnd < frmStart) {
        frm.find('#req-date_end .alert-danger').removeClass('hidden');
        valid = false;
    }

    var at1 = false;
    frm.find('input[type=checkbox]').each(function (pos, ele) {
        if (jQuery(ele).is(':checked')) {
            at1 = true;
        }
    });
    if (!at1) {
        frm.find('.err_sale_apply').removeClass('hidden');
        valid = false;
    }

    return valid;
}

function addTo() {
    jQuery('#modalAddTo').modal('show');
}

function confirmAddTo(value) {
    if (!value) {
        jQuery('#modalAddTo').modal('hide');

        jQuery('#select-add_to').val("");
        jQuery('#modal-apply_to').addClass('hidden');
        jQuery('#select-apply_to').empty();
    } else {
        var to1 = jQuery('#select-add_to').val();
        var to2 = jQuery('#select-apply_to').val();
        jQuery('#modalAddTo .alert-danger').addClass('hidden');
        jQuery('#frm-add_to').removeClass('hidden');

        if (!to1 || parseInt(to1) <= 0) {
            jQuery('#modalAddTo .alert-danger').removeClass('hidden').text("Hãy chọn nhóm SP hoặc sản phẩm hoặc thương hiệu.");
            return false;
        }

        if (!to2 || parseInt(to2) <= 0) {
            jQuery('#modalAddTo .alert-danger').removeClass('hidden').text("Hãy chọn nhóm SP hoặc sản phẩm hoặc thương hiệu áp dụng.");
            return false;
        }

        var chosen = to1 + '_' + to2;
        if (jQuery('#frm-add_to #ele-' + chosen).length) {
            jQuery('#frm-add_to').prepend(jQuery('#ele-' + chosen));
        } else {
            addApplyTo(to1, to2, jQuery('#select-apply_to option:selected').text(), '');
        };

        confirmAddTo(0);
    }
}

function addApplyTo(addTo, applyToId, applyToText, discount) {
    var discount = (parseInt(discount) > 0) ? discount : 10;
    var chosen = addTo + '_' + applyToId;
    var ids = addTo + '_' + applyToId + ';';
    applyToText = applyToText.replace('+ ', '');
    applyToText = applyToText.replace('- ', '');

    if (addTo == 'group') {
        applyToText = 'NHÓM SP: ' + applyToText;
    } else if (addTo == 'brand') {
        applyToText = 'THƯƠNG HIỆU: ' + applyToText;
    }

    var html = '';
    html += '<div id="ele-' + chosen + '" class="apply-to-item row" >';
    html += '<div class="col-lg-6 apply-to-text">' + applyToText + '</div>';
    html += '<div class="col-lg-4 apply-to-discount">' +
        '<input type="hidden" name="item_id[]" value="' + applyToId + '" />' +
        '<input type="hidden" name="item_type[]" value="' + addTo + '" />' +
        '<div class="row"><span class="col-lg-3">giảm</span> <span class="col-lg-7"><input value="' + discount + '" type="text" name="discount[]" placeholder="% giảm" class="form-control" onkeypress="return isInputNumber(event, this)" oncopy="return false;" oncut="return false;" onpaste="return false;" /></span> <span class="col-lg-2">(%)</span></div>' +
        '</div>';
    html += '<div class="col-lg-2 apply-to-remove align-center"><button class="btn btn-danger" onclick="removeApplyTo(\'' + chosen + '\', \'' + ids + '\')"><img src="' + gks.baseURL + '/public/images/icons/ic_trash.png" /></button></div>';
    html += '</div>';

    jQuery('#frm-add_to').prepend(html);
    jQuery('#frm-add_to').removeClass('hidden');

    jQuery('#frm-to_ids').val(jQuery('#frm-to_ids').val() + ids);
}

function removeApplyTo(chosen, ids) {
    jQuery('#frm-add_to #ele-' + chosen).remove();
    jQuery('#frm-to_ids').val(jQuery('#frm-to_ids').val().replace(ids, ''));

    if (!jQuery('#frm-add_to > div').length) {
        jQuery('#frm-add_to').addClass('hidden');
    }
}

function changeAddTo(ele) {
    var value = jQuery(ele).val().trim();
    jQuery('#modalAddTo .alert-danger').addClass('hidden');

    if (!value) {
        jQuery('#modal-apply_to').addClass('hidden');
    } else {
        jQuery.ajax({
            url: gks.baseURL + '/admin/sale/get-apply-to',
            type: 'post',
            data: {
                value: value,
                _token: gks.tempTK,
            },
            beforeSend: function () {
                jQuery('#modalAddTo .modal-footer').hide();
                jQuery('#modalAddTo .modal-footer').after(gks.loadingIMG);
            },
            success: function (response) {
                jQuery('#select-apply_to').empty();
                if (response.ARR.length) {
                    jQuery('#modal-apply_to').removeClass('hidden');

                    // console.log(response.ARR);
                    if (value == 'group') {
                        jQuery("#select-apply_to").select2({
                            data: response.ARR,
                            formatSelection: function (item) {
                                return item.text
                            },
                            formatResult: function (item) {
                                return item.text
                            },
                            templateResult: modalFormatResult,
                        });
                    } else {
                        jQuery("#select-apply_to").select2({
                            data: response.ARR,
                        });
                    }
                } else {
                    jQuery('#modalAddTo .alert-danger').removeClass('hidden').text("Không có dữ liệu phù hợp.");
                }

                jQuery('#modalAddTo .modal-footer').show();
                jQuery('#modalAddTo .js-loading').remove();
            },
        });
    }
}

function modalFormatResult(node) {
    var $result = $('<span style="padding-left:' + (20 * node.level) + 'px;">' + node.text + '</span>');
    return $result;
};

function themDKThu() {
    var frm = jQuery('#frm-add');
    var total = parseInt(frm.find('input[name=sale_dk_total]').val());
    total++;

    var html = '<div class="row">' +
        '<div class="col-md-1 mb-1">' +
        '<label class="c-switch c-switch-label c-switch-pill c-switch-danger float-left">' +
        '<input name="sale_dk_' + total + '_checkbox" class="c-switch-input" type="checkbox" />' +
        '<span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>' +
        '</label>' +
        '</div>' +
        '<div class="col-md-1 mb-1">' +
        '<input name="sale_dk_' + total + '_percent" type="text" class="form-control money_format text-center" placeholder="%" />' +
        '</div>' +
        '<div class="col-md-10 mb-1">' +
        '<span>Áp dụng cho đơn hàng thứ <input type="text" class="text-center number_format width_80px" name="sale_dk_' + total + '_input" /> sau khi đăng kí thành viên</span>' +
        '</div>' +
        '</div>';

    frm.find('#sale_dk').append(html);

    frm.find('input[name=sale_dk_total]').val(total);
    bindEvent();
}

function themDKGT() {
    var frm = jQuery('#frm-add');
    var total = parseInt(frm.find('input[name=sale_gt_total]').val());
    total++;

    var html = '<div class="row">' +
        '<div class="col-md-1 mb-1">' +
        '<label class="c-switch c-switch-label c-switch-pill c-switch-danger float-left">' +
        '<input name="sale_gt_' + total + '_checkbox" class="c-switch-input" type="checkbox" />' +
        '<span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>' +
        '</label>' +
        '</div>' +
        '<div class="col-md-1 mb-1">' +
        '<input name="sale_gt_' + total + '_percent" type="text" class="form-control money_format text-center" placeholder="%" />' +
        '</div>' +
        '<div class="col-md-10 mb-1">' +
        '<span>Áp dụng cho đơn hàng có giá trị từ <input type="text" class="text-center money_format width_150px" name="sale_gt_' + total + '_from" /> đến <input type="text" class="text-center money_format width_150px" name="sale_gt_' + total + '_to" /> </span>' +
        '</div>' +
        '</div>';

    frm.find('#sale_gt').append(html);

    frm.find('input[name=sale_gt_total]').val(total);
    bindEvent();
}
