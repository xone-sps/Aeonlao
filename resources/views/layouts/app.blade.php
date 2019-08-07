<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, user-scalable=0">

    <title>@yield('title') | {{$s['site_name']}}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--CSS--}}
    <link rel="stylesheet" href="{{url('/')}}/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="{{url('/')}}/css/vue-toasted.min.css"><!--Toast-->
    <link rel="stylesheet" href="{{url('/')}}/css/bolloon.css">
    @yield('scripts_header')
    <base href="{{ url('/') }}">
    <meta name="description"
          content="@yield('g_description') {{ $s['site_name'] }} the website for japanese students in laos">
    <meta name="keywords"
          content="@yield('g_keywords'), {{ $s['site_name'] }}, website for japanese students, website students in laos">
    <meta name="author" content="Lao giving">
    <meta property="fb:app_id" content="116465732392262"/>
    <link rel="shortcut icon" href="{{ url('/') }}/assets/images/{{$s['fav_icon'] . $s['fresh_version']}}">
    @yield('meta_search')
    {{--CSS--}}
</head>
<body>
<div id="app">
</div>
<noscript>
    <div id="noscript-warning"><h2 class="title"> Jaol.com works best with JavaScript enabled. </h2></div>
</noscript>
<!-- JavaScripts -->
<script charset="utf-8">
    var settings = {!! json_encode($s) !!};
</script>
<script src="{{url('/')}}/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="{{url('/')}}/js/vue-toasted.min.js"></script><!--Toast-->
@yield('scripts_footer')
</body>
</html>
