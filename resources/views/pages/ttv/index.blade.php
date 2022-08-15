@extends('templates.ttv.master')

@section('content')

    @include('widgets.ttv.index_new')
    @include('widgets.ttv.index_hot')
    @include('widgets.ttv.index_new_blogs')
    @include('widgets.ttv.index_hot_categories')
    @include('widgets.ttv.index_sale')
    @include('widgets.ttv.index_best')
    @include('widgets.ttv.index_hot_brands')
    @include('widgets.ttv.index_info')

@stop
