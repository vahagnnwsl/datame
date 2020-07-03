<template>
    <!-- REFERENCE SECTION -->
    <section class="reference_section" id="reference_section">
        <div class="container">
            <div class="wrapper">

                <div class="row" v-if="app">

                    <div class="coefficient_block">
                        <div class="col">
                            <div class="ball_item">
                                <div class="circle">
                                    <img src="/img/ball.png" alt="img">
                                    <p><span class="spincrement">{{ app.extend.trust.value }}%</span></p>
                                </div>
                                <p>Коэффициент доверия</p>
                            </div>
                        </div>
                        <!--                        -->
                        <div class="col">
                            <ul>
                                <template v-for="(service,key) in app.extend.trust.services"
                                          v-if="service.name !== 'Задолженности отсутствуют'">
                                    <template v-if="status">
                                        <li v-bind:class="{ no: service.status === false }">
                                            {{ service.name }}
                                        </li>
                                    </template>
                                    <template v-else>
                                        <li v-if="service.is_finished" v-bind:class="{ no: service.status === false }">
                                            {{ service.name }}
                                        </li>
                                        <li v-else class="progress-li">
                                            {{ service.name }}
                                        </li>

                                    </template>


                                </template>
                                <template v-if="app.extend.trust.all_amount > 0">
                                    <li class="no">
                                        Общая задолженность: <span style="font-weight: bold">{{ app.extend.trust.all_amount_formatted }}</span>
                                        рублей
                                    </li>
                                </template>
                                <template v-else>
                                    <li v-if="status">
                                        Задолженности отсутствуют
                                    </li>
                                    <li v-if="serviceStatus(3).status !== 4" class="progress-li">
                                        Задолженности отсутствуют
                                    </li>
                                </template>
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
                            <td>{{ app.lastname }} {{ app.name}} {{ app.patronymic}}</td>
                        </tr>
                        <tr>
                            <td>Фамилия, имя, отчество на английском</td>
                            <td>{{ app.extend.lastname_en }} {{ app.extend.name_en }} {{ app.extend.patronymic_en }}
                            </td>
                        </tr>
                        <tr>
                            <td>Дата рождения</td>
                            <td>{{ app.birthday }}</td>
                        </tr>
                        <tr>
                            <td>Полный возраст</td>
                            <td>{{ app.extend.current_age }} лет</td>
                        </tr>
                        <tr v-if="app.city_birth != null">
                            <td>Место рождения</td>
                            <td>{{ app.city_birth }}</td>
                        </tr>

                        <tr>
                            <td>ИНН</td>
                            <template v-if="serviceNotRespond(12)">
                                <td>{{ service_error_message }}</td>
                            </template>
                            <template v-else>

                                <template v-if="serviceMessage(2) != null">
                                    <td>{{ serviceMessage(2) }}</td>
                                </template>

                                <template v-else>
                                    <td v-if="app.inn != null">{{ app.inn}}
                                        <a target='_blank' v-bind:href="'/storage/pdf/'+app.inn+'.pdf'" download
                                           class="red">Скачать</a>
                                    </td>
                                    <td v-else>

                                       <span v-if="serviceStatus(2).status === 4">
                                           ИНН не найден. Возможные причины:
                                        <ul>
                                            <li class="no">человек недавно получил паспорт, но указанная информация еще
                                                не поступила в ИФНС
                                            </li>
                                            <li class="no">документы в ИФНС на присвоение ИНН поданы, но ИНН еще не
                                                получен
                                            </li>
                                        </ul>
                                       </span>
                                        <span v-else>
                                              <span class="sp"></span>
                                        </span>

                                    </td>
                                </template>
                            </template>
                        </tr>


                    </table>

                    <!--Паспорт-->
                    <table class="info_table">
                        <tr>
                            <th colspan="2">Паспорт</th>
                        </tr>
                        <tr>
                            <td>Серия и номер</td>
                            <td>{{ app.passport_code }}</td>
                        </tr>
                        <tr>
                            <td>Дата выдачи</td>
                            <td>{{ app.date_of_issue }} года</td>
                        </tr>
                        <tr v-if="app.extend.passport.who_issue.length > 0">
                            <td>Кем выдан</td>
                            <td>
                                <ul v-for="item in app.extend.passport.who_issue">
                                    <li style="background-image: none;">{{ item.name }}</li>
                                </ul>
                            </td>
                        </tr>
                        <tr v-if="app.extend.passport.who_issue.length === 0 && app.code_department != null">
                            <td>Кем выдан</td>
                            <td>
                                Указанный код подразделения в базе отсутствует
                            </td>
                        </tr>
                        <tr v-if="app.code_department != null">
                            <td>Код подразделения</td>
                            <td>{{ app.code_department }}</td>
                        </tr>
                        <tr>
                            <td>Состояние (по данным МВД РФ)</td>
                            <td v-if="app.extend.passport.is_valid"> {{ app.extend.passport.is_valid}}</td>
                            <td v-if="!app.extend.passport.is_valid && !status">
                                <span class="sp"></span>
                            </td>
                        </tr>
                        <tr v-if="app.extend.passport.passport_date_replace != null">
                            <td>Срок действия</td>
                            <td>{{ app.extend.passport.passport_date_replace }}</td>
                        </tr>
                        <tr v-if="app.extend.passport.is_valid === 'Паспорт действительный'">
                            <td>Кросс-проверка</td>
                            <td>
                                <ul>
                                    <template v-if="app.extend.passport.passport_serie_year != null">
                                        <template v-if="parseInt(app.extend.passport.passport_serie_year) === 1">
                                            <li>Серия паспорта соответствует дате выдачи паспорта</li>
                                        </template>
                                        <template v-else>
                                            <li class="no">Год выдачи паспорта (по дате выдачи) больше или меньше года
                                                серии паспорта на 3 года
                                            </li>
                                        </template>
                                    </template>
                                    <template
                                        v-if="app.extend.passport.passport_serie_region != null && app.code_department != null">
                                        <template v-if="parseInt(app.extend.passport.passport_serie_region) === 1">
                                            <li>Серия паспорта соответствует региону выдачи паспорта</li>
                                        </template>
                                        <template v-else>
                                            <li class="no">Серия паспорта не соответствует региону выдачи паспорта</li>
                                        </template>
                                    </template>
                                </ul>
                            </td>
                        </tr>
                        <tr v-if="app.extend.passport.attachment != null">
                            <td>Принадлежность подразделения</td>
                            <td>{{ app.extend.passport.attachment}}</td>
                            <td v-if="!app.extend.passport.attachment && !status">
                                <span class="sp"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Дополнительная информация</td>

                            <template v-if="status">
                                <td>
                                    {{ app.extend.passport.status }}
                                </td>
                            </template>
                            <template v-else>
                                <td v-if="serviceStatus(1).status === 4">
                                    {{ app.extend.passport.status }}
                                </td>
                                <td v-else>
                                    <span class="sp"></span>
                                </td>
                            </template>
                        </tr>
                    </table>

                    <!--руководство и учредительство-->
                    <table class="info_table" v-if="serviceNotRespond(12)">
                        <tr>
                            <th colspan="2"> РУКОВОДСТВО И УЧРЕДИТЕЛЬСТВО</th>
                        </tr>
                        <tr>
                            <td colspan="2" class="mid">{{ service_error_message }}</td>
                        </tr>
                    </table>
                    <template v-else>

                        <table class="info_table" v-if="serviceMessage(12) != null">
                            <tr>
                                <th colspan="2">РУКОВОДСТВО И УЧРЕДИТЕЛЬСТВО</th>
                            </tr>
                            <tr>
                                <td colspan="2" class="mid">{{ serviceMessage(12) }}</td>
                            </tr>
                        </table>

                        <template v-else>
                            <!--Юридическое лицо-->
                            <table class="info_table">

                                <tr>
                                    <th colspan="2">Юридическое лицо</th>
                                </tr>

                                <template v-for="item in app.extend.business.ul">

                                    <tr>
                                        <td>Сокращенное наименование юридического лица</td>
                                        <td>{{ item.naim_ul_sokr }}</td>
                                    </tr>
                                    <tr>
                                        <td>Полное наименование юридического лица</td>
                                        <td>{{ item.naim_ul_poln }}</td>
                                    </tr>
                                    <tr>
                                        <td>Статус</td>
                                        <td>{{ item.activnost }}</td>
                                    </tr>
                                    <tr>
                                        <td>ИНН</td>
                                        <td>{{ item.inn }}
                                            <a target='_blank' v-bind:href="'/storage/pdf/'+item.inn+'.pdf'" download
                                               class="red">Скачать</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>КПП</td>
                                        <td>{{ item.kpp }}</td>
                                    </tr>
                                    <tr>
                                        <td>Дата регистрации</td>
                                        <td>{{ item.obr_data }}</td>
                                    </tr>
                                    <tr>
                                        <td>Юридический адрес</td>
                                        <td>{{ item.adres }}</td>
                                    </tr>
                                    <tr>
                                        <td>Код по Общероссийскому классификатору видов экономической деятельности</td>
                                        <td>{{ item.kod_okved }}</td>
                                    </tr>
                                    <tr>
                                        <td>Наименование вида деятельности по Общероссийскому классификатору видов
                                            экономической деятельности
                                        </td>
                                        <td>{{ item.naim_okved }}</td>
                                    </tr>
                                    <tr class="big_border">
                                        <td>Руководитель</td>
                                        <td>{{ item.rukovoditel}}</td>
                                    </tr>
                                    s

                                </template>

                                <tr v-if="app.extend.business.ul.length === 0">
                                    <td colspan="2" class="mid" v-if="serviceStatus(12).status === 4">Не является
                                        руководителем или совладельцем
                                        коммерческих
                                        структур.
                                    </td>
                                    <td colspan="2" class="mid" v-if="!status && serviceStatus(12).status !== 4">
                                        <span class="sp"></span>
                                    </td>
                                </tr>

                            </table>

                            <!--Индивидуальный предприниматель-->
                            <table class="info_table">

                                <tr>
                                    <th colspan="2">Индивидуальный предприниматель</th>
                                </tr>

                                <template v-for="item in app.extend.business.ip">

                                    <tr>
                                        <td>Вид</td>
                                        <td>{{ item.naim_vid_ip }}</td>
                                    </tr>
                                    <tr>
                                        <td>Фамилия, имя, отчество</td>
                                        <td>{{ item.familia }} {{ item.imia }} {{ item.otchestvo }}</td>
                                    </tr>
                                    <tr>
                                        <td>Статус</td>
                                        <td>{{ item.activnost }}</td>
                                    </tr>
                                    <tr>
                                        <td>ИНН</td>
                                        <td>{{ item.innfl}}</td>
                                    </tr>
                                    <tr>
                                        <td>Дата регистрации</td>
                                        <td>{{ item.data_ogrnip }}</td>
                                    </tr>
                                    <tr>
                                        <td>Страна</td>
                                        <td>{{ item.naim_stran }}</td>
                                    </tr>
                                    <tr>
                                        <td>Код по Общероссийскому классификатору видов экономической деятельности</td>
                                        <td>{{ item.kod_okved }}</td>
                                    </tr>
                                    <tr class="big_border">
                                        <td>Наименование вида деятельности по Общероссийскому классификатору видов
                                            экономической деятельности
                                        </td>
                                        <td>{{ item.naim_okved }}</td>
                                    </tr>

                                </template>

                                <tr v-if="app.extend.business.ip.length === 0">
                                    <td colspan="2" class="mid" v-if="serviceStatus(12).status === 4">Не является
                                        индивидуальным
                                        предпринимателем.
                                    </td>
                                    <td colspan="2" class="mid" v-if="!status && serviceStatus(12).status !== 4">
                                        <span class="sp"></span>
                                    </td>
                                </tr>

                            </table>

                        </template>

                    </template>

                    <!--Налоги-->
                    <table class="info_table">

                        <tr>
                            <th colspan="2">ЗАДОЛЖЕННОСТЬ ПЕРЕД ГОСУДАРСТВЕННЫМИ ОРГАНАМИ</th>
                        </tr>

                        <template v-if="serviceNotRespond(3)">
                            <tr>
                                <td colspan="2" class="mid">{{ service_error_message }}</td>
                            </tr>
                        </template>
                        <template v-else>

                            <tr v-if="serviceMessage(3) != null">
                                <td colspan="2" class="mid">{{ serviceMessage(3) }}</td>
                            </tr>
                            <template v-else>

                                <template v-for="item in app.extend.tax.items">
                                    <tr>
                                        <td>Вид задолженности</td>
                                        <td>{{ item.article}}</td>
                                    </tr>
                                    <!--<tr>-->
                                    <!--<td>Категория</td>-->
                                    <!--<td>Пени</td>-->
                                    <!--</tr>-->
                                    <tr>
                                        <td>Сумма задолженности</td>
                                        <td>{{ item.amount }} рубля</td>
                                    </tr>
                                    <tr>
                                        <td>Номер начисления</td>
                                        <td>{{ item.number }}</td>
                                    </tr>
                                    <tr class="big_border">
                                        <td>Налоговая инспекция</td>
                                        <td>{{ item.name}}</td>
                                    </tr>
                                </template>

                                <tr v-if="app.extend.tax.items.length > 0">
                                    <td colspan="2" class="mid">Общее количество задолженностей – <strong>{{ taxCount
                                        }}</strong>, на общую сумму <strong>{{ taxAmount }} рублей</strong></td>
                                </tr>

                                <tr v-if="app.extend.tax.items.length === 0">
                                    <td colspan="2" class="mid" v-if="serviceStatus(3).status === 4">
                                        Задолженности не найдены
                                    </td>

                                    <td colspan="2" class="mid" v-if="!status &&   serviceStatus(3).status !== 4">
                                        <span class="sp"></span>
                                    </td>


                                </tr>

                            </template>

                        </template>

                    </table>

                    <!--Испольныетельные производства-->
                    <table class="info_table">

                        <tr>
                            <th colspan="2">ИСПОЛНИТЕЛЬНЫЕ ПРОИЗВОДСТВА</th>
                        </tr>

                        <template v-if="serviceNotRespond(4)">
                            <tr v-if="status">
                                <td colspan="2" class="mid">{{ service_error_message }}</td>
                            </tr>
                            <td colspan="2" class="mid" v-else>
                                <span class="sp"></span>
                            </td>
                        </template>
                        <template v-else>

                            <tr v-if="app.extend.fssp.proceed.length > 0">
                                <td colspan="2" class="mid_blue">Действующие</td>
                            </tr>

                            <template v-for="item in app.extend.fssp.proceed">
                                <tr>
                                    <td>Номер исполнительного производства</td>
                                    <td>{{ item.number }}</td>
                                </tr>
                                <tr>
                                    <td>Регион начисления</td>
                                    <td>{{ item.name_poluch }}</td>
                                </tr>
                                <tr>
                                    <td>Вид документа</td>
                                    <td>{{ item.nazn }}</td>
                                </tr>
                                <tr class="big_border">
                                    <td class="big_border">Сумма задолженности</td>
                                    <td>{{ item.amount }} рубля</td>
                                </tr>
                            </template>

                            <tr v-if="app.extend.fssp.finished.length > 0">
                                <td colspan="2" class="mid_blue">Оконченные</td>
                            </tr>

                            <template v-for="item in app.extend.fssp.finished">
                                <tr>
                                    <td>Номер исполнительного производства</td>
                                    <td>{{ item.number }}</td>
                                </tr>
                                <tr>
                                    <td>Регион начисления</td>
                                    <td>{{ item.name_poluch }}</td>
                                </tr>
                                <tr class="big_border">
                                    <td>Вид документа</td>
                                    <td>{{ item.nazn }}</td>
                                </tr>
                            </template>

                            <tr v-if="app.extend.fssp.proceed.length > 0">
                                <td colspan="2" class="mid">Общее количество задолженностей – <strong>{{ fsspCount
                                    }}</strong>, на общую сумму <strong>{{ fsspAmount }} рублей</strong></td>
                            </tr>

                            <tr v-if="app.extend.fssp.proceed.length === 0 && app.extend.fssp.finished.length === 0">

                                <template v-if="status">
                                    <td colspan="2" class="mid">
                                        Задолженности не найдены
                                    </td>
                                </template>
                                <template v-else>

                                    <td colspan="2" class="mid" v-if="serviceStatus(4).status === 4">
                                        Задолженности не найдены
                                    </td>
                                    <td colspan="2" class="mid" v-else>
                                        <span class="sp"></span>
                                    </td>

                                </template>


                            </tr>
                        </template>

                    </table>

                    <!--Розыск-->
                    <table class="info_table">
                        <tr>
                            <th colspan="2">НАХОЖДЕНИЕ В РОЗЫСКЕ</th>
                        </tr>
                        <tr>
                            <td>Интерпол, красные карточки</td>
                            <td v-if="serviceNotRespond(5)">{{ service_error_message }}</td>
                            <td v-else>{{ app.extend.wanted.interpol_red }}
                                <span class="sp" v-if="!status && !app.extend.wanted.interpol_red"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Интерпол, желтые карточки</td>
                            <td v-if="serviceNotRespond(6)">{{ service_error_message }}</td>
                            <td v-else>{{ app.extend.wanted.interpol_yellow }}
                                <span class="sp" v-if="!status && !app.extend.wanted.interpol_yellow"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Федеральный розыск</td>
                            <td v-if="serviceNotRespond(8)">{{ service_error_message }}</td>
                            <td v-else>{{ app.extend.wanted.mvd_wanted}}
                                <span class="sp" v-if="!status && !app.extend.wanted.mvd_wanted"></span>

                            </td>
                        </tr>
                        <tr>
                            <td>Местный розыск</td>
                            <td v-if="serviceNotRespond(9)">{{ service_error_message }}</td>
                            <td v-else>{{ app.extend.wanted.fssp_wanted}}

                                <span class="sp" v-if="!status && !app.extend.wanted.fssp_wanted"></span>

                            </td>
                        </tr>
                        <tr>
                            <td>Нахождение в списках террористов и экстремистов</td>
                            <td v-if="serviceNotRespond(7)">{{ service_error_message }}</td>
                            <td v-else>{{ app.extend.wanted.fed_fsm }}
                                <span class="sp" v-if="!status && !app.extend.wanted.fed_fsm"></span>

                            </td>
                        </tr>
                        <tr>
                            <td>Федеральной службе исполнения наказаний</td>
                            <td v-if="serviceNotRespond(14)">>{{ service_error_message }}</td>
                            <td v-else>
                                <div v-html="app.extend.wanted.fed_fsin"></div>
                                <span class="sp" v-if="!status && !app.extend.wanted.fed_fsin"></span>
                            </td>
                        </tr>

                    </table>

                    <!--                    <table class="info_table">-->
                    <!--                        <tr>-->
                    <!--                            <th colspan="2">ФЕДЕРАЛЬНАЯ СЛУЖБА ИСПОЛНЕНИЯ НАКАЗАНИЙ</th>-->
                    <!--                        </tr>-->


                    <!--                        <template v-if="!app.extend.fsin.result">-->

                    <!--                            <tr>-->
                    <!--                                <td colspan="2" class="mid" >-->
                    <!--                                    <span class="sp"></span>-->
                    <!--                                </td>-->
                    <!--                            </tr>-->

                    <!--                        </template>-->

                    <!--                        <template v-else>-->

                    <!--                            <tr v-if="app.extend.fsin.result === 'Отсутствует'">-->
                    <!--                                <td colspan="2" class="mid">{{app.extend.fsin.result}}</td>-->
                    <!--                            </tr>-->

                    <!--                            <tr v-else>-->
                    <!--                                <td class="word_break">Отчет</td>-->
                    <!--                                <td v-html="app.extend.fsin.result"></td>-->
                    <!--                            </tr>-->

                    <!--                            <tr v-if="app.extend.fsin.territorial_authorities">-->
                    <!--                                <td class="word_break">Территориальные органы</td>-->
                    <!--                                <td >{{app.extend.fsin.territorial_authorities}}</td>-->
                    <!--                            </tr>-->

                    <!--                            <tr v-if="app.extend.fsin.federal_authorities">-->
                    <!--                                <td class="word_break">Федеральные органы</td>-->
                    <!--                                <td >{{app.extend.fsin.federal_authorities}}</td>-->
                    <!--                            </tr>-->

                    <!--                        </template>-->

                    <!--                    </table>-->

                    <!--Иные источники-->

                    <table class="info_table">
                        <tr>
                            <th colspan="2">Коммерческие базы данных</th>
                        </tr>
                        <tr v-if="serviceMessage(15) != null">
                            <td class="mid">
                                Информация отсутствует
                            </td>
                        </tr>

                        <template v-else v-for="(data) in app.extend.other.custom_data">
                            <tr v-for="(value,key) in data">
                                <td v-if="value.length > 1">{{key}}</td>
                                <td v-if="value.length > 1">{{value}}</td>
                            </tr>
                        </template>

                        <tr v-if="serviceStatus(15).status !== 4">
                            <td>
                                <span class="sp"></span>
                            </td>
                        </tr>
                    </table>

                    <table class="info_table">
                        <tr>
                            <th colspan="2">ИНЫЕ ИСТОЧНИКИ</th>
                        </tr>
                        <tr>
                            <td class="word_break">Банкротство</td>
                            <td v-if="serviceNotRespond(11)">{{ service_error_message }}</td>
                            <td v-else>
                                <template v-if="serviceMessage(11) != null">
                                    {{ serviceMessage(11) }}
                                </template>
                                <template>
                                    <div v-for="item in app.extend.other.debtor">
                                        <div>{{ item.result }}</div>
                                        <div v-if="item.category != null">{{ item.category }}</div>
                                        <div v-if="item.ogrnip != null">{{ item.ogrnip }}</div>
                                        <div v-if="item.snils != null">{{ item.snils }}</div>
                                        <div v-if="item.region != null">{{ item.region }}</div>
                                        <div v-if="item.live_address != null">{{ item.live_address }}</div>
                                        <br>
                                    </div>
                                    <div v-if="!status  && !app.extend.other.debtor && serviceMessage(11) === null">
                                        <span class="sp"></span>
                                    </div>
                                </template>
                            </td>
                        </tr>
                        <tr>
                            <td class="word_break">Дисквалифицированные лица</td>
                            <td v-if="serviceNotRespond(10)">{{ service_error_message }}</td>
                            <td v-else>
                                <template v-if="serviceMessage(10) != null">
                                    {{ serviceMessage(10) }}
                                </template>
                                <template>
                                    <div v-for="item in app.extend.other.disq">
                                        <div>{{ item.result }}</div>
                                        <div v-if="item.period != null">{{ item.period }}</div>
                                        <div v-if="item.start_date != null">{{ item.start_date }}</div>
                                        <div v-if="item.end_date != null">{{ item.end_date }}</div>
                                        <div v-if="item.org_position != null">{{ item.org_position }}</div>
                                        <div v-if="item.name_org_protocol != null">{{ item.name_org_protocol }}</div>
                                        <br>
                                    </div>
                                    <div v-if="!status &&   serviceStatus(10).status !== 4">
                                        <span class="sp"></span>
                                    </div>
                                </template>
                            </td>
                        </tr>
                        <tr>
                            <td class="word_break">Реестр самозанятых </td>
                            <td v-if="serviceNotRespond(16)">{{ service_error_message }}</td>
                            <td v-else>
                                <template v-if="serviceMessage(16) != null">
                                    Не является плательщиком на профессиональный доход (самозанятым)
                                </template>
                                <template>
                                    <div v-if="app.extend.other.fts">
                                        <div>{{ app.extend.other.fts.message }}</div>
                                    </div>
                                    <div v-if="!status &&   serviceStatus(16).status !== 4">
                                        <span class="sp"></span>
                                    </div>
                                </template>
                            </td>
                        </tr>
                    </table>

                    <!--Результаты с наших баз данных-->



                    <p>КОНФИДЕНЦИАЛЬНОСТЬ. Информация, содержащаяся в данном документе, является конфиденциальной и
                        предназначена исключительно для предполагаемого адресата. Любое распространение
                        данного документа или раскрытие содержащейся в нем информации ЗАПРЕЩАЕТСЯ!</p>
                    <div class="download_block">
                        <div class="wrap">
                            <!--<a href="#reference_section" class="back">Назад</a>-->
                            <a target='_blank' v-bind:href="urlPdf" download class="red">Скачать</a>
                            <!--<a href="#" download class="blue">Скачать</a>-->
                            <!--<a href="#" download class="turquoise">Скачать</a>-->
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </section>
    <!-- END REFERENCE SECTION -->
