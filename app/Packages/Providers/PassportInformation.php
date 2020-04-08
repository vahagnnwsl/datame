<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-19
 * Time: 23:08
 */

namespace App\Packages\Providers;


use App\App;
use App\Packages\Loggers\CustomLogger;
use App\Packages\PassportValidChecker;
use App\Region;
use Carbon\Carbon;

class PassportInformation implements IProvider
{
    /**
     * @var App
     */
    private $app;
    private $logger;

    public function __construct(App $app, CustomLogger $logger)
    {
        $this->app = $app;
        $this->logger = $logger;
    }

    /**
     * @return Result|null
     */
    public function check()
    {
        $result = new Result();
        $this->logger->info("passport", $this->app->toArray());

        $passport_serie_year = $this->isValidDateIssue();

        $passport_serie_region = $this->checkPassportCodeAndDepartment();

        //Если паспорт выдан до 01.01.1997 года и после текущей даты, то паспорт недействительный
        if(!$this->isLessTo1997($this->app->date_of_issue) && !$this->isGreaterCurrentDate($this->app->date_of_issue)) {

            $validData = $this->validatePassport();
            if($validData['status']) {

                $ret = [
                    'currentAge' => $this->calculateCurrentAge(),
                    'status' => null,
                    'age14' => $this->calculateDateAge(14),
                    'age20' => $this->calculateDateAge(20),
                    'age45' => $this->calculateDateAge(45),
                    'passport_date_replace' => null, // null - паспорт действует пожизненно,
                    'passport_serie_year' => $passport_serie_year,
                    'passport_serie_region' => $passport_serie_region,
                ];

                if($ret['currentAge'] < 20) {
                    $checking = $this->checkDateIssue($this->app->date_of_issue, $ret['age14']);
                } else if($ret['currentAge'] >= 20 && $ret['currentAge'] < 45) {
                    $checking = $this->checkDateIssue($this->app->date_of_issue, $ret['age20']);
                } else if($ret['currentAge'] >= 45) {
                    $checking = $this->checkDateIssue($this->app->date_of_issue, $ret['age45']);
                }

                //паспорт действительный
                if($checking['valid']) {
                    $ret['status'] = $checking['status'];

                    //дату последующей замены паспорта
                    //если проверяемому лицу более 45 лет паспорт действует пожизненно
                    if($ret['currentAge'] > 14 && $ret['currentAge'] < 20) {
                        $ret['passport_date_replace'] = $ret['age20'];
                    } else if($ret['currentAge'] < 45) {
                        $ret['passport_date_replace'] = $ret['age45'];
                    }

                    $result->setResult($ret);
                    $result->setStatusResult(true);
                } else {
                    //паспорт не действительный
                    $result->setResult([
                            'status' => $checking['status'],
                            'passport_serie_year' => $passport_serie_year,
                            'passport_serie_region' => $passport_serie_region,
                        ]
                    );
                }

            } else {
                $result->setResult([
                        'status' => $validData['message'],
                        'passport_serie_year' => $passport_serie_year,
                        'passport_serie_region' => $passport_serie_region,
                    ]
                );
            }

        } else {
            //Паспорт не действительный, выводим в лог сообщение
            $result->setResult(
                [
                    'status' => "Паспорт недействительный, выдан до 01.01.1997 года или после текущей даты.",
                    'passport_serie_year' => $passport_serie_year,
                    'passport_serie_region' => $passport_serie_region,
                ]
            );
        }

        $this->logger->info("check", $result->toArray());

        return $result;
    }

    /**
     * Вторые 2 цифры серии паспорта (ххХХ хххххх) соответствуют году (в сокращенном написании,
     * при этом 97 – 1997, 98 – 1998, 99 – 1999, 00 – 2000 года)
     * выдачи паспорта +/- 3 года. Если год выдачи паспорта (по дате выдачи) больше или меньше
     * года серии паспорта на 3 года, то паспорт недействительный.
     * Например, если серия паспорта хх10хххххх, значит паспорт выдан в период 2007-2013
     *
     * @return bool
     */
    public function isValidDateIssue()
    {

        $shortYear = substr($this->app->passport_code, 2, 2);
        switch($shortYear) {
            case '97':
                $year = 1997;
                break;
            case '98':
                $year = 1998;
                break;
            case '99':
                $year = 1999;
                break;
            default:
                $year = '20' . $shortYear;
        }

        if($this->app->date_of_issue->year >= ($year - 3) && $this->app->date_of_issue->year <= ($year + 3))
            return true;

        return false;

    }

