<?php
if (!isset($product)) {
    return;
}

$diemThamHut = (float)$product->diem_tham_hut;
?>
<div class="diem_tham_hut_wrapper">
    <div class="diem_tham_hut_container">
    <?php for($i=1;$i<=10;$i++):

    ?>
    @if ($i < $diemThamHut)
        <img src="{{url('public/images/drop_full.png')}}" />
    @else
        @if ($i - $diemThamHut == 0)
            <img src="{{url('public/images/drop_full.png')}}" />
        @elseif ($i - $diemThamHut > 0 && $i - $diemThamHut < 1)
            <img src="{{url('public/images/drop_half.png')}}" />
        @else
            <img src="{{url('public/images/drop_empty.png')}}" />
        @endif
    @endif
    <?php endfor;?>
    </div>
</div>

