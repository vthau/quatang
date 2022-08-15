<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$apiFE = new \App\Api\FE;

if (count($items)):
    foreach ($items as $item):

$districts = $apiFE->getDistrictsByProvinceId($item->province_id);
$wards = $apiFE->getWardsByDistrictId($item->district_id);

$quanHuyen = '';
if (count($districts)) {
    $arr = [];
    foreach ($districts as $district) {
        $arr[] = [
            'id' => $district->id,
            'title' => $district->title,
        ];
    }
    $quanHuyen = json_encode($arr);
}

$phuongXa = '';
if (count($wards)) {
    $arr = [];
    foreach ($wards as $ward) {
        $arr[] = [
            'id' => $ward->id,
            'title' => $ward->title,
        ];
    }
    $phuongXa = json_encode($arr);
}

?>
<div class="table_item" id="person_{{$item->id}}"
     data-title="{{$item->title}}"
     data-phone="{{$item->phone}}"
     data-address="{{$item->address}}"
     data-note="{{$item->note}}"
     data-relationship="{{$item->user_relationship_id}}"
     data-ward="{{$item->ward_id ? $item->ward_id : ''}}"
     data-district="{{$item->district_id ? $item->district_id : ''}}"
     data-province="{{$item->province_id ? $item->province_id : ''}}"
     data-json-district="{{$quanHuyen}}"
     data-json-ward="{{$phuongXa}}"
>
    @include('modals.front_end.user_person_item', [
        'person' => $item,
        'month' => $month,
    ])
</div>
<?php
    endforeach;
endif;?>

