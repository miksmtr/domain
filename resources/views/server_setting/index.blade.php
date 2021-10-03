@extends('layouts.app')
@section('template_title')
  Server Status
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Server  Status </h2>
            </div>
            
        </div>
    </div>

    is_server_busy : {{$server_settings->is_server_busy}}<br>
    new_domain_get_controller : {{$server_settings->new_domain_get_controller}}<br>
    banned_domain_get_controller : {{$server_settings->banned_domain_get_controller}}<br>
    check_domain_controller : {{$server_settings->check_domain_controller}}<br>
    website_picker_busy : {{$server_settings->website_picker_busy}}<br>
    website_picker_second_busy : {{$server_settings->website_picker_second_busy}}<br>

    

   


@endsection
