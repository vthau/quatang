jQuery(document).ready(function () {
    jQuery("#modal_item_update form").on("submit", function (e) {
        e.preventDefault();
        confirmUpdateItem();
    });
});

function addItem() {
    var popup = jQuery("#modal_item_update");

    popup.find(".alert").addClass("hidden");
    popup.find(".modal-header .modal-title").text("Tạo Nhóm Người Dùng");

    popup.find("input[name=item_id]").val("");
    popup.find("input[name=parent_id]").val("");
    popup.find("input[name=title]").val("");

    popup.modal("show");
    jsfocusat("modal_item_update", "input[name=title]");
}

function editItem(id) {
    var popup = jQuery("#modal_item_update");
    var editElement = jQuery("#title-" + id);

    popup.find(".alert").addClass("hidden");
    popup.find(".modal-header .modal-title").text("Sửa Nhóm Người Dùng");

    popup.find("input[name=item_id]").val(id);
    popup.find("input[name=parent_id]").val("");
    popup.find("input[name=title]").val(editElement.attr("data-name"));

    popup.modal("show");
    jsfocusat("modal_item_update", "input[name=title]");
}

function confirmUpdateItem() {
    var popup = jQuery("#modal_item_update");
    var itemId = popup.find("input[name=item_id]").val();
    var parentId = popup.find("input[name=parent_id]").val();
    var name = popup.find("input[name=title]").val().trim();

    popup.find(".alert").addClass("hidden");

    if (!name || name === "") {
        popup
            .find("#req-title .alert")
            .removeClass("hidden")
            .text("Hãy nhập tên nhóm người dùng.");
        return false;
    }

    jspopuploading("modal_item_update");
    popup.find("form")[0].submit();
    return false;
}

function deleteItem(id) {
    var popup = jQuery("#modal_delete_item");
    popup.find("input[name=item_id]").val(id);
    popup.modal("show");
}

function jspopupdelete() {
    var popup = jQuery("#modal_delete_item");
    jQuery.ajax({
        url: gks.baseURL + "/admin/user-categorie/delete",
        type: "post",
        data: {
            item_id: popup.find("input[name=item_id]").val(),
            _token: gks.tempTK,
        },
        beforeSend: function () {
            jspopuploading("modal_delete_item");
        },
        success: function (response) {
            reloadPage();
        },
    });
}

//child
function toggleItem(id, sub) {
    if (!sub) {
        if (jQuery(".sub-" + id + "").hasClass("hidden")) {
            jQuery(".sub-" + id + "").removeClass("hidden");
        } else {
            jQuery(".sub-" + id + "").addClass("hidden");
        }
    } else {
        if (jQuery(".sub-" + id + ".child-" + sub + "").hasClass("hidden")) {
            jQuery(".sub-" + id + ".child-" + sub + "").removeClass("hidden");
        } else {
            jQuery(".sub-" + id + ".child-" + sub + "").addClass("hidden");
        }
    }
}

function addSubItem(parent_id) {
    var popup = jQuery("#modal_item_update");

    popup.find(".alert").addClass("hidden");
    popup.find(".modal-header .modal-title").text("Tạo Nhóm Người Dùng Con");

    popup.find("input[name=item_id]").val("");
    popup.find("input[name=parent_id]").val(parent_id);
    popup.find("input[name=title]").val("");

    popup.modal("show");
    jsfocusat("modal_item_update", "input[name=title]");
}

function editSubItem(id, parent_id) {
    var popup = jQuery("#modal_item_update");
    var editElement = jQuery("#title-" + id);

    popup.find(".alert").addClass("hidden");
    popup.find(".modal-header .modal-title").text("Sửa Nhóm Người Dùng");

    popup.find("input[name=item_id]").val(id);
    popup.find("input[name=parent_id]").val(parent_id);
    popup.find("input[name=title]").val(editElement.attr("data-name"));

    popup.modal("show");
    jsfocusat("modal_item_update", "input[name=title]");
}
