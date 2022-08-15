<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile() ? 1 : 0;

$pageTitle = (isset($page_title)) ? $page_title : $apiCore->getSetting('site_title');
$siteTitle = $apiCore->getSetting('site_title');
$keywords = $apiCore->getSetting('site_seo');
$description = (isset($description)) ? $description : $apiCore->getSetting('site_short_description');

$maxSize = $apiCore->getMaxSize();
$maxSizeText = $apiCore->getMaxSizeText();

$siteLogo = $apiCore->getSetting('site_logo');
$hotline = $apiCore->getSetting('site_hotline');
$tuvan = $apiCore->getSetting('site_tuvan');
$siteEmail = $apiCore->getSetting('site_email');
$sitePhone = $apiCore->getSetting('site_phone');
$siteAddress = $apiCore->getSetting('site_address');
$siteAbout = $apiCore->getSetting('site_short_description');

$brands = $apiCore->getBrands(['active' => 1]);
//$cates = $apiCore->getCates();

$isMobile = $apiMobile->isMobile();

$apiFE = new \App\Api\FE;
$countLoved = $apiFE->getSPLovedCount();
$countCart = $apiFE->getSPCartCount();

$cates = $apiFE->getProductCategories();

$loginPage = (isset($login_page) && $login_page) ? true : false;

$params = request()->all();

?>

