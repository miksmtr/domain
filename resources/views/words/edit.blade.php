@extends('layouts.app')

@section('content')

<br>
<br>



<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h3>Kelime Düzenleme</h3>
        </div>

        <div class="pull-right text-center">
            <form action="{{ route('words.destroy', $word->id) }}" method="POST">

                @csrf
                @method('DELETE')

                <button type="submit" title="delete" style="border: none; background-color:transparent;">
                    <i class="btn btn-danger">DELETE</i>

                </button>
            </form>
            <b>
            </b>
        </div>
        {{$word}}
    </div>
</div>


@endsection