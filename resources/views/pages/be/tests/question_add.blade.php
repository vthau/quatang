<?php
$pageTitle = (isset($page_title)) ? $page_title : "";
$activePage = (isset($active_page)) ? $active_page : "";

$apiCore = new \App\Api\Core();

$viewer = $apiCore->getViewer();
$oldPhotos = "";
?>

@extends('templates.be.master')

@section('content')
    <style type="text/css">
        .fs-10 {
            font-size: 10px;
        }

        .dap_an_dung input {
            position: relative;
            top: 5px;
            width: 17px;
            height: 17px;
            margin-right: 5px;
        }
    </style>

    <div>
        <div class="fade-in">
            <form action="{{url('admin/test/question/save')}}" method="post" enctype="multipart/form-data"
                  id="frm-add" accept-charset="UTF-8" autocomplete="off"
            >
                @csrf

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>{{$pageTitle}}</strong>
                            </div>

                            <div class="card-body">
                                <div class="row" >
                                    <div class="col-md-6" id="req-title">
                                        <div class="form-group">
                                            <label class="frm-label required">* Câu Hỏi</label>
                                            <input required value="{{$item ? $item->title : ""}}" name="title" type="text" autocomplete="off" class="form-control" />
                                        </div>

                                        <div class="form-group alert alert-danger hidden">Hãy nhập câu hỏi.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="frm-label">Loại Câu Hỏi</label>
                                            <select name="type" class="form-control">
                                                <option value="truc_tiep">TV Trực Tiếp</option>
                                                <option value="gian_tiep">TV Gián Tiếp</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                @if ($item)
                                    <div class="row">
                                    <?php
                                    $count = 0;
                                        foreach($item->getAnswers() as $answer):
                                        $count++;
                                    ?>
                                        <div class="col-md-6" id="req-answer-{{$count}}">
                                            <div class="form-group">
                                                <label class="frm-label required">* Đáp Án {{$count}}</label>
                                                <span class="ml-3 text-uppercase fs-10 font-weight-bold dap_an_dung"><input type="radio" name="right" value="{{$answer->id}}" @if($answer->id == $item->answer_id) checked="true" @endif /> Đáp Án Đúng</span>
                                                <input required name="answer_{{$count}}" type="text" autocomplete="off" class="form-control" value="{{$answer->getTitle()}}" />
                                            </div>

                                            <input type="hidden" name="edit_{{$count}}" value="{{$answer->id}}" />
                                            <div class="form-group alert alert-danger hidden">Hãy nhập đáp án.</div>
                                        </div>
                                    <?php endforeach; ?>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-6" id="req-answer-1">
                                            <div class="form-group">
                                                <label class="frm-label required">* Đáp Án 1</label>
                                                <span class="ml-3 text-uppercase fs-10 font-weight-bold dap_an_dung"><input type="radio" name="right" value="1" checked="true" /> Đáp Án Đúng</span>
                                                <input required name="answer_1" type="text" autocomplete="off" class="form-control" />
                                            </div>

                                            <div class="form-group alert alert-danger hidden">Hãy nhập đáp án.</div>
                                        </div>
                                        <div class="col-md-6" id="req-answer-2">
                                            <div class="form-group">
                                                <label class="frm-label required">* Đáp Án 2</label>
                                                <span class="ml-3 text-uppercase fs-10 font-weight-bold dap_an_dung"><input type="radio" name="right" value="2" /> Đáp Án Đúng</span>
                                                <input required name="answer_2" type="text" autocomplete="off" class="form-control" />
                                            </div>

                                            <div class="form-group alert alert-danger hidden">Hãy nhập đáp án.</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6" id="req-answer-3">
                                            <div class="form-group">
                                                <label class="frm-label required">* Đáp Án 3</label>
                                                <span class="ml-3 text-uppercase fs-10 font-weight-bold dap_an_dung"><input type="radio" name="right" value="3" /> Đáp Án Đúng</span>
                                                <input required name="answer_3" type="text" autocomplete="off" class="form-control" />
                                            </div>

                                            <div class="form-group alert alert-danger hidden">Hãy nhập đáp án.</div>
                                        </div>
                                        <div class="col-md-6" id="req-answer-4">
                                            <div class="form-group">
                                                <label class="frm-label required">* Đáp Án 4</label>
                                                <span class="ml-3 text-uppercase fs-10 font-weight-bold dap_an_dung"><input type="radio" name="right" value="4" /> Đáp Án Đúng</span>
                                                <input required name="answer_4" type="text" autocomplete="off" class="form-control" />
                                            </div>

                                            <div class="form-group alert alert-danger hidden">Hãy nhập đáp án.</div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-sm mb-1" >
                                    <i class="fa fa-check-circle mr-1"></i>
                                    Xác Nhận
                                </button>

                                <input type="hidden" id="frm-id" name="item_id" value="{{$item ? $item->id : ""}}" />
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="{{url('public/js/be/test_question_add.js')}}"></script>

@stop
