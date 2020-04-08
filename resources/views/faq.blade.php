@extends('layouts.app')

@section('content')

    <!-- TEXT SECTION -->
    <section class="text_section">
        <div class="container">
            <h1 class="section_title">FAQ</h1>
            <div class="wrapper">
                <div class="spoiler_item">
                    <button data-toggle="collapse" class="spoiler collapsed">Сколько по времени занимает проверка человека онлайн?</button>
                    <div class="collapse spoiler-body">
                        <div class="well">
                            <p>На отправление запроса, сбор информации и ее последующую обработку сервису «Гидра»
                                достаточно 3-5 минут. Даже при большой загруженности это время не превышает 10
                                минут.</p>
                        </div>
                    </div>
                </div>
                <div class="spoiler_item">
                    <button data-toggle="collapse" class="spoiler collapsed">Почему у меня проверка занимает больше 10 минут?</button>
                    <div class="collapse spoiler-body">
                        <div class="well">
                            <p>Если после отправления запроса вам приходится ждать больше 10 минут, значит система
                                не может получить доступ к государственным ресурсам. Скорее всего, на сторонних
                                сервисах произошел технический сбой. Вам необходимо подождать или обратиться к
                                сервису позже. По окончанию проверки мы направим в Ваш адрес уведомление о
                                получении отчета.</p>
                        </div>
                    </div>
                </div>
                <div class="spoiler_item">
                    <button data-toggle="collapse" class="spoiler collapsed">Правомерно ли использование полученной информации?</button>
                    <div class="collapse spoiler-body">
                        <div class="well">
                            <p>Онлайн-проверка человека через наш сервис возможна при наличии согласия
                                проверяемого лица на обработку персональных данных. Соответственно, соискатель,
                                предоставляя свои анкетные и паспортные данные отделу кадров, должен быть оповещен
                                о планах организации выполнить подобную проверку. В этом случае сбор, получение и
                                использование информации считается правомерным и не противоречащим российскому
                                законодательству.</p>
                        </div>
                    </div>
                </div>
                <div class="spoiler_item">
                    <button data-toggle="collapse" class="spoiler collapsed">Как получить доступ к сайту, чтобы проверить сотрудника?</button>
                    <div class="collapse spoiler-body">
                        <div class="well">
                            <p style="margin-bottom: 0;">
                                Чтобы получить доступ к функционалу агрегатора «Гидра» необходимо:
                            </p>
                            <ul>
                                <li>зарегистрироваться на сайте;</li>
                                <li>предоставить всю необходимую информацию (мы проверяем каждый запрос,
                                    чтобы исключить злоумышленников и убедиться в существовании заявленной
                                    компании и правомерности использования полученной информации);</li>
                                <li>
                                    оплатить выбранный тариф или пакет проверок (это можно сделать через сайт или
                                    запросив счет на безналичную оплату).
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="spoiler_item">
                    <button data-toggle="collapse" class="spoiler collapsed">Сервис выдал сообщение о том, что человек уже прошел проверку. Что это значит?</button>
                    <div class="collapse spoiler-body">
                        <div class="well">
                            <p>Проверяемое лицо уже было проверено на нашем сайте (система сообщит о том, как давно
                                были собраны сведения и не утратили ли они свою актуальность). После этого вы можете
                                запросить уже готовый отчет и сэкономить время или заказать новую проверку.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END TEXT SECTION -->


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

@push('title')Как проверить человека и сотрудника онлайн?@endpush
@push('description')Комплексный агрегатор информации «Гидра» расскажет, как быстро проверить человека и сотрудника в режиме онлайн. Отчет содержит сведения о налогах, судимости, кредитах и т.д.@endpush
@push('keywords')как проверить человека онлайн, как проверить сотрудника@endpush