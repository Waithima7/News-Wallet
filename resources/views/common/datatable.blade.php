<?php
$keys = $fields;
$is_multi = false;
if(!is_integer(array_keys($fields)[0])){
    $is_multi = true;
    $tbl_headers = array_map('ucwords',array_keys($fields));
}else{
    $tbl_headers = array_map('ucwords',$keys);
}
$datatable = [];
$datatable['processing']=true;
$datatable['oLanguage']=[
    'sProcessing'=>'<img  src="'.URL::to("img/ajax-loader.gif").'">',
];
$datatable['serverSide']= true;
$datatable['ajax']= url($url);
if($is_multi){
    $columns = [];
    foreach($fields as $key=>$value){
        if(!in_array($value,['action','status','state']) ){
            $col = [
                'data'=>$key,
                'name'=>$value
            ];
        }else{
            $col = ['data'=>$key,'orderable'=>false,'searchable'=>false];
        }
        $columns[] = $col;

    }
}else{
    foreach($fields as $field){
        if(!in_array($field,['action','status','state']) ){
            $col = [
                'data'=>$field
            ];
        }else{
            $col = ['data'=>$field,'orderable'=>false,'searchable'=>false];
        }
        $columns[] = $col;
    }
}
$datatable['columns'] = $columns;
$tbl_id = "table_".str_random(5);
?>
<table id="{{ $tbl_id }}" class="table" style="border-collapse: collapse;
width: 100%;">
    <thead>
    <tr>
       @foreach($tbl_headers as $header)
               <th>{{ strtoupper(str_replace('_',' ',$header)) }}</th>
       @endforeach
    </tr>
    </thead>
    <tbody>

    </tbody>
</table>
<script type="text/javascript">
    var render_fn = $('#{{ $tbl_id }}').DataTable({!! json_encode($datatable) !!});
</script>