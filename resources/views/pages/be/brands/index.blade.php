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
                        @if ($viewer->isAllowed('product_brand_add'))
                        <button class="btn btn-primary btn-sm mb-1" onclick="addItem()">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Tạo Thương Hiệu
                        </button>
                        @endif
                    </div>

                    <div class="frm-search">
                        <form action="{{url('admin/product-brands')}}" method="get" >
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
                                                            Tên Thương Hiệu
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
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'view_count'):?>selected="selected"<?php endif;?> value="view_count">Lượt Xem</option>
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
                                        <th>ảnh</th>
                                        <th>tên thương hiệu</th>
                                        <th>lượt xem</th>
                                        <th>active</th>
                                        <th></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php foreach($items as $item):

                                    ?>
                                    <tr class="row-tr" data-id="{{$item->id}}">
                                        <td>
                                            <div class="c-div-img" style="background-image:url('{{$item->getAvatar()}}')"></div>
                                        </td>

                                        <td class="row-parent text-capitalize" id="row-name-{{$item->id}}" data-name="{{$item->title}}" data-avatar="{{$item->getAvatar('profile')}}" data-banner="{{$item->getBanner()}}">
                                            {{$item->title}}
                                        </td>

                                        <td>{{$item->view_count}}</td>

                                        <td>
                                            @if ($item->is_menu)
                                                <img class="cursor-pointer" src="{{url('public/images/icons/ic_tick_red.png')}}"
                                                     onclick="updateMenu({{$item->id}}, 'is_menu', 0)"
                                                />
                                            @else
                                                <img class="cursor-pointer" src="{{url('public/images/icons/ic_tick_dark.png')}}"
                                                     onclick="updateMenu({{$item->id}}, 'is_menu', 1)"
                                                />
                                            @endif
                                        </td>

                                        <td>
                                            <div class="align-right">
                                                @if ($viewer->isAllowed('product_brand_edit'))
                                                <button class="btn btn-info btn-sm mb-1"
                                                        title="Sửa" data-original-title="Sửa"
                                                        onclick="editItem({{$item->id}})"
                                                >
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                @endif

                                                @if ($viewer->isAllowed('product_brand_delete'))
                                                <button class="btn btn-danger btn-sm mb-1"
                                                        title="Xóa" data-original-title="Xóa"
                                                        onclick="deleteItem({{$item->id}})"
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
                    <form action="{{url('admin/brand/save')}}" method="post" enctype="multipart/form-data"
                          onsubmit="return confirmUpdateItem()" id="frm-add">
                        @csrf
                        <div class="form-group">
                            <input name="title" onkeypress="return pressEnter(event)" type="text" id="frm-update-text" autocomplete="off" class="form-control" />
                            <div class="alert alert-danger hidden"></div>
                        </div>

                        <div class="form-group" id="modal-avatar">
                            <label>Ảnh Đại Diện (recommended: square 128 x 128)</label>
                            <div>
                                <input name="avatar" id="upload-avatar" type="file" accept="image/*" />

                                <div class="alert alert-danger hidden mt-3">Vui lòng không upload hình lớn hơn <b class="max-size-text"></b>.</div>
                            </div>
                            <div class="form-group" id="avatar-preview" style="margin-top: 10px;">

                            </div>
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

                        <input type="hidden" name="id" id="frm-id" />
                        <input type="submit" class="hidden" />
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                    <button type="button" class="btn btn-primary" onclick="confirmUpdateItem(1)">Có</button>
                </div>

                <div class="hidden">
                    <input type="hidden" id="update-item" />
                    <input type="hidden" id="parent-item" />
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
                    <p>Bạn có chắc muốn xóa thương hiệu này không? Vui lòng sửa lại thương hiệu cho các sản phẩm liên quan nếu bạn đồng ý xóa.</p>
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

    <script type="text/javascript" src="{{url('public/js/be/product_brands.js')}}"></script>
@stop
