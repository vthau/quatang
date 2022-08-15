<?php
$apiCore = new \App\Api\Core();
$viewer = $apiCore->getViewer();
?>

@extends('templates.be.master')

@section('content')

<div class="alert alert-warning">
    <div>Xin chào {{$viewer->getTitle()}}!</div>
</div>

@stop
