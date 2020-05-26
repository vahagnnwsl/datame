<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-02-22
 * Time: 18:38
 */

namespace App\Packages\Transformer;

use App\App;
use App\CheckingList;
use App\Debtor;
use App\Department;
use App\Disq;
use App\FedFsm;
use App\FindDepartment;
use App\FindDepartmentList;
use App\FindFssp;
use App\FindInn;
use App\FindTax;
use App\FsspWanted;
use App\HonestBusinessIp;
use App\HonestBusinessUl;
use App\InterpolRed;
use App\InterpolYellow;
use App\MvdWanted;
use App\Packages\AppRatingCalculation;
use App\Packages\Constants;
use App\Passport;
use App\User;
use Carbon\Carbon;

class AppTransformer
{
    protected $extend = false;
    protected $withUser = false;

    /**
     * @return bool
     */
    public function isExtend(): bool
    {
        return $this->extend;
    }

    /**
     * @param bool $extend
     * @return AppTransformer
     */
    public function setExtend(bool $extend): AppTransformer
    {
        $this->extend = $extend;
        return $this;
    }

    /**
     * @param bool $withUser
     * @return AppTransformer
     */
    public function setWithUser(bool $withUser): AppTransformer
    {
        $this->withUser = $withUser;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWithUser(): bool
    {
        return $this->withUser;
    }

    public function transform(App $app)
    {
        $checking_completed = 0;
        $checking_completed_success = 0;

        $data = [
            'id' => $app->id,
            'identity' => $app->identity,
            'name' => mb_ucfirst($app->name),
            'lastname' => mb_ucfirst($app->lastname),
            'birthday' => format_date($app->birthday),
            'patronymic' => !is_null($app->patronymic) ? mb_ucfirst($app->patronymic) : '',
            'passport_code' => $app->passport_code,
            'date_of_issue' => format_date($app->date_of_issue),
            'code_department' => $app->code_department,
            'created_at' => format_date($app->created_at),
            'checking_date_last' => format_date_time($app->checking_date_last),
            'checking_date_next' => format_date_time($app->checking_date_next),
            'checking_count' => intval($app->checking_count),
            'city_birth' => null,
            'inn' => null,
            'snils' => null,
            'ip' => $app->ip,
            'user' => null,
            'status' => (int)$app->status,
            'services' => [
                'completed' => 0,
                'completed_success' => 0,
                'list' => $app->checkingList()->get()->transform(function (CheckingList $item) use (&$checking_completed, &$checking_completed_success) {
                    if ($item->status == Constants::CHECKING_STATUS_ERROR || $item->status == Constants::CHECKING_STATUS_SUCCESS) {
                        $checking_completed += 1;
                    }
                    if ($item->status == Constants::CHECKING_STATUS_SUCCESS) {
                        $checking_completed_success += 1;
                    }
                    return self::transformCheckingList($item);
                })
            ]
        ];
        $data['services']['completed'] = round($checking_completed / $app->checkingList()->count() * 100) . "%";
        $data['services']['completed_success'] = round($checking_completed_success / $app->checkingList()->count() * 100) . "%";
        $data['services']['message'] = Constants::getDescAppStatus($app, $data['services']['completed_success']);


        /** @var FindInn $individualInn */
        $individualInn = $app->inn()->where('type_inn', FindInn::INDIVIDUAL_INN)->whereNotNull('inn')->first();
        if (!is_null($individualInn)) {
            $data['inn'] = $individualInn->inn;
        }

        if ($this->isWithUser()) {
            /** @var User $user */
            $user = $app->user()->first();
            if (!is_null($user)) {

                $data['user']['id'] = $user->id;
                $data['user']['name'] = $user->name;
                $data['user']['email'] = $user->email;

                switch ($user->type_user) {
                    case Constants::USER_INDIVIDUAL:
                        $data['user']['name'] = "{$user->lastname} {$user->name}";
                        break;
                    case Constants::USER_LEGAL:
                        $data['user']['name'] = $user->name;
                        break;
                    default:
                        break;
                }
            }
        }

        if ($this->isExtend()) {

            $data['extend'] = [
                'name_en' => mb_ucfirst(rus2translit($app->name)),
                'lastname_en' => mb_ucfirst(rus2translit($app->lastname)),
                'patronymic_en' => !is_null($app->patronymic) ? mb_ucfirst(rus2translit($app->patronymic)) : '',
                'current_age' => $app->birthday->diffInYears(Carbon::now()),
                'passport' => [
                    'is_valid' => null,
                    'status' => null,
                    'passport_date_replace' => null,
                    'attachment' => null,
                    'passport_serie_year' => null,
                    'passport_serie_region' => null,
                    'who_issue' => []
                ],
                'tax' => [
                    'amount' => 0,
                    'items' => []
                ],
                'fssp' => [
                    'amount' => 0,
                    'proceed' => [],
                    'finished' => []
                ],
                'fsin' => [
                    'result' => '',
                    'territorial_authorities' => '',
                    'federal_authorities' => ''
                ],
                'wanted' => [
                    'interpol_red' => null, //интерпол красные карточки
                    'interpol_yellow' => null, //интерпол желтые карточки
                    'fed_fsm' => null, // террористы
                    'mvd_wanted' => null, // федеральный розыск
                    'fssp_wanted' => null, //местный розыск
                ],
                'other' => [
                    'disq' => null,
                    'debtor' => null,
                ],
                'business' => [
                    'ul' => [],
                    'ip' => [],
                ],
                'trust' => [
                    'all_amount' => 0,
                    'value' => 0,
                    'services' => []
                ]

            ];

            /** @var Passport $passport */
            $passport = $app->passport()->first();
            if (!is_null($passport)) {
                //Паспорт валидный
                if ($passport->is_valid) {
                    //состояние
                    $data['extend']['passport']['is_valid'] = 'Паспорт действительный';
                    //Дополнительная информация
                    $data['extend']['passport']['status'] = $passport->status;
                    $data['extend']['passport']['passport_serie_year'] = $passport->passport_serie_year;
                    $data['extend']['passport']['passport_serie_region'] = $passport->passport_serie_region;
                    $data['extend']['passport']['passport_date_replace'] = !is_null($passport->passport_date_replace) ? format_date($passport->passport_date_replace) : 'Паспорт действует пожизненно';
                } else {
                    //состояние
                    $data['extend']['passport']['is_valid'] = 'Паспорт не действительный';
                    //Дополнительная информация
                    $data['extend']['passport']['status'] = $passport->status;
                    $data['extend']['passport']['passport_date_replace'] = null;
                }
            }

            /** @var FindDepartment $department */
            $department = $app->department()->with('branches')->first();
            if (!is_null($department)) {
                switch ($department->type) {
                    case Constants::CODE_DEPARTMENT_GUVM:
                        $data['extend']['passport']['attachment'] = "УФМС (в настоящее время ГУВМ)";
                        break;
                    case Constants::CODE_DEPARTMENT_PVS:
                        $data['extend']['passport']['attachment'] = "Паспортно-визовая служба (ПВС)";
                        break;
                    case Constants::CODE_DEPARTMENT_PVS_REGION:
                        $data['extend']['passport']['attachment'] = "ПВС района или городского уровня";
                        break;
                    case Constants::CODE_DEPARTMENT_VILLAGE:
                        $data['extend']['passport']['attachment'] = "ПВС сельского или городского типа";
                        break;
                    default:
                        $data['extend']['passport']['attachment'] = "Код подразделения введен неверно";
                }
                $department->branches()->each(function (FindDepartmentList $list) use (&$data) {
                    /** @var Department $dep */
                    $dep = $list->department()->first();
                    if (!is_null($dep)) {
                        $data['extend']['passport']['who_issue'][] = [
                            'id' => $dep->id,
                            'name' => $dep->name,
                            'expire' => $dep->expire != null ? format_date($dep->expire) : null
                        ];
                    }
                });

            }

            if (!is_null($individualInn)) {
                $data['extend']['tax']['items'] = $individualInn->tax()->get()->transform(function (FindTax $item) use (&$data) {
                    $data['extend']['tax']['amount'] += $item->amount;
                    return [
                        'id' => $item->id,
//                        'article' => mb_substr($item->article, 0, mb_strpos($item->article, ",")),
                        'article' => $item->article,
                        'date_protocol' => format_date($item->date_protocol),
                        'number' => $item->number,
                        'name' => $item->name,
                        'amount' => formatNumberDecimal($item->amount),
                        'inn' => $item->inn
                    ];
                });
                $data['extend']['tax']['amount'] = formatNumberDecimal($data['extend']['tax']['amount']);

                //Банкротство
                $data['extend']['other']['debtor'] = $individualInn->debtor()->get()->transform(function (Debtor $item) use (&$data) {
                    if (!is_null($item->error_message)) {
                        $dt = [
                            'result' => $item->error_message,
                            'category' => null,
                            'ogrnip' => null,
                            'snils' => null,
                            'region' => null,
                            'live_address' => null,
                        ];
                    } else {
                        if ($item->result == "Является банкротом") {
                            $dt = [
                                'result' => $item->result,
                                'category' => "Категория: {$item->category}",
                                'ogrnip' => "ОГРНИП: " . $item->ogrnip,
                                'snils' => "СНИЛС: " . $item->snils,
                                'region' => "Регион: {$item->region}",
                                'live_address' => "Адрес: {$item->live_address}"
                            ];

                        } else {
                            $dt = [
                                'result' => $item->result,
                                'category' => null,
                                'ogrnip' => null,
                                'snils' => null,
                                'region' => null,
                                'live_address' => null,
                            ];
                        }
                    }
                    return $dt;
                })->toArray();

                //бизнес
                //руководство
                $data['extend']['business']['ul'] = $individualInn->honestBusinessUl()->get()->transform(function (HonestBusinessUl $item) use (&$data) {
                    return [
                        'naim_ul_sokr' => $item->naim_ul_sokr,
                        'naim_ul_poln' => $item->naim_ul_poln,
                        'activnost' => $item->activnost,
                        'inn' => $item->inn,
                        'kpp' => $item->kpp,
                        'obr_data' => format_date($item->obr_data),
                        'adres' => $item->adres,
                        'kod_okved' => $item->kod_okved,
                        'naim_okved' => $item->naim_okved,
                        'rukovoditel' => $item->rukovoditel
                    ];
                })->toArray();

                //Учредительство
                $data['extend']['business']['ip'] = $individualInn->honestBusinessIp()->get()->transform(function (HonestBusinessIp $item) use (&$data) {
                    return [
                        'naim_vid_ip' => $item->naim_vid_ip,
                        'familia' => $item->familia,
                        'imia' => $item->imia,
                        'otchestvo' => $item->otchestvo,
                        'activnost' => $item->activnost,
                        'innfl' => $item->innfl,
                        'data_ogrnip' => format_date($item->data_ogrnip),
                        'naim_stran' => $item->naim_stran,
                        'kod_okved' => $item->kod_okved,
                        'naim_okved' => $item->naim_okved,
                    ];
                })->toArray();
            }

            //фссп задолженности
            $fssp = $app->fssp()->get();

            if ($fssp->isNotEmpty()) {
                $data['extend']['fssp']['amount'] = formatNumberDecimal($fssp->sum("amount"));

                /** @var FindFssp $item */
                foreach ($fssp->all() as $item) {
                    if ($item->amount > 0)
                        $data['extend']['fssp']['proceed'][] = $this->transformFssp($item);
                    else
                        $data['extend']['fssp']['finished'][] = $this->transformFssp($item);

                }
            }

            //поиск по карточкам интерпола
            /** @var InterpolRed $interpolRed */
            $interpolRed = $app->interpolRed()->first();
            if (!is_null($interpolRed)) {
                $data['extend']['wanted']['interpol_red'] = $interpolRed->result;
            }

            /** @var InterpolYellow $interpolYellow */
            $interpolYellow = $app->interpolYellow()->first();
            if (!is_null($interpolYellow)) {
                $data['extend']['wanted']['interpol_yellow'] = $interpolYellow->result;
            }

            /** @var MvdWanted $mvdWanted */
            $mvdWanted = $app->mvdWanted()->first();
            if (!is_null($mvdWanted)) {
                $data['extend']['wanted']['mvd_wanted'] = $mvdWanted->result;
            }

            /** @var FsspWanted $fsspWanted */
            $fsspWanted = $app->fsspWanted()->first();
            if (!is_null($fsspWanted)) {
                $data['extend']['wanted']['fssp_wanted'] = $fsspWanted->result;
            }

            //в списках террористов и экстремистов
            /** @var FedFsm $fedFsm */
            $fedFsm = $app->fedFsm()->first();
            if (!is_null($fedFsm)) {
                $data['extend']['wanted']['fed_fsm'] = $fedFsm->status;

                //устанавливаем год рождения
                if (!is_null($fedFsm->city_birth)) {
                    $data['city_birth'] = $fedFsm->city_birth;
                }
            }

            $disq = $app->disq()->get();
            if ($disq->isNotEmpty()) {
                $disq->each(function (Disq $findItem) use (&$data) {
                    $data['city_birth'] = $findItem->mesto_rogd;
                    if (!is_null($findItem->error_message)) {
                        $data['extend']['other']['disq'][] = [
                            'result' => $findItem->error_message,
                            'period' => null,
                            'start_date' => null,
                            'end_date' => null,
                            'org_position' => null,
                            'name_org_protocol' => null,
                        ];
                    } else {
                        if ($findItem->result == "Является дисквалифицированным лицом") {
                            $data['extend']['other']['disq'][] = [
                                'result' => $findItem->result,
                                'period' => "Срок: {$findItem->discv_srok}",
                                'start_date' => "Дата начала: " . format_date($findItem->data_nach_discv),
                                'end_date' => "Дата окончания: " . format_date($findItem->data_nach_discv),
                                'org_position' => "Организация, должность: {$findItem->naim_org}, {$findItem->dolgnost}",
                                'name_org_protocol' => "Наименование органа, составившего протокол об административном правонарушении: {$findItem->naim_org_prot}"
                            ];

                        } else {
                            $data['extend']['other']['disq'][] = [
                                'result' => $findItem->result,
                                'period' => null,
                                'start_date' => null,
                                'end_date' => null,
                                'org_position' => null,
                                'name_org_protocol' => null,
                            ];
                        }
                    }

                });
            }
            $fsin = $app->fsin()->first();

            if (!is_null($fsin)) {
                if (!$fsin->error_message) {
                    $data['extend']['fsin']['result'] = $fsin->result;
                    if ($fsin->result !== 'Отсутствует') {
                        $data['extend']['fsin']['territorial_authorities'] = $fsin->territorial_authorities;
                        $data['extend']['fsin']['federal_authorities'] = $fsin->federal_authorities;
                    }
                }
            }



            //расчет коэфициента
            $calculation = (new AppRatingCalculation($data))->calc();
            $data['extend']['trust']['value'] = $calculation['rating']['value'];
            $data['extend']['trust']['all_amount'] = $calculation['rating']['all_amount'];
            $data['extend']['trust']['all_amount_formatted'] = formatNumberDecimal($calculation['rating']['all_amount']);
            $data['extend']['trust']['services'] = $calculation['services'];
            $data['extend']['trust']['parts'] = $calculation['rating']['parts'];
        }
        return $data;
    }

    protected function transformFssp(FindFssp $item)
    {
        $startIndex = mb_strpos($item->nazn, ",");
        $endIndex = mb_strpos($item->nazn, '///') - 1;
        $description = mb_substr($item->nazn, $startIndex + 1, $endIndex - $startIndex);

        return [
            'id' => $item->id,
            'amount' => formatNumberDecimal($item->amount),
            'number' => $item->number,
            'name_poluch' => $item->name_poluch,
            'nazn' => trim($description),
            'date_protocol' => format_date($item->date_protocol)
        ];
    }

    public static function transformCheckingList(CheckingList $item)
    {
        $dt = [
            'type' => $item->type,
            'status' => $item->status,
            'message' => $item->message,
        ];

        switch ($item->type) {
            case CheckingList::ITEM_PASSPORT:
                $dt['name'] = 'Проверка паспорта';
                break;
            case CheckingList::ITEM_FIND_INN:
                $dt['name'] = 'Поиск ИНН';
                break;
            case CheckingList::ITEM_FIND_TAX:
                $dt['name'] = 'Задолженность перед государственными органами';
                break;
            case CheckingList::ITEM_FIND_FSSP:
                $dt['name'] = 'Исполнительные производства';
                break;
            case CheckingList::ITEM_FIND_INTERPOL_RED:
                $dt['name'] = 'Интерпол, красные карточки';
                break;
            case CheckingList::ITEM_FIND_INTERPOL_YELLOW:
                $dt['name'] = 'Интерпол, желтые карточки';
                break;
            case CheckingList::ITEM_FIND_TERRORIST:
                $dt['name'] = 'Нахождение в списках террористов и экстремистов';
                break;
            case CheckingList::ITEM_FIND_MVD_WANTED:
                $dt['name'] = 'Федеральный розыск';
                break;
            case CheckingList::ITEM_FIND_FSSP_WANTED:
                $dt['name'] = 'Местный розыск';
                break;
            case CheckingList::ITEM_FIND_DISQ:
                $dt['name'] = 'Дисквалифицированные лица';
                break;
            case CheckingList::ITEM_FIND_DEBTOR:
                $dt['name'] = 'Банкротство';
                break;
            case CheckingList::ITEM_FIND_HONEST_BUSINESS:
                $dt['name'] = 'Руководство и учредительство';
                break;
            case CheckingList::ITEM_FIND_CODE_DEPARTMENT:
                $dt['name'] = 'Проверка кода подразделения';
                break;
            case CheckingList::ITEM_FIND_FSIN:
                $dt['name'] = 'Федеральная служба исполнения наказаний';
                break;
        }

        return $dt;
    }
}
