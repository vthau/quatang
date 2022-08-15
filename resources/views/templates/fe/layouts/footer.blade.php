<?php
$apiMobile = new \App\Api\Mobile;
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$siteLogo = $apiCore->getSetting('site_logo');
$hotline = $apiCore->getSetting('site_hotline');
$tuvan = $apiCore->getSetting('site_tuvan');
$siteEmail = $apiCore->getSetting('site_email');
$sitePhone = $apiCore->getSetting('site_phone');
$siteTitle = $apiCore->getSetting('site_title');
$siteAddress = $apiCore->getSetting('site_address');
$siteAbout = $apiCore->getSetting('site_short_description');

$isMobile = $apiMobile->isMobile();
?>

<footer class="site-footer footer-opt-1 footer_custom">
    <div class="container">
        <div class="footer-column">
            <div class="row">
                <div class="col-md-4">
                    <div class="logo-footer">
                        <img src="{{$siteLogo}}" />
                    </div>
                    <?php echo nl2br($siteAbout);?>
                </div>
                <div class="col-md-4">
                    <div class="links">
                        <div class="title">về chúng tôi</div>
                        <div class="links-wrapper">
                            <ul>
                                <li>
                                    <a href="{{url('gioi-thieu')}}">Giới Thiệu</a>
                                </li>
                                <li>
                                    <a href="{{url('goc-tu-van')}}">Góc Tư Vấn</a>
                                </li>
                                <li>
                                    <a href="{{url('lien-he')}}">Liên Hệ</a>
                                </li>
                                <li>
                                    <a href="{{url('chinh-sach-thanh-vien')}}">Chính Sách Thành Viên</a>
                                </li>
                            </ul>
                            <ul>
                                <li>
                                    <a href="{{url('chinh-sach-giao-hang')}}">Chính Sách Giao Hàng</a>
                                </li>
                                <li>
                                    <a href="{{url('chinh-sach-doi-tra')}}">Chính Sách Đổi Trả</a>
                                </li>
                                <li>
                                    <a href="{{url('chinh-sach-thanh-toan')}}">Chính Sách Thanh Toán</a>
                                </li>
                                <li>
                                    <a href="{{url('chinh-sach-bao-mat')}}">Chính Sách Bảo Mật</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="links">
                        <div class="title">liên hệ</div>
                        <div class="links-wrapper">
                            <ul>
                                <li>
                                    <a href="{{url('')}}">{{$siteTitle}}</a>
                                </li>
                                <li>
                                    <a href="tel:{{$sitePhone}}">Điện Thoại: {{$sitePhone}}</a>
                                </li>
                            </ul>
                            <ul>
                                <li>
                                    <a href="mailto:{{url('')}}">{{$siteEmail}}</a>
                                </li>
                                <li>
                                    <a href="tel:{{$hotline}}">Hotline: {{$hotline}}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="links-info">
                            <div><a href="tel:{{$tuvan}}">Tổng Đài Tư Vấn: {{$tuvan}}</a></div>
                            <div>Địa Chỉ: {{$siteAddress}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
