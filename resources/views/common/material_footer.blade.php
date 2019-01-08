
<!-- Javascript Libraries -->
<script src="{{ URL::to('vendors/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::to('vendors/input-mask/input-mask.min.js') }}"></script>
<script src="{{ URL::to('vendors/bower_components/flot/jquery.flot.js') }}"></script>
<script src="{{ URL::to('vendors/bower_components/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ URL::to('vendors/bower_components/flot.curvedlines/curvedLines.js') }}"></script>
<script src="{{ URL::to('vendors/sparklines/jquery.sparkline.min.js') }}"></script>
<script src="{{ URL::to('vendors/bower_components/bootstrap-select/dist/js/bootstrap-select.js') }}"></script>
<script src="{{ URL::to('vendors/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js') }}"></script>

<script src="{{ URL::to('vendors/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ URL::to('vendors/bower_components/fullcalendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ URL::to('vendors/bower_components/simpleWeather/jquery.simpleWeather.min.js') }}"></script>
<script src="{{ URL::to('vendors/bower_components/Waves/dist/waves.min.js') }}"></script>
<script src="{{ URL::to('vendors/bootstrap-growl/bootstrap-growl.min.js') }}"></script>
<script src="{{ URL::to('vendors/bower_components/sweetalert/dist/sweetalert.min.js') }}"></script>
<script src="{{ URL::to('vendors/bower_components/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js') }}"></script>

<script src="{{ URL::to('chosen/chosen.jquery.js') }}"></script>

<!-- Placeholder for IE9 -->
<!--[if IE 9 ]>
<script src="{{ URL::to('vendors/bower_components/jquery-placeholder/jquery.placeholder.min.js') }}"></script>
<![endif]-->
<script src="{{ URL::to('js/jquery.datetimepicker.js') }}"></script>
{{--<script src="{{ URL::to('js/jquery.noty.js') }} "></script>--}}
<script type="text/javascript">
    $('a[href="' + window.location.hash + '"]').trigger('click');
    $('input[name="deadline"]').datetimepicker();

//    function runAfterSubmit(response){
//        $(".system-container").html(response);
//    }
</script>
@if(session('notice'))
    <script type="text/javascript">
        noty(<?php echo json_encode(['text'=>'<strong>'.ucwords(session('notice')['class']).'</strong><br/>'.session('notice')['message'],'layout'=>'topRight','type'=>session('notice')['class']]) ?>);
    </script>
    <?php session()->forget('notice'); ?>
@endif
<script src="{{ URL::to('js/app.min.js') }}"></script>
<script src="{{ URL::to('js/local_self.js') }}"></script>
</body>
</html>