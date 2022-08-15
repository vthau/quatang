<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile() ? 1 : 0;

$maxSize = $apiCore->getMaxSize();
$maxSizeText = $apiCore->getMaxSizeText();

$pageTitle = (isset($page_title)) ? $page_title : $apiCore->getPageTitle();
$siteTitle = $apiCore->getSetting('site_title');
$siteLogo = $apiCore->getSetting('site_logo');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="{{$pageTitle}}">
    <meta name="author" content="Geckoso">
    <meta name="keyword" content="{{$pageTitle}}">
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

    <link href="{{url('public/themes/be/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('public/themes/be/css/auth/theme.css')}}" rel="stylesheet">
    <link href="{{url('public/themes/be/css/login.css')}}" rel="stylesheet">
    <link href="{{url('public/themes/be/css/general.css')}}" rel="stylesheet">

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
</head>

<body class="bg-e5">

<div class="container">
    <div class="login-wrap">
        <div class="login-content">
            <div class="login-logo">
                <img class="img-logo" src="{{$siteLogo}}" alt="{{$siteTitle}}">
                <span class="img-logo-txt">{{$siteTitle}}</span>
            </div>
            <div class="login-form" id="frm-login">
                <form method="POST" action="{{url('auth/do-login')}}" accept-charset="UTF-8" onsubmit="return validateLogin()" autocomplete="off" >
                    <input type="hidden" name="_token" value="{{csrf_token()}}" />
                    <div class="form-group">
                        <label>Email / Điện Thoại</label>
                        <input class="au-input au-input--full" autocomplete="off" type="text" name="email" id="frm-email" placeholder="email / điện thoại">
                    </div>
                    <div class="form-group" id="err-email">
                        <div class="alert alert-danger hidden">Hãy nhập email / điện thoại.</div>
                    </div>
                    <div class="form-group">
                        <label>Mật Khẩu</label>
                        <input class="au-input au-input--full" autocomplete="off" type="password" name="password" id="frm-password" placeholder="*******">
                    </div>
                    <div class="form-group" id="err-password">
                        <div class="alert alert-danger hidden">Hãy nhập mật khẩu.</div>
                    </div>
                    <div class="login-checkbox">
                        <label>
                            <input type="checkbox" name="remember">Ghi Nhớ Đăng Nhập
                        </label>
                        <label>
                            <a href="{{url('auth/forgot')}}">Quên Mật Khẩu?</a>
                        </label>
                    </div>

                    <button class="au-btn au-btn--block au-btn--blue m-b-20" type="submit">đăng nhập</button>

                    <div class="hidden">
                        <input type="submit" class="hidden" value="submit">
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="{{url('public/themes/be/js/auth.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        @if ($err_login)
        jQuery('#err-password .alert-danger').removeClass('hidden').text("Thông tin đăng nhập không chính xác.");
        @endif

    });
</script>

</body>
</html>
