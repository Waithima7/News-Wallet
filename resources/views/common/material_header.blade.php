<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie9"><![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') Code Forest </title>
    <script src="{{ URL::to('vendors/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="http://code.highcharts.com/highcharts.js"></script>
    <link href="{{ URL::to('vendors/bower_components/fullcalendar/dist/fullcalendar.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('vendors/bower_components/animate.css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('chosen/chosen.css') }}" rel="stylesheet">
    <link href="{{ URL::to('vendors/bower_components/sweetalert/dist/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ URL::to('css/ppl.css') }}" rel="stylesheet">
    <link href="{{ URL::to('vendors/bower_components/material-design-iconic-font/dist/css/material-design-iconic-font.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('css/app_1.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('css/app_2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::to('css/local_self.css') }}" rel="stylesheet">
    <link href="{{ URL::to('printjs/src/css/print.css') }}" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/i18n/defaults-*.min.js"></script>--}}
    {{--<link href='{{ URL::to('css/jquery.noty.css') }}' rel='stylesheet'>--}}
    {{--<link href='{{ URL::to('css/noty_theme_default.css') }}' rel='stylesheet'>--}}
    {{--<script src="{{ URL::to('js/highcharts/js/highcharts.js') }}" type="text/javascript"></script>--}}
    {{--<script src="{{ URL::to('js/highcharts/js/highcharts-more.js') }}" type="text/javascript"></script>--}}
    {{--<script src="{{ URL::to('rating/jquery.rating.js') }}"></script>--}}
    {{--<link rel="stylesheet" href="{{ URL::to('css/star-rating.css') }}" media="all" rel="stylesheet" type="text/css"/>--}}
    {{--<script src="{{ URL::to('js/star-rating.js') }}" type="text/javascript"></script>--}}
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

    <link rel="stylesheet" href="{{ URL::to("autocomplete/awesomplete.css") }}" />
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/font-awesome.min.css') }}">
    {{--<script src="{{ URL::to('js/jquery.autocomplete.min.js') }}"></script>--}}
    <script src="{{ URL::to('js/jquery.form.js') }}"></script>
    <link href="{{ URL::to('css/jquery.datetimepicker.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ URL::to('js/jquery.datetimepicker.js') }}"></script>
    <script src="{{ URL::to('js/jquery.history.js') }}"></script>
    {{--<script src="{{ URL::to('tinymce/tinymce.min.js') }}"></script>--}}
    {{--<link href="{{ URL::to('css/range.css') }}" rel="stylesheet">--}}
    {{--<link rel="stylesheet" href="{{ url('css/custom.css') }}">--}}


    <link href="{{ URL::to('css/jquery.datetimepicker.css') }}" rel="stylesheet" type="text/css">

</head>
<body>
@include('common.javascript')