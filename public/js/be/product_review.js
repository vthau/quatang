function updateStatus(ele, id) {
    jQuery.ajax({
        url: gks.baseURL + '/admin/product/review/update-status',
        type: 'post',
        data: {
            id: id,
            active: ele.value,
            _token: gks.tempTK,
        },
        success: function (response) {
            window.location.reload(true);
        },
    });
}
