@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>websites List </h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success margin-bottom-1 mb-1" href="{{ route('websites.create') }}" title="Create a website">Create</i>
            </a>

        </div>
    </div>
</div>



<table class="table table-bordered table-responsive-lg">
    <tr>
        <th>id</th>
        <th>Link</th>
        <th>Status</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($websites as $website)
    <tr>
        <td>{{ $website->id }}</td>
        <td>{{ $website->link }}</td>
        @if ($website->status === 0)
        <td style="background-color:orange;color:white;">Not started yet</td>
        @elseif($website->status === 1)
        <td style="background-color:green;color:white;">Done</td>
        @elseif($website->status === -1)
        <td style="background-color:red;color:white;">Error</td>
        @elseif($website->status === -2)
        <td style="background-color:pink;color:white;">Couldn't take</td>
        @endif
     
        <td>
            <form action="{{ route('websites.destroy', $website->id) }}" method="POST">


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