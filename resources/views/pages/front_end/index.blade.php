@extends('templates.front_end.master')

@section('content')
    @foreach ($elements as $element)
        @if ($element->display == 1)
            @include('widgets.front_end.'.$element->key)
        @endif
    @endforeach
@stop
