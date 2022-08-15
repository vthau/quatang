@extends('templates.be.master')

@section('content')

    <style type="text/css">
        .title-5 {
            text-transform: uppercase;
            font-weight: bold;
        }
        .description .badge {
            padding: 10px;
            font-size: 16px;
        }
    </style>

    <?php
    $pageTitle = (isset($page_title)) ? $page_title : "";
    $activePage = (isset($active_page)) ? $active_page : "";

    $apiCore = new \App\Api\Core();

    $viewer = $apiCore->getViewer();
    ?>

    <div class="manage-levels-wrapper">
        <div class="row">
            <div class="col-md-12">

                <h3 class="title-5 m-b-35">{{$pageTitle}}</h3>

                <div class="description">
                    <span class="badge badge-danger">
                        Trang không tồn tại hoặc bạn không có quyền truy cập trang này.
                    </span>
                </div>
            </div>
        </div>
    </div>

@stop
