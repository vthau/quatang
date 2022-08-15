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
            margin-bottom: 20px;
        }
    </style>

    <div>
        <div class="fade-in">
            <div class="row">
                <div class="col-md-12">
                    <div class="frm-search">
                        <form action="{{url('admin/contacts')}}" method="get" >
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
                                                        <button id="btn-filter" type="button" data-toggle="dropdown" aria-haspopup="false" aria-expanded="true" class="dropdown-toggle btn btn-info">
                                                            @if (count($params) && isset($params['filter']))
                                                                @if ($params['filter'] == 'phone')
                                                                    Điện Thoại
                                                                @elseif ($params['filter'] == 'email')
                                                                    Email
                                                                @elseif ($params['filter'] == 'body')
                                                                    Nội Dung
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
                                                            <button onclick="filterBy('body')" type="button" tabindex="0" class="dropdown-item">Nội Dung</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="text" id="filter-keyword" name="keyword" placeholder="Từ Khóa" class="form-control" value="{{count($params) && isset($params['keyword']) ? $params['keyword'] : ""}}" autocomplete="off" />
                                                <input type="hidden" id="filter-by" name="filter" value="{{count($params) && isset($params['filter']) ? $params['filter'] : "name"}}" />
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
                                    <span>Tổng Cộng: </span>
                                    <span class="number_format">{{$items->total()}}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-responsive-sm table-striped">
                                    <thead>
                                    <tr>
                                        <th>họ tên</th>
                                        <th>điện thoại</th>
                                        <th>email</th>
                                        <th style="width: 50%;">nội dung</th>
                                        <th></th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    <?php foreach($items as $item):

                                    ?>
                                    <tr id="item-{{$item->id}}">
                                        <td class="text-capitalize">{{$item->name}}</td>
                                        <td>
                                            <a href="tel:{{$item->phone}}">{{$item->phone}}</a>
                                        </td>
                                        <td>
                                            <a class="text-lowercase" href="mailto:{{$item->email}}">{{$item->email}}</a>
                                        </td>
                                        <td class="text-capitalize"><?php echo nl2br($item->body);?></td>
                                        <td>
                                            <div class="align-right">
                                                <button class="btn btn-danger btn-sm mb-1"
                                                        title="Xóa" data-original-title="Xóa"
                                                        onclick="deleteItem({{$item->id}})"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>
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

    <script type="text/javascript" src="{{url('public/js/back_end/contacts.js')}}"></script>

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
        });
    </script>
@stop
