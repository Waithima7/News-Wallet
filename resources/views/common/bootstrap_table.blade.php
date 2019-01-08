@if(count($table_data) != 0)
<?php
if(!isset($table_class))
    $table_class = ['table'];
if(!isset($table_actions))
    $table_actions = [];
if(!isset($status_fields))
    $status_fields = [];
if(!isset($filters))
    $filters = $table_headers;
?>
@if($is_mobile)
<br/>
<hr/>
<div class="row"></div>
@endif
<form class="search-form form-horizontal row" onsubmit="return startBootstrapSearch();" method="get" action="{{ Request::url() }}" role="form" _lpchecked="1">
    <div class="col-md-3">
        <input type="hidden" name="order_by" value="{{ Request::input('order_by') }}">
        <input type="hidden" name="per_page" value="{{ Request::input('per_page') }}">
        <input type="hidden" name="order_method" value="{{ Request::input('order_method') }}">
        <input type="hidden" name="tab" value="{{ Request::input('tab') }}">
        <div class="form-group">
            <label class="col-md-5 control-label">Filter By</label>
            <div class="col-md-7">
                <select onchange="determinFilterInputType();" name="filter_key" class="form-control">
                    @foreach($filters as $header)
                        <?php
                        $key = $header;
                        $head =str_replace('->',' ',$header) ;
                        $head =str_replace('_',' ',$head) ;
                        ?>

                        <option {{ Request::input('filter_key') ==$key ? "selected":"" }} value="{{ $key }}">{{ ucwords($head) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="col-md-5 control-label">Value</label>
            <div class="col-md-7 filter_value_section">
                <input value="{{ Request::input('filter_value') }}" type="text" class="form-control input-sm filter-value" id="" name="filter_value" placeholder="">
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <div class="col-md-7">
                <button type="submit" class="btn btn-primary btn-sm m-t-5 waves-effect">GO</button>
            </div>
        </div>
    </div>
</form>
<div class="bootstrap_table">
    @if(isset($_GET['ta_optimized']))
        @endsection
    @section('ta_optimized')
        @include('common.bootstrap_table_body')
    @endsection
    @section('ta_optimized_footer')
        @else
            @include('common.bootstrap_table_body')
        @endif
        @if($table_data->lastPage() != 1)
    <div class="col-md-4">
        <div class="form-group">
            <label class="col-md-5 control-label">
                Show
                <select onchange="setBootPages(this.value)">
                    <option {{ Request::input('per_page') == 10 ? 'selected':'' }} value="10">10</option>
                    <option {{ Request::input('per_page') == 25 ? 'selected':'' }} value="25">25</option>
                    <option {{ Request::input('per_page') == 50 ? 'selected':'' }} value="50">50</option>
                    <option {{ Request::input('per_page') == 100 ? 'selected':'' }} value="100">100</option>
                    <option {{ Request::input('per_page') == 200 ? 'selected':'' }} value="200">200</option>
                </select>
            </label>
        </div>
    </div>
        @endif
</div>

<script type="text/javascript">
    determinFilterInputType();
    function startBootstrapSearch(){
        var url = jQuery(".search-form").attr('action');
        var data = jQuery('.search-form').serialize();
        var full_url = url+"?"+data+'&ta_optimized=1';
        ajaxLoad(full_url,'bootstrap_table');
        return false;
    }

    function determinFilterInputType(){
        var key = jQuery("select[name='filter_key']").val();
        var value = jQuery(".filter-value").val();
        var status_fields = <?php echo @json_encode($status_fields)  ?>;
        for (field in status_fields){
            if(field == key){
                jQuery(".filter_value_section").html('<select name="filter_value" class="form-control filter-value"></select>');
                var options = status_fields[field];
                for(key in options){
                    if(value == key){
                        jQuery(".filter-value").append('<option selected value="'+key+'">'+options[key]+'</option>');

                    }else{
                        jQuery(".filter-value").append('<option value="'+key+'">'+options[key]+'</option>');
                    }
                }
                return false;
            }
        }
        jQuery(".filter_value_section").html('<input value="'+value+'" type="text" class="form-control filter-value input-sm" name="filter_value">');
        return false;
    }

    function setOrderBy(key){
        jQuery("input[name='order_by']").val(key);
        var order_method = jQuery("input[name='order_method']").val();
        if(order_method == 'desc'){
            jQuery("input[name='order_method']").val('asc');
        }else{
            jQuery("input[name='order_method']").val('desc');
        }
        startBootstrapSearch();
    }

    function setBootPages(page){
        jQuery("input[name='per_page']").val(page);
        startBootstrapSearch();
    }
</script>
<style type="text/css">
    /*.boots-table {*/
    /*.boots-table-layout: fixed;*/
    /*}*/
    /*.boots-table caption {*/
    /*}*/
    /*.boots-table tr {*/
        /*padding: .35em;*/
    /*}*/
    /*.boots-table th,*/
    /*.boots-table td {*/
    /*}*/
    /*.boots-table th {*/
    /*}*/
    /*@media screen and (max-width: 600px) {*/
        /*.boots-table {*/
            /*border: 0;*/
        /*}*/
        /*.boots-table caption {*/
            /*font-size: 1.3em;*/
        /*}*/
        /*.boots-table thead {*/
            /*border: none;*/
            /*clip: rect(0 0 0 0);*/
            /*height: 1px;*/
            /*margin: -1px;*/
            /*overflow: hidden;*/
            /*padding: 0;*/
            /*position: absolute;*/
            /*width: 1px;*/
        /*}*/
        /*.boots-table tr {*/
            /*border-bottom: 3px solid #ddd;*/
            /*display: block;*/
            /*margin-bottom: .625em;*/
        /*}*/
        /*.boots-table td {*/
            /*border-bottom: 1px solid #ddd;*/
            /*display: block;*/
            /*font-size: .8em;*/
            /*text-align: right;*/
        /*}*/
        /*.boots-table td:before {*/
            /*!**/
            /** aria-label has no advantage, it won't be read inside a .boots-table*/
            /*content: attr(aria-label);*/
            /**!*/
            /*content: attr(data-label);*/
            /*float: left;*/
            /*font-weight: bold;*/
            /*text-transform: uppercase;*/
        /*}*/
        /*.boots-table td:last-child {*/
            /*border-bottom: 0;*/
        /*}*/
    /*}*/
</style>
@else
    <br/>

        <div class="alert alert-info">No data to show</div>
@endif