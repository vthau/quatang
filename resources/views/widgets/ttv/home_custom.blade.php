<?php
$apiFE = new \App\Api\FE();
$apiCore = new \App\Api\Core();
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$kienThuc = $apiFE->getFeaturedEvent();
$events = $apiFE->getRandomEvents(3);
?>

<div id="shopify-section-159021986237513" class="shopify-section nt_section type_banner2 type_packery">
    <div class="nt_se_159021986237513 container home_custom">
        <div class="row al_center fl_center title_10 ">
            <div class="col-auto col-md">
                <h3 class="dib tc section-title fs__24 text-uppercase">bài viết & giới thiệu</h3>
            </div>
        </div>

        <div class="mt__30 nt_banner_holder row fl_center js_packery cat_space_30"
             data-packery='{ "itemSelector": ".cat_space_item","gutter": 0,"percentPosition": true,"originLeft": true }'>
            <div class="grid-sizer"></div>

            @if ($kienThuc)
            <div class="cat_space_item col-lg-3 col-md-3 col-12 pr_animated done" id="event_featured">
                <div class="banner_hzoom nt_promotion oh pr">
                    <div class="nt_bg_lz pr_lazy_img lazyload item__position "
                         data-bgset="{{$kienThuc->getAvatar()}}"
                         data-ratio="1.6777777777777778" data-sizes="auto" data-parent-fit="cover">

                    </div>
                    <a href="{{$kienThuc->getHref(true)}}" class="pa t__0 l__0 r__0 b__0"></a>
                    <div class="nt_promotion_html t__0 l__0 tl pe_none">
                        @if (!$isMobile)
                            <h3 class="mt__10 truncate">{{$kienThuc->getTitle()}}</h3>
                        @else
                            <div class="tc">
                                <h3>{{$kienThuc->getTitle()}}</h3>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            @if (count($events))
            <div class="cat_space_item col-lg-3 col-md-3 col-12 pr_animated done">
                @foreach($events as $ite)
                    <div class="mb__20">
                        <a href="{{$ite->getHref(true)}}">
                            @if (!$isMobile)
                                <div class="pr clearfix">
                                    <div class="float-left mr__10">
                                        <img width="100" height="75" class="ttkt_img" src="{{$ite->getAvatar('normal')}}" />
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="ttkt_tc_title">{{$ite->getShortTitle(90)}}</div>
                                    </div>
                                </div>
                            @else
                            <div class="row">
                                <div class="col-sm-3 mb__5">
                                    <img width="100" height="75" class="ttkt_img" src="{{$ite->getAvatar('normal')}}" />
                                </div>
                                <div class="col-sm-9 mb__5">
                                    <div class="tc">
                                        <h3>{{$ite->getTitle()}}</h3>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </a>
                    </div>
                @endforeach
            </div>
            @endif

            <div class="cat_space_item col-lg-6 col-md-6 col-12 pr_animated done vd_wrapper">
                @if (!$isMobile)
                    <div class="float-right ml__10">
                        @for($i=1;$i<=4;$i++)
                            <?php if(!empty($apiCore->getSetting('video_' . $i))):
                            $vd = $apiCore->getSetting('video_' . $i);
                            $arr = array_filter(explode("/", $vd));
                            ?>
                            <div class="media_thumbnail mb__10 vd_thumb_{{$i}} @if($i == 1) hidden @endif" onclick="jsbindhomevideo({{$i}})">
                                <img width="100" height="75" src="http://img.youtube.com/vi/{{$arr[count($arr)]}}/hqdefault.jpg" />
                            </div>
                            <?php endif;?>
                        @endfor
                    </div>
                    <div class="overflow-hidden text-center">
                        @for($i=1;$i<=4;$i++)
                            @if(!empty($apiCore->getSetting('video_' . $i)))
                            <div class="media_body vd_{{$i}} @if($i > 1) hidden @endif" data-src="{{$apiCore->getSetting('video_' . $i)}}">
                                <iframe width="400" height="250" src="{{$apiCore->getSetting('video_' . $i)}}" frameborder="0" allowfullscreen>
                                </iframe>
                            </div>
                            @endif
                        @endfor
                    </div>
                @else
                    <div class="overflow-hidden">
                        @for($i=1;$i<=4;$i++)
                            @if(!empty($apiCore->getSetting('video_' . $i)))
                                <div class="media_body vd_{{$i}}" data-src="{{$apiCore->getSetting('video_' . $i)}}">
                                    <iframe width="400" height="250" src="{{$apiCore->getSetting('video_' . $i)}}" frameborder="0" allowfullscreen>
                                    </iframe>
                                </div>
                            @endif
                        @endfor
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style data-shopify>
        .nt_se_159021986237513 {
            margin-top: 30px !important;
            margin-right: !important;
            margin-bottom: 30px !important;
            margin-left: !important;
        }

        #event_featured .nt_promotion_html {
            /*top: 50%;*/
            /*left: 11%;*/
            /*transform: translate(-11%, -50%);*/
        }

        #event_featured .nt_promotion_html, #event_featured .nt_promotion_html > *, #event_featured .nt_promotion_html .btn_icon_true:after {
            color: #222222
        }

        #event_featured .nt_promotion > a:after {
            background-color: #000;
            opacity: 0.0
        }

        #event_featured .nt_bg_lz {
            padding-top: 59.60264900662252%;
        }
    </style>
</div>

