<?php
$apiCore = new \App\Api\Core;
$paymentAccount = $apiCore->getSetting('payment_account');

if (!empty($paymentAccount)):
?>


<div class="row form-group" id="frm-payment-account">
    <div class="col-md-12">
        <label class="frm-label">Lưu ý khi mua hàng:</label>
        <div><?php echo nl2br($paymentAccount);?></div>
    </div>
</div>

<?php endif;?>
