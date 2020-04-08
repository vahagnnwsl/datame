@extends('layouts.app')

@section('content')

    <!-- TEXT SECTION -->
    <section class="text_section">
        <div class="container">
            <div class="banner" style="background-image: url(img/api_bg.jpg);"></div>
            <h1 class="section_title">Api-доступ</h1>
            <div class="wrapper">

                <p>Комплексный агрегатор информации «Гидра» – современный сервис по поиску и сбору информации о
                    человеке. Он работает в автоматическом режиме, а потому затрачивает минимум времени
                    на проверку кадров и предоставляет полученные данные в виде четко структурированного
                    отчета.
                </p>

                <p>Однако это не все достоинства нашего сервиса. Для удобства постоянных клиентов мы
                    сделали API-доступ. С его помощью вы сможете интегрировать полученные данные в
                    привычную CRM, сэкономив время на копировании и переносе сведений о соискателе или
                    сотруднике.
                </p>

                <p>Для получения детальной информации свяжитесь с менеджерами нашей компании через
                    личный кабинет или по телефону горячей линии.
                </p>

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

@push('title')Онлайн проверка кадров с API-доступом@endpush
@push('description')Комплексный агрегатор информации «Гидра» осуществляет кадров в режиме онлайн. Отчет с API-доступом содержит сведения о налогах, судимости, кредитах и т.д.@endpush
@push('keywords')проверка кадров@endpush