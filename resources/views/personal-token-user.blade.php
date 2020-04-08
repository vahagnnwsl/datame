@extends('layouts.app')

@section('content')

    <personal-access-tokens></personal-access-tokens>

@endsection

@push('scripts')

    <script type="text/javascript">

        const app = new Vue({
            el: '#app'
        });

    </script>

@endpush

@push('title')Токены доступа - DataMe.Online @endpush
@push('description')Комплексный агрегатор информации «Гидра» расскажет, как быстро проверить человека и сотрудника в режиме онлайн. Отчет содержит сведения о налогах, судимости, кредитах и т.д. @endpush
@push('keywords')как проверить человека онлайн, как проверить сотрудника @endpush