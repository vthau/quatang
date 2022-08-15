<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiFE = new \App\Api\FE();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();
?>

@extends('templates.front_end.master')

@section('content')
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

    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop')
        </div>
    </div>

    <div class="container container_cat pop_default cat_default mb__60">
        @include('modals.breadcrumb', [
            'text1' => 'Tìm Kiếm',
        ])

        <div class="table_wrapper table_search">
            <form action="{{url('tim-kiem')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb__5">
                        <label class="frm-label fs-11">từ khóa</label>
                        <input type="text" name="keyword" placeholder="Từ Khóa" class="form-control" autocomplete="off"
                               value="{{count($params) && isset($params['keyword']) ? $params['keyword'] : ''}}"
                        />
                    </div>
                    <div class="col-md-3 mb__5">
                        <label class="frm-label fs-11">nhóm sản phẩm</label>
                        <select name="category" class="form-control">
                            <option value="">Tất Cả Nhóm Sản Phẩm</option>
                            @if(count($categories))
                                @foreach($categories as $ite)
                                    <option @if(count($params) && isset($params['category']) && (int)$params['category'] == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->getTitle()}}</option>
                                    @if(count($ite->getSubCategories()))
                                        @foreach($ite->getSubCategories() as $children)
                                            <option @if(count($params) && isset($params['category']) && (int)$params['category'] == $children->id) selected="selected" @endif value="{{$children->id}}">{{'---> ' . $children->getTitle()}}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3 mb__5">
                        <label class="frm-label fs-11">thương hiệu</label>
                        <select name="brand" class="form-control">
                            <option value="">Tất Cả Thương Hiệu</option>
                            @if(count($brands))
                                @foreach($brands as $ite)
                                    <option @if(count($params) && isset($params['brand']) && (int)$params['brand'] == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->getTitle()}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3 mb__5">
                        <label class="frm-label fs-11">xuất xứ</label>
                        <select name="made_in" class="form-control">
                            <option value="">Tất Cả Xuất Xứ</option>
                            @foreach ($apiCore->listCountries() as $k => $v)
                                <option @if(count($params) && isset($params['brand']) && (int)$params['brand'] == $k) selected="selected" @endif value="{{$k}}">{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb__5">
                        <label class="frm-label fs-11">mức giá</label>
                        <select name="price" class="form-control">
                            <option value="">Tất Cả Mức Giá</option>
                            <option @if(count($params) && isset($params['price']) && $params['price'] == 'to500') selected="selected" @endif value="to500">0 - 500k</option>
                            <option @if(count($params) && isset($params['price']) && $params['price'] == 'to1m') selected="selected" @endif value="to1m">500k - 1 triệu</option>
                            <option @if(count($params) && isset($params['price']) && $params['price'] == 'to2m') selected="selected" @endif value="to2m">1 triệu - 2 triệu</option>
                            <option @if(count($params) && isset($params['price']) && $params['price'] == 'to3m') selected="selected" @endif value="to3m">2 triệu - 3 triệu</option>
                            <option @if(count($params) && isset($params['price']) && $params['price'] == 'to5m') selected="selected" @endif value="to5m">3 triệu - 5 triệu</option>
                            <option @if(count($params) && isset($params['price']) && $params['price'] == 'to10m') selected="selected" @endif value="to10m">5 triệu - 10 triệu</option>
                            <option @if(count($params) && isset($params['price']) && $params['price'] == 'to20m') selected="selected" @endif value="to20m">10 triệu - 20 triệu</option>
                            <option @if(count($params) && isset($params['price']) && $params['price'] == 'max') selected="selected" @endif value="max">Lớn hơn 20 triệu</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb__5">
                        <label class="frm-label fs-11">lọc</label>
                        <select name="sort" class="form-control">
                            <option value="price_asc">Giá Tăng Dần</option>
                            <option @if(count($params) && isset($params['sort']) && $params['sort'] == 'price_desc') selected="selected" @endif value="price_desc">Giá Giảm Dần</option>
                            <option @if(count($params) && isset($params['sort']) && $params['sort'] == 'sort_new') selected="selected" @endif value="sort_new">Sản Phẩm Mới</option>
                            <option @if(count($params) && isset($params['sort']) && $params['sort'] == 'sort_best_seller') selected="selected" @endif value="sort_best_seller">Sản Phẩm Bán Chạy</option>
                            <option @if(count($params) && isset($params['sort']) && $params['sort'] == 'sort_discount') selected="selected" @endif value="sort_discount">Sản Phẩm Đang Giảm Giá</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="button text-uppercase">
                            <i class="fa fa-search mr__5"></i> tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if ($cart || count($carts))
            <div class="clearfix mt__30 mb__100">
            @if ($cart)
                @include('widgets.front_end.cart_search')
            @endif

            @if (count($carts))
                <div class="text-center mb__30">
                    <span class="text-uppercase text-bold badge badge-warning">{{count($carts) . ' đơn hàng gần nhất'}}</span>
                </div>
                @foreach($carts as $cart)
                        @include('widgets.front_end.cart_search')
                @endforeach
            @endif
            </div>
        @else

            @if (!count($products))
                <div class="empty_cart_page tc">
                    <style type="text/css">
                        .empty_cart_page>i:after {
                            display: none;
                        }

                    </style>
                    <i class="las la-shopping-bag pr mb__30 fs__90"></i>
                    <h4 class="cart_page_heading mg__0 mb__20 tu fs__30">không tìm thấy dữ liệu phù hợp!!!</h4>
                    <div class="mt__30"></div>
                    <p class="mb__15"><a class="button button_primary tu " href="{{url('/')}}">trở lại trang chủ</a>
                    </p>
                </div>
            @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel-content products-wrapper">
                            <div class="row">
                                @if (count($products))
                                    @include('widgets.front_end.pagination_products')
                                @else
                                    <div class="alert alert-warning">Đang Cập Nhật...</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function () {

        });
    </script>
@stop
