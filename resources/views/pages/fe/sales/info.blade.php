@extends('templates.fe.master')

@section('content')

    <?php
    $apiCore = new \App\Api\Core;
    $viewer = $apiCore->getViewer();

    $apiFE = new \App\Api\FE;

    ?>

    <div class="container">
        <ol class="breadcrumb no-hide site-breadcrumb">
            <li>
                <a href="{{url('')}}">Trang chủ</a>
            </li>
            <li>
                <a href="{{url('/khuyen-mai')}}">Khuyến Mãi</a>
            </li>
            <li class="leaf">
                {{$item->getTitle()}}
            </li>
        </ol>

        <div class="row">
            <div class="col-md-3">
                @include('widgets.product_categories')

                @include('widgets.brand_top')
            </div>

            <div class="col-md-9 listing-sale-info">
                @if ($item->hasBanner())
                <div class="sale-banner">
                    <img src="{{$item->getBanner()}}" />
                </div>
                @endif

                <div class="sale-title">{{$item->getTitle()}}</div>

                <div class="sale-info">
                    @if ($item->hasAvatar())
                        <div class="sale-avatar">
                            <img src="{{$item->getAvatar('normal')}}" />
                        </div>
                    @endif

                    <div class="sale-description">
                        <?php echo $item->mo_ta;?>
                    </div>
                </div>

            </div>
        </div>

    </div>

@stop
