@extends('layouts.app')

@section('content')

    <!-- USER SECTION -->
    <section class="user_section" xmlns:v-bind="http://www.w3.org/1999/xhtml">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4">
                    <user-header-component full_name="{{ Auth::user()->full_name }}"
                                           date_service_value="{{ Auth::user()->date_service_value }}"
                                           check_quantity="{{ Auth::user()->check_quantity }}"
                                           v-bind:is_admin="{{ Auth::user()->isAdmin() ? 1 : 0 }}"></user-header-component>
                </div>

                <div class="col-xs-12 col-sm-8">

                    <div class="user_content">

                        @if(Auth::user()->type_user == 1)
                            <profile-change-password-component title="Изменить пароль"></profile-change-password-component>
                        @else

                            <ul class="users_inner_nav">
                                <li v-bind:class="{ active: main_page }" @click="main_page_is_active"><a href="#">Основные данные</a></li>
                                <li v-bind:class="{ active: security_page }" @click="security_page_is_active"><a href="#">Безопасность</a></li>
                            </ul>

                            @if(Auth::user()->type_user == 2)
                                <profile-individual-component v-if="main_page"></profile-individual-component>
                            @endif

                            @if(Auth::user()->type_user == 3)
                                <profile-legal-component v-if="main_page"></profile-legal-component>
                            @endif

                            <profile-change-password-component v-if="security_page"></profile-change-password-component>

                        @endif


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
            el: '#app',
            data: {
                main_page: true,
                security_page: false
            },
            methods: {
                main_page_is_active: function() {
                    this.main_page = true;
                    this.security_page = false;
                },
                security_page_is_active: function() {
                    this.main_page = false;
                    this.security_page = true;
                }
            }
        });

    </script>

@endpush

@push('title')Профиль пользователя - DataMe.Online @endpush
@push('description')Комплексный агрегатор информации «Гидра» расскажет, как быстро проверить человека и сотрудника в режиме онлайн. Отчет содержит сведения о налогах, судимости, кредитах и т.д. @endpush
@push('keywords')как проверить человека онлайн, как проверить сотрудника @endpush