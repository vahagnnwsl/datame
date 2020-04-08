@extends('layouts.app')

@section('content')

    <section class="text_section">
        <div class="container">
            <h1 class="section_title">Тарифы</h1>
            <div class="wrapper">
                <p>Комплексный агрегатор «Гидра» стремится сделать информацию максимально доступной, а потому не только использует автоматизированные системы сбора данных, но и придерживается гибкой ценовой политики. Так, вы можете выбрать тариф в зависимости от текущих потребностей, размера компании и длительности периода найма.</p>
                <p>Самый простой вариант – проверка сотрудников на регулярной основе. Вы подключаете безлимитный тариф сроком на 1, 3, 6 или 12 месяцев. При этом количество запросов не ограничено. Также существует возможность оплаты разовой проверки соискателя или покупка оптимального пакета запросов. С более подробной информацией и конкретными ценами можете ознакомиться в таблице ниже.</p>
                <table class="info_table tarif_table">
                    <tr>
                        <th></th>
                        <th>Индивидуальный предприниматель</th>
                        <th>Уставной капитал до 100 000 рублей</th>
                        <th>Уставной капитал свыше 100 000 рублей</th>
                        <th>Компании с государственным участием</th>
                    </tr>
                    <tr>
                        <td colspan="5" class="mid_blue">Абонентское обслуживание</td>
                    </tr>
                    <tr>
                        <td>1 месяц</td><td>7 500</td>
                        <td>12 500</td><td>17 500</td>
                        <td>25 000</td>
                    </tr>
                    <tr>
                        <td>3 месяца</td>
                        <td>22 000</td>
                        <td>37 000</td>
                        <td>52 000</td>
                        <td>74 500</td>
                    </tr>
                    <tr>
                        <td>6 месяцев</td>
                        <td>40 000</td>
                        <td>70 000</td>
                        <td>100 000</td>
                        <td>145 000</td>
                    </tr>
                    <tr>
                        <td>12 месяцев</td>
                        <td>75 000</td>
                        <td>125 000</td>
                        <td>175 000</td>
                        <td>250 000</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="mid_blue">Разовые запросы</td>
                    </tr>
                    <tr>
                        <td>1 проверка</td>
                        <td>500</td>
                        <td>550</td>
                        <td>600</td>
                        <td>700</td>
                    </tr>
                    <tr>
                        <td>5 проверок</td>
                        <td>2 500</td>
                        <td>2 750</td>
                        <td>3 000</td>
                        <td>3 500</td>
                    </tr>
                    <tr>
                        <td>10 проверок</td>
                        <td>4 500</td>
                        <td>5 000</td>
                        <td>5 500</td>
                        <td>6 500</td>
                    </tr>
                    <tr>
                        <td>25 проверок</td>
                        <td>11 500</td>
                        <td>12 750</td>
                        <td>14 000</td>
                        <td>16 500</td>
                    </tr>
                </table>
            </div>
        </div>
    </section>

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