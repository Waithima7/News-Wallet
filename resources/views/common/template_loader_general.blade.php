<?php $t_id = str_random(10) ?>
    <div id="{{ $t_id }}">
        <img src="{{ url("img/ajax-loader.gif") }}">
    </div>
<script type="text/javascript">
    loadGeneralTemplate('{{ $t_fn }}',{{ @json_encode(@$t_data) }},'{{ $t_id }}');
</script>