<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$dates = $person->getEvents();
?>

<table class="table">
    <tbody>
    @if(!$isMobile)
        <tr class="info_main">
            <td style="width: 15%;" class="text-center">
                <button onclick="jskhpopupdate1({{$person->id}})" type="button" class="btn btn-warning mr__5" title="Thêm Ngày / Tháng Ghi Nhớ">
                    <i class="fa fa-plus"></i>
                </button>
                <button onclick="jskhpopupperson2({{$person->id}})" type="button" class="btn btn-info mr__5" title="Sửa Thông Tin">
                    <i class="fa fa-edit"></i>
                </button>
                <button onclick="jskhpopuppersondel({{$person->id}})" type="button" class="btn btn-danger mr__5" title="Xóa">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
            <td colspan="3">
                <div class="float-right ml__10">
                    <div class="clearfix overflow-hidden">
                        <span class="badge badge-success text-uppercase">{{$person->getRelationship() ? $person->getRelationship()->getTitle() : ''}}</span>
                    </div>
                </div>
                <div class="overflow-hidden">
                    <div class="text-bold fs-16 text-uppercase">{{$person->getTitle()}}</div>
                    <div>
                        @if(!empty($person->phone))
                            <span>{{$person->phone}}</span> -
                        @endif
                        <span>{{$person->getFullAddress()}}</span>
                    </div>
                </div>
            </td>
            <td style="width: 20%;">
                <?php echo nl2br($person->note);?>
            </td>
        </tr>
    @else
        <tr class="info_main">
            <td colspan="2">
                <div class="clearfix">
                    <div class="float-right ml__10">
                        <div class="clearfix overflow-hidden">
                            <span class="badge badge-success text-uppercase">{{$person->getRelationship() ? $person->getRelationship()->getTitle() : ''}}</span>
                        </div>
                    </div>
                    <div class="overflow-hidden">
                        <button onclick="jskhpopupdate1({{$person->id}})" type="button" class="btn btn-warning mr__5" title="Thêm Ngày / Tháng Ghi Nhớ">
                            <i class="fa fa-plus"></i>
                        </button>
                        <button onclick="jskhpopupperson2({{$person->id}})" type="button" class="btn btn-info mr__5" title="Sửa Thông Tin">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button onclick="jskhpopuppersondel({{$person->id}})" type="button" class="btn btn-danger mr__5" title="Xóa">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="mt__10 clearfix">
                    <div class="overflow-hidden">
                        <div class="text-bold fs-16 text-uppercase">{{$person->getTitle()}}</div>
                        <div>
                            @if(!empty($person->phone))
                                <span>{{$person->phone}}</span> -
                            @endif
                            <span>{{$person->getFullAddress()}}</span>
                        </div>
                    </div>
                </div>
                <div class="mt__10 clearfix">
                    <?php echo nl2br($person->note);?>
                </div>
            </td>
        </tr>
    @endif

    @if(count($dates))
        <?php
        $stt = 0;
        foreach($dates as $date):
        if (isset($month) && !empty($month) && (int)$month) {
            if ($month != $date->month) {
                continue;
            }
        }

        $stt++;

        $moTa = 'Ngày ' . $date->day . ' / tháng ' . $date->month . ' = ' . $date->getTitle();
        ?>
        @if($stt == 1)
            @if(!$isMobile)
                <tr>
                    <td style="width: 15%;"></td>
                    <td style="width: 5%;" class="text-uppercase fs-11 text-bold">stt</td>
                    <td style="width: 50%;" class="text-uppercase fs-11 text-bold">sự kiện</td>
                    <td style="width: 10%;" class="text-uppercase fs-11 text-bold">kinh phí dự kiến</td>
                    <td style="width: 20%;" class="text-uppercase fs-11 text-bold">ghi chú</td>
                </tr>
            @else
                <tr>
                    <td class="text-uppercase fs-11 text-bold">stt</td>
                    <td class="text-uppercase fs-11 text-bold">sự kiện</td>
                </tr>
            @endif
        @endif

        @if(!$isMobile)
            <tr class="info_date" id="date_{{$date->id}}"
                data-title="{{$date->title}}"
                data-day="{{$date->day}}"
                data-month="{{$date->month}}"
                data-note="{{$date->note}}"
                data-budget="{{$date->budget ? $date->budget : ''}}"
            >
                <td></td>
                <td class="text-center">{{$stt}}</td>
                <td>
                    <div class="float-right ml__5">
                        <button onclick="gotoPage('{{url('tim-kiem?max=' . $date->budget)}}')" type="button" class="btn btn-primary mr__5" title="Tìm Sản Phẩm Phù Hợp Kinh Phí">
                            <i class="fa fa-search"></i>
                        </button>
                        <button onclick="jskhpopupdate2({{$date->id}})" type="button" class="btn btn-info mr__5" title="Sửa Thông Tin">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button onclick="jskhpopupdatedel({{$date->id}})" type="button" class="btn btn-danger mr__5" title="Xóa">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="overflow-hidden">{{$moTa}}</div>
                </td>
                <td class="text-right">
                    <div class="number_format">{{$date->budget ? $date->budget : ''}}</div>
                </td>
                <td>
                    <?php echo nl2br($date->note);?>
                </td>
            </tr>
        @else
            <tr>
                <td class="text-center">{{$stt}}</td>
                <td>
                    <div class="clearfix number_format text-right text-bold">{{$date->budget ? $date->budget : ''}}</div>
                    <div class="clearfix mt__10">{{$moTa}}</div>
                    <div class="clearfix mt__10"><?php echo nl2br($date->note);?></div>
                    <div class="clearfix mt__10">
                        <button onclick="gotoPage('{{url('tim-kiem?max=' . $date->budget)}}')" type="button" class="btn btn-primary mr__5" title="Tìm Sản Phẩm Phù Hợp Kinh Phí">
                            <i class="fa fa-search"></i>
                        </button>
                        <button onclick="jskhpopupdate2({{$date->id}})" type="button" class="btn btn-info mr__5" title="Sửa Thông Tin">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button onclick="jskhpopupdatedel({{$date->id}})" type="button" class="btn btn-danger mr__5" title="Xóa">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @endif
        <?php endforeach;?>
    @endif
    </tbody>
</table>
