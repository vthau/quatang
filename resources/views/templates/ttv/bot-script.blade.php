<?php
$jsVersion = '20220101';
?>

@include('modals.fe')

<script src="{{url('public/libraries/viewer/jquery.magnify.js')}}" type="text/javascript"></script>

<script src="{{url('public/libraries/sticky/jquery.sticky.js')}}" type="text/javascript"></script>

<script src="{{url('public/libraries/uni-carousel/jquery.uni-carousel.js')}}" type="text/javascript"></script>

<script src="{{url('public/js/main.js?v=' . $jsVersion)}}" type="text/javascript"></script>

<script src="{{url('public/js/ttv/fe/auth.js?v=' . $jsVersion)}}" type="text/javascript"></script>
<script src="{{url('public/js/ttv/fe/index.js?v=' . $jsVersion)}}" type="text/javascript"></script>
