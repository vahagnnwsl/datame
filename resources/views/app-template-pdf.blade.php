<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $app['lastname'] }} {{ $app['name']}} {{ $app['patronymic']}}, {{ $app['birthday'] }}</title>
    <meta name="description"
          content="Комплексный агрегатор информации «Гидра» расскажет, как быстро проверить человека и сотрудника в режиме онлайн. Отчет содержит сведения о налогах, судимости, кредитах и т.д.">
    <meta name="keywords" content="как проверить человека онлайн, как проверить сотрудника">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('libs/bootstrap/bootstrap.min.css') }}">
    <link
        href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&amp;subset=cyrillic"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ mix('css/main.css') }}">
    <link rel="stylesheet" href="{{ mix('css/media.css') }}">
    <link rel="stylesheet" href="{{ mix('css/vendor.css') }}">

    <style>
        .reference_section, .reference_section .wrapper {
            padding-top: 0px;
        }

        .coefficient_block .col {
            float: left;
            width: 50%;
        }

        /* имя */
        .reference_section .top_wrapper .name {
            font-size: {{ $font_name }}{{ $font_type }};
        }

        /* дата */
        .reference_section .top_wrapper .stats {
            font-size: {{ $font_stat }}{{ $font_type }};
        }

        /* Список справа от коэффициента */
        .coefficient_block ul li {
            font-size: 21px;
        }

        /* заголовки в таблице */
        .info_table th {
            font-size: 21px;
        }

        /* текст в таблице */
        .info_table td, .info_table td ul li, .info_table th ul li, .info_table .mid {
            font-size: 21px;
            line-height: 1.3;
        }

        /* текст конфиденциальность после таблицы */
        .reference_section .wrapper p {
            font-size: 21px;
        }
    </style>


</head>
<body style="background: white !important;">

