<div id="modal_message" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <i class="fa fa-info text-danger"></i>
                <span class="font-weight-bold ml-2" id="text_message"></span>
            </div>
        </div>
    </div>
</div>

<div id="modal_delete_item" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xác Nhận</h4>
            </div>
            <div class="modal-body">
                <p class="delete_confirm"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                <button type="button" class="btn btn-primary" onclick="jspopupdelete()">Xác Nhận</button>

                <input type="hidden" name="item_id" />
            </div>
        </div>
    </div>
</div>




