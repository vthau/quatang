<input type="hidden" id="_tks" value="{{csrf_token()}}" />
@include ('modals.fe')

<script src="{{url('public/themes/be/css/coreui/vendors/pace-progress/js/pace.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    document.addEventListener('xhr', function () {
        Pace.restart()
    }, true);
</script>

{{--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>--}}
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script src="{{url('public/libraries/currency/simple.money.format.js')}}" type="text/javascript"></script>

<script src="{{url('public/libraries/select2/select2.min.js')}}" type="text/javascript"></script>

<script src="{{url('public/libraries/viewer/jquery.magnify.js')}}" type="text/javascript"></script>

<script src="{{url('public/libraries/sticky/jquery.sticky.js')}}" type="text/javascript"></script>

<script src="{{url('public/libraries/uni-carousel/jquery.uni-carousel.js')}}" type="text/javascript"></script>

<script src="{{url('public/js/fe/main.js')}}" type="text/javascript"></script>

<script src="{{url('public/js/main.js')}}" type="text/javascript"></script>
