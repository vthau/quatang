<?php
$pageTitle = (isset($page_title)) ? $page_title : "";

$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();
?>

@extends('templates.be.master')

@section('content')

    <div>
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-menu">
                        @if($viewer->isAllowed('product_category_add'))
                        <button class="btn btn-primary btn-sm mb-1" onclick="addItem()">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Tạo Nhóm Sản Phẩm
                        </button>
                        @endif
                    </div>


                    <div class="frm-search">
                        <form action="{{url('admin/product-categories')}}" method="get" >
                            <div class="card">
                                <div class="card-header">
                                    <strong>Tìm Kiếm</strong>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <div class="btn-group">
                                                        <button id="btn-filter" type="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="true" class=" btn btn-info">
                                                            Tên Nhóm
                                                        </button>
                                                    </div>
                                                </div>
                                                <input type="text" id="filter-keyword" name="keyword" placeholder="Từ Khóa" class="form-control" value="{{count($params) && isset($params['keyword']) ? $params['keyword'] : ""}}" autocomplete="off" />
                                            </div>
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
                        <div class="card-filter margin-bot-20">
                            <div class="float-right">
                                <div class="float-left margin-right-5">
                                    <select onchange="frmOrder(this)" class="form-control">
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'newest'):?>selected="selected"<?php endif;?> value="newest">Mới Nhất</option>
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'alphabet'):?>selected="selected"<?php endif;?> value="alphabet">Alphabet</option>
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'view_count'):?>selected="selected"<?php endif;?> value="view_count">Lượt Xem Nhóm Chính</option>
                                    </select>
                                </div>

                                <div class="float-left">
                                    <select onchange="frmOrderBy(this)" class="form-control">
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
                                    Tổng Cộng: {{$items->total()}}
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-responsive-sm table-striped">
                                    <thead>
                                    <tr>
                                        <th>tên nhóm</th>
                                        <th>lượt xem</th>
{{--                                        <th>hàng sắp về</th>--}}
                                        <th></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php foreach($items as $row):
                                    $subs = $row->getSubCategories();
                                    ?>
                                    <tr class="row-tr" data-id="{{$row->id}}">
                                        <td class="row-parent" id="title-{{$row->id}}" data-name="{{$row->title}}" data-banner="{{$row->getBanner('profile')}}" data-banner-mobi="{{$row->getBannerMobi('profile')}}">
                                            @if (count($subs))
                                                <div class="frm-parent">
                                                    <a href="javascript:void(0)" onclick="toggleItem({{$row->id}}, 0)">
                                                        + {{$row->title}}
                                                    </a>
                                                </div>
                                            @else
                                                + {{$row->title}}
                                            @endif
                                        </td>

                                        <td>{{$row->view_count}}</td>

{{--                                        <td>--}}
{{--                                            @if ($row->is_menu)--}}
{{--                                                <img class="cursor-pointer" src="{{url('public/images/icons/ic_tick_red.png')}}"--}}
{{--                                                     @if($viewer->isAllowed('product_category_edit'))--}}
{{--                                                     onclick="updateMenu({{$row->id}}, 'is_menu', 0)"--}}
{{--                                                     @endif--}}
{{--                                                />--}}
{{--                                            @else--}}
{{--                                                <img class="cursor-pointer" src="{{url('public/images/icons/ic_tick_dark.png')}}"--}}
{{--                                                     @if($viewer->isAllowed('product_category_edit'))--}}
{{--                                                     onclick="updateMenu({{$row->id}}, 'is_menu', 1)"--}}
{{--                                                     @endif--}}
{{--                                                />--}}
{{--                                            @endif--}}
{{--                                        </td>--}}

                                        <td>
                                            <div class="align-right">
                                                @if($viewer->isAllowed('product_category_add'))
                                                <button class="btn btn-primary btn-sm mb-1"
                                                        title="Tạo Nhóm Con" data-original-title="Tạo Nhóm Con"
                                                        onclick="addSubItem({{$row->id}})"
                                                >
                                                    <i class="fa fa-plus-circle"></i>
                                                </button>
                                                @endif

                                                @if($viewer->isAllowed('product_category_edit'))
                                                <button class="btn btn-info btn-sm mb-1"
                                                        title="Sửa" data-original-title="Sửa"
                                                        onclick="editItem({{$row->id}})"
                                                >
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                @endif

                                                @if($viewer->isAllowed('product_category_delete'))
                                                <button class="btn btn-danger btn-sm mb-1"
                                                        title="Xóa" data-original-title="Xóa"
                                                        onclick="deleteItem({{$row->id}})"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    @if ($subs && count($subs))
                                        <?php foreach($subs as $sub):
                                        $childs = $sub->getSubCategories();
                                        ?>
                                        <tr class="row-tr sub-{{$row->id}}" data-id="{{$sub->id}}">
                                            <td class="row-parent" id="title-{{$sub->id}}" data-name="{{$sub->title}}" data-banner="{{$sub->getBanner('profile')}}" data-banner-mobi="{{$sub->getBannerMobi('profile')}}">

                                                @if (count($childs))
                                                    <div class="frm-parent" style="padding-left: 50px;">
                                                        <a href="javascript:void(0)" onclick="toggleItem({{$row->id}}, {{$sub->id}})">
                                                            ++ {{$sub->title}}
                                                        </a>
                                                    </div>
                                                @else
                                                    <div style="padding-left: 50px;">++ {{$sub->title}}</div>
                                                @endif
                                            </td>

                                            <td>{{$sub->view_count}}</td>

