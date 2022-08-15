@extends('templates.be.master')

@section('content')

    <style type="text/css">
        .item-notification {
            cursor: pointer;
        }

        .item-notification .c-title-href {
            top: 0;
            font-weight: bold;
            color: #333;
            font-style: italic;
        }

        .margin-left-20 {
            margin-left: 20px;
        }

        .item-notification * {
            text-transform: none !important;
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

                    @if (count($items))
                        <div class="card">
                            <div class="card-header">
                                <strong>{{$pageTitle}}</strong>

                                <div class="c-header-right">
                                    Tổng Cộng: {{$items->total()}}
                                </div>
                            </div>

                            <div class="card-body">
                                <table class="table table-responsive-sm table-striped">
                                    <tbody>
                                    <?php foreach($items as $item):

                                    $subject = $apiCore->getItem($item->subject_type, $item->subject_id);
                                    $object = $apiCore->getItem($item->object_type, $item->object_id);

                                    $href = "";
                                    switch ($item->type) {
                                        case 'review_new':
                                            $href = url('admin/product/review?id=' . $object->id);
                                            break;
                                        case 'contact_new':
                                            $href = url('/admin/contacts');
                                            break;
                                        case 'client_new':
                                            $href = $subject->getHref();
                                            break;
                                        case 'cart_new':
                                            $href = $object->getHref();
                                            break;
                                        case 'product_sale_run':
                                            $href = $object->getHref();
                                            break;
                                    }
                                    ?>
                                        <tr id="item-{{$item->id}}">
                                            <td>
                                                <div class="item-notification" <?php if (!empty($href)):?>onclick="openPage('{{$href}}')"<?php endif;?>>
                                                    <div class="float-right margin-left-20">
                                                        {{$apiCore->timeToString($item->created_at)}}
                                                    </div>
                                                    <div class="overflow-hidden">
                                                        <?php echo $item->getNotification();?>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>

                                {{ $items->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <span class="alert alert-info">Không tìm thấy dữ liệu phù hợp.</span>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{--modal--}}
    @include('modals.all')

    <script type="text/javascript" src="{{url('public/js/be/payments.js')}}"></script>

@stop
