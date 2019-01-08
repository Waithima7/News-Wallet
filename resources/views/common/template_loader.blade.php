<div class="row"></div>
<div class="section">
    <h3 class="heading">{!! $t_title !!}</h3>
    <div class="sectionContent" id="{{ $t_id }}">
        <img src="{{ url("img/ajax-loader.gif") }}">
    </div>
</div>
<script type="text/javascript">
    loadTemplate('{{ $t_fn }}',{{ json_encode($t_data) }},'{{ $t_id }}');
</script>