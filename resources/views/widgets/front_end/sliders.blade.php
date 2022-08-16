<?php
$apiFE = new \App\Api\FE();
$apiCore = new \App\Api\Core();
$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

// $bg1 = $isMobile ? $apiCore->getSetting('mobi_banner_bg_1') : $apiCore->getSetting('banner_bg_1');
// $bg2 = $isMobile ? $apiCore->getSetting('mobi_banner_bg_2') : $apiCore->getSetting('banner_bg_2');
// $bg3 = $isMobile ? $apiCore->getSetting('mobi_banner_bg_3') : $apiCore->getSetting('banner_bg_3');
// $bg4 = $isMobile ? $apiCore->getSetting('mobi_banner_bg_4') : $apiCore->getSetting('banner_bg_4');

// $title1 = $apiCore->getSetting('banner_title_1');
// $title2 = $apiCore->getSetting('banner_title_2');
// $title3 = $apiCore->getSetting('banner_title_3');
// $title4 = $apiCore->getSetting('banner_title_4');

// $link1 = $apiCore->getSetting('banner_link_1');
// $link2 = $apiCore->getSetting('banner_link_2');
// $link3 = $apiCore->getSetting('banner_link_3');
// $link4 = $apiCore->getSetting('banner_link_4');

$displayBanner = $apiCore->getSetting("display_banner");

?>

 

<!-- <style data-shopify>
    #shopify-section-1581505806578 {
        background-color: #efefef !important;
    }
</style>
<style data-shopify>
    .nt_se_1581505806578 .img_slider_block {
        padding-top: 400px
    }

    @media (min-width: 768px) {
        .nt_se_1581505806578 .img_slider_block {
            padding-top: 550px
        }
    }

    @media (min-width: 1025px) {
        .nt_se_1581505806578 .img_slider_block {
            padding-top: 620px
        }
    }

    #nt_1585640154849 .nt_img_txt > a:after {
        background-color: #000;
        opacity: 0.0
    }

    #nt_1585640154849 .pa_txts {
        top: 50%;
        left: 25%;
        transform: translate(-25%, -50%);
    }

    @media (min-width: 768px) {
        #nt_1585640154849 .pa_txts {
            top: 50%;
            left: 0%;
            transform: translate(-0%, -50%);
        }
    }

    #b_1585640505189 {
        font-size: 15px;
        font-weight: 500;
        color: #e4573d
    }

    @media (min-width: 768px) {
        #b_1585640505189 {
            font-size: 22px
        }
    }

    #b_1585640508369 {
        height: 7px
    }

    @media (min-width: 768px) {
        #b_1585640508369 {
            height: 15px
        }
    }

    #b_1585640512264 {
        font-size: 29px;
        font-weight: 600;
        color: #222222
    }

    @media (min-width: 768px) {
        #b_1585640512264 {
            font-size: 45px
        }
    }

    #b_1585640518653 {
        height: 10px
    }

    @media (min-width: 768px) {
        #b_1585640518653 {
            height: 20px
        }
    }

    #b_1590565477331 {
        font-size: 14px;
        font-weight: 400;
        color: #696969
    }

    @media (min-width: 768px) {
        #b_1590565477331 {
            font-size: 16px
        }
    }

    #b_1590375921354 {
        height: 10px
    }

    @media (min-width: 768px) {
        #b_1590375921354 {
            height: 20px
        }
    }

    #b_1585640453760 {
        font-weight: 600;
        min-height: 45px;
        color: #ffffff;
        background-color: #4e97fd;
        border-color: #4e97fd
    }

    #b_1585640453760.btn_icon_true:after {
        color: #ffffff
    }

    #nt_1585640159361 .nt_img_txt > a:after {
        background-color: #000;
        opacity: 0.0
    }

    #nt_1585640159361 .pa_txts {
        top: 50%;
        left: 25%;
        transform: translate(-25%, -50%);
    }

    @media (min-width: 768px) {
        #nt_1585640159361 .pa_txts {
            top: 50%;
            left: 0%;
            transform: translate(-0%, -50%);
        }
    }

    #b_1585640474337 {
        font-size: 16px;
        font-weight: 600;
        color: #e4573d
    }

    @media (min-width: 768px) {
        #b_1585640474337 {
            font-size: 22px
        }
    }

    #b_1585640490350 {
        height: 7px
    }

    @media (min-width: 768px) {
        #b_1585640490350 {
            height: 15px
        }
    }

    #b_1585640480446 {
        font-size: 29px;
        font-weight: 600;
        color: #222222
    }

    @media (min-width: 768px) {
        #b_1585640480446 {
            font-size: 45px
        }
    }

    #b_1585640492985 {
        height: 10px
    }

    @media (min-width: 768px) {
        #b_1585640492985 {
            height: 20px
        }
    }

    #b_1590376518903 {
        font-size: 14px;
        font-weight: 400;
        color: #696969
    }

    @media (min-width: 768px) {
        #b_1590376518903 {
            font-size: 16px
        }
    }

    #b_1590376535412 {
        height: 10px
    }

    @media (min-width: 768px) {
        #b_1590376535412 {
            height: 20px
        }
    }

    #b_1585640485544 {
        font-weight: 600;
        min-height: 45px;
        color: #ffffff;
        background-color: #4e97fd;
        border-color: #4e97fd
    }

    #b_1585640485544.btn_icon_true:after {
        color: #ffffff
    }

    #nt_1585640162346 .nt_img_txt > a:after {
        background-color: #000;
        opacity: 0.0
    }

    #nt_1585640162346 .pa_txts {
        top: 50%;
        left: 25%;
        transform: translate(-25%, -50%);
    }

    @media (min-width: 768px) {
        #nt_1585640162346 .pa_txts {
            top: 50%;
            left: 0%;
            transform: translate(-0%, -50%);
        }
    }

    #b_1585640443284 {
        font-size: 14px;
        font-weight: 400;
        color: #222222
    }

    @media (min-width: 768px) {
        #b_1585640443284 {
            font-size: 22px
        }
    }

    #b_1585640460694 {
        height: 10px
    }

    @media (min-width: 768px) {
        #b_1585640460694 {
            height: 15px
        }
    }

    #b_1585640447349 {
        font-size: 28px;
        font-weight: 600;
        color: #222222
    }

    @media (min-width: 768px) {
        #b_1585640447349 {
            font-size: 45px
        }
    }

    #b_1590563062221 {
        height: 4px
    }

    @media (min-width: 768px) {
        #b_1590563062221 {
            height: 10px
        }
    }

    #b_1590563045753 {
        font-size: 28px;
        font-weight: 600;
        color: #222222
    }

    @media (min-width: 768px) {
        #b_1590563045753 {
            font-size: 45px
        }
    }

    #b_1585640466340 {
        height: 10px
    }

    @media (min-width: 768px) {
        #b_1585640466340 {
            height: 20px
        }
    }

    #b_1590375776939 {
        font-size: 14px;
        font-weight: 400;
        color: #696969
    }

    @media (min-width: 768px) {
        #b_1590375776939 {
            font-size: 16px
        }
    }

    #b_1590564996993 {
        height: 10px
    }

    @media (min-width: 768px) {
        #b_1590564996993 {
            height: 20px
        }
    }

    #b_1585640524232 {
        font-weight: 600;
        min-height: 45px;
        color: #ffffff;
        background-color: #4e97fd;
        border-color: #4e97fd
    }

    #b_1585640524232.btn_icon_true:after {
        color: #ffffff
    } -->

    
