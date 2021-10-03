@extends('layouts.app')

@section('template_title')
  Server Logs
@endsection

@section('template_linked_css')

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">

@endsection

@section('content')

  <div class="container-fluid logs-container">
    <div class="row">

      <div class="col-sm-9 col-md-10 table-container">
        <table id="table-log" class="table table-sm table-striped">
          <thead>
            <tr>
              <th>type</th>
              <th>title</th>
              <th>description</th>
              <th>created_at</th>
            </tr>
          </thead>
          <tbody>
            @foreach($logs as $key => $log)
            <tr>
              <td class="text">{{$log->type}}</td>
              <td class="text">{{$log->title}}</td>
              <td class="text">{{$log->description}}</td>
              <td class="text">{{$log->created_at}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
       
      </div>

    </div>
  </div>


@endsection

@section('footer_scripts')

  @include('scripts.datatables')

  

@endsection
