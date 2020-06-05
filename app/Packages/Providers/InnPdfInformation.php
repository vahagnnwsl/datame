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
use GuzzleHttp\Promise;

use Storage;

class InnPdfInformation implements IProvider
{
    use ArrayProxyInjector;

    private $host = "https://egrul.nalog.ru/";

    /**
     * @var App
     */
    private $app;
    private $logger;
    /**
     * @var RuCaptchaProvider
     */
    private $ruCaptchaService;
    protected $inn;
    protected $sessionId;


    public function __construct($inn)
    {
        $this->inn = $inn;
        $this->ruCaptchaService = new RuCaptchaProvider([RuCaptcha::ACTION_FIELD_LANGUAGE => 'rn']);
        $this->selectProxy();
    }


    /**
     * @return Result|null
     */


    public function check()
    {

        $token = $this->getToken();
        $res = $this->search($token);

        if (count($res->rows) > 0) {
            $t = $res->rows[0];
            if ($req = $this->req($t->t)) {
                $status = $this->status($req->t);
                if ($status->status === 'ready') {
                    return $this->download($req->t);
                }
            }
        }

        return null;
    }

    public function search($token)
    {
        $client = new Client($this->injectProxyOptions([
            'defaults' => [
                'headers' => ['Cookie' => 'JSESSIONID=' . $this->sessionId],
            ]
        ]));

        $time = microtime(true);
        $time = explode('.', $time);
        $time = $time[0] . $time[1];
        $response = $client->request('GET', $this->host . 'search-result/' . $token . '?r=' . $time . '&_=' . $time);

        return json_decode($response->getBody());

    }

    public function req($token)
    {
        $client = new Client($this->injectProxyOptions([
            'defaults' => [
                'headers' => ['Cookie' => 'JSESSIONID=' . $this->sessionId],
            ]
        ]));

        $time = microtime(true);
        $time = explode('.', $time);
        $time = $time[0] . $time[1];
        $response = $client->request('GET', $this->host . 'vyp-request/' . $token . '?r=&_=' . $time);

        return json_decode($response->getBody());

    }

    public function getToken()
    {

        $captcha = $this->getCaptcha();
        $this->sessionId = $captcha['session'];

        $client = new Client($this->injectProxyOptions([
            'defaults' => [
                'headers' => ['Cookie' => 'JSESSIONID=' . $this->sessionId],
            ]
        ]));
        $response = $client->request('POST', $this->host, [
            'form_params' => [
                'query' => $this->inn,
            ]
        ]);

        return json_decode($response->getBody())->t;
    }

    public function status($token)
    {
        $time = microtime(true);
        $time = explode('.', $time);
        $time = $time[0] . $time[1];
        $client = new Client($this->injectProxyOptions([
            'defaults' => [
                'headers' => ['Cookie' => 'JSESSIONID=' . $this->sessionId],
            ]
        ]));


        $response = $client->request('GET', $this->host . 'vyp-status/' . $token . '?r=' . $time . '&_=' . $time);


        return json_decode($response->getBody());


    }


    public function download($token)
    {
        $client = new Client($this->injectProxyOptions([
            'defaults' => [
                'headers' => ['Cookie' => 'JSESSIONID=' . $this->sessionId],
            ]
        ]));

        $response = $client->request('GET', $this->host . 'vyp-download/' . $token);
        $path = "pdf/" . $this->inn . ".pdf";


        Storage::disk('public')->put($path, $response->getBody()->getContents());

        return Storage::disk('public')->path($path);

    }

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
            $uri = 'https://egrul.nalog.ru';

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
