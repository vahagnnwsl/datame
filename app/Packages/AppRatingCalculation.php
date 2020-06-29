<?php


namespace App\Packages;


use App\CheckingList;
use Illuminate\Support\Arr;

class AppRatingCalculation
{
    protected $app;
    protected $result;

    public function __construct(array $app)
    {
        $this->app = $app;
        $this->reInit();
    }

    public function reInit()
    {
        $this->result = [
            'rating' => [
                'all_amount' => 0,
                'value' => 0,
                'parts' => []
            ],
            'services' => [
                0 => [
                    'name' => 'Паспорт',
                    'status' => true,
                    'is_finished' => false,
                    'services' => [1]
                ],
                1 => [
                    'name' => 'Руководство и учредительство',
                    'status' => true,
                    'is_finished' => false,
                    'services' => [12]
                ],
                2 => [
                    'name' => 'Нахождение в розыске',
                    'status' => true,
                    'is_finished' => false,
                    'services' => [5, 6, 7, 8, 9, 14]

                ],
                4 => [
                    'name' => 'Задолженность перед госорганами',
                    'status' => true,
                    'is_finished' => false,
                    'services' => [3]

                ],
                5 => [
                    'name' => 'Исполнительные производства',
                    'status' => true,
                    'is_finished' => false,
                    'services' => [4]

                ],
                6 => [
                    'name' => 'Иные источники',
                    'status' => true,
                    'is_finished' => false,
                    'services' => [10, 11, 16]
                ],
                7 => [
                    'name' => 'Поиск в базах данных',
                    'status' => true,
                    'is_finished' => false,
                    'services' => [15]
                ]
            ]
        ];
    }


