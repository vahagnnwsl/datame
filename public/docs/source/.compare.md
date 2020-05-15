---
title: API Reference

language_tabs:
- php

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://datame.loc/docs/collection.json)

<!-- END_INFO -->

#general


<!-- START_0d5aa3d741708cb5a00931f6f8682c2b -->
## Возвращает информацию по заявке

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("http://datame.loc/api/apps/1", [
    'headers' => [
            "Authorization" => "Bearer {token}",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "status": true,
    "result": {
        "id": 1,
        "identity": "413e1c44f5f35086e38b",
        "name": "Иван",
        "lastname": "Иванов",
        "birthday": "23.01.1986",
        "patronymic": "Иванович",
        "passport_code": "4508 123456",
        "date_of_issue": "27.07.2006",
        "code_department": "770-123",
        "created_at": "13.05.2020",
        "checking_date_last": "13.05.2020 17:58",
        "checking_date_next": "13.05.2020 17:58",
        "checking_count": 0,
        "city_birth": null,
        "inn": "771122334455",
        "snils": null,
        "ip": "127.0.0.1",
        "user": null,
        "status": 4,
        "services": {
            "completed": "100%",
            "completed_success": "100%",
            "list": [
                {
                    "type": 1,
                    "status": 4,
                    "message": null,
                    "name": "Проверка паспорта"
                },
                {
                    "type": 2,
                    "status": 4,
                    "message": null,
                    "name": "Поиск ИНН"
                },
                {
                    "type": 3,
                    "status": 4,
                    "message": null,
                    "name": "Задолженность перед государственными органами"
                },
                {
                    "type": 4,
                    "status": 4,
                    "message": null,
                    "name": "Исполнительные производства"
                },
                {
                    "type": 5,
                    "status": 4,
                    "message": null,
                    "name": "Интерпол, красные карточки"
                },
                {
                    "type": 6,
                    "status": 4,
                    "message": null,
                    "name": "Интерпол, желтые карточки"
                },
                {
                    "type": 7,
                    "status": 4,
                    "message": null,
                    "name": "Нахождение в списках террористов и экстремистов"
                },
                {
                    "type": 8,
                    "status": 4,
                    "message": null,
                    "name": "Федеральный розыск"
                },
                {
                    "type": 9,
                    "status": 4,
                    "message": null,
                    "name": "Местный розыск"
                },
                {
                    "type": 10,
                    "status": 4,
                    "message": null,
                    "name": "Дисквалифицированные лица"
                },
                {
                    "type": 11,
                    "status": 4,
                    "message": null,
                    "name": "Банкротство"
                },
                {
                    "type": 12,
                    "status": 4,
                    "message": null,
                    "name": "Руководство и учредительство"
                },
                {
                    "type": 13,
                    "status": 4,
                    "message": null,
                    "name": "Проверка кода подразделения"
                }
            ],
            "message": "Заявка успешно обработана"
        },
        "extend": {
            "name_en": "Ivan",
            "lastname_en": "Ivanov",
            "patronymic_en": "Ivanovich",
            "current_age": 34,
            "passport": {
                "is_valid": "Паспорт действительный",
                "status": "Паспорт выдан вовремя",
                "passport_date_replace": "23.12.0000",
                "attachment": null,
                "passport_serie_year": 1,
                "passport_serie_region": 1,
                "who_issue": []
            },
            "tax": {
                "amount": "67,63",
                "items": [
                    {
                        "id": 1,
                        "article": "Транспортный налог с физических лиц (пени по соответствующему платежу)",
                        "date_protocol": "14.04.2019",
                        "number": "1234567890123890",
                        "name": "МРИ ФНС России №11 по Московской области",
                        "amount": "67,63",
                        "inn": "5043024703"
                    }
                ]
            },
            "fssp": {
                "amount": "219.564,00",
                "proceed": [
                    {
                        "id": 1,
                        "amount": "219.564,00",
                        "number": "11111\/13\/33\/11 от 10.01.2019",
                        "name_poluch": "УФК ПО Г.МОСКВЕ (ГАГАРИНСКИЙ ОСП УФССП РОССИИ ПО Г.МОСКВЕ Л\/С 05731A53600)",
                        "nazn": "плата задолженности по ИП № 11111\/13\/33\/11 от 10.01.201",
                        "date_protocol": "10.01.2019"
                    }
                ],
                "finished": [
                    {
                        "id": 2,
                        "amount": "0,00",
                        "number": "22222\/14\/33\/11 от 10.02.2019",
                        "name_poluch": "УФК ПО Г.МОСКВЕ (ГАГАРИНСКИЙ ОСП УФССП РОССИИ ПО Г.МОСКВЕ Л\/С 05731A53600)",
                        "nazn": "плата задолженности по ИП № 22222\/14\/33\/11 от 10.02.201",
                        "date_protocol": "10.02.2019"
                    }
                ]
            },
            "wanted": {
                "interpol_red": "В розыске отсутствует",
                "interpol_yellow": "В розыске отсутствует",
                "fed_fsm": "В розыске отсутствует",
                "mvd_wanted": "В розыске отсутствует",
                "fssp_wanted": "В розыске отсутствует"
            },
            "other": {
                "disq": [
                    {
                        "result": "Не является дисквалифицированным лицом",
                        "period": null,
                        "start_date": null,
                        "end_date": null,
                        "org_position": null,
                        "name_org_protocol": null
                    }
                ],
                "debtor": [
                    {
                        "result": "Не является банкротом",
                        "category": null,
                        "ogrnip": null,
                        "snils": null,
                        "region": null,
                        "live_address": null
                    }
                ]
            },
            "business": {
                "ul": [
                    {
                        "naim_ul_sokr": "ООО \"ТТК ПЛЮС\"",
                        "naim_ul_poln": "ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ \"TTK Плюс\"",
                        "activnost": "Ликвидировано",
                        "inn": "1234567890",
                        "kpp": "773301001",
                        "obr_data": "08.02.2019",
                        "adres": "123007, гор. Москва, ул. Академика Королева, д. 12",
                        "kod_okved": "45.1",
                        "naim_okved": "Торговля автотранспортными средствами",
                        "rukovoditel": "Генеральный директор Иванов Иван Иванович"
                    }
                ],
                "ip": [
                    {
                        "naim_vid_ip": "Индивидуальный предприниматель",
                        "familia": "Иванов",
                        "imia": "Иван",
                        "otchestvo": "Иванович",
                        "activnost": "Действующий",
                        "innfl": "12345678905",
                        "data_ogrnip": "13.04.2004",
                        "naim_stran": "",
                        "kod_okved": "45.2",
                        "naim_okved": "Техническое обслуживание и ремонт автотранспортных средств"
                    }
                ]
            },
            "trust": {
                "all_amount": 219631.63,
                "value": 58,
                "services": [
                    {
                        "name": "Паспорт",
                        "status": true
                    },
                    {
                        "name": "Руководство и учредительство",
                        "status": true
                    },
                    {
                        "name": "Нахождение в розыске",
                        "status": true
                    },
                    {
                        "name": "Задолженность перед госорганами",
                        "status": true
                    },
                    {
                        "name": "Иные источники",
                        "status": true
                    },
                    {
                        "name": "Исполнительные производства",
                        "status": false
                    }
                ],
                "all_amount_formatted": "219.631,63",
                "parts": [
                    {
                        "name": "Паспорт действительный",
                        "value": 40
                    },
                    {
                        "name": "Паспорт действительный, установлен инн",
                        "value": 10
                    },
                    {
                        "name": "Задолженности перед госорганами до 500",
                        "value": 8
                    },
                    {
                        "name": "2 фирмы или ИП в сумме",
                        "value": 5
                    },
                    {
                        "name": "Не является банкротом",
                        "value": 5
                    },
                    {
                        "name": "Год соответствует серии",
                        "value": 5
                    },
                    {
                        "name": "Серия соответствет региону",
                        "value": 5
                    },
                    {
                        "name": "Есть действующие фссп от 10000 рублей",
                        "value": -10
                    },
                    {
                        "name": "Не является дисквалифицированным лицом",
                        "value": 5
                    },
                    {
                        "name": "Задолженность от 100.000 до 250.000",
                        "value": -15
                    }
                ]
            }
        }
    }
}
```
> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/apps/{app_id}`


<!-- END_0d5aa3d741708cb5a00931f6f8682c2b -->

<!-- START_a83411c32f769661cfbb969eedb3e732 -->
## Возвращает short информацию getAppShort

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("http://datame.loc/api/apps/short/1", [
    'headers' => [
            "Authorization" => "Bearer {token}",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/apps/short/{app_id}`


<!-- END_a83411c32f769661cfbb969eedb3e732 -->

<!-- START_a5d86f4cc79e75df39d8fe9574d1d9b3 -->
## Возвращает список заявок пользователя getApps

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("http://datame.loc/api/apps/filter/1/1", [
    'headers' => [
            "Authorization" => "Bearer {token}",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`POST api/apps/filter/{page?}/{limit?}`


<!-- END_a5d86f4cc79e75df39d8fe9574d1d9b3 -->

<!-- START_7470ac70812ff53ed511a45d05c966df -->
## Возвращает список всех заявок от пользователей getAppsAll

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("http://datame.loc/api/apps/filter/all/1/1", [
    'headers' => [
            "Authorization" => "Bearer {token}",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



### HTTP Request
`POST api/apps/filter/all/{page?}/{limit?}`


<!-- END_7470ac70812ff53ed511a45d05c966df -->

<!-- START_a769132040e7eb051141abba61516aee -->
## Создать заявку store

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("http://datame.loc/api/apps/store", [
    'headers' => [
            "Authorization" => "Bearer {token}",
        ],
    'query' => [
            "passport_code" => "ullam",
            "date_of_issue" => "doloremque",
            "birthday" => "in",
            "lastname" => "qui",
            "name" => "odit",
            "patronymic" => "ullam",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```


> Example response (200):

```json
{
    "id": 3,
    "identity": "ee7731b232e4ebc504c4",
    "name": "Эдуард",
    "lastname": "Вырыпаев",
    "birthday": "10.02.1969",
    "patronymic": "Геннадьевич",
    "passport_code": "4513 426808",
    "date_of_issue": "28.02.2014",
    "code_department": null,
    "created_at": "13.05.2020",
    "checking_date_last": "13.05.2020 20:12",
    "checking_date_next": "13.05.2020 20:12",
    "inn": null,
    "snils": null,
    "ip": "127.0.0.1",
    "user": null,
    "status": 1,
    "services": {
        "completed": "0%",
        "completed_success": "0%",
        "list": [
            {
                "type": 1,
                "status": 1,
                "message": null,
                "name": "Проверка паспорта"
            },
            {
                "type": 2,
                "status": 1,
                "message": null,
                "name": "Поиск ИНН"
            },
            {
                "type": 3,
                "status": 1,
                "message": null,
                "name": "Задолженность перед государственными органами"
            },
            {
                "type": 4,
                "status": 1,
                "message": null,
                "name": "Исполнительные производства"
            },
            {
                "type": 5,
                "status": 1,
                "message": null,
                "name": "Интерпол, красные карточки"
            },
            {
                "type": 6,
                "status": 1,
                "message": null,
                "name": "Интерпол, желтые карточки"
            },
            {
                "type": 7,
                "status": 1,
                "message": null,
                "name": "Нахождение в списках террористов и экстремистов"
            },
            {
                "type": 8,
                "status": 1,
                "message": null,
                "name": "Федеральный розыск"
            },
            {
                "type": 9,
                "status": 1,
                "message": null,
                "name": "Местный розыск"
            },
            {
                "type": 10,
                "status": 1,
                "message": null,
                "name": "Дисквалифицированные лица"
            },
            {
                "type": 11,
                "status": 1,
                "message": null,
                "name": "Банкротство"
            },
            {
                "type": 12,
                "status": 1,
                "message": null,
                "name": "Руководство и учредительство"
            },
            {
                "type": 13,
                "status": 1,
                "message": null,
                "name": "Проверка кода подразделения"
            }
        ],
        "message": "Ожидайте. Заявка поставлена в очередь на обработку!"
    }
}
```

### HTTP Request
`POST api/apps/store`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    passport_code |  required  | ex. 4513 426808
    date_of_issue |  optional  | optional ex. format 01.01.2001
    birthday |  required  | ex. format 01.01.2001
    lastname |  required  | 
    name |  required  | 
    patronymic |  optional  | optional

<!-- END_a769132040e7eb051141abba61516aee -->


