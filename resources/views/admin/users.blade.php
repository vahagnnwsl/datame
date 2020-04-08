@extends('layouts.app')

@section('content')

    <!-- USER SECTION -->
    <section class="user_section" xmlns:v-bind="http://www.w3.org/1999/xhtml">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4">
                    <user-header-component full_name="{{ Auth::user()->getFullNameAttribute() }}"
                                           message_url="{{ route('admin.messages') }}" v-bind:is_admin="{{ Auth::user()->isAdmin() ? 1 : 0 }}"></user-header-component>
                </div>
                <div class="col-xs-12 col-sm-8">
                    <div class="user_content">
                            <ul class="users_inner_nav">
                                <li v-bind:class="{ active: users_individual }" @click="users_individual_is_active"><a href="#">Физические лица</a></li>
                                <li v-bind:class="{ active: users_legal }" @click="users_legal_is_active"><a href="#">Юридические лица</a></li>
                        </ul>
                        <div class="user_items">

                            <users-individual-component v-if="users_individual"></users-individual-component>

                            <users-legal-component v-if="users_legal"></users-legal-component>
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
            el: '#app',
            data: {
                users_individual: true,
                users_legal: false
            },
            methods: {
                users_individual_is_active: function() {
                    this.users_individual = true;
                    this.users_legal = false;
                },
                users_legal_is_active: function() {
                    this.users_individual = false;
                    this.users_legal = true;
                }
            }
        });

    </script>

@endpush

@push('title')Пользователи - DataMe.Online @endpush
@push('description')Комплексный агрегатор информации «Гидра» расскажет, как быстро проверить человека и сотрудника в режиме онлайн. Отчет содержит сведения о налогах, судимости, кредитах и т.д. @endpush
@push('keywords')как проверить человека онлайн, как проверить сотрудника @endpush