    public function calc()
    {
        $this->checkServicesStatus();


        if ($this->app['extend']['passport']['is_valid'] = 'Паспорт действительный') {
            $this->addRating('Паспорт действительный', 40);

            //Если Паспорт действительный, но ИНН нельзя установить
            if (is_null($this->app['inn'])) {
                $this->addRating('Паспорт действительный, но ИНН нельзя установить', -10);
                $this->result['services'][4]['status'] = false;
            } else {

                $this->addRating('Паспорт действительный, установлен инн', 10);

                //Задолженность перед госорганами
                $taxAmount = toDecimalFromFormatNumber($this->app['extend']['tax']['amount']);
                if ($taxAmount == 0) {
                    $this->addRating('Задолженности перед госорганами нет', 10);
                } else if ($taxAmount < 500) {
                    $this->addRating('Задолженности перед госорганами до 500', 8);
                } else if ($taxAmount >= 500 && $taxAmount < 2000) {
                    $this->addRating('Задолженности перед госорганами от 500 до 2000', 0);
                } else if ($taxAmount >= 2000) {
                    $this->addRating('Задолженности перед госорганами от 2000', -10);
                    $this->result['services'][4]['status'] = false;
                }

                //Фирмы и ИП
                $quantityHonest = count($this->app['extend']['business']['ip']) + count($this->app['extend']['business']['ul']);
                if ($quantityHonest == 0) {
                    $this->addRating('Нет фирм и ИП', 10);
                } else if ($quantityHonest == 1) {
                    $this->addRating('1 фирма или ИП в сумме', 8);
                } else if ($quantityHonest == 2) {
                    $this->addRating('2 фирмы или ИП в сумме', 5);
                } else if ($quantityHonest == 3) {
                    $this->addRating('2 фирмы или ИП в сумме', 2);
                } else if ($quantityHonest >= 4 && $quantityHonest < 7) {
                    $this->addRating('Больше 4 фирм и ИП, но меньше 7', 0);
                } else if ($quantityHonest >= 7) {
                    $this->addRating('Больше 7 фирм (включая) или ИП', $quantityHonest * -1);
                    $this->result['services'][1]['status'] = false;
                }

                //банкротство
                if (!is_null($this->app['extend']['other']['debtor'])) {
                    $statusDisq = collect($this->app['extend']['other']['debtor'])->every(function ($item) {
                        return $item['result'] == "Не является банкротом";
                    });
                    if ($statusDisq) {
                        $this->addRating('Не является банкротом', 5);
                    } else {
                        $this->addRating('Является банкротом или другой ответ от сервиса', -7);
                        $this->result['services'][6]['status'] = false;
                    }
                }
            }

        } else {
            $this->addRating('Если Паспорт не действительный (то и ИНН нельзя установить)', -20);
            $this->result['services'][0]['status'] = false;
        }

        //Cоответствие серии
        if ($this->app['extend']['passport']['passport_serie_year']) {
            $this->addRating('Год соответствует серии', 5);
        } else {
            $this->addRating('Год не соответствует серии', -20);
            $this->result['services'][0]['status'] = false;
        }
        //Cоответствие региону
        if ($this->app['extend']['passport']['passport_serie_region']) {
            $this->addRating('Серия соответствет региону', 5);
        } else {
            $this->addRating('Серия не соответствет региону', -20);
            $this->result['services'][0]['status'] = false;
        }

        if ($this->app['extend']['passport']['status'] == "Произведена внеплановая замена паспорта") {
            $this->addRating('Произведена внеплановая замена паспорта', -5);
            $this->result['services'][0]['status'] = false;
        }

        //Исполнительные производства
        $fsspAmount = toDecimalFromFormatNumber($this->app['extend']['fssp']['amount']);
        if ($fsspAmount == 0) {
            //действующих нет
            $fsspFinishedQuantity = count($this->app['extend']['fssp']['finished']);
            if ($fsspFinishedQuantity == 0) {
                $this->addRating('Исполнительных производств нет и не былo', 10);
            } else if ($fsspFinishedQuantity <= 4) {
                $this->addRating('Исполнительных производств действующих нет, но ранее были (от 0 до 4)', 7);
            } else if ($fsspFinishedQuantity > 4 && $fsspFinishedQuantity <= 9) {
                $this->addRating('Исполнительных производств действующих нет, но ранее были (от 5 до 9)', 5);
            } else {
                $this->addRating('Исполнительных производств действующих нет, но ранее были (от 10 и выше)', 0);
            }
        } else {
            if ($fsspAmount < 2000) {
                $this->addRating('Есть действующие фссп до 2000 рублей', 0);
            } else if ($fsspAmount >= 2000 && $fsspAmount < 10000) {
                $this->addRating('Есть действующие фссп от 2000 до 10000 рублей ', -5);
                $this->result['services'][5]['status'] = false;
            } else {
                $this->addRating('Есть действующие фссп от 10000 рублей', -10);
                $this->result['services'][5]['status'] = false;
            }
        }

        //розыск
        if ($this->app['extend']['wanted']['interpol_red'] != "В розыске отсутствует") {
            $this->addRating('Интерпол, красные', -10);
            $this->result['services'][2]['status'] = false;
        }
        if ($this->app['extend']['wanted']['interpol_yellow'] != "В розыске отсутствует") {
            $this->addRating('Интерпол, желтые', -5);
            $this->result['services'][2]['status'] = false;
        }
        if ($this->app['extend']['wanted']['mvd_wanted'] != "В розыске отсутствует") {
            $this->addRating('Федеральный', -10);
            $this->result['services'][2]['status'] = false;
        }
        if ($this->app['extend']['wanted']['fssp_wanted'] != "В розыске отсутствует") {
            $this->addRating('Местный', -10);
            $this->result['services'][2]['status'] = false;
        }
        if ($this->app['extend']['wanted']['fed_fsm'] != "В розыске отсутствует") {
            $this->addRating('Террористы', -10);
            $this->result['services'][2]['status'] = false;
        }
        if ($this->app['extend']['wanted']['fed_fsin'] != "В розыске отсутствует") {
            $this->addRating('Федеральной службе исполнения наказаний', -10);
            $this->result['services'][2]['status'] = false;
        }

        //дисквалифицированные лица
        if (!is_null($this->app['extend']['other']['disq'])) {
            $statusDisq = collect($this->app['extend']['other']['disq'])->every(function ($item) {
                return $item['result'] == "Не является дисквалифицированным лицом";
            });
            $statusDisq ? $this->addRating('Не является дисквалифицированным лицом', 5) : $this->addRating('Является дисквалифицированным лицом или другой ответ от сервиса', 0);
        }

        //Результат поиска в наших бд
        if (empty($this->app['extend']['other']['custom_data'])) {
            $this->result['services'][7]['status'] = false;
        }

        /**
         * В самом верху, справа от коэффициента доверия добавим еще один пункт - "Общая задолженность: __ рублей".
         * Сумма будет суммироваться от ФССП и задолженности перед госорганами.
         * То есть допустим у ФССП 100 рублей, а госорганы 105 рублей, общая задолженность 205 рублей.
         */
        $taxAmount = toDecimalFromFormatNumber($this->app['extend']['tax']['amount']);

        $this->result['rating']['all_amount'] = $taxAmount + $fsspAmount;
        if ($this->result['rating']['all_amount'] >= 100000 && $this->result['rating']['all_amount'] < 250000) {
            $this->addRating('Задолженность от 100.000 до 250.000', -15);
        } else if ($this->result['rating']['all_amount'] >= 250000 && $this->result['rating']['all_amount'] < 750000) {
            $this->addRating('Задолженность от 250.000 до 750.000', -25);
        } else if ($this->result['rating']['all_amount'] >= 750000) {
            $this->addRating('Задолженность свыше 750.000', -50);
        }

        if ($this->result['rating']['value'] > 100)
            $this->result['rating']['value'] = 100;

        if ($this->result['rating']['value'] < 0)
            $this->result['rating']['value'] = 0;

        if ($this->result['rating']['all_amount'] == 0) {
            $this->result['services'][] = [
                'name' => 'Задолженности отсутствуют',
                'status' => true,
            ];
        }
        foreach ($this->result['services'] as $key => $item) {

            if (isset($this->result['services'][$key]['services']) && is_array($this->result['services'][$key]['services'])) {
                $this->result['services'][$key]['is_finished'] = $this->checkServiceIsFinished($this->result['services'][$key]['services']);

            }
        }


        //сортировка коэфициента доверия
        usort($this->result['services'], function ($item1, $item2) {
            return $item2['status'] <=> $item1['status'];
        });


        return $this->result;
    }

