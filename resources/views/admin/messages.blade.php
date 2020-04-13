@extends('layouts.app')

@section('content')

    <!-- USER SECTION -->
    <section class="user_section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4">
                    <user-header-component full_name="{{ Auth::user()->getFullNameAttribute() }}"
                                           message_url="{{ route('admin.messages') }}" v-bind:is_admin="{{ Auth::user()->isAdmin() ? 1 : 0 }}"></user-header-component>

                </div>
                <div class="col-xs-12 col-sm-8">
                    <send-message-all-component></send-message-all-component>
                </div>
            </div>
        </div>
    </section>
    <!-- END USER SECTION -->

@endsection

@push('scripts')

    <script type="text/javascript">

        new Vue({
            el: '#app'
        });

    </script>

@endpush
@push('header')
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
@endpush
@push('title')Сообщения - DataMe.Online @endpush
@push('description')Комплексный агрегатор информации «Гидра» расскажет, как быстро проверить человека и сотрудника в режиме онлайн. Отчет содержит сведения о налогах, судимости, кредитах и т.д. @endpush
@push('keywords')как проверить человека онлайн, как проверить сотрудника @endpush
