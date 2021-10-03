@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('codes.index') }}" title="Go back"> Go back </a>
        </div>
    </div>
</div>



<form action="{{ route('codes.update', $code->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>name:</strong>
                <textarea class="form-control" style="height:50px" name="name" placeholder="name">{{ $code->name }}</textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>type:</strong>
                <textarea class="form-control" style="height:50px" name="type" placeholder="type">{{ $code->type }}</textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>description:</strong>
                <input type="text" name="description" class="form-control" placeholder="{{ $code->description }}" value="{{ $code->description }}">



            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>limit:</strong>
                <input type="number" name="limit" value="{{ $code->limit }}" class="form-control" placeholder="limit">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>git_address:</strong>
                <input type="text" name="git_address" value="{{ $code->git_address }}" class="form-control" placeholder="git_address">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>

</form>
@endsection