<?php
$apiFE = new \App\Api\FE;
$except = (isset($item) && $item) ? $item->id : 0;
$rows = $apiFE->getOtherNews($except);
?>

@if (count($rows))
    <div class="block-deals-of-opt2">
        <div class="block-title ">
            <h2 class="title">Các Bài Viết Khác</h2>
        </div>
        <div class="block-content other-news-wrapper">
            @foreach ($rows as $row)
            <div class="col-md-12 other-news-item">
                <a href="{{$row->getHref()}}">
                    <div class="news-photo">
                        <div class="news-img" style="background-image:url('{{$row->getAvatar('profile')}}')"></div>
                    </div>
                    <div class="news-info">
                        <div class="news-title">{{$row->getShortTitle(50)}}</div>
                        <div class="news-description">
                            <?php echo $row->getShortBody(150);?>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
@endif
