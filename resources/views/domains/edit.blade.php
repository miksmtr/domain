@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('domains.index') }}" title="Go back"> Go back </a>
            </div>
        </div>
    </div>



    <form action="{{ route('domains.update', $domain->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>name:</strong>
                    <input type="text" name="name" value="{{ $domain->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>hosting:</strong>
                    <textarea class="form-control" style="height:50px" name="hosting"
                        placeholder="hosting">{{ $domain->hosting }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>status:</strong>
                    <input type="text" name="status" class="form-control" placeholder="{{ $domain->status }}"
                        value="{{ $domain->status }}">


                        <p>

       // status -1 ise sorun var kodda sorun var. <br>

// status 0 ise çalışıyor <br>

// status 1 ise banlandı <br>

// status 2 ise taşındı. <br>

// status 3 ise banlandıgı hakkında email gönderildi. <br>

                        </p>
                </div>
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
@endsection