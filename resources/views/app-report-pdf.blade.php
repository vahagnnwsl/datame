<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@stack('title')</title>
    <meta name="description" content=" @stack('description')">
    <meta name="keywords" content="@stack('keywords')">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('libs/bootstrap/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&amp;subset=cyrillic" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ mix('css/main.css') }}">
    <link rel="stylesheet" href="{{ mix('css/media.css') }}">
    <link rel="stylesheet" href="{{ mix('css/vendor.css') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
	
	<style>
		
			.coefficient_block .col {
				float: left;
				width: 50%;
			}
		
	</style>


    <!-- Scripts -->
{{--<script src="{{ asset('js/app.js') }}" defer></script>--}}

<!-- Styles -->
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
</head>
<body style="background: white !important;">

<!-- PAGE WRAPPER -->
<div class="page_wrapper" id="app">


    <!-- REFERENCE SECTION -->
    <section class="reference_section coefficient_section" id="reference_section">
        <div class="container">
            <div class="wrapper">
				<div style="display: block; width: 100%; padding-bottom: 40px; margin-bottom: 40px; border-bottom: 1px solid gray; 	display: -webkit-box; display: -moz-box; display: -ms-flexbox; display: -webkit-flex; display: flex; -ms-flex-wrap: wrap; flex-wrap: wrap;-webkit-align-items: center; align-items: center; -webkit-align-items: flex-end; align-items: flex-end;">
					<div class="col" style="width: 50%;">
						 <img src="{{ asset('img/logo_blue.png') }}" alt="img" style="width: 300px; max-width: 100%; opacity: .7;">
					</div> 
					<div class="col" style="width: 50%;">
						<p style="font-size: 24px; line-heigh: 1.2; text-align: left; position: relative; top: 30px;">Иванов Иван Иванович</p>
					</div> 
				</div>
                <div class="coefficient_block">
                    <div class="col">
                        <div class="ball_item">
                            <div class="circle">
                                <img src="{{ asset('img/ball.png') }}" alt="img">
                                <p><span class="spincrement">75%</span></p>
                            </div>
                            <p>Коэффициент доверия</p>
                        </div>
                    </div>
                    <div class="col">
                        <ul>
                            <li>Паспорт</li>
                            <li>Учредительство</li>
                            <li>Нахождение в розыске</li>
                            <li class="no">Руководство</li>
                            <li class="no">Задолженность перед госорганами</li>
                            <li class="no">Исполнительные производства</li>
                            <li class="no">Иные источники</li>
                        </ul>
                    </div>
                </div>
                <table class="info_table">
                    <tr>
                        <th colspan="2">ОБЩИЕ СВЕДЕНИЯ О ПРОВЕРЯЕМОМ ЛИЦЕ</th>
                    </tr>
                    <tr>
                        <td>Фамилия, имя, отчество</td>
                        <td>Иванов Иван Иванович</td>
                    </tr>
                    <tr>
                        <td>Фамилия, имя, отчество на английском</td>
                        <td>Ivanov Ivan Ivanovich</td>
                    </tr>
                    <tr>
                        <td>Дата рождения</td>
                        <td>01.01.1980</td>
                    </tr>
                    <tr>
                        <td>Полный возраст</td>
                        <td>38 лет</td>
                    </tr>
                    <tr>
                        <td>Место рождения</td>
                        <td>г. Москва</td>
                    </tr>
                    <tr>
                        <td>Адрес регистрации</td>
                        <td>г. Москва, ул. 26 Бакинских комиссаров, <br>
                            д. 16, кв. 29
                        </td>
                    </tr>
                    <tr>
                        <td>ИНН</td>
                        <td>771122334455</td>
                    </tr>
                    <tr>
                        <td>СНИЛС</td>
                        <td>123-456-789 00</td>
                    </tr>
                </table>
                <table class="info_table">
                    <tr>
                        <th colspan="2">Паспорт</th>
                    </tr>
                    <tr>
                        <td>Серия и номер</td>
                        <td>4500 123456</td>
                    </tr>
                    <tr>
                        <td>Дата выдачи</td>
                        <td>01.01.2002 года</td>
                    </tr>
                    <tr>
                        <td>Кем выдан</td>
                        <td>УФМС России по району Ломоносовский гор. Москвы в САО</td>
                    </tr>
                    <tr>
                        <td>Код подразделения</td>
                        <td>770-123</td>
                    </tr>
                    <tr>
                        <td>Состояние</td>
                        <td class="red">Не действительный</td>
                    </tr>
                    <tr>
                        <td>Срок действия</td>
                        <td>01.01.2015 года</td>
                    </tr>
                    <tr>
                        <td>Кросс-проверка</td>
                        <td>
                            <ul>
                                <li>Серия паспорта соответствует дате выдачи паспорта</li>
                                <li class="no">Серия паспорта соответствует региону выдачи паспорта</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td>Принадлежность подразделения</td>
                        <td>УФМС (в настоящее время ГУВМ)</td>
                    </tr>
                    <tr>
                        <td>Дополнительная информация</td>
                        <td>Паспорт выдан лицу в 22 года (через 2 года после плановой замены паспорта). Необходимо уточнить у проверяемого лица причину внеплановой замены паспорта (утеря, кража,
                            смена фамилии, умышленная порча, иное)
                        </td>
                    </tr>
                </table>
                <table class="info_table">
                    <tr>
                        <th colspan="2">Руководство</th>
                    </tr>
                    <tr>
                        <td>Наименование компании</td>
                        <td>ООО «Рога и копыта»</td>
                    </tr>
                    <tr>
                        <td>ИНН / КПП</td>
                        <td>7799123456 / 77010010</td>
                    </tr>
                    <tr>
                        <td>ОГРН</td>
                        <td>1112234222321</td>
                    </tr>
                    <tr>
                        <td>Статус</td>
                        <td class="green">Действующее</td>
                    </tr>
                    <tr>
                        <td>Дата регистрации</td>
                        <td>01.01.2010</td>
                    </tr>
                    <tr>
                        <td>Основной вид деятельности</td>
                        <td>46.01 Производства металла</td>
                    </tr>
                    <tr>
                        <td>Иные разрешенные виды деятельности</td>
                        <td>46.01 Производства металла <br>
                            46.01 Производства металла <br>
                            46.01 Производства металла
                        </td>
                    </tr>
                    <tr>
                        <td>Юридический адрес</td>
                        <td>123007, гор. Москва, ул. Академика Королева, д. 12</td>
                    </tr>
                    <tr>
                        <td>Пенсионный фонд</td>
                        <td>123123123123</td>
                    </tr>
                    <tr>
                        <td>Фонд социального страхования</td>
                        <td>123123123123</td>
                    </tr>
                    <tr>
                        <td>Учредители</td>
                        <td>Петров Петр Петрович <br>
                            ИНН 771231231231 <br><br>
                            Петров Петр Петрович <br>
                            ИНН 771231231231
                        </td>
                    </tr>
                    <tr>
                        <td>Руководитель</td>
                        <td>Петров Петр Петрович <br>
                            ИНН 771231231231
                        </td>
                    </tr>
                </table>
                <table class="info_table">
                    <tr>
                        <th colspan="2">учредительство</th>
                    </tr>
                    <tr>
                        <td>Наименование компании</td>
                        <td>ООО «Рога и копыта»</td>
                    </tr>
                    <tr>
                        <td>ИНН / КПП</td>
                        <td>7799123456 / 77010010</td>
                    </tr>
                    <tr>
                        <td>ОГРН</td>
                        <td>1112234222321</td>
                    </tr>
                    <tr>
                        <td>Статус</td>
                        <td class="green">Действующее</td>
                    </tr>
                    <tr>
                        <td>Дата регистрации</td>
                        <td>01.01.2010</td>
                    </tr>
                    <tr>
                        <td>Основной вид деятельности</td>
                        <td>46.01 Производства металла</td>
                    </tr>
                    <tr>
                        <td>Иные разрешенные виды деятельности</td>
                        <td>46.01 Производства металла <br>
                            46.01 Производства металла <br>
                            46.01 Производства металла
                        </td>
                    </tr>
                    <tr>
                        <td>Юридический адрес</td>
                        <td>123007, гор. Москва, ул. Академика Королева, д. 12</td>
                    </tr>
                    <tr>
                        <td>Пенсионный фонд</td>
                        <td>123123123123</td>
                    </tr>
                    <tr>
                        <td>Фонд социального страхования</td>
                        <td>123123123123</td>
                    </tr>
                    <tr>
                        <td>Учредители</td>
                        <td>Петров Петр Петрович <br>
                            ИНН 771231231231 <br><br>
                            Петров Петр Петрович <br>
                            ИНН 771231231231
                        </td>
                    </tr>
                    <tr>
                        <td>Руководитель</td>
                        <td>Петров Петр Петрович <br>
                            ИНН 771231231231
                        </td>
                    </tr>
                </table>
                <table class="info_table">
                    <tr>
                        <th colspan="2">ЗАДОЛЖЕННОСТЬ ПЕРЕД ГОСУДАРСТВЕННЫМИ ОРГАНАМИ</th>
                    </tr>
                    <tr>
                        <td>Вид задолженности</td>
                        <td>Транспортный налог</td>
                    </tr>
                    <tr>
                        <td>Категория</td>
                        <td>Пени</td>
                    </tr>
                    <tr>
                        <td>Сумма задолженност</td>
                        <td>234 рубля</td>
                    </tr>
                    <tr>
                        <td>Номер начисления</td>
                        <td>1234567890123890</td>
                    </tr>
                    <tr class="big_border">
                        <td>Налоговая инспекция</td>
                        <td>ИФНС № 46 по гор. Москве</td>
                    </tr>
                    <tr>
                        <td>Вид задолженности</td>
                        <td>Транспортный налог</td>
                    </tr>
                    <tr>
                        <td>Категория</td>
                        <td>Начисление</td>
                    </tr>
                    <tr>
                        <td>Сумма задолженности</td>
                        <td>10 235 рублей</td>
                    </tr>
                    <tr>
                        <td>Номер начисления</td>
                        <td>1234567890123890</td>
                    </tr>
                    <tr>
                        <td>Налоговая инспекция</td>
                        <td>ИФНС № 46 по гор. Москве</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="mid">Общее количество задолженностей – <strong>2</strong>, на общую сумму <strong>10 469 рублей</strong></td>
                    </tr>
                </table>
                <table class="info_table">
                    <tr>
                        <th colspan="2">ИСПОЛНИТЕЛЬНЫЕ ПРОИЗВОДСТВА</th>
                    </tr>
                    <tr>
                        <td colspan="2" class="mid_blue">Действующие</td>
                    </tr>
                    <tr>
                        <td>Номер исполнительного производства</td>
                        <td>12312312-ИП</td>
                    </tr>
                    <tr>
                        <td>12312312-ИП</td>
                        <td>12.12.2016</td>
                    </tr>
                    <tr>
                        <td>Регион начисления</td>
                        <td>гор. Москва</td>
                    </tr>
                    <tr>
                        <td>Вид документа</td>
                        <td>Исполнительный лист</td>
                    </tr>
                    <tr>
                        <td>Дата документа</td>
                        <td>01.12.2016</td>
                    </tr>
                    <tr>
                        <td>Номер документа</td>
                        <td>123123123123</td>
                    </tr>
                    <tr>
                        <td>Орган, возбудивший производство</td>
                        <td>Московский районный суд</td>
                    </tr>
                    <tr>
                        <td>Предмет исполнения</td>
                        <td>Штраф ГИБДД</td>
                    </tr>
                    <tr>
                        <td>Сумма задолженности</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="mid_blue">Оконченные</td>
                    </tr>
                    <tr>
                        <td>Номер исполнительного производства</td>
                        <td>12312312-ИП</td>
                    </tr>
                    <tr>
                        <td>12312312-ИП</td>
                        <td>12.12.2016</td>
                    </tr>
                    <tr>
                        <td>Регион начисления</td>
                        <td>гор. Москва</td>
                    </tr>
                    <tr>
                        <td>Вид документа</td>
                        <td>Исполнительный лист</td>
                    </tr>
                    <tr>
                        <td>Дата документа</td>
                        <td>01.12.2016</td>
                    </tr>
                    <tr>
                        <td>Номер документа</td>
                        <td>123123123123</td>
                    </tr>
                    <tr>
                        <td>Орган, возбудивший производство</td>
                        <td>Московский районный суд</td>
                    </tr>
                    <tr>
                        <td>Предмет исполнения</td>
                        <td>Штраф ГИБДД</td>
                    </tr>
                    <tr>
                        <td>Сумма задолженности</td>
                        <td>500</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="mid">Общее количество действующих исполнительных производств – <strong>2</strong>, на общую сумму <strong>1000 рублей.</strong></td>
                    </tr>
                </table>
                <table class="info_table">
                    <tr>
                        <th colspan="2">НАХОЖДЕНИЕ В РОЗЫСКЕ</th>
                    </tr>
                    <tr>
                        <td>Интерпол, красные карточки</td>
                        <td>Отсутствует</td>
                    </tr>
                    <tr>
                        <td>Интерпол, желтые карточки</td>
                        <td>Отсутствует</td>
                    </tr>
                    <tr>
                        <td>Федеральный розыск</td>
                        <td>Отсутствует</td>
                    </tr>
                    <tr>
                        <td>Местный розыск</td>
                        <td>Разыскивается по подозрению в совершении преступления, предусмотренного ч. 1 ст. 159 УК РФ</td>
                    </tr>
                    <tr>
                        <td>Нахождение в списках террористов и экстремистов</td>
                        <td>Отсутствует</td>
                    </tr>
                </table>
                <table class="info_table">
                    <tr>
                        <th colspan="2">ИНЫЕ ИСТОЧНИКИ</th>
                    </tr>
                    <tr>
                        <td>Банкротство</td>
                        <td>Не является банкротом</td>
                    </tr>
                    <tr>
                        <td class="word_break">Дисквалифицированные лица</td>
                        <td class="word_break">Не является дисквалифицированным лицом</td>
                    </tr>
                    <tr>
                        <td>Льготы</td>
                        <td>Не имеет</td>
                    </tr>
                </table>
                <p>Лорем ипсум долор сит амет, консестетуэр адипискинг элит, сед диам нонумми нибх юисмод тинсидунт ут лаореет долоре магна аликуам ерат волютпат. Ут виси еним ад миним вениам,
                    куис ноструд экзерси татион улламкорпер сусципит лобортис нисл ут аликип экс еа коммодо консекат.</p>
            </div>
        </div>
    </section>
    <!-- END REFERENCE SECTION -->


</div>

</body>
</html>