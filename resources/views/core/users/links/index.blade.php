@extends('layouts.app')

@section('title')
    Links
@endsection

@section('content')
    <a href="#link_modal" class="btn btn-primary" data-toggle="modal">ADD LINK</a>
    <hr/>
    @include('common.bootstrap_table_ajax',[
    'table_headers'=>["id","title","author","website","description","category","image","action"],
    'data_url'=>'users/links/list',
    'base_tbl'=>'links'
    ])
    @include('common.auto_modal',[
        'modal_id'=>'link_modal',
        'modal_title'=>'LINK FORM',
        'modal_content'=>Form::autoForm(\App\Models\Core\Link::class,"users/links")
    ])
@endsection