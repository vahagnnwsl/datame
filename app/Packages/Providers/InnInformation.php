<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-21
 * Time: 15:10
 */

namespace App\Packages\Providers;


use App\App;
use App\Packages\Loggers\CustomLogger;
use App\Packages\RuCaptchaProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\GuzzleException;
use jumper423\decaptcha\services\RuCaptcha;
use Storage;
use Vinelab\Http\Client as HttpClient;


class InnInformation implements IProvider
{
    use ArrayProxyInjector;

    private $host = "https://service.nalog.ru/";
    private $path = "inn-proc.do";

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
        $this->selectProxy();
    }

    /**
     * @return Result|null
     */
    public function check()
    {
        $retData = new Result();

        for ($k = 0; $k < 4; $k++) {
            $this->logger->info("find_inn_k: {$k}");

            //делаем запрос в налоговую для поиска инн
            //получаем данные капчи
            $captcha = $this->getCaptcha();

            if (!is_null($captcha)) {

                $this->logger->info("file " . $captcha['file']);
                $codeCaptcha = $this->ruCaptchaService->recognize($captcha['file'])->getCode();

                if (!is_null($codeCaptcha)) {

                    //инициализируем данные капчи
                    $send['captcha'] = $codeCaptcha;
                    $send['captchanum'] = $captcha['token'];
                    $send['session'] = $captcha['session'];

                    $this->logger->info("send", $send);

                    $params = [
                        'c' => 'innMy',
                        'fam' => $this->app->lastname,
                        'nam' => $this->app->name,
                        'bdate' => $this->app->birthday->format('d.m.Y'),
                        'doctype' => 21,
                        'docno' => substr($this->app->passport_code, 0, 2) . " " . substr($this->app->passport_code, 2, 2) . " " . substr($this->app->passport_code, 5, 6),
                        'docdt' => $this->app->date_of_issue->format('d.m.Y'),
                        'captcha' => $send['captcha'],
                        'captchaToken' => $send['captchanum'],
                    ];

                    if ($this->app->patronymic) {
                        $params['otch'] = $this->app->patronymic;
                    } else {
                        $params['opt_otch'] = 1;

                    }

                    $req = [
                        'url' => $this->host . $this->path,
                        'params' => $params,
                        'headers' => [
                            'Accept:application/json, text/javascript, */*; q=0.01',
                            'Accept-Encoding:gzip, deflate',
                            'Cookie: JSESSIONID=' . $send['session'],
                            'User-Agent:Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.75 Safari/537.36z',
                            'Host: service.nalog.ru',
                            'Accept-Language:ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4'
                        ],
                        'timeout' => 180
                    ];

                    $this->logger->info("params", $params);


                    try {

                        $client = new HttpClient;
                        $resp = $client->post($req);


                        $this->logger->info("resp search inn: " . $resp->content());

                        //обратываем ответ
                        $response = str_replace('<html><body><div id="result">', "", $resp->content());
                        $response = str_replace('</div></body></html>', "", $response);
                        $this->logger->info("prepare to convert json: " . $response);

                        $json = json_decode($response);

                        if (!is_null($json) && isset($json->inn)) {
                            $retData->setResult($json->inn)->setStatusResult(true);
                            break;
                        } else if (isset($json->code) && ($json->code == 0 || $json->code == 100)) {
                            $retData->setResult("Не нашли. Возможно введены ошибочные данные или не зарегистрированы в ФНС");
                            break;
                        } else if (isset($json->code) && $json->code == 3) {
                            $retData->setResult("Указанные Вами сведения не прошли однозначной идентификации по Единому государственному реестру налогоплательщиков (ЕГРН)");
                            break;
                        } else if (isset($json->STATUS) && $json->STATUS == "400") {

                            $i = 1;
                            $captchaError = false;
                            foreach ($json->ERRORS as $error) {
                                $errorMessage = $error[0];

                                if ($errorMessage == "Цифры с картинки введены неверно" && isset($json->ERRORS->captcha) && count($json->ERRORS->captcha) == 2) {
                                    //{"ERROR":"-","ERRORS":{"
                                    //":["Цифры с картинки введены неверно"],"captchaToken":["Необходимо обновить картинку с цифрами"]},"STATUS":400}
                                    $captchaError = true;
                                    $this->logger->info("Капча распознана не верно. Ошибок: {$i}");
                                } else {
                                    //если есть другие ошибки, отдаем их пользователю
                                    if ($errorMessage == "Цифры с картинки введены неверно") {
                                        $this->ruCaptchaService->nonTrue();
                                        $this->logger->info("Капча распознана не верно");
                                        $captchaError = true;
                                        continue;
                                    }
                                    if ($errorMessage == "Необходимо обновить картинку с цифрами") {
                                        $captchaError = true;
                                        continue;
                                    }

                                    $retData->setResult($errorMessage);
                                    $this->logger->error($errorMessage);
                                    $i++;
                                }
                            }
                            if ($captchaError) {
                                $this->ruCaptchaService->nonTrue();
                                $this->logger->info("возврат средств за капчу, номер: {$k}");
                                continue;
                            } else {
                                $this->logger->info("не вернули средства, номер: {$k}");
                            }
                            break;
                        } else {
                            $retData->setResult("prepare to convert json: {$response}");
                            break;
                        }
                    } catch (\RuntimeException $e) {
                        $retData->setResult($e->getMessage());
                        break;
                    } catch (\Exception $e) {

                        if (stripos($e->getMessage(), "timed out after") != false) {
                            $retData->setResult("timeout");
                        }
                        $this->logger->error($e->getMessage() . "\r\n" . $e->getTraceAsString());
                        break;
                    }
                } else {
                    $this->logger->error("Не удалось распознать капчу_1");
                    $retData->setResult("B настоящее время на ресурсе ФНС ведутся технологические работы, ИНН получить не представляется возможным...");
                }
            } else {
                $this->logger->error("Не удалось распознать капчу_2");
                $retData->setResult("B настоящее время на ресурсе ФНС ведутся технологические работы, ИНН получить не представляется возможным...");
            }
        }
        $this->logger->info("result", $retData->toArray());
        return $retData;
    }

    /**
     * Парсер капчи формы на сайте налоговой
     *
     * @return array|null
     */
    public function getCaptcha()
    {
        $container = [
            'status' => false,
            'src' => '',
            'token' => '',
            'session' => '',
            'value' => '',
            'type' => 'image',
            'file' => null
        ];

        try {

            $data = ['token' => 0, 'src' => '', 'base64', 'status' => false];
            $uri = 'https://service.nalog.ru';

            $client = new Client($this->injectProxyOptions([
                // Base URI is used with relative requests
                'base_uri' => $uri,
                // You can set any number of default request options.
                'timeout' => 30,
                'cookies' => true,
                'verify' => false
            ]));
            $response = $client->request('GET', 'static/captcha-dialog.html');

            foreach ($client->getConfig('cookies') as $value) {
                if ($value instanceof SetCookie) {
                    if ($value->getName() == 'JSESSIONID') {
                        $data['data'] = $value->toArray();
                        break;
                    }
                }
            }
            $contents = $response->getBody()->getContents();

            $st = preg_match('/<input type="hidden" name="captchaToken" value="(\w+)"/', $contents, $searches);

            if ($st > 0) {
                //был найден токет для капчи
                $container['token'] = $searches[1];
            }
            $st = preg_match('/<img src="(\/static\/captcha.bin\?a=\w+)/', $contents, $searches);

            if ($st > 0) {
                $uriCaptcha = $uri . $searches[1];

                //получили ссылку на фото капчи
                $container['status'] = true;
                $response = $client->request('GET', $uriCaptcha);
                $container['src'] = base64_encode($response->getBody()->getContents());
                $container['session'] = $data['data']['Value'];
                $container['uri'] = $uriCaptcha;
                $container['file'] = null;

                $session = date('YmdHis') . rand(100000, 999999);
                $path = "files/{$session}_inn_captcha.jpg";

                $r = $client->get($uriCaptcha);
                //сохраняем капчу локально
                Storage::disk('public')->put($path, $r->getBody()->getContents());
                //получаем урл на файлы капчи
                $container['file'] = Storage::disk('public')->path($path);

//$this->logger->info("Запрос капчи: $uriCaptcha");

            }

        } catch (GuzzleException $e) {
            $this->logger->error("{$e->getMessage()}:\r\n{$e->getTraceAsString()}");
            return null;
        }

        return $container;
    }

}
