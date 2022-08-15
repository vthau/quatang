<?php
$apiCore = new \App\Api\Core;
$viewer = $apiCore->getViewer();

$apiMobile = new \App\Api\Mobile;
$isMobile = $apiMobile->isMobile() ? 1 : 0;

?>

@extends('templates.ttv.master')

@section('content')
    <div id="shopify-section-us_heading" class="shopify-section page_section_heading">
        <div class="page-head tc pr oh page_bg_img page_head_us_heading">
            @include('modals.backdrop')
        </div>
    </div>

    <div class="container mb__50">
        @include('modals.breadcrumb', [
            'text1' => 'kết quả bài test',
        ])

        <div class="row">
            <div class="col-md-12 mb__10">
                <div class="test-wrapper clearfix" id="frm-test">
                    @if (!empty($result))
                        @if ($result == 'passed')
                            <div class="alert alert-success mb__20">
                                <div><i class="fa fa-check text-success mr__5"></i>Chúc mừng bạn đã hoàn tất bài test</div>
                                <div><a href="{{url('tai-khoan?t=gioi_thieu')}}">Click vào đây để quay về trang tài khoản</a></div>
                            </div>
                        @else
                            <div class="alert alert-warning mb__20">
                                <div><i class="fa fa-ban text-warning mr__5"></i>Rất tiếc bạn đã không đủ điều kiện để hoàn thành chứng chỉ</div>
                                <div><a href="{{url('tai-khoan?t=dtkd')}}">Click vào đây để quay về trang tài khoản</a></div>
                            </div>
                        @endif
                    @endif

                    <div class="row">
                        <div class="col-md-2 mb__10">
                            <div class="frm-label">thực hiện</div>
                        </div>
                        <div class="col-md-10 mb__10">
                            <div class="text-uppercase">{{$viewer->getTitle()}}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4 mb__10">
                                    <div class="frm-label">loại test</div>
                                </div>
                                <div class="col-md-8 mb__10">
                                    <div class="text-uppercase">{{$item->getTypeText()}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4 mb__10">
                                    <div class="frm-label">thời gian</div>
                                </div>
                                <div class="col-md-8 mb__10">
                                    @if($item->times)
                                        <div class="">{{$item->times . ' phút'}}</div>
                                    @else
                                        <i class="fa fa-infinity"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4 mb__10">
                                    <div class="frm-label">tổng số câu</div>
                                </div>
                                <div class="col-md-8 mb__10">
                                    <div class="text-uppercase">{{$item->total_questions}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4 mb__10">
                                    <div class="frm-label">điểm</div>
                                </div>
                                <div class="col-md-8 mb__10">
                                    <div class="text-uppercase">{{$item->total_scores}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4 mb__10">
                                    <div class="frm-label">kết quả</div>
                                </div>
                                <div class="col-md-8 mb__10">
                                    <b class="text-uppercase text-danger">{{$item->getStatusText()}}</b>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4 mb__10">
                                    <div class="frm-label">hoàn thành</div>
                                </div>
                                <div class="col-md-8 mb__10">
                                    <div class="text-uppercase">{{date('d/m/Y H:i:s', strtotime($item->time_submit))}}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt__20">
                        <div class="col-md-8 mb__10">
                            <div class="question-panel">
                                <?php
                                $arr = [];
                                $err = [];

                                $count = 0;
                                foreach($item->getDetails() as $detail):
                                $count++;
                                $question = $apiCore->getItem('test_question', $detail->test_question_id);
                                ?>
                                <div class="question-item question-{{$count}} @if($count > 1) hidden @endif">
                                    <div class="question-title">
                                        <div class="text-uppercase">câu hỏi {{$count}}</div>
                                        <div>
                                            {{$question->getTitle()}}
                                        </div>
                                    </div>
                                    <div class="question-answer">
                                        <div class="row">
                                            <?php
                                            $c = 0;
                                            foreach($question->getAnswers() as $answer):
                                            $c++;
                                            if ($c == 1) {
                                                $stt = 'A';
                                            } elseif ($c == 2) {
                                                $stt = 'B';
                                            } elseif ($c == 3) {
                                                $stt = 'C';
                                            } else {
                                                $stt = 'D';
                                            }


                                            $detail = $apiCore->getTestDetail($item->id, $question->id);
                                            $right = false;
                                            if ($detail->test_answer_id == $question->answer_id && $detail->test_answer_id == $answer->id) {
                                                $right = true;
                                            }

                                            if ($right) {
                                                $arr[] = $count;
                                            } else {
                                                $err[] = $count;
                                            }
                                            ?>
                                            <div class="col-md-6">
                                                <div class="question-answer-item @if($right) active @else @if($detail->test_answer_id == $answer->id) inactive @elseif($answer->id == $question->answer_id) active @endif @endif answer-{{$answer->id}}">
                                                    <div class="question-answer-content">{{$stt . '. ' . $answer->getTitle()}}</div>
                                                </div>
                                            </div>
                                            <?php endforeach;?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach;?>
                            </div>
                            <div class="question-action">
                                <div class="question-action-item next">
                                    <button class="button" onclick="jskhtestnext()">
                                        <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                                <div class="question-action-item prev">
                                    <button class="button" onclick="jskhtestprev()">
                                        <i class="fa fa-arrow-left"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb__10">
                            <div class="question-wrapper">
                                <?php for($i=1;$i<=$item->total_questions;$i++):?>
                                <div class="question-title-item question-title-{{$i}}" onclick="jskhtestshow({{$i}})">
                                    <div class="question-number @if(count($arr) && in_array($i, $arr)) active @elseif(count($err) && in_array($i, $err)) inactive @endif">{{$i}}</div>
                                </div>
                                <?php endfor;?>
                            </div>
                        </div>
                    </div>

                    <div class="row mt__20 mb__60">
                        <div class="col-md-12 text-center">
                            <input type="hidden" name="cur_question" value="1" />
                            <input type="hidden" name="max_question" value="{{$item->total_questions}}" />
                            <input type="hidden" name="cur_test" value="{{$item->id}}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