</template>

<script>
    export default {
        name: "AppReportComponent",
        props: {
            app_id: {
                required: true
            }
        },
        mounted() {

            this.init();
        },
        data() {
            return {

                status: false,
                app: null,
                servicesHeader: [],
                services: [],
                message: null,
                service_error_message: "Сервис не отвечает",
                refreshTime: 3000, // 5сек
                loading: true,
                timerId: null,
            }
        },
        computed: {
            taxCount() {

                return this.app.extend.tax.items.length;
            },
            taxAmount() {
                return this.app.extend.tax.amount;
            },
            fsspCount() {
                return this.app.extend.fssp.proceed.length;
            },
            fsspAmount() {
                return this.app.extend.fssp.amount;
            },
            errors() {
                let errors = [];
                _.forEach(this.services, function (service) {
                    if (service.status === 3)
                        errors.push(service)
                });
                return errors;
            },
            urlPdf() {
                return `/app-report/pdf/${this.app.identity}`;
            }
        },
        methods: {
            init() {
                let self = this;
                self.load();

                self.timerId = setInterval(function () {
                    self.load();
                }, self.refreshTime);
            },
            load() {

                let self = this;
                self.loading = true;
                let url = `/api/apps/${self.app_id}`;
                if (parseInt(self.app_id) === 1)
                    url = `/api/demo`;


                axios.get(url)
                    .then(function (response) {
                        // self.status = response.data.status;
                        //заявка обрабатывается
                        self.status = response.data.status;
                        self.app = response.data.result;

                        self.services = response.data.result.services.list;

                        if (self.status) {
                            if (self.timerId != null)
                                clearInterval(self.timerId);
                        }

                        self.loading = false;
                    })
                    .catch(function (error) {
                    })
            },
            closeWindow() {
                window.close();
            },
            serviceMessage(service_id) {
                let service = _.find(this.services, function (o) {
                    return parseInt(o.type) === parseInt(service_id);
                });
                return service.message != null ? service.message : null;
            },
            serviceNotRespond(service_id) {
                let service = _.find(this.services, function (o) {
                    return parseInt(o.type) === parseInt(service_id);
                });
                return service ? parseInt(service.status) === 3 : true;
            },
            serviceStatus(type) {
                return this.services.filter(service => service.type === type)[0];
            },
            isFinished(arr) {
                var status = false;

                for (let i = 0; i < arr.length; i++) {
                    if (i === 0 && arr[i] === 4) {
                        status = true;
                    } else {
                        if (arr[i - 1] !== arr[i] && !status) {
                            status = false;
                        }
                    }
                }
                return status;
            },
            sortServiceLists(arr) {
                var array = []
                for (var i in arr) {
                    let service = _.find(this.services, function (o) {
                        return parseInt(o.type) === parseInt(arr[i]);
                    });
                    if (service) {
                        array.push(service.status)
                    }
                }
                return array;
            },


        }
    }
</script>

<style scoped>
    .sp {
        background-image: url("/img/Spin-1s-480px.gif") !important;
        content: "";
        display: block;
        float: right;
        width: 40px;
        height: 40px;
        -webkit-border-radius: 50%;
        border-radius: 50%;
        position: absolute;
        top: 10px;
        right: 1px;
        background-repeat: no-repeat;
        -webkit-background-size: 100% 100%;
        -o-background-size: 100% 100%;
        background-size: 100% 100%;
        background-position: center center;
    }

    .progress-li::before {
        background-image: url("/img/Spin-1s-480px.gif") !important;
    }


</style>
