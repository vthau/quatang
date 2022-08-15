<?php
$apiFE = new \App\Api\FE;
$except = (isset($item) && $item) ? $item->id : 0;
$rows = $apiFE->getOtherEvents($except);
?>

@if (count($rows))
    <div class="block-deals-of-opt2">
        <div class="block-title ">
            <h2 class="title">Các Sự Kiện Khác</h2>
        </div>
        <div class="block-content other-event-wrapper">
            @foreach ($rows as $row)
            <div class="col-md-12 other-event-item">
                <a href="{{$row->getHref()}}">
                    <div class="event-photo">
                        <div class="event-img" style="background-image:url('{{$row->getAvatar('profile')}}')"></div>
                    </div>
                    <div class="event-info">
                        <div class="event-title">{{$row->getShortTitle(50)}}</div>
                        <div class="event-description">
                            <?php echo $row->getShortBody(150);?>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
@endif
