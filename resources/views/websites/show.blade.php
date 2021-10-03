@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>  {{ $website->link }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('websites.index') }}" title="Go back"> Go back </a>
            </div>
        </div>
    </div>

    <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                {{ $website->link }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                {{ $website->status }}
            </div>
        </div>
        
       
    </div>
@endsection