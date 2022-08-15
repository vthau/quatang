<?php
$apiFE = new \App\Api\FE();
$news = $apiFE->getLastNews();
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();
?>

@if (count($news))

    <div class="container margin-tb-60">
        <div class="col-md-12 text-center headHome-th thuonghieu_tieude">
            <h2 class="title title_tintuc">bài viết</h2>
        </div>
        <div class="col-md-12 thuonghieu_custom" id="block-last-news">
            <?php
            foreach ($news as $new):
            ?>
            <div class="last-news-item col-md-3">
                <div class="last-news-wrapper">
                    <div class="last-news-photo">
                        <a href="{{$new->getHref(true)}}" title="{{$new->getTitle()}}">
                            <div class="last-news-img" style="background-image:url('{{$new->getAvatar('normal')}}')"></div>
                        </a>
                    </div>
                    <div class="last-news-info">
                        <div class="last-news-title">
                            <a href="{{$new->getHref(true)}}" title="{{$new->getTitle()}}">
                                {{$new->getShortTitle(50)}}
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
