@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Unused Domain List </h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success margin-bottom-1 mb-1" href="{{ route('un_used_domain_create') }}" title="Create a domain">Create</i>
            </a>

        </div>
    </div>
</div>



<table class="table table-bordered table-responsive-lg">
    <tr>
        <th>id</th>
        <th>Name</th>
        <th>Hosting</th>
        <th>status</th>
        <th>move status</th>

        <th width="280px">Action</th>
    </tr>
    @foreach ($domains as $domain)
    <tr>
        <td>{{ $domain->id }}</td>
        <td>{{ $domain->name }}</td>
        <td>{{ $domain->hosting }}</td>
        <td>{{ $domain->status }}</td>
        <td>{{ $domain->domain_status }}</td>
        <td>
            <form action="{{ route('un_used_domain_delete') }}" method="POST">
                @csrf
                @method('POST')

              <input type="hidden" name="domain_id" value="{{$domain->id}}"/>
                <a href="{{ route('un_used_domain_delete', $domain->id) }}" type="submit" title="delete" style="border: none; background-color:transparent;">
                    <i class="fa fa-trash-o fa-fw text-danger"></i>

                </a>
            </form>
        </td>
    </tr>
    @endforeach
</table>


@endsection