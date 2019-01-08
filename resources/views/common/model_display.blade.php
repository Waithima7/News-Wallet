<?php $dates = ['created_at','updated_at','date'] ?>
<div class="model-view">
    <table class="table mb-0 table-sm" style="">
        @foreach($keys as $key)
        <tr>
            <tbody>

            <th>{{ strtoupper(str_replace('_',' ',$key)) }}</th>


            <td>
                @if(isset($custom_functions[$key]))
                    {!! $custom_functions[$key]($model) !!}
                @elseif(in_array($key,$dates))
                    {!! $model->$key->toDayDateTimeString() !!}
                @else
                    {!! $model->$key !!}
                @endif
            </td>
            </tbody>
        </tr>
            @endforeach
    </table>
</div>
