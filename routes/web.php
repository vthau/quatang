<?php

Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    // return what you want
    die('clear!!!');
});

//==========================================================================================

//back-end

//levels
Route::get('/admin/levels', 'BELevelController@index');
Route::post('/admin/level/save', 'BELevelController@save');
Route::post('/admin/level/delete', 'BELevelController@delete');
Route::get('/admin/level/manage', 'BELevelController@manage');
Route::post('/admin/level/update', 'BELevelController@update');
//suppliers
Route::get('/admin/suppliers', 'BESupplierController@index');
Route::post('/admin/supplier/save', 'BESupplierController@save');
Route::post('/admin/supplier/delete', 'BESupplierController@delete');
//profile
Route::get('/admin/account', 'BEStaffController@account');
Route::get('/admin/profile/{href}', 'BEStaffController@info');
Route::post('/admin/staff/update', 'BEStaffController@update');
Route::post('/admin/staff/upload-avatar', 'BEStaffController@uploadAvatar');
Route::post('/admin/staff/change-password', 'BEStaffController@changePassword');
//staff
Route::get('/admin/staffs', 'BEStaffController@index');
Route::get('/admin/staff/add', 'BEStaffController@add');
Route::post('/admin/staff/save', 'BEStaffController@save');
Route::post('/admin/staff/delete', 'BEStaffController@delete');
Route::post('/admin/staff/check-info', 'BEStaffController@checkInfo');
Route::post('/admin/staff/block', 'BEStaffController@block');
//client
Route::get('/admin/clients', 'BEClientController@index');
Route::get('/admin/clients/export', 'BEClientController@exportItem');
Route::get('/admin/client/add', 'BEClientController@add');
Route::get('/admin/client/edit', 'BEClientController@edit');
Route::post('/admin/client/update', 'BEClientController@update');
Route::post('/admin/client/save', 'BEClientController@save');
Route::post('/admin/client/delete', 'BEClientController@delete');
Route::post('/admin/client/change-password', 'BEClientController@changePassword');
//categories
Route::get('/admin/product-categories', 'BECategoryController@index');
Route::post('/admin/category/save', 'BECategoryController@save');
Route::post('/admin/category/delete', 'BECategoryController@delete');
//user categories
Route::get('/admin/user-categories', 'BEUserCategoryController@index');
Route::post('/admin/user-categorie/save', 'BEUserCategoryController@save');
Route::post('/admin/user-categorie/delete', 'BEUserCategoryController@delete');
//brands
Route::get('/admin/product-brands', 'BEBrandController@index');
Route::post('/admin/brand/save', 'BEBrandController@save');
Route::post('/admin/brand/delete', 'BEBrandController@delete');
Route::post('/admin/brand/update-status', 'BEBrandController@updateStatus');
//system categories
Route::get('/admin/system-categories', 'BESystemCategoryController@index');
Route::post('/admin/system-category/save', 'BESystemCategoryController@save');
Route::post('/admin/system-category/delete', 'BESystemCategoryController@delete');
Route::post('/admin/system-category/update-status', 'BESystemCategoryController@updateStatus');
//wishes
Route::get('/admin/wishes', 'BEWishController@index');
Route::post('/admin/wish/save', 'BEWishController@save');
Route::post('/admin/wish/delete', 'BEWishController@delete');
Route::post('/admin/wish/update-status', 'BEWishController@updateStatus');
//cards
Route::get('/admin/cards', 'BECardController@index');
Route::post('/admin/card/save', 'BECardController@save');
Route::post('/admin/card/delete', 'BECardController@delete');
Route::post('/admin/card/update-status', 'BECardController@updateStatus');
//settings
Route::get('/admin/settings', 'BESettingController@index');
Route::post('/admin/settings/save', 'BESettingController@save');
Route::get('/admin/setting', 'BESettingController@setting');
Route::post('/admin/setting/save', 'BESettingController@settingUpdate');
//home
Route::get('/admin/config', 'BESettingController@config');
Route::post('/admin/config/save', 'BESettingController@configSave');
//events
Route::get('/admin/events', 'BEEventsController@index');
Route::get('/admin/event/add', 'BEEventsController@add');
Route::post('/admin/event/save', 'BEEventsController@save');
Route::post('/admin/event/update-status', 'BEEventsController@updateStatus');
Route::post('/admin/event/featured', 'BEEventsController@featured');
Route::post('/admin/event/delete', 'BEEventsController@delete');
//news
Route::get('/admin/news', 'BENewsController@index');
Route::get('/admin/news/add', 'BENewsController@add');
Route::post('/admin/news/save', 'BENewsController@save');
Route::post('/admin/news/update-status', 'BENewsController@updateStatus');
Route::post('/admin/news/featured', 'BENewsController@featured');
Route::post('/admin/news/delete', 'BENewsController@delete');
//products
Route::get('/admin/products', 'BEProductController@index');
Route::get('/admin/product/add', 'BEProductController@add');
Route::post('/admin/product/save', 'BEProductController@save');
Route::post('/admin/product/delete', 'BEProductController@delete');
Route::post('/admin/product/update-status', 'BEProductController@updateStatus');

