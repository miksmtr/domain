@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Codes List </h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success margin-bottom-1 mb-1" href="{{ route('codes.create') }}" title="Create a code">Create</i>
            </a>

        </div>
    </div>
</div>



<table class="table table-bordered table-responsive-lg">
    <tr>
        <th>id</th>
        <th>Name</th>
        <th>Type</th>
        <th>Description</th>
        <th>Limit</th>
        <th>Git Address</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($codes as $code)
    <tr>
        <td>{{ $code->id }}</td>
        <td>{{ $code->name }}</td>
        <td>{{ $code->type }}</td>
        <td>{{ $code->description }}</td>
        <td>{{ $code->limit }}</td>
        <td>{{ $code->git_address }}</td>
        <td>
            <form action="{{ route('codes.destroy', $code->id) }}" method="POST">

                <!--                         <a href="{{ route('codes.show', $code->id) }}" title="show">
                            <i class="fas fa-eye text-success  fa-lg"></i>
                        </a> -->

                <a href="{{ route('codes.edit', $code->id) }}">
                    <i class="fa fa-pencil fa-fw "></i>

                </a>

                @csrf
                @method('DELETE')

                <button type="submit" title="delete" style="border: none; background-color:transparent;">
                    <i class="fa fa-trash-o fa-fw text-danger"></i>

                </button>
            </form>
        </td>
    </tr>
    @endforeach
</table>


@endsection