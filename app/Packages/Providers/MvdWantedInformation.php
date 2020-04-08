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
use simple_html_dom;

/**
 * Нахождение в федеральном розыске
 *
 * Class MvdWantedInformation
 * @package App\Packages\Providers
 */
class MvdWantedInformation implements IProvider
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

        $day = $this->app->birthday->format('j');
        $month = $this->app->birthday->format('n');
        $year = $this->app->birthday->format('Y');

        if(!file_exists(storage_path('tmp/cooks')))
            mkdir(storage_path('tmp/cooks'), 0777, true);

        for($i = 0; $i < 5; $i++) {

            $session = date('YmdHis') . rand(100000, 999999);
            $cookies_file = storage_path('tmp/cooks' . $session . '.txt');

            $ch2 = curl_init();
            $curl_params2 = array(
                CURLOPT_URL => 'https://xn--b1aew.xn--p1ai/captcha',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_COOKIEFILE => $cookies_file,
                CURLOPT_COOKIEJAR => $cookies_file,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => 0

            );
            curl_setopt_array($ch2, $curl_params2);
            $result2 = curl_exec($ch2);
            curl_close($ch2);

            $captchaPath = storage_path('tmp/' . $session . 'captcha.jpg');
            if(file_put_contents($captchaPath, $result2)) {

                $this->logger->info("Капча: {$captchaPath}");
                $codeCaptcha = $this->ruCaptchaService->recognize($captchaPath)->getCode();

                if(!is_null($codeCaptcha)) {
                    $this->logger->info("Результат: {$codeCaptcha}");

                    $content2 = 's_family=' . $this->app->lastname . '&fio=' . $this->app->name . '&s_patr=' . $this->app->patronymic . '&d_year=' . $year . '&d_month=' . $month . '&d_day=' . $day . '&email=2400999@mail.ru&captcha=' . $codeCaptcha;

                    $ch3 = curl_init();
                    $curl_params3 = array(
                        CURLOPT_URL => 'https://xn--b1aew.xn--p1ai/wanted?' . $content2,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_FOLLOWLOCATION => 1,
                        CURLOPT_COOKIEFILE => $cookies_file,
                        CURLOPT_COOKIEJAR => $cookies_file,
                        CURLOPT_HTTPHEADER => array("Cache-Control: no-cache"),
                        CURLOPT_TIMEOUT => 10,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_SSL_VERIFYPEER => 0
                    );
                    curl_setopt_array($ch3, $curl_params3);
                    $result3 = curl_exec($ch3);

                    $html = new simple_html_dom();
                    $html->load($result3);

                    $check_captcha = $html->find('span[style=color:red;font-weight:bold]');

                    if(isset($check_captcha[0]) && preg_match('/неверный код/sei', $check_captcha[0]->plaintext)) {
                        $this->ruCaptchaService->nonTrue();
                        $retData->setResult("Неверный код");
                        $this->logger->warning("Неверный код");
                    } else {

                        $found_count = $html->find('div[class*=bs-count]');
                        //страница не всегда возвращает результат
                        if(count($found_count)) {
                            $found_count = explode(":", $found_count[0]->plaintext);
                            $retData->setStatusResult(true);

                            if(trim($found_count[1]) == 0) {
                                $retData->setResult("В розыске отсутствует");
                            } else {
                                $retData->setResult("Найдено в розыске записей: " . trim($found_count[1]));
                            }
                            break;
                        } else {
                            $retData->setStatusResult(false)->setResult("Ошибка. Сервис не доступен");
                            $this->logger->error("count(found_count) == 0: {$result3}");
                        }
                    }

                } else {
                    $this->logger->error("Не удалось распознать капчу [{$i}]: {$this->ruCaptchaService->getError()}");
                    $retData->setResult("Не удалось распознать капчу: {$this->ruCaptchaService->getError()}");
                    $this->ruCaptchaService->nonTrue();
                }
            }
        }

        return $retData;
    }

}