<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$apiFE = new \App\Api\FE;
$provinces = $apiFE->getProvinces();
$districts = $apiFE->getDistrictsByProvinceId($viewer->province_id);
$wards = $apiFE->getWardsByDistrictId($viewer->district_id);

?>

@extends('templates.front_end.master')

@section('content')
    <style type="text/css">
        @if ($isMobile)
            .mfp-content {
                position: absolute;
                left: 0;
                top: 50%;
                margin-top: -150px;
            }
        @else
            .mfp-ajax-holder .mfp-content, .mfp-inline-holder .mfp-content {
                width: auto;
            }
        @endif

        .cart-table thead th {
            text-align: center;
            vertical-align: middle;
        }
    </style>

    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop')
        </div>
    </div>

    <div class="container mb__100">
        @include('modals.breadcrumb', [
           'text1' => $apiCore->getSetting('text_tk_title'),
        ])

        <div class="row">
            <div class="col-md-12 kero-tab" id="kero-1">
                <div class="mb-3 card">
                    <div class="card-header">
                        <ul class="nav nav-justified">
                            <li class="nav-item">
                                <a data-toggle="tab" href="javascript:void(0)" onclick="jsbindtab1('ttcn')" class="nav-link ttcn active">
                                    <span>thông tin cá nhân</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a data-toggle="tab" href="javascript:void(0)" onclick="jsbindtab1('dhdd')" class="nav-link dhdd">
                                    <span>đơn hàng đã đặt</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a data-toggle="tab" href="javascript:void(0)" onclick="jsbindtab1('dsct')" class="nav-link dsct">
                                    <span>danh sách của tôi</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a data-toggle="tab" href="javascript:void(0)" onclick="jsbindtab1('spdx')" class="nav-link spdx">
                                    <span>sản phẩm xem gần nhất</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body mt__20 mb__20">
                        <div class="tab-content">
                            <div class="tab-pane ttcn active" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <div id="frm-account">
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label class="frm-label">Họ Tên</label>
                                                </div>
                                                <div class="col-md-9" id="frm-name">
                                                    <button onclick="jskhinfoedit('name')" type="button" class="button button_edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>

                                                    <button onclick="jskhinfono('name')" type="button" class="button button_hide">
                                                        <i class="fas fa-times"></i>
                                                    </button>

                                                    <button onclick="jskhinfook('name')" type="button" class="button button_primary button_save">
                                                        <i class="fas fa-check"></i>
                                                    </button>

                                                    <div class="txt">{{$viewer->name}}</div>

                                                    <div class="input">
                                                        <input id="input-name" value="{{$viewer->name}}" class="form-control text-center" type="text" autocomplete="off" />
                                                        <div class="alert alert-danger hidden">Vui lòng nhập họ tên.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label class="frm-label">Email</label>
                                                </div>
                                                <div class="col-md-9" id="frm-email">
                                                    <button onclick="jskhinfoedit('email')" type="button" class="button button_edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>

                                                    <button onclick="jskhinfono('email')" type="button" class="button button_hide">
                                                        <i class="fas fa-times"></i>
                                                    </button>

                                                    <button onclick="jskhinfook('email')" type="button" class="button button_primary button_save">
                                                        <i class="fas fa-check"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-danger hidden">
                                                        <i class="fas fa-spinner"></i>
                                                    </button>

                                                    <div class="txt">{{$viewer->email}}</div>

                                                    <div class="input">
                                                        <input id="input-email" value="{{$viewer->email}}" class="form-control text-center" type="email" autocomplete="off" />
                                                        <div class="alert alert-danger hidden">Vui lòng nhập email hợp lệ.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label class="frm-label">Mật khẩu</label>
                                                </div>
                                                <div class="col-md-9" id="frm-password">
                                                    <button onclick="jskhinfoedit('password')" type="button" class="button button_edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>

                                                    <button onclick="jskhinfono('password')" type="button" class="button button_hide">
                                                        <i class="fas fa-times"></i>
                                                    </button>

                                                    <button onclick="jskhinfook('password')" type="button" class="button button_primary button_save">
                                                        <i class="fas fa-check"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-danger hidden">
                                                        <i class="fas fa-spinner"></i>
                                                    </button>

                                                    <div class="txt">********</div>

                                                    <div class="input">
                                                        <input id="input-password" class="form-control text-center" type="password" autocomplete="off" placeholder="hãy nhập mật khẩu mới..." />
                                                        <div class="alert alert-danger hidden">Vui lòng nhập mật khẩu.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label class="frm-label">Điện Thoại</label>
                                                </div>
                                                <div class="col-md-9" id="frm-phone">
                                                    <button onclick="jskhinfoedit('phone')" type="button" class="button button_edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>

                                                    <button onclick="jskhinfono('phone')" type="button" class="button button_hide">
                                                        <i class="fas fa-times"></i>
                                                    </button>

                                                    <button onclick="jskhinfook('phone')" type="button" class="button button_primary button_save">
                                                        <i class="fas fa-check"></i>
                                                    </button>

                                                    <div class="txt">{{$viewer->phone}}</div>

                                                    <div class="input">
                                                        <input id="input-phone" value="{{$viewer->phone}}" class="form-control text-center" type="number" autocomplete="off" />
                                                        <div class="alert alert-danger hidden">Vui lòng nhập điện thoại.</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label class="frm-label">Địa Chỉ</label>
                                                </div>
                                                <div class="col-md-9" id="frm-dia_chi_nha">
                                                    <button onclick="jskhinfoedit('dia_chi_nha')" type="button" class="button button_edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>

                                                    <button onclick="jskhinfono('dia_chi_nha')" type="button" class="button button_hide">
                                                        <i class="fas fa-times"></i>
                                                    </button>

                                                    <button onclick="jskhinfook('dia_chi_nha')" type="button" class="button button_primary button_save">
                                                        <i class="fas fa-check"></i>
                                                    </button>

                                                    <div class="txt">{{$viewer->getFullAddress()}}</div>

                                                    <div class="input" id="frm-address">
                                                        <input id="input-dia_chi_nha" value="{{$viewer->address}}" class="form-control text-center" type="text" autocomplete="off" />

                                                        <select name="province_id" class="form-control select-css mt__10" onchange="jscartaddressopts(this, 'district')">
                                                            <option value="">Hãy chọn tỉnh / thành</option>
                                                            @foreach($provinces as $ite)
                                                                <option @if($viewer->province_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                            @endforeach
                                                        </select>

                                                        <select name="district_id" class="form-control select-css mt__10" onchange="jscartaddressopts(this, 'ward')">
                                                            <option value="">Hãy chọn quận / huyện</option>
                                                            @if (count($districts))
                                                                @foreach($districts as $ite)
                                                                    <option @if($viewer->district_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>

                                                        <select name="ward_id" class="form-control select-css mt__10">
                                                            <option value="">Hãy chọn phường / xã</option>
                                                            @if (count($wards))
                                                                @foreach($wards as $ite)
                                                                    <option @if($viewer->ward_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane dhdd" role="tabpanel">
                                <div class="table_wrapper table_search">
                                    <div class="row">
                                        <div class="col-md-3 mb__5">
                                            <select name="person_id" class="form-control">
                                                <option value="">Tất Cả Mọi Người</option>
                                                @if(count($persons))
                                                    @foreach($persons as $ite)
                                                        <option value="{{$ite->id}}">{{$ite->getTitle()}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb__5">
                                            <select name="relationship_id" class="form-control">
                                                <option value="">Tất Cả Mối Quan Hệ</option>
                                                <option value="other">Ngoài Mối Quan Hệ</option>
                                                @if(count($relationships))
                                                    @foreach($relationships as $ite)
                                                        <option value="{{$ite->id}}">{{$ite->getTitle()}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb__5">
                                            <select name="month" class="form-control">
                                                <option value="">Tất Cả Các Tháng</option>
                                                @for($i=1;$i<=12;$i++)
                                                    <option value="{{$i}}">{{'Trong Tháng ' . $i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb__5">
                                            <select name="year" class="form-control">
                                                <option value="">Tất Cả Các Năm</option>
                                                @for($i=2021;$i<=date('Y');$i++)
                                                    <option @if($i == (int)date('Y')) selected="selected" @endif value="{{$i}}">{{'Năm ' . $i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb__5">
                                            <button type="button" class="button button_primary text-uppercase" onclick="jskhcart()">
                                                <i class="fa fa-search mr__5"></i> tìm kiếm
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mt__20">
                                        <div class="clearfix">
                                            <span class="mr__10">
                                                <span class="frm-label">Tổng tiền hàng: </span>
                                                <b class="number_format text-warning text-bold" id="tong_tien_hang"></b>
                                                <span class="currency_format text-warning text-bold">₫</span>
                                            </span>
                                            <span class="mr__10 hidden">
                                                <span class="frm-label">Tổng Giảm Giá: </span>
                                                <b class="number_format text-danger text-bold"></b>
                                                <span class="currency_format text-danger text-bold">₫</span>
                                            </span>
                                            <span>
                                                <span class="frm-label">Tổng thanh toán: </span>
                                                <b class="number_format text-success text-bold" id="tong_thanh_toan"></b>
                                                <span class="currency_format text-success text-bold">₫</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="cart-table width_full">
                                            <thead>
                                            <tr>
                                                <th class="frm-label">stt</th>
                                                <th class="frm-label">thời gian<br />đặt</th>
                                                <th class="frm-label">mã<br />đơn hàng</th>
                                                <th class="align-center frm-label">số lượng<br />SP</th>
                                                <th class="align-center frm-label">tổng<br />tiền hàng</th>
                                                <th class="align-center frm-label hidden">giảm giá</th>
                                                <th class="align-center frm-label">phí<br />giao hàng</th>
                                                <th class="align-center frm-label">tổng<br />thanh toán</th>
                                                <th class="align-center frm-label">phương thức</th>
                                                <th class="align-center frm-label">mua tặng</th>
                                                <th class="align-center frm-label">trạng thái</th>
                                                <th class="align-center"></th>
                                            </tr>
                                            </thead>
                                            <tbody id="cart_found"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane spdx" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if (count($views))
                                            <div class="container container_cat pop_default cat_default mb__60">
                                                <div class="cat_toolbar row fl_center al_center mt__30">
                                                    <div class="cat_view col-auto hidden">
                                                        <div class="dn dev_desktop dev_view_cat">
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="dk" data-col="6"
                                                               class="pr mr__10 cat_view_page view_6"></a>
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="dk" data-col="4"
                                                               class="pr mr__10 cat_view_page view_4"></a>
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="dk" data-col="3"
                                                               class="pr mr__10 cat_view_page view_3"></a><a rel="nofollow" data-no-instant="" href="#"
                                                                                                             data-dev="dk" data-col="15"
                                                                                                             class="pr mr__10 cat_view_page view_15"></a><a
                                                                rel="nofollow" data-no-instant="" href="#" data-dev="dk" data-col="2"
                                                                class="pr cat_view_page view_2"></a></div>
                                                        <div class="dn dev_tablet dev_view_cat">
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="tb" data-col="6"
                                                               class="pr mr__10 cat_view_page view_6"></a>
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="tb" data-col="4"
                                                               class="pr mr__10 cat_view_page view_4"></a>
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="tb" data-col="3"
                                                               class="pr cat_view_page view_3"></a>
                                                        </div>
                                                        <div class="flex dev_mobile dev_view_cat">
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="mb" data-col="12"
                                                               class="pr mr__10 cat_view_page view_12"></a>
                                                            <a rel="nofollow" data-no-instant="" href="#" data-dev="mb" data-col="6"
                                                               class="pr cat_view_page view_6"></a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-12 col-12">
                                                        <div id="shopify-section-collection_page" class="shopify-section tp_se_cdt">
                                                            <div class="nt_svg_loader dn"></div>
                                                            <div class="products nt_products_holder row fl_center row_pr_1 cdt_des_1 round_cd_false nt_cover ratio_nt position_8 space_30 nt_default">
                                                                <?php foreach ($views as $item):
                                                                $product = $apiCore->getItem('product',(int)$item->product_id);

                                                                ?>
                                                                <div
                                                                    class="col-lg-3 col-md-3 col-12 pr_animated done mt__30 pr_grid_item product nt_pr desgin__1">
                                                                    <div class="product-inner pr">
                                                                        <div class="product-image pr oh lazyloaded product-custom" >
                                                                            <span class="tc nt_labels pa pe_none cw"></span>
                                                                            <a class="db" href="{{$product->getHref(true)}}">
                                                                                <div class="pr_lazy_img main-img nt_img_ratio nt_bg_lz lazyloaded"
                                                                                     data-id="14246008717451"
                                                                                     data-bgset="{{$product->getAvatar()}}"
                                                                                     data-parent-fit="width" data-wiis="" data-ratio="0.7837837837837838"
                                                                                     style="padding-top: 127.586%; background-image: url('{{$product->getAvatar()}}');">
                                                                                    <picture style="display: none;">
                                                                                        <source
                                                                                            data-srcset="{{$product->getAvatar()}}"
                                                                                            sizes="270px"
                                                                                            srcset="{{$product->getAvatar()}}">
                                                                                        <img alt="" class="lazyautosizes lazyloaded" data-sizes="auto"
                                                                                             data-ratio="0.7837837837837838" sizes="270px"></picture>
                                                                                </div>
                                                                            </a>
                                                                            <div class="hover_img pa pe_none t__0 l__0 r__0 b__0 op__0">
                                                                                <div class="pr_lazy_img back-img pa nt_bg_lz lazyloaded"
                                                                                     data-id="14246008750219"
                                                                                     data-bgset="{{$product->getAvatar()}}"
                                                                                     data-parent-fit="width" data-wiis="" data-ratio="0.7837837837837838"
                                                                                     style="padding-top: 127.586%; background-image: url('{{$product->getAvatar()}}');">
                                                                                    <picture style="display: none;">
                                                                                        <source
                                                                                            data-srcset="{{$product->getAvatar()}}"
                                                                                            sizes="270px"
                                                                                            srcset="{{$product->getAvatar()}}">
                                                                                        <img alt="" class="lazyautosizes lazyloaded" data-sizes="auto"
                                                                                             data-ratio="0.7837837837837838" sizes="270px"></picture>
                                                                                </div>
                                                                            </div>
                                                                            @if($product->is_new || $product->is_best_seller)
                                                                                <div class="hot_best ts__03 pa">
                                                                                    @if($product->is_new)
                                                                                        <div class="hot_best_text is_new">mới</div>
                                                                                    @endif
                                                                                    @if($product->is_best_seller)
                                                                                        <div class="hot_best_text is_hot">bán chạy</div>
                                                                                    @endif
                                                                                </div>
                                                                            @endif

                                                                            @if ($product->price_main != $product->price_pay)
                                                                                <div class="discount_percent ts__03 pa">
                                                                                    <div class="discount_percent_text">giảm {{$product->discount}}%</div>
                                                                                </div>
                                                                            @endif
                                                                            <div class="nt_add_w ts__03 pa ">
                                                                                <div class="product-love sp-love-{{$product->id}}" onclick="jssplove(this, {{$product->id}})">
                                                                                    @if ($product->isLoved())
                                                                                        <i class="fas fa-heart active" title="Đã Yêu Thích SP"></i>
                                                                                    @else
                                                                                        <i class="fas fa-heart" title="Thêm SP Yêu Thích"></i>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="hover_button op__0 tc pa flex column ts__03">
                                                                                <a href="javascript:void(0)" data-id="4540696920203" onclick="jscartdh({{$product->id}})"
                                                                                   class="pr pr_atc cd br__40 bgw tc dib cb chp ttip_nt tooltip_top_left"
                                                                                   rel="nofollow"><span class="tt_txt text-capitalize">thêm vào giỏ</span><i
                                                                                        class="iccl iccl-cart"></i><span class="text-capitalize">thêm vào giỏ</span>
                                                                                </a>
                                                                            </div>

                                                                        </div>
                                                                        <div class="product-info mt__15">
                                                                            <h3 class="product-title pr fs__14 mg__0 fwm"><a
                                                                                    class="cd chp" href="{{$product->getHref(true)}}">{{$product->getTitle()}}</a></h3>
                                                                            <span class="price dib mb__5">
                                                                                @if ($product->price_main != $product->price_pay)
                                                                                    <del class="price_old">
                                                                                    <span class="number_format">{{$product->price_main}}</span>
                                                                                    <span class="currency_format">₫</span>
                                                                                </del>
                                                                                @endif
                                                                                <ins>
                                                                                    <span class="number_format">{{$product->price_pay}}</span>
                                                                                    <span class="currency_format">₫</span>
                                                                                </ins>

                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php endforeach;?>
                                                            </div>
                                                            <style>
                                                                .dev_view_cat.dev_desktop a.view_3 {
                                                                    border-color: #222
                                                                }

                                                                .dev_view_cat.dev_desktop a.view_3:before {
                                                                    background: #222;
                                                                    box-shadow: 13px 0 0 #222, 26px 0 0 #222, 39px 0 0 #222
                                                                }

                                                                .dev_view_cat.dev_tablet a.view_3 {
                                                                    border-color: #222
                                                                }

                                                                .dev_view_cat.dev_tablet a.view_3:before {
                                                                    background: #222;
                                                                    box-shadow: 13px 0 0 #222, 26px 0 0 #222, 39px 0 0 #222
                                                                }

                                                                .dev_view_cat.dev_mobile a.view_6 {
                                                                    border-color: #222
                                                                }

                                                                .dev_view_cat.dev_mobile a.view_6:before {
                                                                    background: #222;
                                                                    box-shadow: 13px 0 0 #222, 13px 0 0 #222
                                                                }
                                                            </style>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-info notfound"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane dsct" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table_wrapper table_btn">
                                            <button type="button" class="button text-uppercase" onclick="jskhpopupperson1()">
                                            <i class="fa fa-plus mr__5"></i> thêm người
                                            </button>
                                        </div>
                                        <div class="table_wrapper table_search">
                                            <div class="row">
                                                <div class="col-md-4 mb__5">
                                                    <input type="text" name="keyword" placeholder="Từ Khóa" class="form-control" autocomplete="off" />
                                                </div>
                                                <div class="col-md-3 mb__5">
                                                    <select name="relationship_id" class="form-control">
                                                        <option value="">Tất Cả Mối Quan Hệ</option>
                                                        @if(count($relationships))
                                                            @foreach($relationships as $ite)
                                                                <option value="{{$ite->id}}">{{$ite->getTitle()}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb__5">
                                                    <select name="month" class="form-control">
                                                        <option value="">Tất Cả Các Tháng</option>
                                                        @for($i=1;$i<=12;$i++)
                                                            <option value="{{$i}}">{{'Trong Tháng ' . $i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mb__5">
                                                    <button type="button" class="button button_primary text-uppercase" onclick="jskhsearchperson()">
                                                        <i class="fa fa-search mr__5"></i> tìm kiếm
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table_wrapper table_body">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="popup_person" class="mfp-wrap mfp-close-btn-in mfp-auto-cursor mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_2 hidden" tabindex="-1" style="overflow: hidden auto;">
        <div class="mfp-container mfp-s-ready mfp-inline-holder">
            <div class="mfp-content">
                <div class="popup_gks">
                    <form action="" method="post" id="frm-person">
                        <table class="table">
                            <thead>
                            <tr>
                                <td>
                                    <div class="overflow-hidden text-uppercase text-bold table_title"></div>
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <div class="popup_gks_body">
                                        <div class="form-group" id="req-title">
                                            <label class="frm-label fs-11 required">* họ tên</label>
                                            <input required name="title" type="text" class="form-control" autocomplete="off" />
                                            <div class="alert alert-danger hidden mt__10">Vui lòng nhập họ tên.</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="frm-label fs-11">điện thoại</label>
                                            <input name="phone" type="text" class="form-control" autocomplete="off"
                                                   onkeypress="return isInputPhone(event, this)"
                                                   oncopy="return false;" oncut="return false;" onpaste="return false;"
                                            />
                                        </div>
                                        <div class="form-group" id="frm_popup_address">
                                            <label class="frm-label fs-11">địa chỉ</label>
                                            <input name="address" type="text" class="form-control" autocomplete="off" />
                                            <select name="province_id" class="form-control select-css mt__10" onchange="jspopupaddress(this, 'district')">
                                                <option value="">Hãy chọn tỉnh / thành</option>
                                                @foreach($provinces as $ite)
                                                    <option value="{{$ite->id}}">{{$ite->title}}</option>
                                                @endforeach
                                            </select>

                                            <select name="district_id" class="form-control select-css mt__10" onchange="jspopupaddress(this, 'ward')">
                                                <option value="">Hãy chọn quận / huyện</option>
                                            </select>

                                            <select name="ward_id" class="form-control select-css mt__10">
                                                <option value="">Hãy chọn phường / xã</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="frm-label fs-11">mối quan hệ</label>
                                            <select name="relationship_id" class="form-control">
                                                @if(count($relationships))
                                                    @foreach($relationships as $ite)
                                                        <option value="{{$ite->id}}">{{$ite->getTitle()}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="frm-label fs-11">mối quan hệ khác</label>
                                            <input name="relationship" type="text" class="form-control" autocomplete="off" />
                                        </div>
                                        <div class="form-group">
                                            <label class="frm-label fs-11">ghi chú</label>
                                            <textarea name="note" rows="3" cols="3" class="form-control min_height_100px"></textarea>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td class="text-right">
                                    <button type="button" class="button text-uppercase" onclick="jsbindpopupclose()">không</button>
                                    <button type="submit" class="button button_primary text-uppercase">xác nhận</button>

                                    <input name="item_id" type="hidden" />
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="popup_person_delete" class="mfp-wrap mfp-close-btn-in mfp-auto-cursor mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_2 hidden" tabindex="-1" style="overflow: hidden auto;">
        <div class="mfp-container mfp-s-ready mfp-inline-holder">
            <div class="mfp-content">
                <div class="popup_gks">
                    <table class="table">
                        <thead>
                        <tr>
                            <td>
                                <div class="overflow-hidden text-uppercase text-bold">Xác Nhận</div>
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <div class="popup_gks_body">Bạn có chắc muốn xóa không?</div>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td class="text-right">
                                <button class="button text-uppercase" onclick="jsbindpopupclose()">không</button>
                                <button class="button button_primary text-uppercase" onclick="jskhpopuppersondelok()">xác nhận</button>

                                <input type="hidden" name="item_id" />
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="popup_date" class="mfp-wrap mfp-close-btn-in mfp-auto-cursor mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_2 hidden" tabindex="-1" style="overflow: hidden auto;">
        <div class="mfp-container mfp-s-ready mfp-inline-holder">
            <div class="mfp-content">
                <div class="popup_gks">
                    <form action="" method="post" id="frm-date">
                        <table class="table">
                            <thead>
                            <tr>
                                <td>
                                    <div class="overflow-hidden text-uppercase text-bold table_title"></div>
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <div class="popup_gks_body">
                                        <div class="form-group" id="req-title">
                                            <label class="frm-label fs-11 required">* tiêu đề</label>
                                            <input required name="title" type="text" class="form-control" autocomplete="off" placeholder="Ví dụ: Sinh Nhật, ..." />
                                            <div class="alert alert-danger hidden mt__10">Vui lòng nhập họ tên.</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="frm-label fs-11 required">* ngày / tháng</label>
                                            <div class="clearfix">
                                                <select required name="day" class="form-control float-left mr__5 text-center" style="width: 30%;">
                                                    <option value="">Hãy chọn ngày</option>
                                                    @for($i=1;$i<=31;$i++)
                                                        <option value="{{$i}}">{{"Ngày " . $i}}</option>
                                                    @endfor
                                                </select>
                                                <div class="float-left mr__5" style="font-size: 24px;">/</div>
                                                <select required name="month" class="form-control float-left mr__5 text-center" style="width: 30%;">
                                                    <option value="">Hãy chọn tháng</option>
                                                    @for($i=1;$i<=12;$i++)
                                                        <option value="{{$i}}">{{"Tháng " . $i}}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="frm-label fs-11">kinh phí dự kiến (₫)</label>
                                            <input name="budget" type="text" class="form-control number_format" autocomplete="off" />
                                        </div>
                                        <div class="form-group">
                                            <label class="frm-label fs-11">ghi chú</label>
                                            <textarea name="note" rows="3" cols="3" class="form-control min_height_100px"></textarea>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td class="text-right">
                                    <button type="button" class="button text-uppercase" onclick="jsbindpopupclose()">không</button>
                                    <button type="submit" class="button button_primary text-uppercase">xác nhận</button>

                                    <input name="item_id" type="hidden" />
                                    <input name="person_id" type="hidden" />
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="popup_date_delete" class="mfp-wrap mfp-close-btn-in mfp-auto-cursor mfp-move-horizontal prpr_pp_wrapper mfp-ready overlay_bg_2 hidden" tabindex="-1" style="overflow: hidden auto;">
        <div class="mfp-container mfp-s-ready mfp-inline-holder">
            <div class="mfp-content">
                <div class="popup_gks">
                    <table class="table">
                        <thead>
                        <tr>
                            <td>
                                <div class="overflow-hidden text-uppercase text-bold">Xác Nhận</div>
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <div class="popup_gks_body">Bạn có chắc muốn xóa không?</div>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td class="text-right">
                                <button class="button text-uppercase" onclick="jsbindpopupclose()">không</button>
                                <button class="button button_primary text-uppercase" onclick="jskhpopupdatedelok()">xác nhận</button>

                                <input type="hidden" name="item_id" />
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.notfound').text("Không tìm thấy dữ liệu phù hợp.");

            @if (count($params) && isset($params['t']))
            jsbindtab1('{{$params['t']}}')
            @endif

            jskhcart();

            jskhsearchperson();
        });

    </script>
@stop

