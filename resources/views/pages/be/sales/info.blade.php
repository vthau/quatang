@extends('templates.be.master')

@section('content')

    <style type="text/css">

    </style>

    <?php
    $pageTitle = (isset($page_title)) ? $page_title : "";
    $activePage = (isset($active_page)) ? $active_page : "";

    $apiCore = new \App\Api\Core();

    $viewer = $apiCore->getViewer();
    ?>

    <div>
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>
                                {{$pageTitle}}

                                @if ($item->isDeleted())
                                    <span class="text-uppercase error">( Đã Xóa )</span>
                                @endif
                            </strong>

                            @if (!$item->isDeleted())
                            <div class="c-header-right">
                                <button class="btn btn-info btn-sm mb-1"
                                        title="Sửa" data-original-title="Sửa"
                                        onclick="gotoPage('{{url('admin/sale/add?id=' . $item->id)}}')"
                                >
                                    <i class="fa fa-edit"></i>
                                </button>

                                <button class="btn btn-danger btn-sm mb-1"
                                        title="Xóa" data-original-title="Xóa"
                                        onclick="deleteItem({{$item->id}})"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>

                            </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 frm-upload">
                                    <div class="c-account-avatar" style="background-image:url('{{$item->getAvatar('profile')}}')"></div>

                                    <div class="c-account-banner" style="background-image:url('{{$item->getBanner('normal')}}')"></div>
                                </div>

                                <div class="col-md-9">
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Tên Chương Trình</label>
                                        <div class="col-md-9">
                                            <div>
                                                <a href="{{$item->getHref(true)}}">{{$item->getTitle()}}</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Ngày Bắt Đầu</label>
                                        <div class="col-md-9">
                                            <div>{{!empty($item->date_start) ? date("d/m/Y", strtotime($item->date_start)) : ""}}</div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Ngày Kết Thúc</label>
                                        <div class="col-md-9">
                                            <div>{{!empty($item->date_end) ? date("d/m/Y", strtotime($item->date_end)) : ""}}</div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Trạng Thái</label>
                                        <div class="col-md-9">
                                            @if ($item->isDeleted())
                                                <div class="text-uppercase font-weight-bold">{{$item->getStatus()}}</div>
                                            @else
                                            <div>
                                                <select class="form-control" onchange="updateStatus(this)" data-id="{{$item->id}}">
                                                    <option <?php if ($item->status == "moi_tao"):?>selected="selected"<?php endif;?> value="moi_tao">Mới Tạo</option>
                                                    <option <?php if ($item->status == "dang_chay"):?>selected="selected"<?php endif;?> value="dang_chay">Đang Chạy</option>
                                                    <option <?php if ($item->status == "het_han"):?>selected="selected"<?php endif;?> value="het_han">Hết Hạn</option>
                                                    <option <?php if ($item->status == "tam_dung"):?>selected="selected"<?php endif;?> value="tam_dung">Tạm Dừng</option>
                                                </select>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Áp Dụng Cho</label>
                                        <div class="col-md-9">
                                            <div>
                                                @if (count($toItems))
                                                    <table class="table table-responsive-sm table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <td class="font-weight-bold text-capitalize">sản phẩm</td>
                                                            <td class="font-weight-bold text-capitalize align-right">giảm</td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($toItems as $toItem)
                                                        <tr>
                                                            <td>{{$toItem['item_title']}}</td>
                                                            <td class="align-right">{{$toItem['discount']}}</td>
                                                        </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Mô Tả</label>
                                        <div class="col-md-9">
                                            <div><?php echo $item->mo_ta;?></div>
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

    @include('modals.all')

    <script type="text/javascript" src="{{url('public/js/be/sale_info.js')}}"></script>

@stop
