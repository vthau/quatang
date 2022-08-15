<?php
$apiMobile = new \App\Api\Mobile;
$apiCore = new \App\Api\Core;
$apiFE = new \App\Api\FE;
$viewer = $apiCore->getViewer();

$siteTitle = $apiCore->getSetting('site_title');
$siteLogo = $apiCore->getSetting('site_logo');
$hotline = $apiCore->getSetting('site_hotline');
$tuvan = $apiCore->getSetting('site_tuvan');

$brands = $apiCore->getBrands();
$cates = $apiCore->getCates();

$isMobile = $apiMobile->isMobile();

$countLoved = $apiFE->getSPLovedCount();
$countCart = $apiFE->getSPCartCount();
?>

<header class="site-header header-opt-5">
    <!-- header-top -->
    <div class="header-top">
        <div class="container">
            <!-- nav-left -->
            <ul class="nav-left hidden-xs">
                <li>
                    <span>
                        <a href="tel:{{$hotline}}" title="{{$hotline}}">
                            <span>Hotline:</span>
                            {{$hotline}}
                        </a>
                    </span>
                </li>
                <li>
                    <span>
                        <a href="tel:{{$tuvan}}" title="{{$tuvan}}">
                            <span>Tổng đài tư vấn:</span>
                            {{$tuvan}}
                        </a>
                    </span>
                </li>
            </ul>
            <!-- nav-right -->
            <ul class="nav-right hidden-xs">
                <li class="dropdown setting">
                    <a role="button" href="#" class="dropdown-toggle ">
                        @if ($viewer)
                            <span>Chào {{$viewer->getTitle()}}</span>
                        @else
                            <span>Tài khoản</span>
                        @endif
                        <i aria-hidden="true" class="fa fa-angle-down"></i>
                    </a>
                    <div class="dropdown-menu  @if (!$viewer) width_200 @endif">
                        <ul class="account account_header">
                            @if ($viewer)
                            <li>
                                <a href="{{url('/tai-khoan')}}" title="Tài Khoản">
                                    <i class="fas fa-user"></i>
                                    Tài Khoản
                                </a>
                            </li>
                            <li>
                                <a href="{{url('/dang-xuat')}}" title="Đăng Xuất">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Đăng Xuất
                                </a>
                            </li>
                            @else
                            <li>
                                <a href="{{url('/dang-nhap')}}" title="Đăng Nhập">
                                    <i class="fas fa-sign-in-alt"></i>
                                    Đăng Nhập / Đăng Ký
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
            </ul>

            <div class="hidden-lg hidden-md hidden-sm hidden-desktop" style="text-align: center;">
                <span style="font-size: 14px; font-family: Arial, serif; color: #f17445; line-height: 40px; font-weight: bold;">
                    <a href="tel:{{$hotline}}" title="{{$hotline}}" style="color: #f17445;">
                        Hotline: {{$hotline}}
                    </a>
                </span>
            </div>
        </div>
    </div>
    <!-- header-top -->

    <!-- header-content -->
    <div class="header-content">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-push-4 nav-left hidden-xs">
                    <!-- logo -->
                    <strong class="logo">
                        <a href="{{url('')}}">
                            <img src="{{$siteLogo}}" alt="{{$siteTitle}}" title="{{$siteTitle}}">
                        </a>
                    </strong>
                    <!-- logo -->
                </div>
                <div class="col-md-4 col-md-push-4 nav-left hidden-sm hidden-desktop hidden-lg hidden-md">
                    <!-- logo -->
                    <strong class="logo">
                        <a href="{{url('')}}">
                            <img src="{{$siteLogo}}" alt="{{$siteTitle}}" title="{{$siteTitle}}">
                        </a>
                    </strong>
                    <!-- logo -->
                </div>
                <div class="col-md-4 col-md-pull-4 nav-mind">
                    <!-- block search -->
                    <div class="block-search " id="keySearchClick">
                        <div class="block-title">
                            <span>Search</span>
                        </div>
                        <div class="block-content">
                            <div class="form-search">
                                <form action="{{url('tim-kiem')}}" method="GET" id="form_search_id" >
                                    <div class="box-group">
                                        <input type="text" name="keyword" id="keySearch"
                                               class="form-control ui-autocomplete-input"
                                               placeholder="Tìm kiếm..." autocomplete="off">
                                        <button class="btn btn-search" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <div class="box-group" style="display: block">
                                        <ul id="ui-top-search" tabindex="0"
                                            class="ui-menu ui-widget ui-widget-content ui-autocomplete ui-front"
                                            style="top: 40px; display: none;"></ul>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 nav-right hidden-xs">
                    <!--giỏ hàng-->
                    <div class="box_cart" title="Giỏ hàng" onclick="openPage('{{url('gio-hang')}}')" role="button">
                        <div class="icon-cart">
                            <i class="fa fa-shopping-cart shop_cart"></i>
                        </div>
                        <span class="gio_hang_new">Giỏ hàng</span>
                        <span class="counter qty" style="margin-left: 5px;">
                            <span class="cart-count quick_count" id="count-cart-sp">{{$countCart}}</span>
                        </span>
                    </div>
                    <!--giỏ hàng-->
                    <!--yêu thích-->
                    <div class="box_favorite" onclick="openPage('{{url('yeu-thich')}}')" title="Sản phẩm yêu thích">
                        <div class="icon-favorite">
                            <i class="fas fa-heart heart_new color_red"></i>
                        </div>
                        <span class="love_like"> Yêu thích</span>
                        <span id="countfavorite" class="quick_count">
                            <span id="count-love-sp">{{$countLoved}}</span>
                        </span>
                    </div>
                    <!--yêu thích-->
                </div>
            </div>
        </div>
    </div>

    <div id="sticky-wrapper" class="sticky-wrapper" style="height: 50px;">
        <div class="header-nav mid-header" >
            <div class="container">
                <div id="stick_nav-sticky-wrapper" class="sticky-wrapper" style="height: 50px;">
                    <div class="box-header-nav" id="stick_nav">
                        <span data-action="toggle-nav-cat" class="nav-toggle-menu nav-toggle-cat">
                            <span>Categories</span>
                            <i class="fas fa-bars"></i>
                        </span>

                        <span class="hotline_mobile hidden-md hidden-lg hidden-desktop">
                            <!--giỏ hàng-->
                            <div class="box_cart_mobile" title="Giỏ hàng" onclick="gotoPage('{{url('gio-hang')}}')" role="button" style="margin-top: 7px;">
                                <div class="icon-cart">
                                    <i class="fa fa-shopping-cart shop_cart"></i>
                                </div>
                                <span class="cart-count">0</span>
                            </div>
                            <!--giỏ hàng-->
                            <!--yêu thích-->
                            <div class="box_favorite" onclick="gotoPage('{{url('yeu-thich')}}')" title="Sản phẩm yêu thích" style="margin-top: 7px; float: left !important;">
                                <div class="icon-favorite">
                                    <i class="fa fa-heart heart_new color_red"></i>
                                </div>
                                <span class="love_like"></span>
                                <span id="countfavorite">0</span>
                            </div>
                            <!--yêu thích-->
                            <!--CSKH-->
                            <div class="box_favorite" title="CSKH" style="margin-top: 7px; float: left !important;">
                                <div class="icon-phone">
                                    <i class="fa fa-phone" style="color: #f17000;"></i>
                                </div>
                                <span class="love_like" style="font-weight: bold; color: #f17000; margin-left: 10px;">
                                    <span>CSKH:</span>
                                    <a href="tel:{{$tuvan}}" style="color: green;">{{$tuvan}}</a>
                                </span>
                            </div>
                            <!--CSKH-->
                        </span>

                        <div class="block-nav-categori hidden-xs">
                            <div class="block-title">
                                <span>Categories</span>
                                <i class="fas fa-bars"></i>
                            </div>
                            <div class="block-content" id="hidecatagory">
                                <ul class="ui-categori">
                                    <li class="parent">
                                        <a href="javascript:void(0)" title="thông tin">
                                            <span class="icon">
                                                <i class="fas fa-question"></i>
                                            </span>
                                            Thông Tin
                                        </a>
                                        <span class="toggle-submenu"></span>
                                        <div class="submenu hidden-phone hidden-xs" style="left: 200px; width: 180px !important;">
                                            <ul class="categori-list">
                                                <li class="col-sm-12">
                                                    <ul>
                                                        <li>
                                                            <a href="{{url('goc-tu-van')}}" title="Góc Tư Vấn">
                                                                Góc Tư Vấn
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{url('tin-tuc')}}" title="Tin Tức">
                                                                Tin Tức
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{url('khuyen-mai')}}" title="Khuyến Mãi">
                                                                Khuyến Mãi
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{url('hoi-dap')}}" title="Hỏi Đáp">
                                                                Hỏi Đáp
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="parent">
                                        <a href="javascript:void(0)" title="về chúng tôi">
                                            <span class="icon">
                                                <i class="fas fa-info"></i>
                                            </span>
                                            Về Chúng Tôi
                                        </a>
                                        <span class="toggle-submenu"></span>
                                        <div class="submenu hidden-phone hidden-xs" style="left: 200px; width: 230px !important;">
                                            <ul class="categori-list">
                                                <li class="col-sm-12">
                                                    <ul>
                                                        <li>
                                                            <a href="{{url('gioi-thieu')}}" title="Giới thiệu">
                                                                Giới Thiệu
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{url('chinh-sach-thanh-vien')}}" title="Chính Sách Thành Viên">
                                                                Chính Sách Thành Viên
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{url('chinh-sach-giao-hang')}}" title="Chính Sách Giao Hàng">
                                                                Chính Sách Giao Hàng
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{url('chinh-sach-doi-tra')}}" title="Chính Sách Đổi Trả">
                                                                Chính Sách Đổi Trả
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{url('chinh-sach-thanh-toan')}}" title="Chính Sách Thanh Toán">
                                                                Chính Sách Thanh Toán
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{url('chinh-sach-bao-mat')}}" title="Chính Sách Bảo Mật">
                                                                Chính Sách Bảo Mật
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="{{url('lien-he')}}" title="liên hệ">
                                            <span class="icon">
                                                <i class="fas fa-phone"></i>
                                            </span>
                                            Liên Hệ
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--click an menu catagories-->
                        <!-- menu -->
                        <div class="block-nav-menu" id="header-menu">
                            <div class="clearfix">
                                <span data-action="close-nav" class="close-nav" onclick="jsbindmobimenu()">
                                    <i class="fas fa-times"></i>
                                </span>
                            </div>
                            <ul class="ui-menu">
                                @if (count($brands))
                                <li class="parent parent-megamenu">
                                    <a href="{{url('/thuong-hieu')}}">Thương Hiệu</a>
                                    <span class="toggle-submenu" onclick="jsbindmobimenucate(this)">
                                        <i class="fa fa-arrow-down"></i>
                                    </span>

                                    <div class="megamenu drop-menu"
                                         style="right: auto; left: 0px;">
                                        <ul>
                                            @foreach ($brands as $brand)
                                            <li class="col-md-3">
                                                <ul class="list-submenu">
                                                    <li><a href="{{$brand->getHref()}}" title="{{$brand->getTitle()}}">{{$brand->getTitle()}}</a></li>
                                                </ul>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                                @endif

                                @if (count($cates))
                                    @foreach ($cates as $cate)
                                        @if ($cate->had2Childs())
                                        <li class="parent">
                                            <a href="{{$cate->getHref()}}" title="{{$cate->getTitle()}}">{{$cate->getTitle()}}</a>

                                            @if (count($cate->getSubCategories()))
                                            <span class="toggle-submenu" onclick="jsbindmobimenucate(this)">
                                                <i class="fa fa-arrow-down"></i>
                                            </span>

                                            <div class="megamenu drop-menu " style="right: auto; left: 0px;">
                                                <ul>
                                                    @foreach ($cate->getSubCategories() as $child)
                                                    <li class="col-md-3">
                                                        <ul class="list-submenu">
                                                            <li class="">
                                                                <strong class="title">
                                                                    <a href="{{$child->getHref()}}" title="{{$child->getTitle()}}">
                                                                        <span>{{$child->getTitle()}}</span>
                                                                    </a>
                                                                </strong>
                                                                @if (count($child->getSubCategories()))
                                                                <ul class="list-submenu">
                                                                    @foreach ($child->getSubCategories() as $leaf)
                                                                    <li>
                                                                        <a href="{{$leaf->getHref()}}" title="{{$leaf->getTitle()}}">
                                                                            {{$leaf->getTitle()}}
                                                                        </a>
                                                                    </li>
                                                                    @endforeach
                                                                </ul>
                                                                @endif
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                        </li>
                                        @else
                                        <li class="parent">
                                            <a href="{{$cate->getHref()}}" title="{{$cate->getTitle()}}">{{$cate->getTitle()}}</a>

                                            @if (count($cate->getSubCategories()))
                                            <span class="toggle-submenu" onclick="jsbindmobimenucate(this)">
                                                <i class="fa fa-arrow-down"></i>
                                            </span>

                                            <div class="megamenu drop-menu" style="right: auto; left: 0px;">
                                                <ul>
                                                    @foreach ($cate->getSubCategories() as $child)
                                                    <li class="col-md-3">
                                                        <ul class="list-submenu">
                                                            <li class="limenusmall">
                                                                <span style="color: #888;">
                                                                    <a href="{{$child->getHref()}}" title="{{$child->getTitle()}}">
                                                                        <span class="menu-small-img">{{$child->getTitle()}}</span>
                                                                    </a>
                                                                </span>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                        </li>
                                        @endif
                                    @endforeach
                                @endif

                            </ul>
                        </div>
                        <!-- menu -->
                        <!-- menu mobile -->
                        <span data-action="toggle-nav" class="nav-toggle-menu" onclick="jsbindmobimenu()">
                            <span></span>
                            <i aria-hidden="true" class="fa fa-bars"></i>
                        </span>

                        <div class="block-minicart dropdown ">
                            <a class="dropdown-toggle" href="{{url('gio-hang')}}" role="button" title="giỏ hàng">
                                <span class="cart-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </span>
                                <span class="counter qty">
                                    <span class="counter-number counter-number-custom">0</span>
                                </span>
                            </a>
                        </div>

                        <div class="block-search">
                            <div class="block-title">
                                <span>Search</span>
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="block-content" id="keySearchClick_down">
                                <div class="form-search frm-mini-search">
                                    <form action="{{url('tim-kiem')}}" method="GET">
                                        <div class="box-group">
                                            <input type="text" name="keyword" id="keySearchdown" class="form-control border_searchdown ui-autocomplete-input" placeholder="Tìm kiếm..." autocomplete="off">
                                            <button class="btn btn-search" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