<!-- </style> -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


<style>
.carousel-inner {
    height: auto;
}

.fa.fa-chevron-right, .fa.fa-chevron-left {
  position: absolute;
    top: 50%;
    z-index: 5;
    display: inline-block;
    margin-top: -10px;
}

.banner-title {
   position: absolute;
    top: 50%;
    font-size: 40px;
    margin-left: 60px;
}
</style>

@if($displayBanner == 1 && count($banners))
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
          @php $i = 0; @endphp
          @foreach ($banners as $banner)
          <li data-target="#myCarousel" data-slide-to="{{$i}}" class="<?php if($i == 0) {echo 'active'; $i++; }?>"></li>
          @endforeach
    </ol>

    <div class="carousel-inner">
        @php $k = 0; @endphp
        @foreach ($banners as $banner)
          <a href="{{$banner->href}}" class="item <?php if($k == 0) {echo 'active'; $k++; }?>">
            <img src="{{url('public/') . ($isMobile ? $banner->img_mobi : $banner->img )}}" alt="Los Angeles" style="width:100%; height: auto">
            <!-- <div class="carousel-caption"> -->
    <h3 class="banner-title">There are 3 red tomatoes</h3>
  <!-- </div> -->
</a>
        @endforeach
    </div>

    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
    <i class="fa fa-chevron-left" aria-hidden="true"></i>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
    <i class="fa fa-chevron-right" aria-hidden="true"></i>
      <span class="sr-only">Next</span>
    </a>

  </div>



    @endif
<script type="text/javascript">
    jQuery(document).ready(function () {
        setInterval(function () {
            changeBG();
        }, 5888);
    });
</script>
