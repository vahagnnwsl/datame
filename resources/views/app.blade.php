@extends('layouts.app')

@section('content')

    <!-- USER SECTION -->
    <section class="user_section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4">
                    <user-header-component full_name="{{ Auth::user()->getFullNameAttribute() }}"
                                           date_service_value="{{ Auth::user()->date_service_value }}"
                                           check_quantity="{{ Auth::user()->check_quantity }}"
                                           v-bind:is_admin="{{ Auth::user()->isAdmin() ? 1 : 0 }}"
                                           message_url="{{ route('admin.messages') }}"></user-header-component>
                </div>

                <div class="col-xs-12 col-sm-8">
                    <div class="user_content">
                        <check-person-component title="Поиск проверяемого лица"></check-person-component>
                        <div class="user_items">
                            <search-check-person-component title="Поиск по проверенным лицам"></search-check-person-component>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END USER SECTION -->

@endsection

@push('scripts')

    <script type="text/javascript">

        const app = new Vue({
            el: '#app'
        });

    </script>

@endpush

@push('title')Поиск проверяемого лица - DataMe.Online @endpush
@push('description')Комплексный агрегатор информации «Гидра» расскажет, как быстро проверить человека и сотрудника в режиме онлайн. Отчет содержит сведения о налогах, судимости, кредитах и т.д. @endpush
@push('keywords')как проверить человека онлайн, как проверить сотрудника @endpush