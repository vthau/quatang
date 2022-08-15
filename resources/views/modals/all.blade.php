<div id="modal-order-confirm"  class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xác Nhận</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning message"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                <button type="button" class="btn btn-primary" onclick="confirmOrderConfirm()">Có</button>

                <input type="hidden" id="item_id" />
                <input type="hidden" id="item_key" />
                <input type="hidden" id="item_value" />
            </div>

        </div>
    </div>
</div>

<div id="modalDelete"  class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xác Nhận</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">Bạn có chắc muốn xóa không?</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                <button type="button" class="btn btn-primary" onclick="confirmDeleteItem(1)">Có</button>
            </div>

            <div class="hidden">
                <input type="hidden" id="delete-item" />
            </div>
        </div>
    </div>
</div>

<div id="modalBlock"  class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xác Nhận</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">Bạn có chắc muốn chặn không?</div>

                <div class="modal-textarea">
                    <textarea id="modal-reason" class="form-control" rows="5" cols="5" placeholder="Lí do"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                <button type="button" class="btn btn-primary" onclick="confirmBlockItem(1)">Có</button>
            </div>

            <div class="hidden">
                <input type="hidden" id="block-item" />
            </div>
        </div>
    </div>
</div>

<div id="modalUnblock"  class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xác Nhận</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">Bạn có chắc muốn mở chặn không?</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                <button type="button" class="btn btn-primary" onclick="confirmUnblockItem(1)">Có</button>
            </div>

            <div class="hidden">
                <input type="hidden" id="unblock-item" />
            </div>
        </div>
    </div>
</div>

<div id="modalPassword"  class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Đổi Mật Khẩu Mới</h4>
            </div>
            <div class="modal-body">
                <div class="modal-password">
                    <input type="password" id="frm-password" placeholder="********" class="form-control" />
                </div>
                <div class="alert alert-danger hidden">Hãy nhập mật khẩu mới.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                <button type="button" class="btn btn-primary" onclick="confirmChangePassword(1)">Có</button>
            </div>

            <div class="hidden">
                <input type="hidden" id="change-password" />
            </div>
        </div>
    </div>
</div>

