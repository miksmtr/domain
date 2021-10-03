@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Content List </h2>
        </div>
    </div>
</div>



<table class="table table-bordered table-responsive-lg">
    <tr>
        <th>id</th>
        <th>First Title</th>
        <th>Last Title</th>
        <th>Status</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($contents as $content)
    <tr>
        <td>{{ $content->id }}</td>
        <td>{{ $content->first_title }}</td>
        <td>{{ $content->last_title }}</td>
        @if ($content->status === 0)
        <td style="background-color:red;color:white;">Not Rewritered</td>
        @elseif($content->status === 1)
        <td style="background-color:orange;color:white;">Draft</td>
        @elseif($content->status === 2)
        <td style="background-color:green;color:white;">Published</td>
        @endif

        <td>
            <form action="{{ route('contents.destroy', $content->id) }}" method="POST">

                <a href="{{ route('contents.edit', $content->id) }}">
                    <i class="fa fa-pencil fa-fw "></i>

                </a>

                <a href="/contents/1">
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