<header id="ntheader" class="ntheader header_9 h_icon_la">
    <h1 class="h2 site-header__logo dn">{{$siteTitle}}</h1>

    <div class="ntheader_wrapper pr z_200">
        <div id="shopify-section-header_top" class="shopify-section">
            <div class="h__top bgbl pt__10 pb__10 fs__12 flex fl_center al_center">
                <div class="container">
                    <div class="row al_center">
                        <div class="col-lg-4 col-12 tc tl_lg col-md-12 dn_false_1024">
                            <div class="header-text">
                                <i class="pegk pe-7s-call"></i> <a href="tel:{{$hotline}}">{{$hotline}}</a>
                                <i class="pegk pe-7s-mail ml__15"></i> <a class="cg" href="mailto:{{$siteEmail}}">{{$siteEmail}}</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-12 tc col-md-12 dn_true_1024">

                        </div>
                        <div class="col-lg-4 col-12 tc col-md-12 tr_lg dn_true_1024">
                            <div class="nt_currency pr cg currencies wsn dib  cur_stt_0" data-nonet4="#"
                                 data-pos="right" data-remove="true" data-class="popup_filter currencies"
                                 data-bg="hide_btn">
                                <span class="current dib lazyload">
                                    @if ($viewer)
                                        <span class="text-capitalize">Chào {{$viewer->getTitle()}}</span>
                                    @else
                                        <span class="text-capitalize @if ($loginPage) hidden @endif">Tài khoản</span>
                                    @endif
                                </span>
                                @if (!$loginPage)
                                <i class="facl facl-angle-down ml__5"></i>
                                @endif

                                <ul class="pa pe_none ts__03 bgbl ul_none tl op__0 z_100 r__0 pt__15 pb__15 pr__15 pl__15">
                                    @if ($viewer)
                                        <li>
                                            <a href="{{url('/tai-khoan')}}" title="Tài Khoản"  class="text-capitalize fs-14">
                                                <i class="fas fa-user width_20px"></i>
                                                Tài Khoản
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{url('/dang-xuat')}}" title="Đăng Xuất"  class="text-capitalize fs-14">
                                                <i class="fas fa-sign-out-alt width_20px"></i>
                                                Đăng Xuất
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="cb chp db push_side text-capitalize fs-14" href="javascript:void(0)" title="Đăng Nhập / Đăng Kí" data-id="#nt_login_canvas">
                                                <i class="fas fa-sign-in-alt width_20px"></i>
                                                Đăng Nhập
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="shopify-section-header_9" class="shopify-section sp_header_mid">
            <div class="header__mid header__mid9">
                <div class="container">
                    <div class="row al_center css_h_se">
                        <div class="col-md-4 col-3 dn_lg">
                            <a href="javascript:void(0)" id="mobi_menu" data-id='#nt_menu_canvas' class="push_side push-menu-btn  lh__1 flex al_center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="16" viewBox="0 0 30 16">
                                    <rect width="30" height="1.5"></rect>
                                    <rect y="7" width="20" height="1.5"></rect>
                                    <rect y="14" width="30" height="1.5"></rect>
                                </svg>
                            </a></div>
                        <div class="col-lg-auto col-md-4 col-6 tc tl_lg">
                            <div class=" branding ts__05 lh__1">
                                <a class="dib" href="{{url('')}}">
                                    <img class="logo_normal dn db_lg"
                                         src="{{$siteLogo}}"
                                         alt="{{$siteTitle}}" width="80" height="80">
                                    <img class="logo_sticky dn"
                                         src="{{$siteLogo}}"
                                         alt="{{$siteTitle}}" width="80" height="80">
                                    <img class="logo_mobile dn_lg"
                                         src="{{$siteLogo}}"
                                         alt="{{$siteTitle}}" width="55" height="55" style="padding: 0" />
                                </a>
                            </div>
                        </div>
                        <div class="col dn db_lg">
                            <nav class="nt_navigation tl hover_side_up nav_arrow_false">
                                <ul id="nt_menu_id" class="nt_menu in_flex wrap al_center">
                                    <li class="type_mega menu_wid_cus menu-item menu_center pos_center">
                                        <a class="lh__1 flex al_center pr text-uppercase text-bold" href="{{url('gioi-thieu')}}">về chúng tôi</a>
                                    </li>
                                    <li class="type_dropdown menu_wid_ menu-item has-children menu_has_offsets menu_right pos_right">
                                        <a class="lh__1 flex al_center pr text-uppercase text-bold" href="{{url('thuong-hieu')}}">thương hiệu</a>
                                        @if(count($brands))
                                            <div class="sub-menu calc_pos" style="">
                                                <div class="lazy_menu lazyloaded">
                                                    @foreach($brands as $brand)
                                                        <div class="menu-item"><a href="{{$brand->getHref()}}">{{$brand->getTitle()}}</a></div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </li>
                                    <li class="type_dropdown menu_wid_ menu-item has-children menu_has_offsets menu_right pos_right">
                                        <a class="lh__1 flex al_center pr text-uppercase text-bold" href="{{url('san-pham')}}">sản phẩm</a>
                                        @if(count($cates))
                                        <div id="menu_san_pham" class="cus sub-menu calc_pos" style="left: -153.078px;">
                                            <div class="container" style="width:1050px">
                                                <div class="row lazy_menu lazy_menu_mega lazyloaded" style="position:relative; min-height: 340px; overflow: auto">
                                                    <?php
                                                    $count = 0;
                                                    foreach($cates as $cate):
                                                    $count++;

                                                    $left = 0;
                                                    if ($count == 2) {
                                                        $left = 25;
                                                    } elseif ($count == 3) {
                                                        $left = 50;
                                                    } elseif ($count == 4) {
                                                        $left = 75;
                                                    }

                                                    $subs = $apiFE->getSubCategories($cate->id);
                                                    ?>
                                                    <div class="type_mn_link menu-item sub-column-item col-3">
                                                        <a href="{{$cate->getHref()}}"><b>{{$cate->getTitle()}}</b></a>
                                                        @if (count($subs))
                                                        <ul class="sub-column not_tt_mn">
                                                            @foreach($subs as $sub)
                                                            <li class="menu-item">
                                                                <a href="{{$sub->getHref()}}">{{$sub->getTitle()}}</a>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                        @endif
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </li>
                                    <li class="type_mega menu_wid_cus menu-item menu_center pos_center">
                                        <a class="lh__1 flex al_center pr text-uppercase text-bold" href="{{url('goc-tu-van')}}">góc tư vấn</a>
                                    </li>
                                    <li class="type_mega menu_wid_cus menu-item menu_center pos_center">
                                        <a class="lh__1 flex al_center pr text-uppercase text-bold" href="{{url('tin-tuc')}}">tin tức</a>
                                    </li>
                                    <li class="type_mega menu_wid_cus menu-item menu_center pos_center">
                                        <a class="lh__1 flex al_center pr text-uppercase text-bold" href="{{url('lien-he')}}">liên hệ</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-lg-auto col-md-4 col-3 tr">
                            <div class="nt_action in_flex al_center cart_des_1">
                                <div class="frm_search_ac pr widget dn db_lg">
                                    <form action="{{url('tim-kiem')}}" method="get"
                                          class="search_header mini_search_frm pr flex al_center"
                                          role="search">
                                        <div class="frm_search_input pr oh">
                                            <input class="search_header__input" autocomplete="off" value="{{count($params) && isset($params['keyword']) ? $params['keyword'] : ''}}"
                                                   type="text" name="keyword" placeholder="Tìm kiếm...">
                                            <button class="search_header__submit pe_none"
                                                    type="submit"><i class="iccl iccl-search"></i></button>
                                        </div>
                                        <div class="ld_bar_search"></div>
                                    </form>
                                    <div class="search_h_break pa w__100"></div>
                                    <div
                                        class="search_header__prs fwsb cd pa dn product_list_widget"></div>
                                </div>

                                <a class="icon_search push_side cb chp dn_lg mobi_sm" data-id="#nt_search_canvas"
                                   href="javascript:void(0)"><i class="las la-search"></i></a>

                                <div class="my-account ts__05 pr dn db_md">
                                    <a class="cb chp db" href="{{url('tai-khoan')}}" ><i class="las la-user"></i></a>
                                </div>

                                <a class="icon_like cb chp pr dn db_md" href="{{url('yeu-thich')}}" >
                                    <i class="lar la-heart pr">
                                        <span class="op__0 ts_op pa tcount bgb br__50 cw tc" id="count-love-sp">{{$countLoved}}</span>
                                    </i>
                                </a>

                                <div class="icon_cart pr mobi_sm">
                                    <a class="push_side pr cb chp db" href="{{url('gio-hang')}}" >
                                        <i class="las la-shopping-cart pr">
                                            <span class="op__0 ts_op pa tcount bgb br__50 cw tc" id="count-cart-sp">{{$countCart}}</span>
                                        </i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
