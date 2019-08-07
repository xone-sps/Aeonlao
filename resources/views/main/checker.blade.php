@extends('layouts.app')
@section('title', 'Home')
@section('scripts_header')
    <link rel="stylesheet" href="{{url('/')}}/css/bulma.css">
    <link rel="stylesheet" href="{{url('/')}}/css/admin.css{{$s["fresh_version"]}}">
    <link rel="stylesheet" href="{{url('/')}}/css/vue-datetime.min.css">
    <link rel="stylesheet" href="{{url('/')}}/css/vue-multiselect.min.css">  <!--Multi select-->
@endsection
@section('scripts_footer')
    <script type="text/javascript">
        var baseUrl = '{{ url('/') }}';
        var baseRes = "/bundles/checker/";
        var pathPrefix = '/';
    </script>
    <script src="{{ asset('/js') }}/tiny/jquery.tinymce.min.js" type="text/javascript"></script>
    <script src="{{ asset('/js') }}/tiny/tinymce.min.js" type="text/javascript"></script>
    <script src="{{ asset('/js') }}/tiny/tinymce.setting.js{{$s["fresh_version"]}}" type="text/javascript"></script>
    <script src="{{ asset('/js') }}/luxon/luxon.min.js" type="text/javascript"></script>
    <script src="{{ asset('/js') }}/vue-datetime.min.js{{$s["fresh_version"]}}" type="text/javascript"></script>
    <script type="text/javascript"
            src="{{url('/bundles/generated/checker')}}/checker.a76cbb2018aa6b7dde0b.bundle.js"></script>
@endsection
