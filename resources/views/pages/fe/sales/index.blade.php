@extends('templates.fe.master')

@section('content')

    <?php
    $apiCore = new \App\Api\Core;
    $viewer = $apiCore->getViewer();


    ?>

    <div class="container">
        <ol class="breadcrumb no-hide site-breadcrumb">
            <li>
                <a href="{{url('')}}">Trang chủ</a>
            </li>
            <li class="leaf">
                Khuyến Mãi
            </li>
        </ol>

        <div class="block-deals-of-opt2">
            <div class="block-title ">
                <h2 class="title">Chương Trình Khuyến Mãi</h2>
            </div>

            <div class="block-content listing-sale-wrapper">
                @if (count($items))
                    @foreach ($items as $item)
                    <div class="row listing-sale-item">
                        <div class="col-md-3">
                            <a href="{{$item->getHref(['fe' => true])}}">
                                <div class="sale-img" style="background-image:url('{{$item->getAvatar('normal')}}')"></div>
                            </a>
                        </div>

                        <div class="col-md-9">
                            <div class="sale-title">
                                <a href="{{$item->getHref(['fe' => true])}}">{{$item->getTitle()}}</a>
                            </div>
                            <div class="sale-info">
                                <div class="sale-info-top">
                                    <div class="sale-body">
                                        <?php echo $item->getShortBody(700);?>
                                    </div>
                                </div>
                                <div class="sale-info-bot">
                                    <div class="sale-more">
                                        <button type="button" onclick="parent.window.location.href = '{{$item->getHref(['fe' => true])}}'" class="btn btn-info">
                                            Xem Thêm
                                        </button>
                                    </div>
                                    <div class="sale-date">
                                        {{$apiCore->timeToString($item->created_at)}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="view-more">
                        <div class="more-pagination">
                            {{ $items->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">Đang cập nhật...</div>
                @endif
            </div>
        </div>
    </div>

@stop
