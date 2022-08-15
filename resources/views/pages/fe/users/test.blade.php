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
            'text1' => 'làm bài test',
        ])

        <div class="row">
            <div class="col-md-12 mb__10">
                <form action="{{url('/kh/dt/test/done')}}" method="post" id="frm-test">
                    @csrf
                    <div class="test-wrapper clearfix">
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
                                        <div class="frm-label">điểm cần đạt</div>
                                    </div>
                                    <div class="col-md-8 mb__10">
                                        <div class="text-uppercase">{{$passed . '/' . $item->total_questions}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt__20">
                            <div class="col-md-8 mb__10">
                                <div class="question-panel">
                                    <?php
                                    $arr = [];
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
                                                if ($detail && $detail->test_answer_id) {
                                                    $arr[] = $count;
                                                }
                                            ?>
                                            <div class="col-md-6">
                                                <div class="question-answer-item @if($detail && $detail->test_answer_id == $answer->id) active @endif answer-{{$answer->id}}" onclick="jskhtestanswer({{$count}}, {{$question->id}}, {{$answer->id}})">
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
                                        <button type="button" class="button" onclick="jskhtestnext()">
                                            <i class="fa fa-arrow-right"></i>
                                        </button>
                                    </div>
                                    <div class="question-action-item prev">
                                        <button type="button" class="button" onclick="jskhtestprev()">
                                            <i class="fa fa-arrow-left"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb__10">
                                <div class="question-wrapper">
                                    <?php for($i=1;$i<=$item->total_questions;$i++):?>
                                    <div class="question-title-item question-title-{{$i}}" onclick="jskhtestshow({{$i}})">
                                        <div class="question-number @if(count($arr) && in_array($i, $arr)) active @endif">{{$i}}</div>
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

                                <button type="submit" class="button button_primary text-uppercase button_confirm">kết thúc bài test</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="test-countdown" id="dem_nguoc"></div>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('#dem_nguoc').addClass('hidden');

            @if ($item->times && strtotime($item->created_at) + $item->times * 60 > time())
                demNguoc();
            @endif
        });

        var testConfirm = 0;
        var dongHo;
        var thoiGian = '{{(strtotime($item->created_at) + $item->times * 60) - time()}}';

        function demNguoc() {
            clearInterval(dongHo);
            jQuery('#dem_nguoc').addClass('hidden');
            var time = parseInt(thoiGian); //so phut
            if (time) {
                var deadline = new Date().getTime() + (time * 1000) + 1000;

                // Update the count down every 1 second
                dongHo = setInterval(function() {

                    // Get today's date and time
                    var now = new Date().getTime();

                    // Find the distance between now and the count down date
                    var distance = deadline - now;

                    // Time calculations for days, hours, minutes and seconds
                    // var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    // var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Output the result in an element with id="demo"
                    if (minutes < 10) {
                        minutes = '0' + minutes;
                    }
                    if (seconds < 10) {
                        seconds = '0' + seconds;
                    }

                    document.getElementById("dem_nguoc").innerHTML = minutes + " : " + seconds;

                    // If the count down is over, write some text
                    if (distance <= 0) {
                        clearInterval(dongHo);
                        document.getElementById("dem_nguoc").innerHTML = "00 : 00";
                        testConfirm = 1;
                        jskhtesthh();
                    }

                    jQuery('#dem_nguoc').removeClass('hidden');
                }, 1000);

            }
        }
    </script>
@stop
