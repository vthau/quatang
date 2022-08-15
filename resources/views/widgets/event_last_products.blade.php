<?php
$apiFE = new \App\Api\FE();
$events = $apiFE->getLastEvents();
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();
?>

@if (count($events))

    <div class="container block-the-blog-opt7 margin-tb-60 ">
        <div class="block-title block-title_showhome">
            <h2 class="title title_tintuc">tin tức - sự kiện</h2>
        </div>
        <div class="block-content" id="block-last-events">
            <?php
            foreach ($events as $event):
            ?>
            <div class="last-event-item col-md-3">
                <div class="last-event-wrapper">
                    <div class="last-event-photo">
                        <a href="{{$event->getHref(true)}}" title="{{$event->getTitle()}}">
                            <div class="last-event-img" style="background-image:url('{{$event->getAvatar('normal')}}')"></div>
                        </a>
                    </div>
                    <div class="last-event-info">
                        <div class="last-event-title">
                            <a href="{{$event->getHref(true)}}" title="{{$event->getTitle()}}">
                                {{$event->getShortTitle(50)}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            endforeach;
            ?>
        </div>
    </div>

@endif
