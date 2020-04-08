@extends('layouts.main.app')

@section('content')

    <!-- MAIN SECTION -->
    <section class="main_section">
        <div class="video_bg" id="video_bg">
            <video width="100%" height="auto" poster="{{ asset('img/main_bg.jpg') }}" id="videobg" playsinline autoplay muted loop>
                <source src="{{ asset('video/2.mp4') }}" type="video/mp4">
            </video>
        </div>
        <div class="container">
            <h1 class="main_title">Комплексный агрегатор информации «Гидра» – проверим каждого!</h1>

            @guest
                <a class="main_btn" href="{{ route('register-individual') }}">Начать пользоваться</a>
            @endguest

            <div class="row">
                <div class="col-xs-12 col-sm-4">
                    <div class="item">
                        <div class="icon">
                            <img src="img/main_icon_1.svg" alt="img">
                        </div>
                        <h5 class="item_title">БЫСТРО</h5>
                        <p>Проверка человека занимает не более 10 минут</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <div class="item">
                        <div class="icon">
                            <img src="img/main_icon_2.svg" alt="img">
                        </div>
                        <h5 class="item_title">ОФИЦИАЛЬНО</h5>
                        <p>Отчет состоит из информации, полученной из государственных источников</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <div class="item">
                        <div class="icon">
                            <img src="img/main_icon_3.svg" alt="img">
                            <img src="img/main_icon_3.svg" alt="img">
                        </div>
                        <h5 class="item_title">АКТУАЛЬНО</h5>
                        <p>Полученные сведения актуальны на день запроса</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END MAIN SECTION -->

    <!-- SERVICES DESCRIPTION -->
    <section class="services_description">
        <div class="container">
            <div class="wrapper">
                <div class="inner">
                    <h3 class="section_title">Описание услуг</h3>
                    <p>Комплексный агрегатор информации «Гидра» – ресурс, который станет вашим помощником в борьбе с
                        мошенниками и ненадежными сотрудниками. Онлайн-проверка человека по паспорту
                        позволяет узнать следующую информацию:
                    </p>

                    <div style="margin-left: 50px;">
                        <ul style="list-style-type: circle; font-size: 16px; line-height: 28px;">
                            <li>о налогах и сборах (задолженности);</li>
                            <li>о судимости и нахождении в розыске;</li>
                            <li>об открытых исполнительных производствах;</li>
                            <li>о юридическом статусе (учредитель ООО, ИП и т.д.);</li>
                            <li>иная информация из государственных источников;</li>
                            <li>анализ и обобщение полученных данных;</li>
                        </ul>
                    </div>


                    <p style="margin-top: 10px;">Сервис в автоматическом режиме собирает данные с государственных источников, после
                        чего формирует удобный отчет (справку). Данная процедура занимает не более 10 минут,
                        так что вы экономите свое время и можете быть уверены в надежности полученных
                        сведений.</p>
                    {{--<a href="#" class="main_btn">Узнать подробнее</a>--}}
                </div>
            </div>
        </div>
    </section>
    <!-- SERVICES DESCRIPTION -->

    <!-- USED SERVICES -->
    <section class="used_services">
        <div class="container">
            <h3 class="section_title">Используемые сервисы</h3>
            <div class="slider_wrapper">
                <div class="swiper-button-prev prev_service"></div>
                <div class="swiper-button-next next_service"></div>
                <div class="swiper-container services_slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="icon">
                                <img src="img/logo_1.png" alt="img">
                            </div>
                            <p>МВД России</p>
                        </div>
                        <div class="swiper-slide">
                            <div class="icon">
                                <img src="img/logo_2.png" alt="img">
                            </div>
                            <p>Федеральная налоговая служба</p>
                        </div>
                        <div class="swiper-slide">
                            <div class="icon">
                                <img src="img/logo_3.png" alt="img">
                            </div>
                            <p>Федеральная служба судебных приставов</p>
                        </div>
                        <div class="swiper-slide">
                            <div class="icon">
                                <img src="img/logo_4.png" alt="img">
                            </div>
                            <p>Интерпол</p>
                        </div>
                        <div class="swiper-slide">
                            <div class="icon">
                                <img src="img/logo_1.png" alt="img">
                            </div>
                            <p>МВД России</p>
                        </div>
                        <div class="swiper-slide">
                            <div class="icon">
                                <img src="img/logo_2.png" alt="img">
                            </div>
                            <p>Федеральная налоговая служба</p>
                        </div>
                        <div class="swiper-slide">
                            <div class="icon">
                                <img src="img/logo_3.png" alt="img">
                            </div>
                            <p>Федеральная служба судебных приставов</p>
                        </div>
                        <div class="swiper-slide">
                            <div class="icon">
                                <img src="img/logo_4.png" alt="img">
                            </div>
                            <p>Интерпол</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END USER SERVICES -->

    <!-- WORK STEPS -->
    <section class="work_steps">
        <div class="container">
            <h3 class="section_title white">Как происходит работа</h3>
            <div class="row">
                <div class="col-xs-12 col-sm-4">
                    <div class="item">
                        <div class="icon">
                            <img src="img/step_1.svg" alt="img">
                            <p class="number">1</p>
                        </div>
                        <p>Регистрация в сервисе и <br>оплата выбранного тарифа</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <div class="item">
                        <div class="icon">
                            <img src="img/step_2.svg" alt="img">
                            <p class="number">2</p>
                        </div>
                        <p>Ввод ФИО, даты рождения и <br>паспортных данных проверяемого</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <div class="item">
                        <div class="icon">
                            <img src="img/step_3.svg" alt="img">
                            <p class="number">3</p>
                        </div>
                        <p>Мгновенное получение <br>итогового отчета</p>
                    </div>
                </div>
            </div>
            <a class="main_btn" href="{{ route('app-report.demo') }}">Посмотреть образец справки</a>
        </div>
    </section>
    <!-- END WORK STEPS -->

    {{--  сообщения для всех  --}}
    <div id="message_for_all" style="display: none">
        {{ $message_for_all }}
    </div>

    <notification-component></notification-component>
@endsection

@push('scripts')

    <script type="text/javascript">

        const app = new Vue({
            el: '#app',
            mounted() {
                var text = $('#message_for_all').text();
                if(text.length > 0) {
                    this.$notify({
                        duration: 10000,
                        group: 'message-for-all',
                        title: 'Обратите внимание',
                        text: text,
                    });
                }
            }
        });

    </script>

@endpush

@push('title')Онлайн проверка налогов и судимости человека по паспорту@endpush
@push('description')Комплексный агрегатор информации «Гидра» осуществляет проверку человека в режиме онлайн. Отчет содержит сведения о налогах, судимости, кредитах и т.д.@endpush
@push('keywords')проверка налогов онлайн, проверка судимости онлайн, проверка человека онлайн, проверка по паспорту онлайн@endpush