@extends('templates.be.master')

@section('content')

    <style type="text/css">
        .frm-search .form-group > div {
            float: left;
            margin-bottom: 20px;
        }
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
                        <button class="btn btn-primary btn-sm mb-1" onclick="parent.window.location.href = '{{url('admin/faq/add')}}'" >
                            <i class="fa fa-plus-circle mr-1"></i>
                            Tạo Câu Hỏi
                        </button>
                    </div>

                    <div class="frm-search">
                        <form action="{{url('admin/faqs')}}" method="get" >
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
                                                                @if ($params['filter'] == 'answer')
                                                                    Trả Lời
                                                                @else
                                                                    Câu Hỏi
                                                                @endif
                                                            @else
                                                                Câu Hỏi
                                                            @endif
                                                        </button>
                                                        <div tabindex="-1" aria-hidden="true" role="menu" class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -173px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                            <button onclick="filterBy('question')" type="button" tabindex="0" class="dropdown-item">Câu Hỏi</button>
                                                            <button onclick="filterBy('answer')" type="button" tabindex="0" class="dropdown-item">Trả Lời</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="text" id="filter-keyword" name="keyword" placeholder="Từ Khóa" class="form-control" value="{{count($params) && isset($params['keyword']) ? $params['keyword'] : ""}}" autocomplete="off" />
                                                <input type="hidden" id="filter-by" name="filter" value="{{count($params) && isset($params['filter']) ? $params['filter'] : "question"}}" />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <select id="filter-active" name="active" class="form-control">
                                                <option value="">Tất Cả Trạng Thái</option>
                                                <option <?php if (count($params) && isset($params['active']) && (int)$params['active'] == 1):?>selected="selected"<?php endif;?> value="1">Cho Xem</option>
                                                <option <?php if (count($params) && isset($params['active']) && (int)$params['active'] == 2):?>selected="selected"<?php endif;?> value="2">Tắt Xem</option>
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

                    @if (count($rows))
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
                                Tổng Cộng: {{$rows->total()}}
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-responsive-sm table-striped">
                                <thead>
                                <tr>
                                    <th>câu hỏi</th>
                                    <th style="width: 50%;">trả lời</th>
                                    <th>trạng thái</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach($rows as $row):

                                ?>
                                    <tr id="item-{{$row->id}}">
                                        <td>{{$row->question}}</td>
                                        <td><?php echo nl2br($row->answer);?></td>

                                        <td>
                                            <select class="form-control" onchange="updateStatus(this, 'active')" data-id="{{$row->id}}">
                                                <option <?php if ($row->active):?>selected="selected"<?php endif;?> value="1">Cho Xem</option>
                                                <option <?php if (!$row->active):?>selected="selected"<?php endif;?> value="0">Tắt Xem</option>
                                            </select>
                                        </td>

                                        <td>
                                            <div class="align-right">
                                                <button class="btn btn-info btn-sm mb-1"
                                                        title="Sửa" data-original-title="Sửa"
                                                        onclick="gotoPage('{{url('admin/faq/add?id=' . $row->id)}}')"
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

    <script type="text/javascript" src="{{url('public/js/be/faqs.js')}}"></script>
@stop