<!-- PAGE WRAPPER -->
<div class="page_wrapper" id="app">


    <!-- REFERENCE SECTION -->
    <section class="reference_section coefficient_section" id="reference_section">
        <div class="container">
            <div class="wrapper top_wrapper">
                <div
                    style="display: block; width: 100%; padding-bottom: 40px; margin-bottom: 40px; border-bottom: 1px solid gray; 	display: -webkit-box; display: -moz-box; display: -ms-flexbox; display: -webkit-flex; display: flex; -ms-flex-wrap: wrap; flex-wrap: wrap;-webkit-align-items: center; align-items: center; -webkit-align-items: flex-end; align-items: flex-end;">
                    <div class="col" style="width: 50%;">
                        <img src="{{ asset('img/logo_blue.png') }}" alt="img"
                             style="width: 300px; max-width: 100%; opacity: 1;">
                    </div>
                    <div class="col" style="width: 50%;">
                        <p class="name"
                           style="top: 7px; line-heigh: 1.2; text-align: left; position: relative; font-family: 'Roboto Condensed' ,sans-serif; text-align: right; font-weight: 400; line-height: 34px; color: #1a2244;">
                            {{ $app['lastname'] }} {{ $app['name']}} {{ $app['patronymic']}}
                        </p>
                        <p class="stats"
                           style="top: 7px; line-heigh: 1.2; text-align: left; position: relative; font-family: 'Roboto Condensed' ,sans-serif; text-align: right; font-weight: 400; line-height: 24px; color: #1a2244;">
                            проверка от {{ $app['created_at'] }}
                            @if($app['status'] < 3)
                                <strong>(не окончена)</strong>
                            @endif

                        </p>
                    </div>

                </div>
                <div class="coefficient_block">
                    <div class="col">
                        <div class="ball_item">
                            <div class="circle">
                                <img src="{{ asset('img/ball.png') }}" alt="img">
                                <p><span class="spincrement">{{ $app['extend']['trust']['value'] }}%</span></p>
                            </div>
                            <p>Коэффициент доверия</p>
                        </div>
                    </div>
                    <div class="col">
                        <ul>
                            @foreach($app['extend']['trust']['services'] as $service)
                                <li class="{{ !$service['status'] ? 'no' : '' }}">{{ $service['name'] }}</li>
                            @endforeach
                            @if($app['extend']['trust']['all_amount'] > 0)
                                <li class="no">
                                    Общая задолженность: <span
                                        style="font-weight: bold">{{ $app['extend']['trust']['all_amount_formatted'] }}</span>
                                    рублей
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!--Общие сведения о проверяемом лице-->
                <table class="info_table">
                    <tr>
                        <th colspan="2">ОБЩИЕ СВЕДЕНИЯ О ПРОВЕРЯЕМОМ ЛИЦЕ</th>
                    </tr>
                    <tr>
                        <td>Фамилия, имя, отчество</td>
                        <td>{{ $app['lastname'] }} {{ $app['name']}} {{ $app['patronymic']}}</td>
                    </tr>
                    <tr>
                        <td>Фамилия, имя, отчество на английском</td>
                        <td>{{ $app['extend']['lastname_en'] }} {{ $app['extend']['name_en'] }} {{ $app['extend']['patronymic_en'] }}</td>
                    </tr>
                    <tr>
                        <td>Дата рождения</td>
                        <td>{{ $app['birthday'] }}</td>
                    </tr>
                    <tr>
                        <td>Полный возраст</td>
                        <td>{{ $app['extend']['current_age'] }} лет</td>
                    </tr>
                    @if(!is_null($app['city_birth']))
                        <tr>
                            <td>Место рождения</td>
                            <td>{{ $app['city_birth'] }}</td>
                        </tr>
                    @endif

                    <tr>
                        <td>ИНН</td>
                        <td>
                            @if(serviceNotRespond(12, $services))
                                {{ $service_error_message }}
                            @else

                                @if(!is_null(serviceMessage(2, $services)))
                                    {{ serviceMessage(2, $services) }}
                                @else

                                    @if(!is_null($app['inn']))
                                        {{ $app['inn']}}
                                    @else

                                        ИНН не найден. Возможные причины:
                                        <ul>
                                            <li class="no">человек недавно получил паспорт, но указанная информация еще
                                                не поступила в ИФНС
                                            </li>
                                            <li class="no">документы в ИФНС на присвоение ИНН поданы, но ИНН еще не
                                                получен
                                            </li>
                                        </ul>

                                    @endif

                                @endif
                            @endif
                        </td>
                    </tr>
                </table>

                <!--Паспорт-->
                <table class="info_table">
                    <tr>
                        <th colspan="2">Паспорт</th>
                    </tr>
                    <tr>
                        <td>Серия и номер</td>
                        <td>{{ $app['passport_code'] }}</td>
                    </tr>
                    <tr>
                        <td>Дата выдачи</td>
                        <td>{{ $app['date_of_issue'] }} года</td>
                    </tr>
                    @if(count($app['extend']['passport']['who_issue']))
                        <tr>
                            <td>Кем выдан</td>
                            <td>
                                <ul>
                                    @foreach($app['extend']['passport']['who_issue'] as $item)
                                        <li>{{ $item['name'] }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endif
                    @if(count($app['extend']['passport']['who_issue']) == 0 && !is_null($app['code_department']))
                        <tr>
                            <td>Кем выдан</td>
                            <td>
                                Указанный код подразделения в базе отсутствует
                            </td>
                        </tr>
                    @endif
                    @if(!is_null($app['code_department']))
                        <tr>
                            <td>Код подразделения</td>
                            <td>{{ $app['code_department'] }}</td>
                        </tr>
                    @endif

                    <tr>
                        <td>Состояние (по данным МВД РФ)</td>
                        <td> {{ $app['extend']['passport']['is_valid'] }}</td>
                    </tr>
                    @if(!is_null($app['extend']['passport']['passport_date_replace']))
                        <tr>
                            <td>Срок действия</td>
                            <td>{{ $app['extend']['passport']['passport_date_replace'] }}</td>
                        </tr>
                    @endif
                    @if($app['extend']['passport']['is_valid'] == 'Паспорт действительный')
                        <tr>
                            <td>Кросс-проверка</td>
                            <td>
                                <ul>
                                    @if(!is_null($app['extend']['passport']['passport_serie_year']))
                                        @if($app['extend']['passport']['passport_serie_year'] == 1)
                                            <li>Серия паспорта соответствует дате выдачи паспорта</li>
                                        @else
                                            <li class="no">Год выдачи паспорта (по дате выдачи) больше или меньше года
                                                серии паспорта на 3 года
                                            </li>
                                        @endif
                                    @endif
                                    @if(!is_null($app['extend']['passport']['passport_serie_region']) && !is_null($app['code_department']))
                                        @if($app['extend']['passport']['passport_serie_region'] == 1)
                                            <li>Серия паспорта соответствует региону выдачи паспорта</li>
                                        @else
                                            <li class="no">Серия паспорта не соответствует региону выдачи паспорта</li>
                                        @endif
                                    @endif
                                </ul>
                            </td>
                        </tr>
                    @endif
                    @if(!is_null($app['extend']['passport']['attachment']))
                        <tr>
                            <td>Принадлежность подразделения</td>
                            <td>{{ $app['extend']['passport']['attachment']}}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>Дополнительная информация</td>
                        <td>
                            {{ $app['extend']['passport']['status'] }}
                        </td>
                    </tr>
                </table>

                <!--руководство и учредительство-->
                @if(serviceNotRespond(12, $services))
                    <table class="info_table">
                        <tr>
                            <th colspan="2">РУКОВОДСТВО И УЧРЕДИТЕЛЬСТВО</th>
                        </tr>
                        <tr>
                            <td colspan="2" class="mid">{{ $service_error_message }}</td>
                        </tr>
                    </table>
                @else

                    @if(!is_null(serviceMessage(12, $services)))
                        <table class="info_table">
                            <tr>
                                <th colspan="2">РУКОВОДСТВО И УЧРЕДИТЕЛЬСТВО</th>
                            </tr>
                            <tr>
                                <td colspan="2" class="mid">{{ serviceMessage(12, $services) }}</td>
                            </tr>
                        </table>
                @else
                    <!--Юридическое лицо-->
                        <table class="info_table">

                            <tr>
                                <th colspan="2">Юридическое лицо</th>
                            </tr>

                            @foreach($app['extend']['business']['ul'] as $item)

                                <tr>
                                    <td>Сокращенное наименование юридического лица</td>
                                    <td>{{ $item['naim_ul_sokr'] }}</td>
                                </tr>
                                <tr>
                                    <td>Полное наименование юридического лица</td>
                                    <td>{{ $item['naim_ul_poln'] }}</td>
                                </tr>
                                <tr>
                                    <td>Статус</td>
                                    <td>{{ $item['activnost'] }}</td>
                                </tr>
                                <tr>
                                    <td>ИНН</td>
                                    <td>{{ $item['inn'] }}</td>
                                </tr>
                                <tr>
                                    <td>КПП</td>
                                    <td>{{ $item['kpp'] }}</td>
                                </tr>
                                <tr>
                                    <td>Дата регистраци</td>
                                    <td>{{ $item['obr_data'] }}</td>
                                </tr>
                                <tr>
                                    <td>Юридический адрес</td>
                                    <td>{{ $item['adres'] }}</td>
                                </tr>
                                <tr>
                                    <td>Код по Общероссискому классификатору видов экономической деятельности</td>
                                    <td>{{ $item['kod_okved'] }}</td>
                                </tr>
                                <tr>
                                    <td>Наименование вида детельности по Общероссийскому классификатору видов
                                        экономической деятельности
                                    </td>
                                    <td>{{ $item['naim_okved'] }}</td>
                                </tr>
                                <tr class="big_border">
                                    <td>Руководитель</td>
                                    <td>{{ $item['rukovoditel'] }}</td>
                                </tr>

                            @endforeach

                            @if(count($app['extend']['business']['ul']) == 0)
                                <tr>
                                    <td colspan="2" class="mid">Не является руководителем или совладельцем коммерческих
                                        структур.
                                    </td>
                                </tr>
                            @endif

                        </table>

                        <!--Индивидуальный предприниматель-->
                        <table class="info_table">

                            <tr>
                                <th colspan="2">Индивидуальный предприниматель</th>
                            </tr>

                            @foreach($app['extend']['business']['ip'] as $item)

                                <tr>
                                    <td>Вид</td>
                                    <td>{{ $item['naim_vid_ip'] }}</td>
                                </tr>
                                <tr>
                                    <td>Фамилия, имя, отчество</td>
                                    <td>{{ $item['familia'] }} {{ $item['imia'] }} {{ $item['otchestvo'] }}</td>
                                </tr>
                                <tr>
                                    <td>Статус</td>
                                    <td>{{ $item['activnost'] }}</td>
                                </tr>
                                <tr>
                                    <td>ИНН</td>
                                    <td>{{ $item['innfl'] }}</td>
                                </tr>
                                <tr>
                                    <td>Дата регистрации</td>
                                    <td>{{ $item['data_ogrnip'] }}</td>
                                </tr>
                                <tr>
                                    <td>Страна</td>
                                    <td>{{ $item['naim_stran'] }}</td>
                                </tr>
                                <tr>
                                    <td>Код по Общероссийскому классификатору видов экономической деятельности</td>
                                    <td>{{ $item['kod_okved'] }}</td>
                                </tr>
                                <tr class="big_border">
                                    <td>Наименование вида деятельности по Общероссийскому классификатору видов
                                        экономической деятельности
                                    </td>
                                    <td>{{ $item['naim_okved'] }}</td>
                                </tr>

                            @endforeach

                            @if(count($app['extend']['business']['ip']) == 0)
                                <tr>
                                    <td colspan="2" class="mid">Не является индивидуальным предпринимателем.</td>
                                </tr>
                            @endif

                        </table>

                @endif

            @endif


            <!--Налоги-->
                <table class="info_table">

                    <tr>
                        <th colspan="2">ЗАДОЛЖЕННОСТЬ ПЕРЕД ГОСУДАРСТВЕННЫМИ ОРГАНАМИ</th>
                    </tr>

                    @if(serviceNotRespond(3, $services))
                        <tr>
                            <td colspan="2" class="mid">{{ $service_error_message }}</td>
                        </tr>
                    @else


                        @if(!is_null(serviceMessage(3, $services)))
                            <tr>
                                <td colspan="2" class="mid">{{ serviceMessage(3, $services) }}</td>
                            </tr>
                        @else

                            @foreach($app['extend']['tax']['items'] as $item)
                                <tr>
                                    <td>Вид задолженности</td>
                                    <td>{{ $item['article']}}</td>
                                </tr>
                                <tr>
                                    <td>Сумма задолженности</td>
                                    <td>{{ $item['amount'] }} рубля</td>
                                </tr>
                                <tr>
                                    <td>Номер начисления</td>
                                    <td>{{ $item['number'] }}</td>
                                </tr>
                                <tr class="big_border">
                                    <td>Налоговая инспекция</td>
                                    <td>{{ $item['name']}}</td>
                                </tr>
                            @endforeach

                            @if($taxCount > 0)
                                <tr>
                                    <td colspan="2" class="mid">Общее количество задолженностей –
                                        <strong>{{ $taxCount }}</strong>, на общую сумму <strong>{{ $taxAmount }}
                                            рублей</strong></td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="2" class="mid">Задолженности не найдены</td>
                                </tr>
                            @endif

                        @endif
                    @endif
                </table>

                <!--Испольныетельные производства-->
                <table class="info_table">

                    <tr>
                        <th colspan="2">ИСПОЛНИТЕЛЬНЫЕ ПРОИЗВОДСТВА</th>
                    </tr>

                    @if(serviceNotRespond(4, $services))
                        <tr>
                            <td colspan="2" class="mid">{{ $service_error_message }}</td>
                        </tr>

                    @else

                        @if($fsspCountProceed > 0)
                            <tr>
                                <td colspan="2" class="mid_blue">Действующие</td>
                            </tr>
                            @foreach($app['extend']['fssp']['proceed'] as $item)

                                <template v-for="item in app.extend.fssp.proceed">
                                    <tr>
                                        <td>Номер исполнительного производства</td>
                                        <td>{{ $item['number'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Регион начисления</td>
                                        <td>{{ $item['name_poluch'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Вид документа</td>
                                        <td>{{ $item['nazn'] }}</td>
                                    </tr>
                                    <tr class="big_border">
                                        <td class="big_border">Сумма задолженности</td>
                                        <td>{{ $item['amount'] }} рубля</td>
                                    </tr>
                                </template>

                            @endforeach

                        @endif


                        @if($fsspCountFinished > 0)
                            <tr>
                                <td colspan="2" class="mid_blue">Оконченные</td>
                            </tr>
                            @foreach($app['extend']['fssp']['finished'] as $item)

                                <template v-for="item in app.extend.fssp.proceed">
                                    <tr>
                                        <td>Номер исполнительного производства</td>
                                        <td>{{ $item['number'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Регион начисления</td>
                                        <td>{{ $item['name_poluch'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Вид документа</td>
                                        <td>{{ $item['nazn'] }}</td>
                                    </tr>
                                    <tr class="big_border">
                                        <td class="big_border">Сумма задолженности</td>
                                        <td>{{ $item['amount'] }} рубля</td>
                                    </tr>
                                </template>

                            @endforeach

                        @endif

                        @if($fsspCountProceed > 0)
                            <tr>
                                <td colspan="2" class="mid">Общее количество задолженностей –
                                    <strong>{{ $fsspCountProceed }}</strong>, на общую сумму <strong>{{ $fsspAmount }}
                                        рублей</strong></td>
                            </tr>
                        @endif

                        @if($fsspCountFinished == 0 && $fsspCountProceed == 0)
                            <tr>
                                <td colspan="2" class="mid">Задолженности не найдены</td>
                            </tr>

                        @endif
                    @endif
                </table>


                <!--Розыск-->
                <table class="info_table">
                    <tr>
                        <th colspan="2">НАХОЖДЕНИЕ В РОЗЫСКЕ</th>
                    </tr>
                    <tr>
                        <td>Интерпол, красные карточки</td>
                        @if(serviceNotRespond(5, $services))
                            <td>{{ $service_error_message }}</td>
                        @else
                            <td v-else>{{ $app['extend']['wanted']['interpol_red'] }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td>Интерпол, желтые карточки</td>
                        @if(serviceNotRespond(6, $services))
                            <td>{{ $service_error_message }}</td>
                        @else
                            <td v-else>{{ $app['extend']['wanted']['interpol_yellow'] }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td>Федеральный розыск</td>
                        @if(serviceNotRespond(8, $services))
                            <td>{{ $service_error_message }}</td>
                        @else
                            <td v-else>{{ $app['extend']['wanted']['mvd_wanted'] }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td>Местный розыск</td>
                        @if(serviceNotRespond(9, $services))
                            <td>{{ $service_error_message }}</td>
                        @else
                            <td v-else>{{ $app['extend']['wanted']['fssp_wanted'] }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td>Нахождение в списках террористов и экстремистов</td>
                        @if(serviceNotRespond(7, $services))
                            <td>{{ $service_error_message }}</td>
                        @else
                            <td v-else>{{ $app['extend']['wanted']['fed_fsm'] }}</td>
                        @endif
                    </tr>
                </table>

                <!--Иные источники-->
                <table class="info_table">
                    <tr>
                        <th colspan="2">ИНЫЕ ИСТОЧНИКИ</th>
                    </tr>
                    <tr>
                        <td class="word_break">Банкротство</td>
                        <td>
                            @if(serviceNotRespond(11, $services))
                                {{ $service_error_message }}
                            @else
                                @if(!is_null(serviceMessage(11, $services)))
                                    {{ serviceMessage(11, $services) }}
                                @else
                                    @if(!is_null($app['extend']['other']['debtor']))
                                        @foreach($app['extend']['other']['debtor'] as $item)
                                            <div>
                                                <div>{{ $item['result'] }}</div>

                                                @if(!is_null($item['category']))
                                                    <div>{{ $item['category'] }}</div>
                                                @endif
                                                @if(!is_null($item['ogrnip']))
                                                    <div>{{ $item['ogrnip'] }}</div>
                                                @endif

                                                @if(!is_null($item['snils']))
                                                    <div>{{ $item['snils'] }}</div>
                                                @endif

                                                @if(!is_null($item['region']))
                                                    <div>{{ $item['region'] }}</div>
                                                @endif

                                                @if(!is_null($item['live_address']))
                                                    <div>{{ $item['live_address'] }}</div>
                                                @endif

                                                <br>
                                            </div>
                                        @endforeach
                                    @endif

                                @endif
                        </td>
                    @endif

                    <tr>
                        <td class="word_break">Дисквалифицированные лица</td>
                        <td>

                            @if(serviceNotRespond(10, $services))
                                {{ $service_error_message }}
                            @else
                                @if(!is_null(serviceMessage(10, $services)))
                                    {{ serviceMessage(10, $services) }}
                                @else
                                    @if(!is_null($app['extend']['other']['disq']))
                                        @foreach($app['extend']['other']['disq'] as $item)
                                            <div>
                                                <div>{{ $item['result'] }}</div>

                                                @if(!is_null($item['period']))
                                                    <div>{{ $item['period'] }}</div>
                                                @endif
                                                @if(!is_null($item['start_date']))
                                                    <div>{{ $item['start_date'] }}</div>
                                                @endif

                                                @if(!is_null($item['end_date']))
                                                    <div>{{ $item['end_date'] }}</div>
                                                @endif

                                                @if(!is_null($item['org_position']))
                                                    <div>{{ $item['org_position'] }}</div>
                                                @endif

                                                @if(!is_null($item['name_org_protocol']))
                                                    <div>{{ $item['name_org_protocol'] }}</div>
                                                @endif

                                                <br>
                                            </div>
                                        @endforeach
                                    @endif

                                @endif
                            @endif
                        </td>
                    </tr>

                </table>


                <p style="text-align: justify !important;">КОНФИДЕНЦИАЛЬНОСТЬ. Информация, содержащаяся в данном
                    документе, является конфиденциальной и предназначена исключительно для предполагаемого адресата.
                    Любое распространение
                    данного документа или раскрытие содержащейся в нем информации ЗАПРЕЩАЕТСЯ!</p>
            </div>
        </div>
    </section>
    <!-- END REFERENCE SECTION -->


</div>

</body>
</html>
