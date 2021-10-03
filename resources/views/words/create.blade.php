@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('words.index') }}" title="Go back"> Go back </a>
            </div>
        </div>
    </div>

 
    <form action="{{ route('words.store') }}" method="POST" >
        @csrf

     

    </form>
@endsection