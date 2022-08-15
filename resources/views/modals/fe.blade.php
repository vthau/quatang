<div id="modalCartAdded"  class="frm-popup">
    <div class="frm-popup-main">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-uppercase">Giỏ Hàng</h4>
            </div>
            <div class="modal-body">
                <p>Đã thêm sản phẩm vào giỏ hàng thành công.</p>
                <p>(tự động tắt sau 3 giây)</p>
            </div>
            <div class="modal-footer mt-2 text-center">
                <button type="button" class="button button_primary" onclick="gotoPage('{{url('gio-hang')}}')">Xem Giỏ Hàng</button>
            </div>
        </div>
    </div>
</div>

<div id="modalCartCancelled"  class="frm-popup">
    <div class="frm-popup-main">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-uppercase">Hủy Đơn Hàng</h4>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc muốn hủy đơn hàng này không?</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="cart-cancel" />

                <div id="frm-btn-loading" class="hidden">
                    <i class="fas fa-spinner"></i>
                </div>

                <button type="button" class="button" data-dismiss="modal">Không</button>
                <button type="button" class="button button_primary" onclick="confirmCancelCart(1)">Có</button>
            </div>
        </div>
    </div>
</div>
