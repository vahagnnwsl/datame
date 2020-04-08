@extends('layouts.app')

@section('content')

    <!-- REG SECTION -->
    <section class="reg_section">
        <div class="container">
            <h3 class="section_title">Регистрация</h3>

            <div class="tabs">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="{{ route('register-individual') }}">Как физическое лицо</a>
                    </li>
                    <li>
                        <a href="{{ route('register-legal') }}">Как юридическое лицо</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in active">
                        <form action="{{ route('register') }}" method="post" class="main_form fiz_form" id="register_individual">
                            @csrf
                            <input type="hidden" name="type_user" value="2"/>
                            <div class="double_inputs">
                                <label>
                                    <p>Ваше имя: <i></i></p>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                                </label>
                                <label>
                                    <p>Ваша фамилия: <i></i></p>
                                    <input type="text" name="lastname" id="lastname" value="{{ old('lastname') }}" required>
                                </label>
                            </div>
                            <label>
                                <p>Номер телефона: <i></i></p>
                                <input type="text" name="phone" value="{{ old('phone') }}" id="phone" required>
                            </label>
                            <label>
                                <p>Почта: <i></i></p>
                                <input type="text" name="email" value="{{ old('email') }}" required  autocomplete="off">
                            </label>
                            <div class="double_inputs">
                                <label>
                                    <p>Пароль: <i></i></p>
                                    <div class="input-group" id="show_hide_password">
                                        <input class="form-control" type="password" id="password" name="password" required>
                                        <div class="input-group-addon" style="border:none;border-top: 1px solid #EDEDED;background-color:#FFFFFF">
                                            <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                        </div>
                                    </div>

                                </label>
                                <label>
                                    <p>Пароль еще раз: <i></i></p>
                                    <div class="input-group" id="show_hide_password_confirmation">
                                        <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" required>
                                        <div class="input-group-addon" style="border:none;border-top: 1px solid #EDEDED;background-color:#FFFFFF">
                                            <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                        </div>
                                    </div>

                                </label>
                            </div>
                            <label class="checkbox_label">
                                <input type="checkbox" name="oferta_confirmed">
                                <i class="icon"></i>
                                <span>Ознакомлен с <a target="_blank" href="{{ route('oferta') }}" class="oferta_link"> договором публиной оферты</a> и порядком оказания услуг</span>
                            </label>
                            <button type="submit" class="main_btn submit_btn">Зарегистрироваться</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END REG SECTION -->

@endsection
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

        $("#show_hide_password_confirmation a").on('click', function(event) {
            event.preventDefault();
            if($('#show_hide_password_confirmation input').attr("type") == "text"){
                $('#show_hide_password_confirmation input').attr('type', 'password');
                $('#show_hide_password_confirmation i').addClass( "fa-eye-slash" );
                $('#show_hide_password_confirmation i').removeClass( "fa-eye" );
            }else if($('#show_hide_password_confirmation input').attr("type") == "password"){
                $('#show_hide_password_confirmation input').attr('type', 'text');
                $('#show_hide_password_confirmation i').removeClass( "fa-eye-slash" );
                $('#show_hide_password_confirmation i').addClass( "fa-eye" );
            }
        });


        $(function() {

            new IMask(document.getElementById('phone'), {
                mask: '+{7} (000) 000-00-00',
                lazy: false
            });

            new IMask(document.getElementById('name'), {
                mask: /^[ёЁa-zA-Zа-яА-Я-]+$/,
            });

            new IMask(document.getElementById('lastname'), {
                mask: /^[ёЁa-zA-Zа-яА-Я-]+$/,
            });

            $('#register_individual').validate({

                rules: {
                    name: {
                        required: true,
                    },
                    lastname: {
                        required: true,
                    },
                    phone: {
                        required: true,
                        minlength: 6,
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6,
                        equalTo: "#password"
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 6,
                    },
                },
                messages: {
                    name: {
                        required: "Поле не заполнено"
                    },
                    lastname: {
                        required: "Поле не заполнено"
                    },
                    phone: {
                        required: "Поле не заполнено",
                        minlength: "Мало символов"
                    },
                    email: {
                        required: "Поле не заполнено",
                        email: "Введен некорректный email"
                    },
                    password: {
                        required: "Поле не заполнено",
                        minlength: "Мало символов"
                    },
                    password_confirmation: {
                        required: "Поле не заполнено",
                        minlength: "Мало символов",
                        equalTo: "Пароль не совпадает"
                    }
                },
                submitHandler: function (form) {
                $('.main_btn').html('<img src="/img/spiner.gif" style="max-height: 30px">');
                form.submit();
                $('.fiz_form').find('input').attr('disabled','disabled');
            }

            });


        });

    </script>

@endpush

@push('title')Регистрация - как физическое лицо@endpush
@push('description')Комплексный агрегатор информации «Гидра» осуществляет проверку человека в режиме онлайн. Отчет содержит сведения о налогах, судимости, кредитах и т.д.@endpush
@push('keywords')проверка налогов онлайн, проверка судимости онлайн, проверка человека онлайн, проверка по паспорту онлайн@endpush
