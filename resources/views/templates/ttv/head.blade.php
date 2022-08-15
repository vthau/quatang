<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile() ? 1 : 0;

$maxSize = $apiCore->getMaxSize();
$maxSizeText = $apiCore->getMaxSizeText();

$jsVersion = '20220101';

$pageTitle = (isset($page_title)) ? $page_title : $apiCore->getSetting('site_title');
$siteTitle = $apiCore->getSetting('site_title');
$keywords = (isset($keywords)) ? $keywords : $apiCore->getSetting('site_seo');
$description = (isset($description)) ? $description : $apiCore->getSetting('site_short_description');
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
<meta name="theme-color" content="#223e80">
<meta name="format-detection" content="telephone=no">

<link rel="canonical" href="{{url('')}}">

<link rel="preload" as="style"
      href="{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/pre_theme.minb2aa.css?v={{$jsVersion}}">
<link rel="preload" as="style"
      href="{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/theme.scss9c56.css?v={{$jsVersion}}">

<title>{{$pageTitle}}</title>

<link rel="shortcut icon" href="{{url('public/images/logo/favicon.ico')}}" type="image/x-icon" />
<link rel="apple-touch-icon" href="{{url('public/images/logo/apple-touch-icon.png')}}" />
<link rel="apple-touch-icon" sizes="57x57" href="{{url('public/images/logo/apple-touch-icon-57x57.png')}}" />
<link rel="apple-touch-icon" sizes="72x72" href="{{url('public/images/logo/apple-touch-icon-72x72.png')}}" />
<link rel="apple-touch-icon" sizes="76x76" href="{{url('public/images/logo/apple-touch-icon-76x76.png')}}" />
<link rel="apple-touch-icon" sizes="114x114" href="{{url('public/images/logo/apple-touch-icon-114x114.png')}}" />
<link rel="apple-touch-icon" sizes="120x120" href="{{url('public/images/logo/apple-touch-icon-120x120.png')}}" />
<link rel="apple-touch-icon" sizes="144x144" href="{{url('public/images/logo/apple-touch-icon-144x144.png')}}" />
<link rel="apple-touch-icon" sizes="152x152" href="{{url('public/images/logo/apple-touch-icon-152x152.png')}}" />
<link rel="apple-touch-icon" sizes="180x180" href="{{url('public/images/logo/apple-touch-icon-180x180.png')}}" />

<meta name="description" content="{{$description}}" />
<meta name="robots" content="noodp,index,follow" />
<meta name="revisit-after" content="1 days" />
<meta http-equiv="content-language" content="vi" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="{{asset('public')}}/favicon.ico" rel="shortcut icon" type="image/x-icon" />

<link rel="shortcut icon" type="image/png" href="{{asset('public')}}/favicon.ico">

<!-- social-meta-tags.liquid -->
<meta name="keywords" content="{{$keywords}}"/>
<meta name="author" content="GECKOSO">
<meta property="og:site_name" content="{{$siteTitle}}">
<meta property="og:url" content="{{url('')}}">
<meta property="og:title" content="{{$siteTitle}}">
<meta property="og:type" content="website">
<meta property="og:description" content="{{$description}}">
<meta property="og:image" content="{{asset('public')}}/favicon.ico">
<meta property="og:image:secure_url" content="{{asset('public')}}/favicon.ico">

<link href="{{url('public/libraries/font-awesome/css/all.css')}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/main/main.css')}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/main/bootstrap.min.css')}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/main/style.css')}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/main/jquery-ui.css')}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/main/AllinOne.css')}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/main/Style1.css')}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/main/cssphone.min.css')}}" rel="stylesheet">

<link href="{{url('public/libraries/uni-carousel/jquery.uni-carousel.css')}}" rel="stylesheet">

<link href="{{url('public/libraries/viewer/jquery.magnify.css')}}" rel="stylesheet">

<link href="{{url('public/themes/fe/css/custom.css?v=' . $jsVersion)}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/custom_mobile.css?v=' . $jsVersion)}}" rel="stylesheet">

<script type="text/javascript" src="{{url('public/themes/be/js/jquery.min.js')}}"></script>

<script src="{{url('public/libraries/zoom/magiczoomplus.js')}}" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.22/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.22/datatables.min.js"></script>

<link rel="preload"
      href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&amp;display=swap"
      as="style" onload="this.onload=null;this.rel='stylesheet'">
<link
    href="{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/pre_theme.minb2aa.css?v={{$jsVersion}}"
    rel="stylesheet" type="text/css" media="all"/>
