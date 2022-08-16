function deleteItem(id) {
    console.log(id);
    jQuery.ajax({
        url: gks.baseURL + "/admin/banner/delete",
        type: "post",
        data: {
            item_id: id,
            _token: gks.tempTK,
        },
        success: function (response) {
            reloadPage();
        },
    });
}
