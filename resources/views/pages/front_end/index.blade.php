@extends('templates.front_end.master')

@section('content')

    @include('widgets.front_end.sliders')
    @include('widgets.front_end.categories')
    @include('widgets.front_end.news_and_videos')
    @include('widgets.front_end.new_products')
    @include('widgets.front_end.active_brands')
    @include('widgets.front_end.new_news')

@stop
