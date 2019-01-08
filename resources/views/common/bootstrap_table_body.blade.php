@if($is_mobile)
    <div class="row"></div>

        @foreach($table_data as $row)            <div class="section" style="padding: 0px;!important;">
            @if(isset($row['name']))
                <h3 class="section-title">{{ $row['name'] }}</h3>
                <?php unset($table_headers['name']) ?>
                @endif
                <table class="table table-bordered table-condensed">

                    @foreach($table_headers as $header)
                        <tr>
                            <?php
                                if($header == 'name')
                                    continue;
                            $head =str_replace('->',' ',$header) ;
                            $head =str_replace('_',' ',$head) ;
                            ?>
                            <th>{{ ucwords($head) }}</th>
                            <td data-label="{{ strtoupper($head) }}">
                                @if(in_array($header,['address','message']))
                                    {!! $row->$header !!}
                                @elseif(isset($custom_functions[$header]))
                                    {!! $custom_functions[$header]($row) !!}
                                @elseif(isset($status_fields[$header]))
                                    @if(isset($status_fields[$header][$row->$header]))
                                        {!! $status_fields[$header][$row->$header] !!}
                                    @else
                                        {!! $status_fields[$header]['-1'] !!}
                                    @endif
                                @else
                                    {{ $row->$header }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if(count($table_actions)>0)
                        <tr>
                            <th>Action</th>
                            <td>
                                @foreach($table_actions as $action)
                                    <?php
                                    $btn_class = 'info';
                                    if(in_array($action,['edit','update','user']))
                                        $btn_class = 'primary';
                                    if(in_array($action,['delete']))
                                        $btn_class = 'danger';

                                    ?>
                                    <button onclick="{{ camel_case('do'.ucwords($action)) }}({{ $row }})" class="btn btn-xs btn-{{ $btn_class }}">{{ ucwords(str_replace('_',' ',$action)) }}</button>
                                @endforeach
                            </td>
                        </tr>
                    @endif
                </table>

            </div>
        @endforeach
    @else
<table class="{{ implode(' ',$table_class) }} boots-table main_search_table">
    <thead>
    <tr>
        @foreach($table_headers as $header)
            <?php
            $h_key = $header;
            $head =str_replace('->',' ',$header) ;
            $head =str_replace('_',' ',$head) ;
            ?>
            <th scope="col" onclick="setOrderBy('{{ $h_key }}');" style="cursor: pointer;">
                <span>{{ ucwords($head) }}</span>
                @if(Request::input('order_by') == $h_key)
                    <i class=" zmdi zmdi-sort-amount-{{ Request::input('order_method') }}"></i>
                @endif
            </th>
        @endforeach
        @if(count($table_actions)>0)
            <th>&nbsp;</th>
        @endif
    </tr>
    </thead>
    <tbody class="main_table_bdy">
    @foreach($table_data as $row)
        @foreach($table_headers as $header)
            <?php
            $head =str_replace('->',' ',$header) ;
            $head =str_replace('_',' ',$head) ;
            ?>
            <td data-label="{{ strtoupper($head) }}">
                @if(in_array($header,['address','message']))
                    {!! $row->$header !!}

                @elseif(isset($status_fields[$header]))
                    @if(isset($status_fields[$header][$row->$header]))
                        {!! $status_fields[$header][$row->$header] !!}
                    @else
                        {!! $status_fields[$header]['-1'] !!}
                    @endif
                @elseif(isset($custom_functions[$header]))
                    {!! $custom_functions[$header]($row) !!}
                @else
                    {{ $row->$header }}
                @endif
            </td>
        @endforeach
        @if(count($table_actions)>0)
            <td>
                @foreach($table_actions as $action)
                    <?php
                    $btn_class = 'info';
                    if(in_array($action,['edit','update']))
                        $btn_class = 'primary';
                    if(in_array($action,['delete']))
                        $btn_class = 'danger';

                    ?>
                    <button onclick="{{ camel_case('do'.ucwords($action)) }}({{ $row }})" class="btn btn-xs btn-{{ $btn_class }}">{{ ucwords(str_replace('_',' ',$action)) }}</button>
                @endforeach
            </td>
            @endif
            </tr>
            @endforeach
    </tbody>
</table>
@endif
{{ $table_data->appends(Request::all())->links() }}