    protected function addRating($name, $value)
    {
        $this->result['rating']['value'] = $this->result['rating']['value'] + $value;
        $this->result['rating']['parts'][] = [
            'name' => $name,
            'value' => $value,
        ];
    }

    protected function checkServiceIsFinished(array $services)
    {

        $filtered = $this->app['services']['list']->filter(function ($value, $key) use ($services) {
            return in_array($value['type'], $services);
        });


        return $filtered->every(function ($value, $key) {
            return $value['status'] === 4;
        });


    }

    /**
     * Проверяем какие сервисы с 3 статусом
     */
    protected function checkServicesStatus()
    {
        $this->app['services']['list']->each(function ($item) {
            if ($item['status'] == 3) {
                switch ($item['type']) {
                    case CheckingList::ITEM_PASSPORT:
                        $this->result['services'][0]['status'] = false;
                        break;
                    case CheckingList::ITEM_FIND_FT_SERVICE:
                        $this->result['services'][6]['status'] = false;
                        break;
                    case CheckingList::ITEM_FIND_TAX:
                        $this->result['services'][4]['status'] = false;
                        break;
                    case CheckingList::ITEM_FIND_FSSP:
                        $this->result['services'][5]['status'] = false;
                        break;
                    case CheckingList::ITEM_FIND_FSIN:
                        $this->result['services'][2]['status'] = false;
                        break;
                    case CheckingList::ITEM_FIND_INTERPOL_RED:
                        $this->result['services'][2]['status'] = false;
                        break;
                    case CheckingList::ITEM_FIND_INTERPOL_YELLOW:
                        $this->result['services'][2]['status'] = false;
                        break;
                    case CheckingList::ITEM_FIND_TERRORIST:
                        $this->result['services'][2]['status'] = false;
                        break;
                    case CheckingList::ITEM_FIND_MVD_WANTED:
                        $this->result['services'][2]['status'] = false;
                        break;
                    case CheckingList::ITEM_FIND_FSSP_WANTED:
                        $this->result['services'][2]['status'] = false;
                        break;
                    case CheckingList::ITEM_FIND_DISQ:
                        $this->result['services'][6]['status'] = false;
                        break;
                    case CheckingList::ITEM_FIND_DEBTOR:
                        $this->result['services'][6]['status'] = false;
                        break;
                    case CheckingList::ITEM_FIND_HONEST_BUSINESS:
                        $this->result['services'][1]['status'] = false;
                        break;
                    case CheckingList::ITEM_FIND_CUSTOM_DATA:
                        $this->result['services'][7]['status'] = false;
                        break;
                }
            }
        });
    }


}
