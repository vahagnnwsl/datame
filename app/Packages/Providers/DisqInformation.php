<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-21
 * Time: 23:45
 */

namespace App\Packages\Providers;


use App\App;
use App\Packages\Loggers\CustomLogger;
use App\Packages\RuCaptchaProvider;
use jumper423\decaptcha\services\RuCaptcha;

/**
 * Проверка по реестру дисквалифицированных лиц
 *
 * Class DesqInformation
 * @package App\Packages\Providers
 */
class DisqInformation implements IProvider
{

    /**
     * @var App
     */
    private $app;
    private $logger;
    /**
     * @var RuCaptchaProvider
     */
    private $ruCaptchaService;

    public function __construct(App $app, CustomLogger $logger)
    {
        $this->app = $app;
        //rn - группа стран Россия, Украина, Беларусь, Казахстан | Определяет язык очереди, в которую должна попасть капча. |
        $this->ruCaptchaService = new RuCaptchaProvider([RuCaptcha::ACTION_FIELD_LANGUAGE => 'rn']);
        $this->logger = $logger;
    }

    /**
     * @return Result|null
     */
    function check()
    {
        $retData = new Result();

        for($i = 0; $i < 3; $i++) {

            $session = date('YmdHis') . rand(100000, 999999);

            $cookies_file = storage_path('tmp/cooks/cookies' . $session . '.txt');

            $host = "https://service.nalog.ru/disqualified.do";
            $path = "";
            $data = "";
            $curl = curl_init($host . $path);
            curl_setopt($curl, CURLOPT_URL, $host . $path);
            curl_setopt($curl, CURLOPT_TIMEOUT, 60);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookies_file);
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookies_file);

            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $content = curl_exec($curl);
            curl_close($curl);

            $captchanum = null;
            if(preg_match('/captcha.bin\?a=(.*)\"/', $content, $arr)) {
                $captchanum = explode("\"", $arr[1]);
            }

            if(!is_null($captchanum)) {

                $host = "https://service.nalog.ru/";
                $path = "static/captcha.bin";
                $data = "a=" . $captchanum[0];
                $curl = curl_init($host . $path);
                curl_setopt($curl, CURLOPT_URL, $host . $path);
                curl_setopt($curl, CURLOPT_TIMEOUT, 60);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($curl, CURLOPT_COOKIEFILE, $cookies_file);
                curl_setopt($curl, CURLOPT_COOKIEJAR, $cookies_file);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $content2 = curl_exec($curl);
                curl_close($curl);

                $captchafile = storage_path('tmp') . "/" . $captchanum[0] . '.gif';

                if(file_put_contents($captchafile, $content2)) {

                    $this->logger->info("Капча: {$captchafile}");
                    $codeCaptcha = $this->ruCaptchaService->recognize($captchafile)->getCode();

                    if(!is_null($codeCaptcha)) {
                        $this->logger->info("Результат: {$codeCaptcha}");
                        $captchanumber = $captchanum[0];

                        $host = "https://service.nalog.ru/";
                        $path = "disqualified-ajax.do";
                        $data = "fam=" . mb_strtoupper($this->app->lastname) . "&nam=" . mb_strtoupper($this->app->name) . "&otch=" . mb_strtoupper($this->app->patronymic) . "&birthDate={$this->app->birthday->format('d.m.Y')}&captcha=$codeCaptcha&captchaToken=$captchanumber";

                        $curl = curl_init($host . $path);
                        curl_setopt($curl, CURLOPT_URL, $host . $path);
                        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($curl, CURLOPT_POST, 1);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $content = curl_exec($curl);
                        curl_close($curl);

                        $data = json_decode($content, true);
                        $this->logger->info("Ответ", $data);

                        if(isset($data['STATUS']) && $data['STATUS'] == "400" && $data['ERRORS']['captcha'][0] == "Цифры с картинки введены неверно") {
                            $retData->setResult("Цифры с картинки введены неверно");
                            $this->logger->error("Цифры с картинки введены неверно");
                            continue;
                        } else if(isset($data['rowsFound']) && $data['rowsFound'] == 0) {
                            $retData->setResult("Не является дисквалифицированным лицом")->setStatusResult(true);
                            break;
                        } else if(isset($data['rowsFound']) && $data['rowsFound'] > 0) {
                            $retData->setResult($data['rows'])->setStatusResult(true);
                            break;
                        } else {
                            $retData->setResult("Ошибка обработки ответа");
                            $this->logger->error("Ошибка обработки ответа");
                            break;
                        }
                    } else {
                        $retData->setResult("Не удалось получить ответ от рукапча");
                        $this->logger->error("Не удалось получить ответ от рукапча");
                    }
                } else {
                    $retData->setResult("Ошибка записи файла");
                    $this->logger->error("Ошибка записи файла");
                };
            } else {
                $retData->setResult("Не удалось получить капчу");
                $this->logger->error("Не удалось получить капчу");
            }
        }

        return $retData;
    }
}