@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('websites.index') }}" title="Go back"> Go back </a>
            </div>
        </div>
    </div>



    <form action="{{ route('websites.update', $website->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
       
        <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>link:</strong>
                    <textarea class="form-control" style="height:50px" name="link"
                        placeholder="link">{{ $website->link }}</textarea>
                </div>
            </div>
       
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>status:</strong>
                    <input type="number" name="status" class="form-control" placeholder="{{ $website->status }}"
                        value="{{ $website->status }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
@endsection