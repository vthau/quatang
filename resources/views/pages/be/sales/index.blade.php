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
                    <div class="btn-menu">
                        <button class="btn btn-primary btn-sm mb-1" onclick="parent.window.location.href = '{{url('admin/sale/add')}}'" >
                            <i class="fa fa-plus-circle mr-1"></i>
                            Tạo Khuyến Mãi
                        </button>
                    </div>

                    <div class="alert alert-warning">
                        Chỉ có khuyến mãi đang chạy được hiển thị ngoài trang chính
                    </div>

                    <div class="frm-search">
                        <form action="{{url('admin/product-sales')}}" method="get" >
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
                                                                @if ($params['filter'] == 'code')
                                                                    Code KM
                                                                @else
                                                                    Tên KM
                                                                @endif
                                                            @else
                                                                Tên KM
                                                            @endif
                                                        </button>
                                                        <div tabindex="-1" aria-hidden="true" role="menu" class="dropdown-menu" x-placement="top-start">
                                                            <button onclick="filterBy('name')" type="button" tabindex="0" class="dropdown-item">Tên KM</button>
                                                            <button onclick="filterBy('code')" type="button" tabindex="0" class="dropdown-item">Code KM</button>
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

                    @if (count($rows))
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
                                Tổng Cộng: {{$rows->total()}}
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-responsive-sm table-striped">
                                <thead>
                                <tr>
                                    <th>chương trình</th>
                                    <th>code</th>
                                    <th>bắt đầu</th>
                                    <th>kết thúc</th>
                                    <th>tổng đơn hàng sử dụng</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach($rows as $row):

                                ?>
                                    <tr class="row-tr" data-id="{{$row->id}}">
                                        <td><?php echo $row->toHTML(['avatar' => true, 'short' => true, 'href' => true]);?></td>
                                        <td>{{$row->code}}</td>
                                        <td>{{(!empty($row->date_start)) ? date("d/m/Y", strtotime($row->date_start)) : ""}}</td>
                                        <td>{{(!empty($row->date_end)) ? date("d/m/Y", strtotime($row->date_end)) : ""}}</td>
                                        <td>{{$row->view_count}}</td>
                                        <td>
                                            <div class="align-right">
                                                <button class="btn btn-info btn-sm mb-1"
                                                        title="Sửa" data-original-title="Sửa"
                                                        onclick="gotoPage('{{url('admin/sale/add?id=' . $row->id)}}')"
                                                >
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <button class="btn btn-danger btn-sm mb-1"
                                                        title="Xóa" data-original-title="Xóa"
                                                        onclick="deleteItem({{$row->id}})"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>

                            {{ $rows->appends(request()->query())->links() }}
                        </div>
                    </div>
                    @else
                        <div class="alert alert-info">Không tìm thấy dữ liệu phù hợp.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{--modal--}}
    @include('modals.all')

    <script type="text/javascript" src="{{url('public/js/be/product_sales.js')}}"></script>
@stop
