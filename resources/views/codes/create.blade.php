@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('codes.index') }}" title="Go back"> Go back </a>
            </div>
        </div>
    </div>

 
    <form action="{{ route('codes.store') }}" method="POST" >
        @csrf

        <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>name:</strong>
                    <textarea class="form-control" style="height:50px" name="name"
                        placeholder="name"></textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>type:</strong>
                    <textarea class="form-control" style="height:50px" name="type"
                        placeholder="type"></textarea>
                </div>
            </div>
          
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>description:</strong>
                    <textarea class="form-control" style="height:50px" name="description"
                        placeholder="description"></textarea>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>limit:</strong>
                    <input type="number" name="limit" class="form-control" placeholder="limit">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>git_address:</strong>
                    <input type="text" name="git_address" class="form-control" placeholder="git_address">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
@endsection