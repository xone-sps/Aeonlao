@extends('layouts.app')
@php
    $url = route('get.home.posts.single', [$type, $post->id]);
    if ($type === 'institutes') {//dif from ajax
        $image_url = url('/') . $post->image;
        $title = $post->institute_name . ' - ' . $post->short_institute_name . ' | ' . $post_type_name . ' | ' . $s['site_name'];
        $description = $post->title . ' - ' . strip_tags(htmlspecialchars_decode($post->description))  . ' - ' . $post_type_name . ' on ' . $s['site_name'] . ', Latest ' . $post_type_name . ' on ' . $s['site_name'] . ' | ' .  $s['site_name'];
        $post->user = new \stdClass();
        $post->user->name = $post->institute_name . ' - ' . $post->short_institute_name;
    }else{
        $image_url = url('/') . \App\Models\Posts::$uploadPath . $post->image;
        $title = $post->title .' - ' . $post_type_name . ' | ' . $s['site_name'];
        $description = $post->title . ' - ' . strip_tags(htmlspecialchars_decode($post->description))  . ' - ' . $post_type_name . ' on ' . $s['site_name'] . ', Latest ' . $post_type_name . ' on ' . $s['site_name'] . ' | ' .  $s['site_name'];
    }
@endphp

@section('g_description'){!! $description  !!}
@stop
@section('g_keywords'){{$post->title}}, {{ $post_type_name }},  {{ $post_type_name }} on {{ $s['site_name'] }}, Latest {{ $post_type_name }}
@stop
@section('meta_search')
    <link rel="canonical" href="{{ urldecode( $url) }}">
    <meta property="og:url" content="{{ urldecode( $url) }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content="{{ $title }}"/>
    <meta property="og:description" content="{!! $description  !!}"/>
    <meta property="og:image" content="{{$image_url}}"/>
    <meta property="og:site_name" content="{{ $s['site_name'] }}">
    <meta property="og:image:height" content="630">
    <meta property="og:image:width" content="1200">

    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{!! $description  !!}">
    <meta name="twitter:image:src" content="{{$image_url}}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="{{ $s['site_name'] }}">
    <meta name="twitter:creator" content="{{$post->user->name}} - {{ $s['site_name'] }}">
    <meta name="twitter:image" content="{{$image_url}}">
    <meta name="twitter:domain" content="{{ $url }}">

    <meta itemprop="name" content="{{ $title }}">
    <meta itemprop="description" content="{!! $description  !!}">
    <meta itemprop="image" content="{{$image_url}}">

@stop

@section('title'){{ $title }}
@stop
@section('scripts_header')
    <link rel="stylesheet" href="{{url('/')}}/css/general.css{{$s['fresh_version']}}">
    <link rel="stylesheet" href="{{url('/')}}/css/vue-multiselect.min.css">  <!--Multi select-->
    {{--<!--Template CSS-->--}}
    <link rel=stylesheet href="{{url('/')}}/bundles/general/assets/css/bootstrap.min.css">
    <!--====== Animate css ======-->
    <link rel="stylesheet" href="{{url('/')}}/bundles/general/assets/css/animate.css">
    <!--====== Default css ======-->
    <link rel="stylesheet" href="{{url('/')}}/bundles/general/assets/css/default.css">
    <!--====== Style css ======-->
    <link rel="stylesheet" href="{{url('/')}}/bundles/general/assets/css/style.css">
    <!--====== Responsive css ======-->
    <link rel="stylesheet" href="{{url('/')}}/bundles/general/assets/css/responsive.css">
    {{--<!--Template CSS-->--}}
    {{-- @GeneratedResourcesTop--}}
    {{-- @GeneratedResourcesTop--}}
@endsection
@section('scripts_footer')
    @include('main.general.defaultData')
    {{--<!--Template JS-->--}}
    <!--====== Bootstrap js ======-->
    <script type="text/javascript" src="{{url('/')}}/bundles/general/assets/js/bootstrap.min.js"></script>
    <!--====== Map js ======-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDC3Ip9iVC0nIxC6V14CKLQ1HZNF_65qEQ"></script>
    <script type="text/javascript" src="{{url('/')}}/bundles/general/assets/js/map-script.js"></script>
    {{--<!--Template JS-->--}}
    <script>
        var baseRes = "/bundles/general/";
        window.$ = jQuery;
    </script>
    {{-- @GeneratedResourcesBottom--}}
    <script type="text/javascript" src="{{url('/bundles/generated/general')}}/general.7c4feaa419f3ee59ae9d.bundle.js"></script>
    {{-- @GeneratedResourcesBottom--}}
@endsection


