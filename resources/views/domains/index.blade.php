@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Domain List </h2>
            </div>
           
        </div>
    </div>



    <table class="table table-bordered table-responsive-lg">
        <tr>
            <th>id</th>
            <th>Name</th>
            <th>Hosting</th>
            <th>Status</th>
            <th>Activation Date</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($domains as $domain)
            <tr>
                <td>{{ $domain->id }}</td>
                <td>{{ $domain->name }}</td>
                <td>{{ $domain->hosting }}</td>
                @if ($domain->status === -1)
                <td style="background-color:orange;color:white;">Hata var!!!</td>
                @elseif($domain->status === 0)
                <td style="background-color:green;color:white;">Aktif</td>
                @elseif($domain->status === 1)
                <td style="background-color:red;color:white;">Deaktif</td>
                @elseif($domain->status === 2)
                <td style="background-color:blue;color:white;">Taşınmış</td>
                @elseif($domain->status === 3)
                <td style="background-color:red;color:white;">Deaktif - Mail gönderildi.</td>
                @endif
                <td>{{$domain->created_at}}</td>
                <td>
                    <form action="{{ route('domains.destroy', $domain->id) }}" method="POST">

<!--                         <a href="{{ route('domains.show', $domain->id) }}" title="show">
                            <i class="fas fa-eye text-success  fa-lg"></i>
                        </a> -->

                        <a href="{{ route('domains.edit', $domain->id) }}">
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
