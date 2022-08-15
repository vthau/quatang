<?php
$pageTitle = (isset($page_title)) ? $page_title : "";

$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();
?>

@extends('templates.be.master')

@section('content')

    <style type="text/css">
        .frm-search .form-group > div {
            float: left;
        }

        .c-title-href {
            top: 0 !important;
        }
    </style>

    <div>
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-menu">
                        @if ($viewer->isAllowed("partner_add"))
                        <button class="btn btn-primary btn-sm mb-1" onclick="parent.window.location.href = '{{url('admin/partner/add')}}'" >
                            <i class="fa fa-plus-circle mr-1 mb-1"></i>
                            Tạo Đối Tác
                        </button>
                        @endif
                    </div>

                    <div class="frm-search">
                        <form action="{{url('admin/partners')}}" method="get" >
                            <div class="card">
                                <div class="card-header">
                                    <strong>Tìm Kiếm</strong>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <div class="input-group-btn">
                                                    <div class="btn-group">
                                                        <button id="btn-filter" type="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="true" class="dropdown-toggle btn btn-info">
                                                            @if (count($params) && isset($params['filter']))
                                                                @if ($params['filter'] == 'phone')
                                                                    Điện Thoại
                                                                @elseif ($params['filter'] == 'email')
                                                                    Email
                                                                @elseif ($params['filter'] == 'ref_code')
                                                                    Mã đối tác
                                                                @else
                                                                    Họ Tên
                                                                @endif
                                                            @else
                                                                Họ Tên
                                                            @endif
                                                        </button>
                                                        <div tabindex="-1" aria-hidden="true" role="menu" class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -173px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                            <button onclick="filterBy('name')" type="button" tabindex="0" class="dropdown-item">Họ Tên</button>
                                                            <button onclick="filterBy('phone')" type="button" tabindex="0" class="dropdown-item">Điện Thoại</button>
                                                            <button onclick="filterBy('email')" type="button" tabindex="0" class="dropdown-item">Email</button>
                                                            <button onclick="filterBy('ref_code')" type="button" tabindex="0" class="dropdown-item">Mã đối tác</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="text" id="filter-keyword" name="keyword" placeholder="Từ Khóa" class="form-control" value="{{count($params) && isset($params['keyword']) ? $params['keyword'] : ""}}" autocomplete="off" />
                                                <input type="hidden" id="filter-by" name="filter" value="{{count($params) && isset($params['filter']) ? $params['filter'] : "name"}}" />
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <select name="dtdv" class="form-control">
                                                <option value="">Tất Cả Đơn Vị</option>
                                                <option <?php if (count($params) && isset($params['dtdv']) && $params['dtdv'] == 'ca_nhan'):?>selected="selected"<?php endif;?> value="ca_nhan">Cá Nhân</option>
                                                <option <?php if (count($params) && isset($params['dtdv']) && $params['dtdv'] == 'to_chuc'):?>selected="selected"<?php endif;?> value="to_chuc">Tổ Chức</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <select name="dtdb" class="form-control">
                                                <option value="">Tất Cả Đối Tác</option>
                                                <option <?php if (count($params) && isset($params['dtdb']) && (int)$params['dtdb'] == 4):?>selected="selected"<?php endif;?> value="4">Chuyên Gia Đặc Biệt</option>
                                                <option <?php if (count($params) && isset($params['dtdb']) && (int)$params['dtdb'] == 1):?>selected="selected"<?php endif;?> value="1">Đối Tác Đặc Biệt</option>
                                                <option <?php if (count($params) && isset($params['dtdb']) && (int)$params['dtdb'] == 2):?>selected="selected"<?php endif;?> value="2">Đối Tác Là Chuyên Gia</option>
                                                <option <?php if (count($params) && isset($params['dtdb']) && (int)$params['dtdb'] == 3):?>selected="selected"<?php endif;?> value="3">Đối Tác Là Thành Viên</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <select name="ngt" class="form-control">
                                                <option value="">Tất Cả Người Giới Thiệu</option>
                                                @if(count($ngts))
                                                    @foreach($ngts as $ite)
                                                        <option <?php if (count($params) && isset($params['ngt']) && (int)$params['ngt'] == $ite->id):?>selected="selected"<?php endif;?> value="{{$ite->id}}">{{$ite->ref_code . ' - ' . $ite->getTitle()}}</option>
                                                    @endforeach
                                                @endif
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
                        <div class="card-filter margin-bot-20">
                            <div class="float-right">
                                <div class="float-left margin-right-5">
                                    <select onchange="frmOrder(this)" class="form-control">
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'newest'):?>selected="selected"<?php endif;?> value="newest">Mới Nhất</option>
                                        <option <?php if (count($params) && isset($params['order']) && $params['order'] == 'alphabet'):?>selected="selected"<?php endif;?> value="alphabet">Alphabet</option>
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
                                        <th>Đối Tác</th>
                                        <th>người giới thiệu</th>
                                        <th>liên hệ</th>
                                        <th>đơn vị</th>
                                        <th></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php foreach($items as $user):

                                    ?>
                                    <tr id="item-{{$user->id}}">
                                        <td>
                                            <div class="mb-1">
                                                @if(!empty($user->doiTacLoai()))
                                                    <b class="text-uppercase fs-11">{{$user->doiTacLoai()}}</b>
                                                @endif
                                            </div>
                                            <div class="overflow-hidden">
                                                <?php echo $user->ref_code . ' - ' . $user->toHTML(['avatar' => false]);?>
                                            </div>
                                        </td>
                                        <td>
                                            @if($viewer->isAllowed('partner_update_refer'))
                                                <div class="float-left mr-3">
                                                    <button onclick="updateRef({{$user->id}})" class="btn btn-success btn-sm" type="button">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </div>
                                            @endif

                                            <div class="float-left" id="ref_{{$user->id}}" data-ref="{{$user->ref_id}}">
                                                @if($user->doiTacCha())
                                                    <?php echo $user->doiTacCha()->ref_code . ' - ' . $user->doiTacCha()->toHTML(['avatar' => false]);?>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <b>ĐT:</b> <a href="tel:{{$user->phone}}">{{$user->phone}}</a>
                                            </div>
                                            <div>
                                                <a class="text-lowercase" href="mailto:{{$user->email}}">{{$user->email}}</a>
                                            </div>
                                        </td>
                                        <td>
                                            @if($viewer->isAllowed('partner_edit'))
                                                <select class="form-control" onchange="updateDonVi(this, {{$user->id}})">
                                                    <option @if($user->don_vi == 'ca_nhan') selected="selected" @endif value="ca_nhan">Cá Nhân</option>
                                                    <option @if($user->don_vi == 'to_chuc') selected="selected" @endif value="to_chuc">Tổ Chức</option>
                                                </select>
                                            @else
                                                <div class="text-uppercase fs-13">{{$user->doiTacDonVi()}}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="align-right">
                                                <button class="btn btn-primary btn-sm mb-1"
                                                        title="Xem" data-original-title="Xem"
                                                        onclick="gotoPage('{{$user->getHref()}}')"
                                                >
                                                    <i class="fa fa-eye"></i>
                                                </button>

                                                @if ($viewer->isAllowed("partner_edit"))
                                                    <button class="btn btn-info btn-sm mb-1"
                                                            title="Sửa" data-original-title="Sửa"
                                                            onclick="openPage('{{url('admin/partner/add?id=' . $user->id)}}')"
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endif
                                                @if ($viewer->isAllowed("partner_reset_password"))
                                                    <button class="btn btn-secondary btn-sm mb-1"
                                                            title="Reset Mật Khẩu" data-original-title="Reset Mật Khẩu"
                                                            onclick="changePassword({{$user->id}})"
                                                    >
                                                        <i class="fa fa-user-lock"></i>
                                                    </button>
                                                @endif
                                                @if ($viewer->isAllowed("partner_delete"))
                                                    <button class="btn btn-danger btn-sm mb-1"
                                                            title="Xóa" data-original-title="Xóa"
                                                            onclick="deleteItem({{$user->id}})"
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

    {{--modal--}}
    @include('modals.all')

    <div id="modal-ngt" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thay đổi người giới thiệu</h4>
                </div>
                <div class="modal-body">
                    <select class="form-control" name="ref_id">
                        <option value="">Không Có Người Giới Thiệu</option>
                        @if (count($ngts))
                            @foreach($ngts as $ite)
                                <option value="{{$ite->id}}">{{$ite->ref_code . ' - ' . $ite->getTitle()}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                    <button type="button" class="btn btn-primary" onclick="updateRefConfirm()">Xác Nhận</button>

                    <input type="hidden" name="item_id">
                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{url('public/js/be/clients.js')}}"></script>
@stop
