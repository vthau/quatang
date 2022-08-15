<?php
$apiCore = new \App\Api\Core;

$siteTitle = $apiCore->getSetting('site_title');
$siteLogo = $apiCore->getSetting('site_logo');

$viewer = $apiCore->getViewer();

$newNotifications = $viewer->getNewNotifications();
if (count($newNotifications)) {
    $notifications = $newNotifications;
} else {
    $notifications = $viewer->getNotifications(5);
}
?>

<header class="c-header c-header-light c-header-fixed c-header-with-subheader">
    <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar"
            data-class="c-sidebar-show">
        <span class="c-header-toggler-icon"></span>
    </button>

    <a class="c-header-brand d-sm-none" href="{{url('')}}">
        <img class="c-header-brand-full c-d-dark-none" src="{{$siteLogo}}"
             width="40" height="40" alt="{{$siteTitle}}" />
        <img class="c-header-brand-minimized c-d-dark-none" src="{{$siteLogo}}"
             width="40" height="40" alt="{{$siteTitle}}" />
        <img class="c-header-brand-full c-d-light-none" src="{{$siteLogo}}"
             width="40" height="40" alt="{{$siteTitle}}" />
        <img class="c-header-brand-minimized c-d-light-none" src="{{$siteLogo}}"
             width="40" height="40" alt="{{$siteTitle}}" />
    </a>

{{--    <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar"--}}
{{--            data-class="c-sidebar-lg-show" responsive="true">--}}
{{--        <span class="c-header-toggler-icon"></span>--}}
{{--    </button>--}}

    <ul class="c-header-nav mfs-auto">
        <li class="c-header-nav-item px-3">

        </li>
    </ul>

    <ul class="c-header-nav">
        <li class="c-header-nav-item dropdown d-md-down-none mx-2">
            <a class="c-header-nav-link" onclick="refreshTimeNotify(this);" href="javascript:void(0)">
                <img class="c-header-ic" src="{{url('public/images/icons/ic_bell_dark.png')}}" />
                @if (count($newNotifications))
                <span class="badge badge-pill badge-danger" id="my-notification">{{count($newNotifications)}}</span>
                @endif
            </a>

            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg pt-0 w-250" id="ele-panel-notification">
                @if (count($newNotifications))
                    <div class="dropdown-header">
                        <strong>Bạn có {{count($newNotifications)}} thông báo mới</strong>
                    </div>
                @else
                    <div class="dropdown-header">
                        <strong>5 thông báo gần nhất</strong>
                    </div>
                @endif

                @if (count($notifications))
                    <div class="panel-notifications">
                        <?php foreach ($notifications as $notification):

                        $subject = $apiCore->getItem($notification->subject_type, $notification->subject_id);
                        $object = $apiCore->getItem($notification->object_type, $notification->object_id);

                        $href = "";
                        switch ($notification->type) {
                            case 'review_new':
                                $href = url('admin/product/review?id=' . $object->id);
                                break;
                            case 'contact_new':
                                $href = url('/admin/contacts');
                                break;
                            case 'client_new':
                                $href = $subject->getHref();
                                break;
                            case 'cart_new':
                                $href = $object->getHref();
                                break;
                            case 'product_sale_run':
                                $href = $object->getHref();
                                break;
                        }
                        ?>
                        <div>
                            <div class="dropdown-item" <?php if (!empty($href)):?>onclick="openPage('{{$href}}')"<?php endif;?>>
                                <div class="dropdown-item-txt">
                                    <?php echo $notification->getNotification();?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>

                    <a class="dropdown-item view-all" href="{{url('/admin/notifications')}}">
                        <div class="dropdown-item-txt">
                            Xem Tất Cả Thông Báo
                        </div>
                    </a>
                @else
                    <a class="dropdown-item" href="javascript:void(0)">
                        <div class="dropdown-item-txt">
                            Chưa có thông báo nào
                        </div>
                    </a>
                @endif

            </div>
        </li>

        <li class="c-header-nav-item dropdown">
            <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <div class="c-avatar">
                    <div class="c-avatar-img" style="background-image:url('{{$viewer->getAvatar('profile')}}')"></div>
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-right pt-0">
                <a class="dropdown-item" href="{{url('admin/notifications')}}">
                    <img class="c-header-ic" src="{{url('public/images/icons/ic_bell_dark.png')}}" />
                    Thông Báo
                </a>
                <a class="dropdown-item" href="{{url('admin/account')}}">
                    <img class="c-header-ic" src="{{url('public/images/icons/ic_user_dark.png')}}" />
                    Tài Khoản
                </a>
                <a class="dropdown-item" href="{{url('auth/logout')}}">
                    <img class="c-header-ic" src="{{url('public/images/icons/ic_logout_dark.png')}}" />
                    Đăng Xuất
                </a>
            </div>
        </li>
    </ul>
</header>

<script type="text/javascript">
    jQuery(document).ready(function () {

    });
</script>
