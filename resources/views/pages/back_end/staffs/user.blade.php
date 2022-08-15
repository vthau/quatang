<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile();

$apiFE = new \App\Api\FE;
$provinces = $apiFE->getProvinces();
$districts = $apiFE->getDistrictsByProvinceId($user->province_id);
$wards = $apiFE->getWardsByDistrictId($user->district_id);

?>

@extends('templates.be.master')

@section('content')
    <style type="text/css">
        #ui-view a.c-href-item {
            position: relative;
            top: 10px;
        }
    </style>

    <div>
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>{{$pageTitle}}</strong>
                        </div>
                        <div class="card-body card-block">
                            <div class="card kero-tab" id="kero-1">
                                <div class="card-header">
                                    <ul class="nav nav-justified">
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('tab_ttin')" class="nav-link tab_ttin active">
                                                <span>thông tin</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('tab_dhang')" class="nav-link tab_dhang">
                                                <span>đơn hàng đã đặt</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a data-toggle="tab" href="javascript:void(0)" onclick="viewContent1('tab_spxem')" class="nav-link tab_spxem">
                                                <span>sản phẩm xem gần nhất</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="tab-pane tab_ttin active" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="text-right">
                                                        @if ($viewer->id == $user->id)
                                                            {{-- tai khoan = sua / doi mat khau / upload avatar --}}
                                                            <button class="btn btn-info btn-sm mb-1"
                                                                    title="Sửa" data-original-title="Sửa"
                                                                    onclick="frmEdit()"
                                                            >
                                                                <i class="fa fa-edit"></i>
                                                            </button>

                                                            <button class="btn btn-warning btn-sm mb-1"
                                                                    title="Đổi Mật Khẩu" data-original-title="Đổi Mật Khẩu"
                                                                    onclick="changePassword({{$user->id}})"
                                                            >
                                                                <i class="fa fa-user-lock text-white"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 frm-upload">
                                                    @if ($viewer->id == $user->id)
                                                        <a href="javascript:void(0)" onclick="uploadAvatar()">
                                                            <form action="{{url('admin/staff/upload-avatar')}}" method="post" >
                                                                @csrf
                                                                <div class="c-account-avatar" style="background-image:url('{{$user->getAvatar('profile')}}')"></div>
                                                                <input name="photos[]" id="select_avatar" type="file" accept="image/*" class="hidden" />
                                                                <input type="hidden" name="item_id" value="{{$user->id}}" />
                                                                <input type="submit" class="hidden" />
                                                            </form>
                                                        </a>
                                                    @else
                                                        <div class="c-account-avatar" style="background-image:url('{{$user->getAvatar('profile')}}')"></div>
                                                    @endif
                                                </div>
                                                <div class="col-md-9">
                                                    <form method="post" action="{{url('admin/staff/update')}}" id="frm-add">
                                                        @csrf
                                                        <div class="form-group row">
                                                            <label class="col-md-3 frm-label">Quyền Truy Cập</label>
                                                            <div class="col-md-9">
                                                                <div class="text-uppercase">
                                                                    @if($user->level_id != 4)
                                                                        {{$user->getLevel() ? $user->getLevel()->getTitle() : ''}}
                                                                    @else
                                                                        khách hàng
                                                                    @endif

                                                                    @if($user->isDeleted())
                                                                        <span class="required">( đã xóa )</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-md-3 frm-label">Họ Tên</label>
                                                            <div class="col-md-9" id="req-name">
                                                                <div class="c-info">{{$user->name}}</div>
                                                                <div class="c-edit hidden">
                                                                    <input required value="{{$user->name}}" name="name" type="text" autocomplete="off" class="form-control" />
                                                                    <div class="alert alert-danger hidden mt-sm-1">Vui lòng nhập họ tên.</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-md-3 frm-label">Email</label>
                                                            <div class="col-md-9" id="req-email">
                                                                <div class="c-info">{{$user->email}}</div>
                                                                <div class="c-edit hidden">
                                                                    <input required value="{{$user->email}}" name="email" type="email" autocomplete="off" class="form-control" />
                                                                    <div class="alert alert-danger hidden mt-sm-1">Vui lòng nhập email hợp lệ.</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-md-3 frm-label">Điện Thoại</label>
                                                            <div class="col-md-9" id="req-phone">
                                                                <div class="c-info">{{$user->phone}}</div>
                                                                <div class="c-edit hidden">
                                                                    <input required value="{{$user->phone}}" name="phone" type="text" autocomplete="off" class="form-control"
                                                                           onkeypress="return isInputPhone(event, this)"
                                                                           oncopy="return false;" oncut="return false;" onpaste="return false;"
                                                                    />
                                                                    <div class="alert alert-danger hidden mt-sm-1">Vui lòng nhập số điện thoại (>= 10 số).</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-md-3 frm-label">Địa Chỉ</label>
                                                            <div class="col-md-9">
                                                                <div class="c-info">{{$user->getFullAddress()}}</div>
                                                                <div class="c-edit hidden">
                                                                    <div class="input" id="frm-address">
                                                                        <input name="address" value="{{$user->address}}" class="form-control" type="text" autocomplete="off" />

                                                                        <div class="mt-3">
                                                                            <select name="province_id" class="form-control" onchange="jscartaddressopts(this, 'district')">
                                                                                <option value="">Hãy chọn tỉnh / thành</option>
                                                                                @foreach($provinces as $ite)
                                                                                    <option @if($user->province_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="mt-3">
                                                                            <select name="district_id" class="form-control" onchange="jscartaddressopts(this, 'ward')">
                                                                                <option value="">Hãy chọn quận / huyện</option>
                                                                                @if (count($districts))
                                                                                    @foreach($districts as $ite)
                                                                                        <option @if($user->district_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>

                                                                        <div class="mt-3">
                                                                            <select name="ward_id" class="form-control">
                                                                                <option value="">Hãy chọn phường / xã</option>
                                                                                @if (count($wards))
                                                                                    @foreach($wards as $ite)
                                                                                        <option @if($user->ward_id == $ite->id) selected="selected" @endif value="{{$ite->id}}">{{$ite->title}}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row c-edit hidden float-right mr-lg-5">
                                                            <button type="submit" class="btn btn-primary btn-sm mb-1">
                                                                <i class="fa fa-check-circle mr-1"></i>
                                                                Xác Nhận
                                                            </button>

                                                            <input type="hidden" name="item_id" value="{{$user->id}}" />
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane tab_dhang" role="tabpanel">
                                            @if (count($carts))
                                                <div class="row">
                                                    <div class="col-md-12 mb-4">
                                                        <div class="clearfix">
                                                            <span class="mr-4">
                                                                <span class="frm-label">Tổng tiền hàng: </span>
                                                                <b class="number_format text-warning font-weight-bold">{{$totalCart}}</b>
                                                                <span class="currency_format text-warning font-weight-bold">₫</span>
                                                            </span>
                                                            <span class="mr-4 hidden">
                                                                <span class="frm-label">Tổng Giảm Giá: </span>
                                                                <b class="number_format text-danger font-weight-bold">{{$totalDiscount}}</b>
                                                                <span class="currency_format text-danger font-weight-bold">₫</span>
                                                            </span>
                                                            <span>
                                                                <span class="frm-label">Tổng thanh toán: </span>
                                                                <b class="number_format text-success font-weight-bold">{{$totalPrice}}</b>
                                                                <span class="currency_format text-success font-weight-bold">₫</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table class="table kero_tables table-hover table-striped table-bordered dataTable dtr-inline">
                                                            <thead>
                                                            <tr>
                                                                <th class="frm-label">stt</th>
                                                                <th class="frm-label">thời gian đặt</th>
                                                                <th class="frm-label">mã đơn hàng</th>
                                                                <th class="align-center frm-label">số lượng SP</th>
                                                                <th class="align-center frm-label">tổng tiền hàng</th>
                                                                <th class="align-center frm-label">giảm giá</th>
                                                                <th class="align-center frm-label">phí giao hàng</th>
                                                                <th class="align-center frm-label">tổng thanh toán</th>
                                                                <th class="align-center frm-label">phương thức</th>
                                                                <th class="align-center frm-label">trạng thái</th>
                                                                <th class="align-center"></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $stt = 0;
                                                            foreach($carts as $cart):
                                                            $stt++;
                                                            ?>
                                                            <tr>
                                                                <td class="text-center">{{$stt}}</td>
                                                                <td class="long_td_sm">{{$apiCore->timeToString($cart->created_at)}}</td>
                                                                <td class="long_td_mid"><?php echo $cart->toHTML(['href' => true]);?></td>
                                                                <td class="align-center long_td_sm">{{$cart->total_quantity}}</td>
                                                                <td class="align-center font-weight-bold text-primary long_td_mid">
                                                                    <span class="number_format">{{$cart->total_cart}}</span>
                                                                    <span class="currency_format">₫</span>
                                                                </td>
                                                                <td class="align-center font-weight-bold long_td_mid">
                                                                    @if ($cart->total_discount > 0)
                                                                        <span class="number_format">{{$cart->total_discount}}</span>
                                                                        <span class="currency_format">₫</span>
                                                                    @endif
                                                                </td>
                                                                <td class="align-center long_td_mid ">
                                                                    <div class="font-weight-bold @if($cart->free_ship) line_through @endif">
                                                                        <span class="number_format">{{$cart->total_ship ? $cart->total_ship : 0}}</span>
                                                                        <span class="currency_format">₫</span>
                                                                    </div>
                                                                    @if($cart->free_ship)
                                                                        <div>
                                                                            <div class="badge badge-danger font-weight-bold text-uppercase">miễn phí ship</div>
                                                                        </div>
                                                                    @endif

                                                                </td>
                                                                <td class="align-center text-danger font-weight-bold long_td_mid">
                                                                    <span class="number_format">{{$cart->total_price}}</span>
                                                                    <span class="currency_format">₫</span>
                                                                </td>
                                                                <td class="align-center frm-label long_td_mid">{{$cart->getPaymentText()}}</td>
                                                                <td class="align-center frm-label long_td_mid text-success">{{$cart->getGhnStatus()}}</td>
                                                                <td class="long_td_mid text-center">
                                                                    <div class="align-center">
                                                                        <button class="btn btn-warning btn-sm mb-1 text-uppercase"
                                                                                onclick="gotoPage('{{url('/dh/pdf/' . $cart->id)}}')"
                                                                        >
                                                                            <i class="fa fa-file-pdf"></i>
                                                                        </button>
                                                                        <button class="btn btn-success btn-sm mb-1 text-uppercase"
                                                                                onclick="gotoPage('{{url('/dh/excel/' . $cart->id)}}')"
                                                                        >
                                                                            <i class="fa fa-file-excel"></i>
                                                                        </button>
                                                                        <button class="btn btn-primary btn-sm mb-1 text-uppercase"
                                                                                onclick="openPage('{{$cart->getHref(true)}}')"
                                                                        >
                                                                            <i class="fa fa-eye"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach;?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-info notfound"></div>
                                            @endif
                                        </div>

                                        <div class="tab-pane tab_spxem" role="tabpanel">
                                            @if (count($views))
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table kero_tables table-hover table-striped table-bordered dataTable dtr-inline">
                                                        <thead>
                                                        <tr>
                                                            <th class="frm-label">stt</th>
                                                            <th class="frm-label">sản phẩm</th>
                                                            <th class="frm-label">giá</th>
                                                            <th class="align-center"></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $stt = 0;
                                                        foreach($views as $item):
                                                        $stt++;
                                                        $product = $apiCore->getItem('product',(int)$item->product_id);
                                                        ?>
                                                        <tr>
                                                            <td class="text-center">{{$stt}}</td>
                                                            <td class="long_td_mid"><?php echo $product->toHTML(['href' => true]);?></td>
                                                            <td class="align-center font-weight-bold text-primary long_td_mid">
                                                                <span class="number_format">{{$product->price_pay}}</span>
                                                                <span class="currency_format">₫</span>
                                                            </td>
                                                            <td class="long_td_mid text-center">
                                                                <div class="align-center">
                                                                    <button class="btn btn-primary btn-sm mb-1 text-uppercase"
                                                                            onclick="openPage('{{$product->getHref(true)}}')"
                                                                    >
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach;?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            @else
                                                <div class="alert alert-info notfound"></div>
                                            @endif
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

    <div id="modal_change_password"  class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Đổi Mật Khẩu</h4>
                </div>
                <form action="{{url('admin/staff/change-password')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-password" id="req-pwd">
                            <input required type="password" name="pwd" autocomplete="new-password" class="form-control text-center" />
                            <div class="alert alert-danger hidden">Hãy nhập mật khẩu hợp lệ.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Không</button>
                        <button type="submit" class="btn btn-primary" >Xác Nhận</button>

                        <input type="hidden" name="item_id" />
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="{{url('public/libraries/upload/jquery.ui.widget.js')}}" type="text/javascript"></script>
    <script src="{{url('public/libraries/upload/jquery.iframe-transport.js')}}" type="text/javascript"></script>
    <script src="{{url('public/libraries/upload/jquery.fileupload.js')}}" type="text/javascript"></script>

    <script type="text/javascript" src="{{url('public/js/back_end/user_info.js')}}"></script>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            @if(!empty($message))
            @if($message == 'ITEM_UPDATED')
            showMessage(gks.successUPDATE);
            @endif
            @endif
        });
    </script>
@stop
