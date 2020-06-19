<?php


namespace App\Console\Commands;


use App\Packages\Providers\CurlProxyInjector;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class TmpCommand extends Command
{
    use CurlProxyInjector;

    protected $signature = 'tmp';

    protected $description = 'Temp command for tests';

    public function handle()
    {
        $this->tryCurl();
//        $this->tryGuzzle();
    }

    private function tryGuzzle()
    {
        $client = new Client();
        $response = $client->request('get', '//api.ipify.org?format=json', [
//            'proxy' => 'Y5NVdC:Jmd7J2@212.109.222.53:3128'
//            'proxy' => 'http://sexy4321:sexy654321@94.242.215.55:61094'
//            'proxy' => 'Y5NVdE:LLmFxz@193.31.101.128:9017'
//            'proxy' => 'Y5NVdE:LLmFxz@193.31.102.128:9222'
            'proxy' => 'Y5NVdE:LLmFxz@193.31.102.24:9835'
        ]);
        dd($response->getBody()->getContents());
    }

    private function tryCurl()
    {
//        $url = 'http://dynupdate.no-ip.com/ip.php';
//        $proxy = '193.31.102.24:9835';
//        $proxyauth = 'Y5NVdE:LLmFxz';
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL,$url);
//        curl_setopt($ch, CURLOPT_PROXY, $proxy);
//        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_HEADER, 1);
//        $res = curl_exec($ch);
//        $error = curl_error($ch);
//        curl_close($ch);

        $this->selectProxy();
        $ch = curl_init('https://api.ipify.org?format=json');
//        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
//        curl_setopt($ch, CURLOPT_HEADER, 1);
//        curl_setopt($ch, CURLOPT_PROXY, '212.109.222.53:3128');
//        curl_setopt($ch, CURLOPT_PROXYUSERNAME, 'Y5NVdC');
//        curl_setopt($ch, CURLOPT_PROXYPASSWORD, 'Jmd7J2');
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $ch = $this->injectProxyOptions($ch);
        $res = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        dd([$res, $error]);
        $this->info("test");
    }
}