    /**
     * Первые 2 цифры серии паспорта (ХХхх хххххх) соответствуют региону выдачи паспорта по ОКАТО
     * (например, Москва – 45, МО – 46, Санкт-Петербург – 40 и т.д). Делаем перекрестную проверку
     * места выдачи паспорта (наверное, лучше ассоциативным массивом) через серию паспорта и первые
     * две цифры кода подразделения. Так, 45 = 77 = Москва и т.д.
     */
    public function checkPassportCodeAndDepartment()
    {
        if(!empty($this->app->code_department)) {
            $serie = intval(mb_substr($this->app->passport_code, 0, 2));
            $code = intval(mb_substr($this->app->code_department, 0, 2));

            $region = Region::where('okato', $serie)->where('kladr', $code)->get();

            return $region->isNotEmpty();
        }
        return false;
    }

    /**
     * Осуществляем проверку даты выдачи паспорта.
     * Если паспорт выдан до 01.01.1997 то паспорт недействительный
     *
     * @param Carbon $dateIssue
     * @return bool
     */
    public function isLessTo1997(Carbon $dateIssue)
    {
        $dt1997 = Carbon::createFromDate(1997, 1, 1);
        return $dateIssue->lessThanOrEqualTo($dt1997);
    }

    /**
     * Осуществляем проверку даты выдачи паспорта.
     * Если паспорт выдан после текущей даты то паспорт недействительный
     *
     * @param Carbon $dateIssue
     * @return bool
     */
    public function isGreaterCurrentDate(Carbon $dateIssue)
    {
        return $dateIssue->greaterThan(Carbon::now());
    }

    /**
     * Проверка состояния выдачи паспорта
     *
     * Если дата выдачи паспорта больше даты $age лица, значит паспорт выдан вовремя.
     * Если дата выдачи паспорта менее даты $age лица, а с момента наступления $age прошло не более 1 месяца, значит паспорт должен быть заменен в ближайшее время
     * Если дата выдачи паспорта менее даты $age лица, а с момента наступления $age прошло более 1 месяца, значит паспорт не действительный – истек срок действия
     * Если паспорт выдан через 1 год и более после наступления $age, значит произведена внеплановая замена паспорта
     *
     * @param Carbon $dateIssue Дата выдачи паспорта
     * @param Carbon $age сравниваемый год
     * @return array
     */
    public function checkDateIssue(Carbon $dateIssue, Carbon $age)
    {
        $ret = [
            'status' => '',
            'valid' => true
        ];
        //Если дата выдачи паспорта больше даты $age лица, значит паспорт выдан вовремя.
        //Берем запас в 6 месяцев до даты $age лица. Так как паспорт может быть выдан раньше. Исключение после реального тестирования
        if($dateIssue->greaterThanOrEqualTo($age) || $dateIssue->greaterThanOrEqualTo($age->addMonth("-6"))) {
            //если паспорт выдан через год или более
            if($dateIssue->diffInYears($age) >= 1) {
                $ret['status'] = "Произведена внеплановая замена паспорта";
            } else {
                $ret['status'] = "Паспорт выдан вовремя";
            }
        } //Если дата выдачи паспорта менее даты $age лица
        else if($dateIssue->lessThanOrEqualTo($age)) {
            //прошло не более 1 месяца с момента наступления $age лет
            if(Carbon::now()->diffInMonths($age) == 0) {
                $ret['status'] = "Паспорт должен быть заменен в близжайшее время";
            } //прошло более 1 месяца с момента наступления $age лет
            else {
                $ret['status'] = "Паспорт не действительный - истек срок действия";
                $ret['valid'] = false;
            }
        }

        return $ret;
    }

    public function validatePassport()
    {
        $parts = explode(' ', $this->app->passport_code);
        return (new PassportValidChecker($this->logger))->check($parts[0], $parts[1]);
    }

    public function calculateCurrentAge()
    {
        return $this->app->birthday->diffInYears(Carbon::now());
    }

    protected function calculateDateAge($age)
    {
        return $this->app->birthday->copy()->addYear($age);
    }
}