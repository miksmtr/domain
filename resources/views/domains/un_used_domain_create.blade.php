@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('domains.index') }}" title="Go back"> Go back </a>
            </div>
        </div>
    </div>

  
    <form action="{{ route('un_used_domain_store') }}" method="POST" >
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>name:</strong>
                    <input type="text" name="name" class="form-control" placeholder="name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>hosting:</strong>
                    <textarea class="form-control" style="height:50px" name="hosting"
                        placeholder="hosting"></textarea>
                </div>
            </div>
           

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>movable:</strong>

       <input  min="0" max="1" type="number" name="movable" class="form-control" placeholder="movable" value="0">

                </div>
            </div>
           
         

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
@endsection