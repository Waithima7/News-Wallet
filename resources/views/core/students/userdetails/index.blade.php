@extends('layouts.app')

@section('title')
    StudentDetails
@endsection

@section('content')
    <a href="#studentdetails_modal" class="btn btn-primary" data-toggle="modal">ADD STUDENTDETAILS</a>
    <hr/>
    @include('common.bootstrap_table_ajax',[
    'table_headers'=>["id","student_id","school_name","action"],
    'data_url'=>'students/userdetails/list',
    'base_tbl'=>'studentdetails'
    ])
    @include('common.auto_modal',[
        'modal_id'=>'studentdetails_modal',
        'modal_title'=>'STUDENTDETAILS FORM',
        'modal_content'=>Form::autoForm(\App\Models\Core\StudentDetails::class,"students/userdetails")
    ])
@endsection