<link rel="preload" as="script"
      href="{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/lazysizes.minc3ed.js?v={{$jsVersion}}">
<link rel="preload" as="script"
      href="{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/nt_vendor.min455d.js?v={{$jsVersion}}">
<link rel="preload"
      href="{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/style.min9d6d.css?v={{$jsVersion}}"
      as="style" onload="this.onload=null;this.rel='stylesheet'">
<link
    href="{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/theme.scss9c56.css?v={{$jsVersion}}"
    rel="stylesheet" type="text/css" media="all"/>
<link id="sett_clt4" rel="preload"
      href="{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/sett_cl1c1e.css?v={{$jsVersion}}"
      as="style" onload="this.onload=null;this.rel='stylesheet'">
<link rel="preload"
      href="{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/line-awesome.min2fc9.css?v={{$jsVersion}}"
      as="style" onload="this.onload=null;this.rel='stylesheet'">
<script id="js_lzt4"
        src="{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/lazysizes.minc3ed.js?v={{$jsVersion}}"
        async="async"></script>

<script type="text/javascript">
    var t_name = '';
    var t_shop_currency = 'USD';
</script>

<script
    src="{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/nt_vendor.min455d.js?v={{$jsVersion}}"
    defer="defer" id="js_ntt4"
    data-theme='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/nt_theme.min.js?v={{$jsVersion}}'
    data-stt='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/nt_settings.js?v={{$jsVersion}}'
    data-cat='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/cat.min.js?v={{$jsVersion}}'
    data-sw='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/swatch.min.js?v={{$jsVersion}}'
    data-prjs='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/produc.min.js?v={{$jsVersion}}'
    data-mail='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/platform_mail.min.js?v={{$jsVersion}}'
    data-my='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/my.js?v={{$jsVersion}}'
    data-cusp='{{asset('public')}}/ttv/theme/s/javascripts/currencies.js?v={{$jsVersion}}'
    data-cur='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/nt_currencies.min.js?v={{$jsVersion}}'
    data-mdl='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/module.min.js?v={{$jsVersion}}'
    data-map='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/maplace.min.js?v={{$jsVersion}}'
    data-time='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/spacetime.min.js?v={{$jsVersion}}'
    data-ins='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/nt_instagram.min.js?v={{$jsVersion}}'
    data-user='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/interactable.min.js?v={{$jsVersion}}'

    data-font='iconKalles , fakalles , Pe-icon-7-stroke , Font Awesome 5 Free:n9'
    data-fm=''
    data-spcmn='{{asset('public')}}/ttv/theme/s/assets/themes_support/shopify_common-8ea6ac3faf357236a97f5de749df4da6e8436ca107bc3a4ee805cbf08bc47392.js'
    data-cust='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/customerclnt.min.js?v={{$jsVersion}}'
    data-cusjs='none'
    data-desadm='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/des_adm.js?v={{$jsVersion}}'
    data-otherryv='{{asset('public')}}/ttv/theme/s/files/1/0270/2098/4401/t/6/assets/reviewOther.js?v={{$jsVersion}}'
></script>

<script>var Shopify = Shopify || {};</script>

{{--custom--}}
<link rel="stylesheet" href="{{url('public/css/ttv/header.css?v=' . $jsVersion)}}">
<link rel="stylesheet" href="{{url('public/css/ttv/general.css?v=' . $jsVersion)}}">
<link rel="stylesheet" href="{{url('public/css/ttv/custom.css?v=' . $jsVersion)}}">

<script type="text/javascript">
    var gks = {
        baseURL: '{{url('')}}',
        loading: 'Đang xử lý...',
        loadingUPLOADPHOTO: '{{url('public/images/loading_img.jpg')}}',
        successADD: 'Đã thêm thành công!',
        successEDIT: 'Đã sửa thành công!',
        successDEL: 'Đã xóa thành công!',
        successUPDATE: 'Đã cập nhật thành công!',
        successCHANGE: 'Đã cập nhật thành công!',
        saveERR: "Không thể kết nối. Vui lòng thử lại sau.",
        loadingIMG: '<div class="js_loading"><img src="{{url('public/images/loading.gif')}}"></div>',
        maxSize: '{{$maxSize}}',
        maxSizeText: '{{$maxSizeText}}',
        tempTK: '{{csrf_token()}}',
        importExcelOnly: "Vui lòng chỉ import excel file.",
        isMobile: '{{$isMobile}}',
        user: '{{$viewer && $viewer->id ? $viewer->id : 0}}',
        timeOutFocus: 888,
    };
</script>


