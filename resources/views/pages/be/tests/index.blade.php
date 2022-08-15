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
                        <form action="{{url('admin/tests')}}" method="get" >
                            <div class="card">
                                <div class="card-header">
                                    <strong>Tìm Kiếm</strong>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <select name="user" class="form-control">
                                                <option value="">Tất Cả Đối Tác</option>
                                                @foreach($users as $ite)
                                                <option <?php if (count($params) && isset($params['user']) && (int)$params['user'] == $ite->id):?>selected="selected"<?php endif;?> value="{{$ite->id}}">{{$ite->getTitle()}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <select name="type" class="form-control">
                                                <option value="">Tất Cả Loại Đề</option>
                                                <option <?php if (count($params) && isset($params['type']) && $params['type'] == 'truc_tiep'):?>selected="selected"<?php endif;?> value="truc_tiep">Tư Vấn Trực Tiếp</option>
                                                <option <?php if (count($params) && isset($params['type']) && $params['type'] == 'gian_tiep'):?>selected="selected"<?php endif;?> value="gian_tiep">Tư Vấn Gián Tiếp</option>
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
                                    <th>thời gian</th>
                                    <th>Đối Tác</th>
                                    <th>kết quả</th>
                                    <th>trạng thái</th>
                                    <th>loại đề</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach($items as $item):

                                ?>
                                    <tr id="item-{{$item->id}}">
                                        <td>{{date('d/m/Y H:i:s', strtotime($item->created_at))}}</td>
                                        <td class="text-capitalize">{{$item->getUser()->getTitle()}}</td>
                                        <td>{{$item->total_scores . ' / ' . $item->total_questions}}</td>
                                        <td>{{$item->getStatusText()}}</td>
                                        <td>{{$item->getTypeText()}}</td>
                                        <td>
                                            <div class="align-right">
                                                <button class="btn btn-info btn-sm mb-1"
                                                        title="Xem" data-original-title="Xem"
                                                        onclick="gotoPage('{{$item->getHref()}}')"
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

@stop
