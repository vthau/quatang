<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile() ? 1 : 0;

$maxSize = $apiCore->getMaxSize();
$maxSizeText = $apiCore->getMaxSizeText();

$pageTitle = (isset($page_title)) ? $page_title : $apiCore->getSetting('site_title');
$keywords = $apiCore->getSetting('site_seo');
$description = (isset($description)) ? $description : $apiCore->getSetting('site_short_description');

?>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="generator" content="Geckoso">
<meta name="author" content="Geckoso">
<meta name="keyword" content="{{$keywords}}">
<meta name="description" content="{{$description}}">
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

<meta name="description" content="{{$description}}">
<link rel="canonical" href="{{url('')}}">
<meta property="og:title" content="{{$pageTitle}}">
<meta property="og:url" content="{{url('')}}">
<meta property="og:site_name" content="website">
<meta property="og:type" content="website">
<meta name="keywords" content="{{$keywords}}">
<meta property="og:description" content="{{$description}}">

<link href="{{url('public/themes/be/css/coreui/vendors/pace-progress/css/pace.min.css')}}" rel="stylesheet">
{{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">--}}
<link href="{{url('public/libraries/font-awesome/css/all.css')}}" rel="stylesheet">

<link href="{{url('public/themes/fe/css/main/main.css')}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/main/bootstrap.min.css')}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/main/style.css')}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/main/jquery-ui.css')}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/main/AllinOne.css')}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/main/Style1.css')}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/main/cssphone.min.css')}}" rel="stylesheet">

<link href="{{url('public/libraries/uni-carousel/jquery.uni-carousel.css')}}" rel="stylesheet">

<link href="{{url('public/libraries/select2/select2.min.css')}}" rel="stylesheet">

<link href="{{url('public/libraries/viewer/jquery.magnify.css')}}" rel="stylesheet">

<link href="{{url('public/themes/fe/css/custom.css')}}" rel="stylesheet">
<link href="{{url('public/themes/fe/css/custom_mobile.css')}}" rel="stylesheet">

{{--jquery--}}
<script src="{{url('public/themes/be/js/jquery.min.js')}}" type="text/javascript"></script>

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
        loadingIMG: '<div class="frm-loading js-loading"><img src="{{url('public/images/icons/ic_loading.png')}}"></div>',
        maxSize: '{{$maxSize}}',
        maxSizeText: '{{$maxSizeText}}',
        tempTK: '{{csrf_token()}}',
        importExcelOnly: "Vui lòng chỉ import excel file.",
        isMobile: '{{$isMobile}}',
        user: '{{$viewer && $viewer->id ? $viewer->id : 0}}',
    };
</script>

