@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Bet Company List </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success margin-bottom-1 mb-1" href="{{ route('bet_companies.create') }}" title="Create a bet_company">Create</i>
                    </a>

            </div>
        </div>
    </div>



    <table class="table table-bordered table-responsive-lg">
        <tr>
            <th>id</th>
            <th>Name</th>
            <th>Status</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($bet_companies as $bet_company)
            <tr>
                <td>{{ $bet_company->id }}</td>
                <td>{{ $bet_company->name }}</td>
                <td>{{ $bet_company->status }}</td>
                <td>
                    <form action="{{ route('bet_companies.destroy', $bet_company->id) }}" method="POST">

<!--                         <a href="{{ route('bet_companies.show', $bet_company->id) }}" title="show">
                            <i class="fas fa-eye text-success  fa-lg"></i>
                        </a> -->

                        <a href="{{ route('bet_companies.edit', $bet_company->id) }}">
                            <i class="fa fa-pencil fa-fw "></i>

                        </a>

                        @csrf
                       <!--  @method('DELETE')

                        <button type="submit" title="delete" style="border: none; background-color:transparent;">
                            <i class="fa fa-trash-o fa-fw text-danger"></i>

                        </button> -->
                    </form>
                </td>
            </tr>
        @endforeach
    </table>


@endsection
