@extends('layouts.app')

@section('title')
    User Dashboard
@endsection

@section('content')
    @include('common.bootstrap_table_ajax',[
    'table_headers'=>["id","title","author","website","description","category","image","action"],
    'data_url'=>'users/links/list',
    'base_tbl'=>'links'
    ])
@endsection