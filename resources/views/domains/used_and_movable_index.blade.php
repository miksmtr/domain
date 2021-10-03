@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Unused Domain List </h2>
        </div>
       
    </div>
</div>



<table class="table table-bordered table-responsive-lg">
    <tr>
        <th>id</th>
        <th>Name</th>
        <th>status</th>
        <th>move status</th>

    </tr>
    @foreach ($domains as $domain)
    <tr>
        <td>{{ $domain->id }}</td>
        <td>{{ $domain->name }}</td>
        <td>{{ $domain->status }}</td>
        <td>{{ $domain->domain_status }}</td>
    </tr>
    @endforeach
</table>


@endsection