//banner
Route::get('/admin/banners', 'BEBannerController@index');
Route::get('/admin/banner/add', 'BEBannerController@add');
Route::post('/admin/banner/save', 'BEBannerController@save');
Route::post('/admin/banner/delete', 'BEBannerController@delete');

//element
Route::get('/admin/elements', 'BEElementController@index');
Route::post('/admin/element/sort', 'BEElementController@sort');
Route::post('/admin/element/update', 'BEElementController@update');

//contacts
Route::get('/admin/contacts', 'BEContactController@index');
Route::post('/admin/contact/delete', 'BEContactController@delete');
//order
Route::get('/admin/orders', 'BEOrderController@index');
Route::get('/admin/order-settings', 'BEOrderController@setting');
Route::post('/admin/order-settings/save', 'BEOrderController@settingUpdate');
Route::post('/admin/order/delete', 'BEOrderController@delete');
Route::get('/admin/order/export', 'BEOrderController@exportItem');
Route::post('/admin/order/update-shipment', 'BEOrderController@updateShipment');

//undone
Route::get('/admin/product/review', 'BEProductController@review');
Route::post('/admin/product/review/update-status', 'BEProductController@reviewStatus');

//front-end
Route::get('/', 'FEHomeController@trangChu');
Route::get('/gioi-thieu', 'FEHomeController@gioiThieu');
Route::get('/lien-he', 'FEHomeController@lienHe');
Route::get('/chinh-sach-thanh-vien', 'FEHomeController@chinhSachThanhVien');
Route::get('/chinh-sach-giao-hang', 'FEHomeController@chinhSachGiaoHang');
Route::get('/chinh-sach-doi-tra', 'FEHomeController@chinhSachDoiTra');
Route::get('/chinh-sach-thanh-toan', 'FEHomeController@chinhSachThanhToan');
Route::get('/chinh-sach-bao-mat', 'FEHomeController@chinhSachBaoMat');

Route::get('/thuong-hieu', 'FEHomeController@thuongHieu');
Route::get('/thuong-hieu/{href}', 'FEHomeController@thuongHieuChiTiet');

Route::get('/danh-muc/{href}', 'FEHomeController@danhMuc');
Route::get('/san-pham', 'FEHomeController@sanPham');

Route::get('/goc-tu-van', 'FEHomeController@tuVan');
Route::get('/tu-van/{href}', 'FEHomeController@tuVanChiTiet');

Route::get('/tin-tuc', 'FEHomeController@tinTuc');
Route::get('/tin/{href}', 'FEHomeController@tinTucChiTiet');

Route::get('/tim-kiem', 'FEHomeController@timKiem');
Route::post('/tim-kiem', 'FEHomeController@timKiem');
Route::get('/yeu-thich', 'FEHomeController@yeuThich');

Route::post('/lh/gui', 'FEHomeController@lienHeGui');

//kh
Route::get('/tai-khoan', 'FEUserController@taiKhoan');
Route::post('/kh/tt/cn', 'FEUserController@thongTinCapNhat');
Route::post('/kh/ds/p', 'FEUserController@danhSachCapNhat');
Route::post('/kh/ds/g-p', 'FEUserController@danhSach');
Route::post('/kh/ds/d-p', 'FEUserController@danhSachXoa');
Route::post('/kh/ds/pd', 'FEUserController@suKienCapNhat');
Route::post('/kh/ds/g-pd', 'FEUserController@suKien');
Route::post('/kh/ds/d-pd', 'FEUserController@suKienXoa');
Route::post('/kh/dh/g-c', 'FEUserController@donHang');
Route::post('/kh/dh/u-p', 'FEUserController@donHangCapNhat');

//cart
Route::get('/gio-hang', 'FECartController@gioHang');
Route::get('/gio-hang-loi', 'FECartController@gioHangLoi');
Route::get('/don-hang/{href}', 'FECartController@donHang');
Route::get('/dh/pdf/{id}', 'PdfController@cartPdf');
Route::get('/dh/excel/{id}', 'ExcelController@cartExcel');
Route::post('/dh/pgh', 'FECartController@phiGiaoHang');
Route::post('/dh/g-d', 'FECartController@khachHangSuKien');
//cod
Route::post('/dh/cod', 'FECartController@codCreate');

//undone
Route::get('/san-pham/{href}', 'FEHomeController@sanPhamChiTiet');

