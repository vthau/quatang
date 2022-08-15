<?php
$pageTitle = (isset($page_title)) ? $page_title : "";

$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();
?>

@extends('templates.be.master')

@section('content')

    <style type="text/css">
        #ui-view a.c-href-item {
            position: relative;
            top: 10px;
        }
        .frm-search .form-group > div {
            float: left;
            margin-bottom: 20px;
        }
        .currency_format {
            font-size: 10px;
            position: relative;
            top: -3px;
            left: 2px;
        }
    </style>

    <div>
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-menu">
                        @if ($viewer->isAllowed("product_add") || $viewer->isSupplier())
                            <button class="btn btn-primary btn-sm mb-1" onclick="openPage('{{url('admin/product/add')}}')" >
                                <i class="fa fa-plus-circle mr-1"></i>
                                Tạo Sản Phẩm
                            </button>
                        @endif
                        @if ($viewer->isAllowed("product_excel_export") || $viewer->isSupplier())
                            <button class="btn btn-success btn-sm mb-1" onclick="exportItems()" >
                                <i class="fa fa-file-excel mr-1"></i>
                                Xuất Excel
                            </button>
                        @endif
                        @if ($viewer->isAllowed("product_excel_import") || $viewer->isSupplier())
                            <button class="btn btn-warning btn-sm mb-1" onclick="nhapTuExcel()" >
                                <i class="fa fa-file-excel mr-1"></i>
                                Nhập Excel
                            </button>
                        @endif
                    </div>

                    <div class="clearfix mb-4 mt-4">
                        <span class="alert alert-warning">Sản phẩm bị tạm dừng sẽ không hiển thị ngoài trang chính.</span>
                    </div>

                    <div class="frm-search">
                        <form action="{{url('admin/products')}}" method="get" id="frm-search">
                            <div class="card">
                                <div class="card-header">
                                    <strong>Tìm Kiếm</strong>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <div class="btn-group">
                                                        <button id="btn-filter" type="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="true" class="dropdown-toggle btn btn-info">
                                                            @if (count($params) && isset($params['filter']))
                                                                @if ($params['filter'] == 'hdsd')
                                                                    HDSD
                                                                @elseif ($params['filter'] == 'thanh_phan')
                                                                    Thành Phần
                                                                @elseif ($params['filter'] == 'cong_dung')
                                                                    Công Dụng
                                                                @elseif ($params['filter'] == 'mo_ta')
                                                                    Mô Tả
                                                                @else
                                                                    Tên
                                                                @endif
                                                            @else
                                                                Tên
                                                            @endif
                                                        </button>
                                                        <div tabindex="-1" aria-hidden="true" role="menu" class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -173px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                            <button onclick="filterBy('name')" type="button" tabindex="0" class="dropdown-item">Tên</button>
                                                            <button onclick="filterBy('mo_ta')" type="button" tabindex="0" class="dropdown-item">Mô Tả</button>
                                                            <button onclick="filterBy('cong_dung')" type="button" tabindex="0" class="dropdown-item">Công Dụng</button>
                                                            <button onclick="filterBy('thanh_phan')" type="button" tabindex="0" class="dropdown-item">Thành Phần</button>
                                                            <button onclick="filterBy('hdsd')" type="button" tabindex="0" class="dropdown-item">HDSD</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="text" id="filter-keyword" name="keyword" placeholder="Từ Khóa" class="form-control" value="{{count($params) && isset($params['keyword']) ? $params['keyword'] : ""}}" autocomplete="off" />
                                                <input type="hidden" id="filter-by" name="filter" value="{{count($params) && isset($params['filter']) ? $params['filter'] : "name"}}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 select2-single-wrapper">
                                            <select name="product_supplier_id" class="form-control select2-single select2-hidden-accessible">
                                                <option value="">Tất Cả Nhà Cung Cấp</option>
                                                @foreach ($suppliers as $ite)
                                                    <option <?php if (count($params) && isset($params['product_supplier_id']) && (int)$params['product_supplier_id'] == $ite->id):?> selected="selected" <?php endif;?> value="{{$ite->id}}">{{$ite->getTitle()}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4 select2-single-wrapper">
                                            <select id="filter-category" name="category" class="form-control"></select>
                                        </div>

                                        <div class="col-md-4 select2-single-wrapper">
                                            <select name="product_brand_id" class="form-control select2-single select2-hidden-accessible">
                                                <option value="">Tất Cả Thương Hiệu</option>
                                                <option value="he_thong">Hệ Thống</option>
                                                @foreach ($brands as $ite)
                                                    <option <?php if (count($params) && isset($params['product_brand_id']) && (int)$params['product_brand_id'] == $ite->id):?> selected="selected" <?php endif;?> value="{{$ite->id}}">{{$ite->getTitle()}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 select2-single-wrapper">
                                            <select name="country" class="form-control select2-single select2-hidden-accessible">
                                                <option value="">Tất Cả Xuất Xứ</option>
                                                @foreach ($apiCore->listCountries() as $k => $v)
                                                    <option <?php if (count($params) && isset($params['country']) && $params['country'] == $k):?>selected="selected"<?php endif;?> value="{{$k}}">{{$v}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <select name="status" class="form-control">
                                                <option value="">Tất Cả Trạng Thái</option>
                                                <option <?php if (count($params) && isset($params['status']) && $params['status'] == 'con_hang'):?>selected="selected"<?php endif;?> value="con_hang">Còn Hàng</option>
                                                <option <?php if (count($params) && isset($params['status']) && $params['status'] == 'het_hang'):?>selected="selected"<?php endif;?> value="het_hang">Hết Hàng</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <select name="active" class="form-control">
                                                <option value="">Tất Cả Trạng Thái</option>
                                                <option <?php if (count($params) && isset($params['active']) && (int)$params['active'] == 1):?>selected="selected"<?php endif;?> value="1">Đang Bán</option>
                                                <option <?php if (count($params) && isset($params['active']) && (int)$params['active'] == 2):?>selected="selected"<?php endif;?> value="2">Tạm Dừng</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <select name="state" class="form-control">
                                                <option value="">Tất Cả Thuộc Tính</option>
                                                <option <?php if (count($params) && isset($params['state']) && $params['state'] == 'is_new'):?>selected="selected"<?php endif;?> value="is_new">SP Mới</option>
                                                <option <?php if (count($params) && isset($params['state']) && $params['state'] == 'is_best_seller'):?>selected="selected"<?php endif;?> value="is_best_seller">SP Bán Chạy</option>
                                                <option <?php if (count($params) && isset($params['state']) && $params['state'] == 'other'):?>selected="selected"<?php endif;?> value="other">Khác</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary btn-sm mb-1">
                                        <i class="fa fa-search fs-14 mr-1"></i>
                                        Tìm
                                    </button>

                                    <input type="hidden" id="search-order" name="order" value="{{count($params) && isset($params['order']) ? $params['order'] : ""}}" />
                                    <input type="hidden" id="search-order-by" name="order-by" value="{{count($params) && isset($params['order-by']) ? $params['order-by'] : ""}}" />
                                </div>
                            </div>
                        </form>
                    </div>


                    @if (count($items))
                        <div class="card-filter margin-bot-20" id="frm-order">
                            <div class="float-right">
                                <div class="float-left margin-right-5">
                                    <select onchange="frmOrder(this)" class="form-control" name="order">
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'newest'):?>selected="selected"<?php endif;?> value="newest">Mới Nhất</option>
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'alphabet'):?>selected="selected"<?php endif;?> value="alphabet">Alphabet</option>
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'price_pay'):?>selected="selected"<?php endif;?> value="price_pay">Giá Bán</option>
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'quantity'):?>selected="selected"<?php endif;?> value="quantity">Số Lượng</option>
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'sold_count'):?>selected="selected"<?php endif;?> value="sold_count">Lượt Mua</option>
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'view_count'):?>selected="selected"<?php endif;?> value="view_count">Lượt Xem</option>
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'love_count'):?>selected="selected"<?php endif;?> value="love_count">Lượt Thích</option>
                                    </select>
                                </div>

                                <div class="float-left">
                                    <select onchange="frmOrderBy(this)" class="form-control" name="orderby">
                                        <option <?php if (count($params) && isset($params['order-by']) && $params['order-by'] == 'desc'):?>selected="selected"<?php endif;?> value="desc">Giảm Dần</option>
                                        <option <?php if (count($params) && isset($params['order-by']) && $params['order-by'] == 'asc'):?>selected="selected"<?php endif;?> value="asc">Tăng Dần</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <strong>{{$pageTitle}}</strong>

                                <div class="c-header-right font-weight-bold">
                                    <span>Tổng Cộng: </span>
                                    <span class="number_format">{{$items->total()}}</span>
                                </div>
                            </div>

                            <div class="card-body">
                                <table class="table table-responsive-sm table-striped">
                                    <thead>
                                    <tr>
                                        <th>sản phẩm</th>
                                        <th>nhà cung cấp</th>
                                        <th>nhóm sp chính</th>
                                        <th>thương hiệu</th>
                                        <th>giá bán</th>
                                        <th>số lượng</th>
                                        <th>mua / xem / thích</th>
                                        <th>trạng thái</th>
                                        <th>thuộc tính</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($items as $product):

                                    $canEditDelete = $viewer->isSupplier() && $viewer->supplier_id == $product->product_supplier_id;
                                    ?>
                                    <tr id="item-{{$product->id}}" data-id="{{$product->id}}">
                                        <td class="break-long-text"><?php echo $product->toHTML(['avatar' => false, 'short' => false, 'href' => true]);?></td>
                                        <td>
                                            <div>{{$product->getSupplier() ? $product->getSupplier()->getTitle() : ''}}</div>
                                        </td>
                                        <td>
                                            <div>{{$product->getCategory() ? $product->getCategory()->getTitle() : ''}}</div>
                                        </td>
                                        <td>
                                            <div>{{$product->getBrand() ? $product->getBrand()->getTitle() : ''}}</div>
                                        </td>
                                        <td>
                                            @if($product->price_pay != $product->price_main)
                                                <div>
                                                    <span class="money_format text-line-through">{{$product->price_main}}</span>
                                                    <span class="currency_format text-line-through">₫</span>
                                                    @if($product->discount)
                                                        <span>(- {{$product->discount}}%)</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <span class="money_format font-weight-bold">{{$product->price_pay}}</span>
                                                    <span class="currency_format font-weight-bold">₫</span>
                                                </div>
                                            @else
                                                <div>
                                                    <span class="money_format font-weight-bold">{{$product->price_pay}}</span>
                                                    <span class="currency_format font-weight-bold">₫</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->unlimited)
                                                <i class="fa fa-infinity"></i>
                                            @else
                                                {{$product->quantity}}
                                            @endif
                                        </td>
                                        <td>
                                            {{$product->sold_count . ' / ' . $product->view_count . ' / ' . $product->love_count}}
                                        </td>
                                        <td>
                                            @if ($viewer->isAllowed("product_edit") || $canEditDelete)
                                                <div class="mb-1">
                                                    @if ($product->active)
                                                        <a class="badge badge-success text-uppercase" onclick="updateStatus(this, 'active', 0)" href="javascript:void(0)">
                                                            <i class="fa fa-check text-white mr-1"></i> đang bán
                                                        </a>
                                                    @else
                                                        <a class="badge badge-warning text-uppercase text-white" onclick="updateStatus(this, 'active', 1)" href="javascript:void(0)">
                                                            <i class="fa fa-ban text-white mr-1"></i> tạm dừng
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="mb-1">
                                                    @if ($product->status == 'con_hang')
                                                        <a class="badge badge-success text-uppercase" onclick="updateStatus(this, 'status', 'het_hang')" href="javascript:void(0)">
                                                            <i class="fa fa-check text-white mr-1"></i> còn hàng
                                                        </a>
                                                    @else
                                                        <a class="badge badge-warning text-uppercase text-white" onclick="updateStatus(this, 'status', 'con_hang')" href="javascript:void(0)">
                                                            <i class="fa fa-ban text-white mr-1"></i> hết hàng
                                                        </a>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="mb-1">
                                                    @if ($product->active)
                                                        <span class="badge badge-success text-uppercase">
                                                        <i class="fa fa-check text-white mr-1"></i> đang bán
                                                    </span>
                                                    @else
                                                        <span class="badge badge-warning text-white text-uppercase">
                                                        <i class="fa fa-ban text-white mr-1"></i> tạm dừng
                                                    </span>
                                                    @endif
                                                </div>
                                                <div class="mb-1">
                                                    @if ($product->status == 'con_hang')
                                                        <span class="badge badge-success text-uppercase">
                                                        <i class="fa fa-check text-white mr-1"></i> còn hàng
                                                    </span>
                                                    @else
                                                        <span class="badge badge-warning text-white text-uppercase">
                                                        <i class="fa fa-ban text-white mr-1"></i> hết hàng
                                                    </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($viewer->isAllowed("product_edit") || $canEditDelete)
                                                <div class="mb-1">
                                                    @if ($product->is_new)
                                                        <a class="badge badge-danger text-uppercase" onclick="updateStatus(this, 'is_new', 0)" href="javascript:void(0)">
                                                            <i class="fa fa-check text-white mr-1"></i> sp mới
                                                        </a>
                                                    @else
                                                        <a class="badge badge-secondary text-uppercase" onclick="updateStatus(this, 'is_new', 1)" href="javascript:void(0)">
                                                            <i class="fa fa-times mr-1"></i> sp mới
                                                        </a>
                                                    @endif
                                                </div>

                                                <div class="mb-1">
                                                    @if ($product->is_best_seller)
                                                        <a class="badge badge-danger text-uppercase" onclick="updateStatus(this, 'is_best_seller', 0)" href="javascript:void(0)">
                                                            <i class="fa fa-check text-white mr-1"></i> sp bán chạy
                                                        </a>
                                                    @else
                                                        <a class="badge badge-secondary text-uppercase" onclick="updateStatus(this, 'is_best_seller', 1)" href="javascript:void(0)">
                                                            <i class="fa fa-times mr-1"></i> sp bán chạy
                                                        </a>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="mb-1">
                                                    @if ($product->is_new)
                                                        <span class="badge badge-danger text-uppercase">
                                                            <i class="fa fa-check text-white mr-1"></i> sp mới
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary text-uppercase">
                                                            <i class="fa fa-times mr-1"></i> sp mới
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="mb-1">
                                                    @if ($product->is_best_seller)
                                                        <span class="badge badge-danger text-uppercase">
                                                            <i class="fa fa-check text-white mr-1"></i> sp bán chạy
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary text-uppercase">
                                                            <i class="fa fa-times mr-1"></i> sp bán chạy
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="align-right">
                                                <button class="btn btn-primary btn-sm mb-1"
                                                        title="Xem" data-original-title="Xem"
                                                        onclick="gotoPage('{{$product->getHref()}}')"
                                                >
                                                    <i class="fa fa-eye"></i>
                                                </button>

                                                @if ($viewer->isAllowed("product_edit") || $canEditDelete)
                                                    <button class="btn btn-info btn-sm mb-1"
                                                            title="Sửa" data-original-title="Sửa"
                                                            onclick="openPage('{{url('admin/product/add?id=' . $product->id)}}')"
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endif

                                                @if ($viewer->isAllowed("product_edit") || $canEditDelete)
                                                    <button class="btn btn-warning btn-sm mb-1"
                                                            title="Đánh Giá" data-original-title="Đánh Giá"
                                                            onclick="openPage('{{url('admin/product/review?id=' . $product->id)}}')"
                                                    >
                                                        <i class="fa fa-star"></i>
                                                    </button>
                                                @endif

                                                @if ($viewer->isAllowed("product_delete") || $canEditDelete)
                                                    <button class="btn btn-danger btn-sm mb-1"
                                                            title="Xóa" data-original-title="Xóa"
                                                            onclick="deleteItem({{$product->id}})"
                                                    >
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="gks_pagination">
                            {{$items->appends(request()->query())->links()}}
                        </div>
                    @else
                        <div class="clearfix mb-4 mt-4">
                            <span class="alert alert-info notfound"></span>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div id="modalImport" class="modal fade bd-example-modal-lg in" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{url('admin/product/import')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title ">nhập từ excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-2">
                                    <a href="{{url('public/import_san_pham.xlsx')}}"
                                       class="form-control font-weight-bold text-uppercase text-danger" download="">Tải về file mẫu</a>
                                </div>
                                <div class="clearfix">
                                    <input type="file" name="file" id="file-upload"
                                           accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                           onchange="kiemTraFileImport(this)">
                                </div>
                                <div class="alert alert-danger mt-2 hidden error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary " data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary ">Xác Nhận</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <a class="hidden" id="import_failed" download href="{{url('admin/product/import-failed')}}"></a>

    <script type="text/javascript" src="{{url('public/js/back_end/products.js')}}"></script>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            @if(!empty($message))
            @if($message == 'ITEM_ADDED')
            showMessage(gks.successADD);
            @elseif($message == 'ITEM_EDITED')
            showMessage(gks.successEDIT);
            @elseif($message == 'ITEM_DELETED')
            showMessage(gks.successDEL);
            @elseif($message == 'ITEM_UPDATED')
            showMessage(gks.successUPDATE);
            @endif
            @endif

            //
            {{--            @if (!empty($message))--}}
            {{--            @if ($message == 'IMPORT_SUCCESS')--}}
            {{--            @if ($importSaved)--}}
            {{--            showMessage('Đã cập nhật thành công {{$importSaved}} hàng số liệu từ file excel!');--}}
            {{--            @else--}}
            {{--            showMessage(gks.successUPDATE);--}}
            {{--            @endif--}}
            {{--            @elseif ($message == 'IMPORT_ERROR')--}}
            {{--            showMessage(gks.saveERR);--}}
            {{--            @endif--}}
            {{--            @endif--}}

            {{--            @if ($importFailed && count($importFailed))--}}
            {{--            jQuery('#import_failed')[0].click();--}}
            {{--            @endif--}}


            //categories
            var data = [
                {
                    id: "0",
                    text: "Tất Cả Nhóm SP",
                    level: 1
                },
                    <?php foreach ($categories as $category):
                    $subs = $category->getSubCategories();
                    ?>
                {
                    id: '{{$category->id}}',
                    text: "> {{$category->title}}",
                    level: 1,
                    <?php if (count($params) && isset($params['category']) && (int)$params['category'] == $category->id):?>
                    selected: 1,
                    <?php endif;?>
                },
                    <?php if (count($subs)):?>
                    <?php foreach ($subs as $sub):
                    $childs = $sub->getSubCategories();
                    ?>
                {
                    id: '{{$sub->id}}',
                    text: "+ {{$sub->title}}",
                    level: 2,
                    <?php if (count($params) && isset($params['category']) && (int)$params['category'] == $sub->id):?>
                    selected: 1,
                    <?php endif;?>
                },
                    <?php if (count($childs)):?>
                    <?php foreach ($childs as $child):?>
                {
                    id: '{{$child->id}}',
                    text: "- {{$child->title}}",
                    level: 3,
                    <?php if (count($params) && isset($params['category']) && (int)$params['category'] == $child->id):?>
                    selected: 1,
                    <?php endif;?>
                },
                <?php endforeach;?>
                <?php endif;?>
                <?php endforeach;?>
                <?php endif;?>
                <?php endforeach;?>
            ];
            function formatResult(node) {
                var $result = $('<span style="padding-left:' + (20 * node.level) + 'px;">' + node.text + '</span>');
                return $result;
            };
            jQuery("#filter-category").select2({
                data: data,
                formatSelection: function (item) {
                    return item.text
                },
                formatResult: function (item) {
                    return item.text
                },
                templateResult: formatResult,
            });
        });

    </script>
@stop
