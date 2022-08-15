<input type="hidden" id="_tks" value="{{csrf_token()}}" />

<script src="{{url('public/themes/be/css/coreui/vendors/pace-progress/js/pace.min.js')}}" type="text/javascript"></script>
<script src="{{url('public/themes/be/css/coreui/vendors/coreui/coreui-pro/js/coreui.bundle.js')}}" type="text/javascript"></script>
{{--<script src="{{url('public/themes/be/css/coreui/vendors/coreui/coreui-pro/js/coreui.bundle.min.js')}}" type="text/javascript"></script>--}}
<script type="text/javascript">
    new coreui.AsyncLoad(document.getElementById('ui-view'));
    document.addEventListener('xhr', function () {
        Pace.restart()
    }, true);
    var tooltipEl = document.getElementById('header-tooltip');
    var tootltip = new coreui.Tooltip(tooltipEl);
</script>

<script src="{{url('public/libraries/select2/select2.min.js')}}" type="text/javascript"></script>

<!-- Money parser -->
<script src="{{ asset('public') }}/libraries/currency/simple.money.format.js" type="text/javascript"></script>
<script src="{{ asset('public') }}/libraries/currency/simple.number.format.js" type="text/javascript"></script>

<script src="{{url('public/js/main.js')}}" type="text/javascript"></script>

<script src="{{url('public/js/back_end/1_index.js')}}" type="text/javascript"></script>


