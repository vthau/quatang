@extends('templates.be.master')

@section('content')

    <style type="text/css">

    </style>

    <?php
    $pageTitle = (isset($page_title)) ? $page_title : "";
    $activePage = (isset($active_page)) ? $active_page : "";

    $apiCore = new \App\Api\Core();

    $viewer = $apiCore->getViewer();
    $oldPhotos = "";
    ?>

    <div>
        <div class="fade-in">
            <form action="{{url('admin/faq/save')}}" method="post" enctype="multipart/form-data"
                  onsubmit="return submitFrm()" id="frm-add" accept-charset="UTF-8" autocomplete="off"
            >
                <input type="hidden" name="_token" value="{{csrf_token()}}" />

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>{{$pageTitle}}</strong>
                            </div>

                            <div class="card-body">

                                <div class="row" >
                                    <div class="col-md-12" id="req-question">
                                        <div class="form-group">
                                            <label class="required">* Câu Hỏi</label>
                                            <input id="frm-question" value="{{$faq ? $faq->question : ""}}" name="question" type="text" autocomplete="off" class="form-control" />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập câu hỏi.</div>
                                    </div>
                                </div>

                                <div class="row" >
                                    <div class="col-md-12" id="req-answer">
                                        <div class="form-group">
                                            <label class="required">* Trả Lời</label>
                                            <textarea class="form-control" id="frm-answer" name="answer" rows="5">{{$faq ? $faq->answer : ""}}</textarea>
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập trả lời.</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12" id="req-status">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Trạng Thái</label>
                                            <div>
                                                <div class="align-center">
                                                    <label class="float-left" style="margin-right: 5px; width: 20%; position:relative; top: 3px;">Cho Xem</label>
                                                    <label class="c-switch c-switch-label c-switch-pill c-switch-danger float-left">
                                                        <input name="active" class="c-switch-input" type="checkbox" @if ($faq && $faq->active) checked="true" @endif /><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="button" class="btn btn-primary btn-sm mb-1" onclick="submitFrm()" >
                                    <i class="fa fa-check-circle mr-1"></i>
                                    Xác Nhận
                                </button>

                                <input type="submit" class="hidden" />
                                <input type="hidden" id="frm-id" name="item-id" value="{{$faq ? $faq->id : ""}}" />
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="{{url('public/js/be/faq_add.js')}}"></script>

@stop
