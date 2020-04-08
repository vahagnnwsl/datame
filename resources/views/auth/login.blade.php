@extends('layouts.app')

@section('content')

    <!-- REG SECTION -->
    <section class="reg_section">
        <div class="container">
            <h3 class="section_title">Вход</h3>

            <form action="{{ route('login') }}" method="post" class="main_form join_form">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <label>
                    <p>Логин: <i></i></p>
                    <input type="text" name="email" required>
                </label>
                <label>
                    <p>Пароль: <i></i></p>
                    <div class="input-group" id="show_hide_password">
                        <input class="form-control" type="password" name="password" id="password" required>
                        <div class="input-group-addon" style="border:none;border-top: 1px solid #EDEDED;background-color:#FFFFFF">
                            <a href="#"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </label>
                <label class="checkbox_label">
                    <a target="_blank" href="{{ route('password.request') }}" class="oferta_link" style="float: right">Забыл пароль?</a>
                </label>

                <button type="submit" class="main_btn submit_btn">Войти</button>
            </form>
        </div>
    </section>
    <!-- END REG SECTION -->

@endsection

@push('title')Вход@endpush
@push('description')Комплексный агрегатор информации «Гидра» осуществляет проверку человека в режиме онлайн. Отчет содержит сведения о налогах, судимости, кредитах и т.д.@endpush
@push('keywords')проверка налогов онлайн, проверка судимости онлайн, проверка человека онлайн, проверка по паспорту онлайн@endpush

@push('header')
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
@endpush


@push('scripts')
    <script type="text/javascript">
        $("#show_hide_password a").on('click', function(event) {
            event.preventDefault();
            if($('#show_hide_password input').attr("type") == "text"){
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass( "fa-eye-slash" );
                $('#show_hide_password i').removeClass( "fa-eye" );
            }else if($('#show_hide_password input').attr("type") == "password"){
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass( "fa-eye-slash" );
                $('#show_hide_password i').addClass( "fa-eye" );
            }
        });
    </script>
@endpush
