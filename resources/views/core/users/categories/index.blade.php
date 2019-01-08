@extends('layouts.app')

@section('title')
    Categories
@endsection

@section('content')
    <a href="#category_modal" class="btn btn-primary" data-toggle="modal">ADD CATEGORY</a>
    <hr/>
    @include('common.bootstrap_table_ajax',[
    'table_headers'=>["id","name","description","action"],
    'data_url'=>'users/categories/list',
    'base_tbl'=>'categories'
    ])
    @include('common.auto_modal',[
        'modal_id'=>'category_modal',
        'modal_title'=>'CATEGORY FORM',
        'modal_content'=>Form::autoForm(\App\Models\Core\Category::class,"users/categories")
    ])
@endsection