{{--                                            <td>--}}
{{--                                                @if ($sub->is_menu)--}}
{{--                                                    <img class="cursor-pointer" src="{{url('public/images/icons/ic_tick_red.png')}}"--}}
{{--                                                         @if($viewer->isAllowed('product_category_edit'))--}}
{{--                                                         onclick="updateMenu({{$sub->id}}, 'is_menu', 0)"--}}
{{--                                                         @endif--}}
{{--                                                    />--}}
{{--                                                @else--}}
{{--                                                    <img class="cursor-pointer" src="{{url('public/images/icons/ic_tick_dark.png')}}"--}}
{{--                                                         @if($viewer->isAllowed('product_category_edit'))--}}
{{--                                                         onclick="updateMenu({{$sub->id}}, 'is_menu', 1)"--}}
{{--                                                         @endif--}}
{{--                                                    />--}}
{{--                                                @endif--}}
{{--                                            </td>--}}

                                            <td>
                                                <div class="align-right">
                                                    @if($viewer->isAllowed('product_category_edit'))
                                                    <button class="btn btn-info btn-sm mb-1"
                                                            title="Sửa" data-original-title="Sửa"
                                                            onclick="editSubItem({{$sub->id}}, {{$row->id}})"
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    @endif

                                                    @if($viewer->isAllowed('product_category_delete'))
                                                    <button class="btn btn-danger btn-sm mb-1"
                                                            title="Xóa" data-original-title="Xóa"
                                                            onclick="deleteItem({{$sub->id}})"
                                                    >
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <?php endforeach;?>
                                    @endif

                                    <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
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

    {{--modal--}}
    <div id="modalUpdate"  class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="frm-update-title"></h4>
                </div>
                <div class="modal-body">
                    <form action="{{url('admin/category/save')}}" method="post" enctype="multipart/form-data"
                          onsubmit="return confirmUpdateItem()" id="frm-add">
                        @csrf
                        <div class="form-group">
                            <input name="title" onkeypress="return pressEnter(event)" type="text" id="frm-update-text" autocomplete="off" class="form-control" />
                            <div class="alert alert-danger hidden"></div>
                        </div>

                        <div class="form-group" id="modal-banner">
                            <label>Ảnh Banner (recommended: 1920 x 250)</label>
                            <div>
                                <input name="banner" id="upload-banner" type="file" accept="image/*" />

                                <div class="alert alert-danger hidden mt-3">Vui lòng không upload hình lớn hơn <b class="max-size-text"></b>.</div>
                            </div>
                            <div class="form-group" id="banner-preview" style="margin-top: 10px;">

                            </div>
                        </div>

                        <div class="form-group" id="modal-banner-mobi">
                            <label>Ảnh Banner Mobile (recommended: 360 x 250 - cách lề 2 bên 20-30px)</label>
                            <div>
                                <input name="banner-mobi" id="upload-banner-mobi" type="file" accept="image/*" />

                                <div class="alert alert-danger hidden mt-3">Vui lòng không upload hình lớn hơn <b class="max-size-text"></b>.</div>
                            </div>
                            <div class="form-group" id="banner-preview-mobi" style="margin-top: 10px;">

                            </div>
                        </div>

                        <input type="submit" class="hidden" />

                        <input type="hidden" name="id" id="update-item" />
                        <input type="hidden" name="parent_id" id="parent-item" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                    <button type="button" class="btn btn-primary" onclick="confirmUpdateItem(1)">Có</button>
                </div>
            </div>
        </div>
    </div>
    <div id="modalDelete"  class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Xác Nhận</h4>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc muốn xóa nhóm sản phẩm này không? Vui lòng sửa lại nhóm sản phẩm cho các sản phẩm liên quan nếu bạn đồng ý xóa.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                    <button type="button" class="btn btn-primary" onclick="confirmDeleteItem(1)">Có</button>
                </div>

                <div class="hidden">
                    <input type="hidden" id="delete-item" />
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{url('public/js/be/product_categories.js')}}"></script>
@stop
