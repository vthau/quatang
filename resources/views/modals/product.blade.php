<?php
if (!isset($products) || !count($products)) {
    return;
}
foreach ($products as $product):
?>
    @include('modals.product_one')
<?php endforeach;?>
