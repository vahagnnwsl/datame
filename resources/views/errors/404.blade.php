@extends('layouts.app')


@section('content')

    <div class="row" style="margin-top: 40px; text-align: center;">
        <div class="col-xs-12">
            <h3>
                {{ __($exception->getMessage() ?: 'Извините, страница не найдена.') }}
            </h3>
        </div>
    </div>

@endsection

