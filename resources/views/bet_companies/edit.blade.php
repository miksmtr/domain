@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('bet_companies.index') }}" title="Go back"> Go back </a>
        </div>
    </div>
</div>



<form action="{{ route('bet_companies.update', $bet_company->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>name:</strong>
                <input type="text" name="name" value="{{ $bet_company->name }}" class="form-control" placeholder="Name">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>free_bonus:</strong>
                <input type="text" name="free_bonus" value="{{ $bet_company->free_bonus }}" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>first_deposit:</strong>
                <input type="text" name="first_deposit" value="{{ $bet_company->first_deposit }}" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>second_deposit:</strong>
                <input type="text" name="second_deposit" value="{{ $bet_company->second_deposit }}" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>thirth_deposit:</strong>
                <input type="text" name="thirth_deposit" value="{{ $bet_company->thirth_deposit }}" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>casino_bonus:</strong>
                <input type="text" name="casino_bonus" value="{{ $bet_company->casino_bonus }}" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>link:</strong>
                <input type="text" name="link" value="{{ $bet_company->link }}" class="form-control" placeholder="">
            </div>
        </div>



        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Sırası:</strong>
                <input type="number" name="sort" value="{{ $bet_company->sort }}" class="form-control" placeholder="">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>rating:</strong>
                <input type="number" name="rating" value="{{ $bet_company->rating }}" class="form-control" placeholder="">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>btc:</strong>
                <smal>0 ise pasif 1 ise aktif</smal>
                <input type="number" name="btc" value="{{ $bet_company->btc }}" class="form-control" placeholder="">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>credit_card:</strong>
                <smal>0 ise pasif 1 ise aktif</smal>
                <input type="number" name="credit_card" value="{{ $bet_company->credit_card }}" class="form-control" placeholder="">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>live_tv:</strong>
                <smal>0 ise pasif 1 ise aktif</smal>
                <input type="number" name="live_tv" value="{{ $bet_company->live_tv }}" class="form-control" placeholder="">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>cash_out:</strong>
                <smal>0 ise pasif 1 ise aktif</smal>
                <input type="number" name="cash_out" value="{{ $bet_company->cash_out }}" class="form-control" placeholder="">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>papara:</strong>
                <smal>0 ise pasif 1 ise aktif</smal>
                <input type="number" name="papara" value="{{ $bet_company->papara }}" class="form-control" placeholder="">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>havale:</strong>
                <smal>0 ise pasif 1 ise aktif</smal>

                <input type="number" name="havale" value="{{ $bet_company->havale }}" class="form-control" placeholder="">
            </div>
        </div>
      



        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>status:</strong>
                <textarea class="form-control" style="height:50px" name="status" placeholder="status">{{ $bet_company->status }}</textarea>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>type:</strong>
                <smal>0 ise çöp site 1 ise anlaşmalı bahis şirketleri</smal>
                <textarea class="form-control" style="height:50px" name="type" placeholder="type">{{ $bet_company->type }}</textarea>
            </div>
        </div>

    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
    </div>

</form>
@endsection