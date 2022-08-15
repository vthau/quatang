<?php
$id = (isset($item) && $item) ? $item->id : 0;
if (!$id) {
    return;
}

$apiFE = new \App\Api\FE;
$products = $apiFE->getProductsByCategoryId($id);
if (!count($products)) {
    return;
}

?>

@include ('modals.product')