//public
Route::post('/get-opts', 'FEPublicController@getOpts');
Route::post('/get-opts-wish', 'FEPublicController@getOptWish');
Route::post('/get-wish', 'FEPublicController@getWish');
Route::post('/get-opts-card', 'FEPublicController@getOptCard');
Route::post('/get-card', 'FEPublicController@getCard');
//==========================================================================================

//test
Route::get('/test', 'TestController@test');
Route::get('/t/dev/localhost', 'TestController@localhost');

Route::get('/t/set_permissions', 'TestController@setPermissions');

//cron
Route::get('/cron/jobs', 'CronController@index'); //moi 5 phut

//social
Route::get('/auth/redirect/{provider}', 'FESocialController@redirect');
Route::get('/callback/{provider}', 'FESocialController@callback');

//permission
Route::get('/set-permissions', 'BEPermissionController@setPermissions');

//notification
Route::get('/admin/notifications', 'BENotificationController@index');
Route::post('/admin/notification/refresh', 'BENotificationController@refresh');

//back-end
Route::get('/admin', 'BEIndexController@index');

//tinymce
Route::post('/admin/tinymce/upload', 'BETinymceController@upload');

//texts
Route::get('/admin/texts', 'BETextController@index');
Route::post('/admin/texts/save', 'BETextController@save');



//reviews
Route::get('/admin/reviews', 'BEReviewsController@index');
Route::get('/admin/review/add', 'BEReviewsController@add');
Route::post('/admin/review/save', 'BEReviewsController@save');
Route::post('/admin/review/update-status', 'BEReviewsController@updateStatus');
Route::post('/admin/review/delete', 'BEReviewsController@delete');

Route::get('/admin/product/export', 'BEProductController@exportItem');
Route::post('/admin/product/import', 'BEProductController@importItem');
Route::get('/admin/product/import-failed', 'BEProductController@importFailed');

Route::post('/admin/product/import-size', 'BEProductController@updateSize');
Route::get('/admin/product/import-size-failed', 'BEProductController@updateSizeFailed');

//cart
Route::post('/admin/order/update-status', 'BECartController@updateStatus');
Route::post('/admin/order/update-shipped', 'BECartController@updateShipped');
Route::post('/ghn/create-order', 'GhnController@createOrder');
Route::post('/admin/order/ship-manual', 'GhnController@shipManual');

//authorization
Route::get('/login', 'BEAuthController@login')->name('login');
Route::post('/auth/do-login', 'BEAuthController@doLogin');
Route::get('/auth/logout', 'BEAuthController@logout');
Route::post('/auth/check-email', 'BEAuthController@checkEmail');
Route::get('/auth/forgot', 'BEAuthController@forgot');
Route::post('/auth/do-forgot', 'BEAuthController@doForgot');
Route::get('/auth/reset/{token}', 'BEAuthController@reset')->name('password.reset');
Route::post('/auth/reset-password', 'BEAuthController@resetPassword');

//error
Route::get('/invalid', 'BEErrorController@invalid');
Route::get('/private', 'BEErrorController@err403');

//auth
Route::get('/dang-nhap', 'FEAuthController@login');
Route::post('/auth/dang-nhap', 'FEAuthController@doLogin');
Route::post('/auth/dang-ki', 'FEAuthController@doSignup');
Route::get('/dang-xuat', 'FEAuthController@logout');
Route::post('/auth/ktra-email', 'FEAuthController@checkEmail');
Route::get('/auth/mat-khau', 'FEAuthController@forgot');
Route::post('/auth/quen-mat-khau', 'FEAuthController@doForgot');
Route::get('/auth/lay-lai-mat-khau/{token}', 'FEAuthController@reset');
Route::post('/auth/doi-mat-khau', 'FEAuthController@resetPassword');





//ajax
Route::post('/sp/love', 'FEAjaxController@spLove');
Route::post('/sp/buy', 'FEAjaxController@spBuy');
Route::post('/sp/buy-side', 'FEAjaxController@spBuySide');
Route::post('/sp/by-brand', 'FEAjaxController@spByBrand');
Route::post('/sp/remove', 'FEAjaxController@spRemove');
Route::post('/sp/review', 'FEAjaxController@spReview');
Route::post('/sp/review-more', 'FEAjaxController@spReviewMore');
Route::post('/sp/random', 'FEAjaxController@spRandom');


//vnpay
Route::post('/dh/vnpay', 'FECartController@vnpayCreate');
Route::get('/dh/vnpay/return', 'FECartController@vnpayReturn');
Route::get('/vnpay/callback', 'FECartController@vnpayCallback');
//zalopay
Route::post('/dh/zalopay', 'FECartController@zalopayCreate');
Route::get('/dh/zalopay/return', 'FECartController@zalopayReturn');

Route::get('/403', function () {
    return view("pages.front_end.403");
});
