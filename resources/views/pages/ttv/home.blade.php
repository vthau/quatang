@extends('templates.ttv.master')

@section('content')

    @include('widgets.ttv.home_sliders')
    @include('widgets.ttv.home_categories')
    @include('widgets.ttv.home_custom')
    @include('widgets.ttv.index_sale')

@stop
