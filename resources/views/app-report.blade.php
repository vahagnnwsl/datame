@extends('layouts.app')

@section('content')

    <app-report-component app_id="{{ $app_id }}"></app-report-component>

@endsection

@push('scripts')

    <script type="text/javascript">

        const app = new Vue({
            el: '#app'
        });

    </script>

@endpush

@push('title'){{ $app['lastname'] }} {{ $app['name']}} {{ $app['patronymic']}}, {{ $app['birthday'] }} @endpush
@push('description')Комплексный агрегатор информации «Гидра» расскажет, как быстро проверить человека и сотрудника в режиме онлайн. Отчет содержит сведения о налогах, судимости, кредитах и т.д. @endpush
@push('keywords')как проверить человека онлайн, как проверить сотрудника @endpush