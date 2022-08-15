<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();


?>

@extends('templates.be.master')

@section('content')

    <style type="text/css">
        tbody {
            display: block;
            height: 500px;
            overflow: auto;
        }
        thead, tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed; /* even columns width , fix width of table too*/
        }
    </style>

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
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 frm-upload">
                                    <div class="c-account-avatar" style="background-image:url('{{$item->getAvatar()}}')"></div>
                                </div>

                                <div class="col-md-9">
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Sản Phẩm</label>
                                        <div class="col-md-9">
                                            <div>
                                                <a href="{{$item->getHref(true)}}">{{$item->getTitle()}}</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">đánh giá trung bình</label>
                                        <div class="col-md-9">
                                            <div>
                                                <?php
                                                for($i=0;$i<=4;$i++):
                                                ?>
                                                    @if ($item->star_count - $i >= 1)
                                                        <img src="{{url('public/images/star_full.png')}}" />
                                                    @elseif ($item->star_count - $i > 0)
                                                        <img src="{{url('public/images/star_half.png')}}" />
                                                    @else
                                                        <img src="{{url('public/images/star_empty.png')}}" />
                                                    @endif

                                                <?php endfor;?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <div class="alert alert-info">Chỉ tính những bài đánh giá cho phép hiển thị ngoài trang chính</div>
                                        </div>
                                    </div>

                                    @if (count($reviews))
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <div class="sum-stats" style="text-align: right;">
                                                    <div>
                                                        <span>Tổng Cộng: </span>
                                                        <span class="number_format">{{count($reviews)}}</span>
                                                    </div>
                                                </div>

                                                <table class="table table-responsive-sm table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>khách hàng</th>
                                                        <th>đánh giá</th>
                                                        <th>nội dung</th>
                                                        <th>trạng thái</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($reviews as $review):
                                                    $owner = $review->getUser();
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            @if($owner)
                                                                <a href="{{$owner->getHref()}}">{{$owner->href}}</a>
                                                            @else
                                                                <span>{{$review->phone}} - <a href="mailto:{{$review->email}}">{{$review->email}}</a></span>
                                                            @endif
                                                        </td>
                                                        <td>{{$review->star . ' sao'}}</td>
                                                        <td><?php echo nl2br($review->note)?></td>
                                                        <td>
                                                            <select class="form-control" onchange="updateStatus(this, {{$review->id}})">
                                                                <option @if(!$review->active) selected="selected" @endif value="0">Không hiển thị</option>
                                                                <option @if($review->active) selected="selected" @endif value="1">Cho phép hiển thị</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.all')

    <script type="text/javascript" src="{{url('public/js/be/product_review.js')}}"></script>

@stop
