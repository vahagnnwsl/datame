<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-21
 * Time: 23:45
 */

namespace App\Packages\Providers;


use App\FindInn;
use App\Packages\App;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\Bankrot\Aes;
use App\Packages\Providers\Bankrot\CryptoHelpers;
use DOMDocument;

/**
 * Нахождение в списке банкротов
 *
 * Class DebtorInformation
 * @package App\Packages\Providers
 */
class DebtorInformation implements IProvider
{

    /**
     * @var FindInn
     */
    private $inn;
    private $logger;

    public function __construct(FindInn $inn, CustomLogger $logger)
    {
        $this->inn = $inn;
        $this->logger;
    }

    public function check()
    {

        $retData = new Result();

        $session = date('YmdHis') . rand(100000, 999999);
        $bankrotcookie = storage_path('cooks/bankrotcookie.cache' . $session);

        $steps = 0;
        $need_to_stop = true;
        do {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://bankrot.fedresurs.ru/DebtorsSearch.aspx");

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Cookie: _ym_uid=154956620172832849; _ym_d=1549566201; bankrotcookie={$bankrotcookie}; ASP.NET_SessionId=4tju4wz20ynl3fz2slk0c3bi; _ym_isad=2; _ym_visorc_45311283=w; debtorsearch=typeofsearch=Persons&orgname=&orgaddress=&orgregionid=&orgogrn=&orginn=&orgokpo=&OrgCategory=&prslastname=&prsfirstname=&prsmiddlename=&prsaddress=&prsregionid=&prsinn={$this->inn->inn}&prsogrn=&prssnils=&PrsCategory=&pagenumber=0",
                'Connection: keep-alive',
                'Upgrade-Insecure-Requests: 1',
                'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Referer: http://bankrot.fedresurs.ru/DebtorsSearch.aspx',
                'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            ]);

            // curl_setopt($ch, CURLOPT_PROXY, $proxy);
            // curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_HEADER, 0);
            $html = curl_exec($ch);
            curl_close($ch);

            if(strpos($html, 'setting cookie...') !== false) {
                $need_to_stop = false;

                $arr = $this->get_AES_vars($html);

                $AES = new Aes();
                $cryptoHelpers = new CryptoHelpers();

                $a = $cryptoHelpers->toNumbers($arr['a']);
                $b = $cryptoHelpers->toNumbers($arr['b']);
                $c = $cryptoHelpers->toNumbers($arr['c']);

                $r = $AES->decrypt($c, 16, 2, $a, 16, $b);
                $bankrotcookie = $cryptoHelpers->toHex($r);
            } else {
                $need_to_stop = true;
            }

        } while($steps++ < 3 && $need_to_stop == false);

        //ошибка парсинга
        if($need_to_stop == false) {
            return $retData->setResult('Фанатальная ошибка парсинга');
        }

        $doc = new DOMDocument();
        @$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

        $response = array();

        $doc_table = $doc->getElementById('ctl00_cphBody_gvDebtors');
        $doc_trs = $doc_table->getElementsByTagName('tr');

        if(!empty($doc_trs) && $doc_trs->length > 1) {

            for($i = 1; $i < $doc_trs->length; $i++) {
                $doc_tr = $doc_trs->item($i);
                $doc_tds = $doc_tr->getElementsByTagName('td');

                $link = '';
                $doc_td_a = $doc_tr->getElementsByTagName('a');
                if(!empty($doc_td_a) && $doc_td_a->length > 0) {
                    $doc_td_a = $doc_td_a->item(0);
                    $link = 'http://bankrot.fedresurs.ru' . $doc_td_a->getAttribute('href');
                }

                $response_item = [
                    'category' => $doc_tds->item(0)->textContent,
                    'debtor' => $doc_tds->item(1)->textContent,
                    'INN' => $doc_tds->item(2)->textContent,
                    'OGRNIP' => $doc_tds->item(3)->textContent,
                    'SNILS' => $doc_tds->item(4)->textContent,
                    'region' => $doc_tds->item(5)->textContent,
                    'link' => $link,
                ];

                foreach($response_item as $key => $value) {
                    $response_item[$key] = trim($value);
                }

                $response[] = $response_item;

            }

        }

        /*
        ** Private Card
        */

        foreach($response as $key => $response_item) {

            if(empty($response_item['link'])) {
                continue;
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $response_item['link']);

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Cookie: _ym_uid=154956620172832849; _ym_d=1549566201; bankrotcookie=' . $bankrotcookie . '; ASP.NET_SessionId=4tju4wz20ynl3fz2slk0c3bi; _ym_isad=2; _ym_visorc_45311283=w; debtorsearch=typeofsearch=Persons&orgname=&orgaddress=&orgregionid=&orgogrn=&orginn=&orgokpo=&OrgCategory=&prslastname=&prsfirstname=&prsmiddlename=&prsaddress=&prsregionid=&prsinn=' . $this->inn->inn . '&prsogrn=&prssnils=&PrsCategory=&pagenumber=0',
                'Connection: keep-alive',
                'Upgrade-Insecure-Requests: 1',
                'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Referer: http://bankrot.fedresurs.ru/DebtorsSearch.aspx',
                'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            ]);

            // curl_setopt($ch, CURLOPT_PROXY, $proxy);
            // curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($ch, CURLOPT_HEADER, 0);
            $html = curl_exec($ch);
            curl_close($ch);

            $doc = new DOMDocument();
            @$doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

            $doc_last_name = $doc->getElementById('ctl00_cphBody_trLastName');
            if(empty($doc_last_name)) {
                continue;
            }

            $doc_trs = $doc_last_name->parentNode->getElementsByTagName('tr');

            if(empty($doc_trs)) {
                continue;
            }

            $response[$key]['card_data'] = array();

            for($i = 0; $i < $doc_trs->length; $i++) {
                $doc_tr = $doc_trs->item($i);
                $doc_tds = $doc_tr->getElementsByTagName('td');

                if(empty($doc_tds) || $doc_tds->length !== 2) {
                    continue;
                }

                $prop_key = $doc_tds->item(0)->textContent;
                $prop_key = mb_strtolower($prop_key);
                $prop_key = str_replace(' ', '_', $prop_key);
                $prop_key = str_replace('!', '', $prop_key);
                $prop_key = trim($prop_key);

                $prop_value = $doc_tds->item(1)->getElementsByTagName('span');
                if(empty($prop_value) || $prop_value->length !== 1) {
                    $prop_value = '';
                } else {
                    $prop_value = $prop_value->item(0)->textContent;
                    $prop_value = trim($prop_value);
                }

                $response[$key]['card_data'][$prop_key] = $prop_value;
            }
        }

        $result = array(
            'response' => $response,
            'status' => (count($response) == 0) ? 'Не найдено' : 'Найдено',
            'count' => count($response),
        );

        if($result['count'] > 0) {
            $retData->setResult($result['response'])->setStatusResult(true);
        } else {
            $retData->setResult("Не является банкротом")->setStatusResult(true);
        }

        return $retData;
    }

    function get_AES_vars($html = '')
    {

        $a = '';
        $pos = strpos($html, 'a=toNumbers("');
        if($pos !== false) {
            $pos2 = strpos($html, '")', $pos);
            if($pos2 !== false) {
                $a = substr($html, $pos + 13, $pos2 - $pos - 13);
            }
        }

        $b = '';
        $pos = strpos($html, 'b=toNumbers("');
        if($pos !== false) {
            $pos2 = strpos($html, '")', $pos);
            if($pos2 !== false) {
                $b = substr($html, $pos + 13, $pos2 - $pos - 13);
            }
        }

        $c = '';
        $pos = strpos($html, 'c=toNumbers("');
        if($pos !== false) {
            $pos2 = strpos($html, '")', $pos);
            if($pos2 !== false) {
                $c = substr($html, $pos + 13, $pos2 - $pos - 13);
            }
        }

        return array(
            'a' => $a,
            'b' => $b,
            'c' => $c,
        );
    }
}