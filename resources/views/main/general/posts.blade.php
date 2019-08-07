@extends('layouts.app')
@section('g_description')Read {{ $post_type_name }} on {{ $s['site_name'] }}, Latest {{ $post_type_name }} on {{ $s['site_name'] }}
@stop
@section('g_keywords'){{ $post_type_name }},  {{ $post_type_name }} on {{ $s['site_name'] }}, Latest {{ $post_type_name }}
@stop
@section('meta_search')

    <link rel="canonical" href="{{ urldecode(url()->full()) }}">
    <meta property="og:url" content="{{ urldecode(url()->full()) }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title"
          content="{{ $post_type_name }} | {{ $s['site_name'] }}"/>
    <meta property="og:description"
          content="Read {{ $post_type_name }} on {{ $s['site_name'] }}, Latest {{ $post_type_name }} on {{ $s['site_name'] }} | {{ $s['site_name'] }}"/>
    <meta property="og:image" content="{{ url('/') }}/assets/images/{{ $s['website_logo']  }}"/>

    <meta property="og:site_name" content="{{ $s['site_name'] }}">
    <meta property="og:image:height" content="630">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:type" content="image/png">

    <meta name="twitter:title"
          content="{{ $post_type_name }} | {{ $s['site_name'] }}">
    <meta name="twitter:description"
          content="Read {{ $post_type_name }} on {{ $s['site_name'] }}, Latest {{ $post_type_name }} on {{ $s['site_name'] }} | {{ $s['site_name'] }}">
    <meta name="twitter:image:src" content="{{ url('/') }}/assets/images/{{ $s['website_logo']  }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="{{ $s['site_name'] }}">
    <meta name="twitter:creator" content="{{ $s['site_name'] }}">
    <meta name="twitter:image" content="{{ url('/') }}/assets/images/{{ $s['website_logo']  }}">
    <meta name="twitter:domain" content="{{ url('/') }}">

    <meta itemprop="name" content="{{ $post_type_name }} | {{ $s['site_name'] }}">
    <meta itemprop="description"
          content="Read {{ $post_type_name }} on {{ $s['site_name'] }}, Latest {{ $post_type_name }} on {{ $s['site_name'] }} | {{ $s['site_name'] }}">
    <meta itemprop="image" content="{{ url('/') }}/assets/images/{{ $s['website_logo']  }}">

@stop

@section('title')Latest {{ $post_type_name }}
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

