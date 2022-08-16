$("#element-list").sortable({
    placeholder: "bg-move-admin",
    update: function (event, ui) {
        let ids = [];
        $("#element-list tr").each(function () {
            ids.push($(this).attr("id"));
        });

        console.log("ids", ids);

        $.ajax({
            url: gks.baseURL + "/admin/element/sort",
            method: "POST",
            data: {
                ids,
                _token: gks.tempTK,
            },
            success: function (res) {},
        });
    },
});

$(".display-element").change(function () {
    const element = $(this);
    const id = element.data("id");
    const isChecked = element.is(":checked") ? 1 : 0;

    if (isChecked) {
        console.log("Check", isChecked);
    } else {
        console.log("Uncheck", isChecked);
    }

    $.ajax({
        url: gks.baseURL + "/admin/element/update",
        method: "POST",
        data: {
            id,
            isChecked,
            _token: gks.tempTK,
        },
        success: function (res) {},
    });
});
