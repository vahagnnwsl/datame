<?php

namespace App\Packages;

use App\Packages\Loggers\CustomLogger;
use GuzzleHttp\Client;
use jumper423\decaptcha\services\RuCaptcha;
use Storage;

class PassportValidChecker
{

    /**
     * @var RuCaptchaProvider
     */
    private $ruCaptchaService;
    private $logger;

    public function __construct(CustomLogger $logger)
    {
        //rn - группа стран Россия, Украина, Беларусь, Казахстан | Определяет язык очереди, в которую должна попасть капча. |
        $this->ruCaptchaService = new RuCaptchaProvider([RuCaptcha::ACTION_FIELD_LANGUAGE => 'rn']);
        $this->logger = $logger;
    }

    public function check($series, $number)
    {
        $this->logger->info("Проверка по базе данных недействительных паспортов {$series} {$number}");
        $ret = [
            'status' => false,
            'message' => 'Не удалось провести проверку на действительность'
        ];

        try {

            for($i = 0; $i < 3; $i++) {

                $session = date('YmdHis') . rand(100000, 999999);
                $path = "files/{$session}_captcha.jpg";

                $client = new Client(['cookies' => true]);
                $r = $client->get('http://xn--b1afk4ade4e.xn--b1ab2a0a.xn--b1aew.xn--p1ai/services/captcha.jpg');

                //сохраняем капчу локально
                Storage::disk('public')->put($path, $r->getBody()->getContents());

                //получаем урл на файлы капчи
                $fileUrl = Storage::disk('public')->path($path);

                $this->logger->info("fileUrl: " . $fileUrl);

                if($this->ruCaptchaService->recognize($fileUrl)) {
                    $code = $this->ruCaptchaService->getCode();

                    if(!is_null($code)) {
                        $this->logger->info("code: " . $code);

                        $r = $client->request("POST", 'http://xn--b1afk4ade4e.xn--b1ab2a0a.xn--b1aew.xn--p1ai/info-service.htm', [
                            'form_params' => [
                                'sid' => '2000',
                                'form_name' => 'form',
                                'DOC_SERIE' => $series,
                                'DOC_NUMBER' => $number,
                                'captcha-input' => $code
                            ]
                        ]);

                        $content = $r->getBody()->getContents();

                        if(strpos($content, 'не значится')) {
                            $ret['status'] = true;
                            $ret['message'] = 'Не значится';
                            break;
                        } elseif(strpos($content, 'Не действителен')) {
                            $ret['status'] = false;
                            $ret['message'] = 'Не действителен';
                            break;
                        } else {
                            //капча не разспознана, пробуем еще раз
                            $m = "Капча не распознана [{$i}]";
                            $this->logger->error($m);
                            $ret['message'] = $m;
                            $this->ruCaptchaService->nonTrue();
                        }
                    } else {
                        //капча не разспознана, пробуем еще раз
                        $m = "Не удалось получить капчу от сервиса: [{$this->ruCaptchaService->getError()}]";
                        $this->logger->error($m);
                        $ret['message'] = $m;
                    }
                } else {
                    $error = $this->ruCaptchaService->getError();
                    $this->logger->error("captcha_error: " . $error);
                }
            }

        } catch(\Exception $e) {
            $m = "error: " . $e->getMessage() . "\r\n" . $e->getTraceAsString();
            $this->logger->error($m);
            $ret['message'] = $m;
        }

        $this->logger->info("ret", $ret);

        return $ret;

    }
}