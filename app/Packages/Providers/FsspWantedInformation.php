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
use DOMDocument;
use DOMXPath;

/**
 * Нахождение в местном розыске
 *
 * Class MvdWantedInformation
 * @package App\Packages\Providers
 */
class FsspWantedInformation implements IProvider
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
    function check()
    {
        $retData = new Result();

        if(!file_exists(storage_path('tmp/cooks')))
            mkdir(storage_path('tmp/cooks'), 0777, true);

        for($i = 0; $i<3; $i++) {

            $session = date('YmdHis') . rand(100000, 999999);
            $cookies_file = storage_path('tmp/cooks' . $session . '.txt');

            set_time_limit(300);

            $fp = fopen(storage_path('tmp/cooks/'.$session), 'w');
            $r="{$this->app->lastname}, {$this->app->name}, {$this->app->patronymic}, {$this->app->birthday->format('d.m.Y')}";
            fwrite($fp, $r);

            $params = array(
                'system' => 'suspect_info',
                'nocache' => '1',
                'is' => array(
                    'extended' => '1',
                    'all' => '1',
                    'suspect_fio' => $this->app->lastname." ".$this->app->name." ".$this->app->patronymic,
                    'suspect_birth_date' => $this->app->birthday->format('d.m.Y'),
                    'or' => '0'
                )
            );
            $ch = curl_init();
            $curl_params = array(
                CURLOPT_URL => 'http://is.fssprus.ru/ajax_search?'.http_build_query($params),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_COOKIEFILE => $cookies_file,
                CURLOPT_COOKIEJAR => $cookies_file,
                CURLOPT_TIMEOUT => 10
            );
            curl_setopt_array($ch, $curl_params);
            $result = curl_exec($ch);
            $counter = 0;

            $captchaPath = storage_path('tmp/' . $session . 'fssp_wanted_captcha.jpg');

            while (!empty($result) && preg_match('#<img[^>]+src="([^"]+)#s', stripslashes($result), $matches) && $counter < 1) {
                if (file_put_contents($captchaPath, file_get_contents($matches[1]))) {
                    $recogn = $this->recognizefssp($captchaPath,  config('datame.rucaptcha_key'), false, 'rucaptcha.com', 5, 60, 0, 0, 0, 5, 5, 1);
                    $params['code'] = $recogn[0];
                }


//print_r($recogn);

                curl_setopt($ch, CURLOPT_URL, 'http://is.fssprus.ru/ajax_search?'.http_build_query($params));
                $result = curl_exec($ch);
                $counter++;

            }

            curl_close($ch);

            fwrite($fp, $result);

//print_r($result);

            $key = 0;
            $output = array();
            $result = json_decode($result, true);

            if (!empty($result['error'])) {
                $retData->setResult($result['error']);
                $this->logger->error($result['error']);
            } elseif (strpos($result['data'], 'По вашему запросу ничего не найдено') !== false) {
                //$output[] = trim(strip_tags($result['data']));
                $retData->setResult("В розыске отсутствует")->setStatusResult(true);
                break;
            } elseif (strpos($result['data'], 'Неверно введен код') !== false) {
                //$output[] = 'captchaerror|'.$recogn[1];
                $this->reportbadcaptcha($recogn[1]);
                $this->logger->error("Неверно введен код");
            } else {
                $result['data'] = '<?xml version="1.0" encoding="utf-8"?>'.$result['data'];

                $dom = new DOMDocument();
                @$dom->loadHTML($result['data']);
                $xpath = new DOMXPath($dom);

                $xres = $xpath->query('//table/tr[position() > 1 and @class!="region-title"]');
                if ($xres->length) {
                    foreach ($xres as $item) {
                        $output[$key] = array();
                        $childs = $item->getElementsByTagName('td');

                        foreach ($childs as $num => $td) {
                            //if ($num == 3 || $num == 4) {
                            //		continue;
                            //	}
                            $output[$key][] = $td->nodeValue;
                        }
                        $key++;
                    }
                }
                unset($params['code'], $params['t']);

                $xres = $xpath->query('//div[@class="pagination"]/div/a[position()=(last()-1)]');
                if ($xres->length) {
                    $last_page = (int)$xres->item(0)->nodeValue;
                    if ($last_page > 1) {

                        for ($i = 2; $i <= $last_page; $i++) {
                            sleep(10);
                            $params['page'] = $i;

                            $ch = curl_init();
                            $curl_params = array(
                                CURLOPT_URL => 'http://is.fssprus.ru/ajax_search?'.http_build_query($params),
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_COOKIEFILE => $cookies_file,
                                CURLOPT_COOKIEJAR => $cookies_file,
                                CURLOPT_TIMEOUT => 10
                            );
                            curl_setopt_array($ch, $curl_params);

                            $result = curl_exec($ch);
                            curl_close($ch);

                            if (!empty($result)) {
                                $result = json_decode($result, true);
                                $result['data'] = '<?xml version="1.0" encoding="utf-8"?>'.$result['data'];

                                $dom = new DOMDocument();
                                @$dom->loadHTML($result['data']);
                                $xpath = new DOMXPath($dom);

                                $xres = $xpath->query('//table/tr[position() > 1 and @class!="region-title"]');
                                if ($xres->length) {
                                    foreach ($xres as $item) {
                                        $output[$key] = array();
                                        $childs = $item->getElementsByTagName('td');

                                        foreach ($childs as $num => $td) {
                                            //if ($num == 3 || $num == 4) {
                                            //	continue;
                                            //}
                                            $output[$key][] = $td->nodeValue;
                                        }
                                        $key++;
                                    }
                                }
                            }
                        }

                    }
                }
                $retData->setStatusResult(true)->setResult($output);
                break;
            }
        }

        return $retData;
    }

    // Helped libs
    function recognizefssp(
        $filename,
        $apikey,
        $is_verbose = true,
        $sendhost = "rucaptcha.com",
        $rtimeout = 5,
        $mtimeout = 120,
        $is_phrase = 0,
        $is_regsense = 0,
        $is_numeric = 0,
        $min_len = 0,
        $max_len = 0,
        $language = 0)
    {
        if(!file_exists($filename)) {
            if($is_verbose) echo "file $filename not found\n";
            return false;
        }
        $fp = fopen($filename, "r");
        if($fp != false) {
            $body = "";
            while(!feof($fp)) $body .= fgets($fp, 1024);
            fclose($fp);
            $ext = strtolower(substr($filename, strpos($filename, ".") + 1));
        } else {
            if($is_verbose) echo "could not read file $filename\n";
            return false;
        }

        $conttype = "image/pjpeg";
        if($ext == "gif") $conttype = "image/gif";
        if($ext == "png") $conttype = "image/png";


        $boundary = "---------FGf4Fh3fdjGQ148fdh";

        $content = "--$boundary\r\n";
        $content .= "Content-Disposition: form-data; name=\"method\"\r\n";
        $content .= "\r\n";
        $content .= "post\r\n";
        $content .= "--$boundary\r\n";
        $content .= "Content-Disposition: form-data; name=\"key\"\r\n";
        $content .= "\r\n";
        $content .= "$apikey\r\n";
        $content .= "--$boundary\r\n";
        $content .= "Content-Disposition: form-data; name=\"phrase\"\r\n";
        $content .= "\r\n";
        $content .= "$is_phrase\r\n";
        $content .= "--$boundary\r\n";
        $content .= "Content-Disposition: form-data; name=\"regsense\"\r\n";
        $content .= "\r\n";
        $content .= "$is_regsense\r\n";
        $content .= "--$boundary\r\n";
        $content .= "Content-Disposition: form-data; name=\"numeric\"\r\n";
        $content .= "\r\n";
        $content .= "$is_numeric\r\n";
        $content .= "--$boundary\r\n";
        $content .= "Content-Disposition: form-data; name=\"min_len\"\r\n";
        $content .= "\r\n";
        $content .= "$min_len\r\n";
        $content .= "--$boundary\r\n";
        $content .= "Content-Disposition: form-data; name=\"max_len\"\r\n";
        $content .= "\r\n";
        $content .= "$max_len\r\n";
        $content .= "--$boundary\r\n";
        $content .= "Content-Disposition: form-data; name=\"language\"\r\n";
        $content .= "\r\n";
        $content .= "$language\r\n";
        $content .= "--$boundary\r\n";
        $content .= "Content-Disposition: form-data; name=\"file\"; filename=\"capcha.$ext\"\r\n";
        $content .= "Content-Type: $conttype\r\n";
        $content .= "\r\n";
        $content .= $body . "\r\n"; //тело файла
        $content .= "--$boundary--";


        $poststr = "POST http://$sendhost/in.php HTTP/1.0\r\n";
        $poststr .= "Content-Type: multipart/form-data; boundary=$boundary\r\n";
        $poststr .= "Host: $sendhost\r\n";
        $poststr .= "Content-Length: " . strlen($content) . "\r\n\r\n";
        $poststr .= $content;

        // echo $poststr;

        if($is_verbose) echo "connecting $sendhost...";
        $fp = fsockopen($sendhost, 80, $errno, $errstr, 30);
        if($fp != false) {
            if($is_verbose) echo "OK\n";
            if($is_verbose) echo "sending request " . strlen($poststr) . " bytes...";
            fputs($fp, $poststr);
            if($is_verbose) echo "OK\n";
            if($is_verbose) echo "getting response...";
            $resp = "";
            while(!feof($fp)) $resp .= fgets($fp, 1024);
            fclose($fp);
            $result = substr($resp, strpos($resp, "\r\n\r\n") + 4);
            if($is_verbose) echo "OK\n";
        } else {
            if($is_verbose) echo "could not connect to anti-captcha\n";
            if($is_verbose) echo "socket error: $errno ( $errstr )\n";
            return false;
        }

        if(strpos($result, "ERROR") !== false or strpos($result, "<HTML>") !== false) {
            if($is_verbose) echo "server returned error: $result\n";
            return false;
        } else {
            $ex = explode("|", $result);
            $captcha_id = $ex[1];
            if($is_verbose) echo "captcha sent, got captcha ID $captcha_id\n";
            $waittime = 0;
            if($is_verbose) echo "waiting for $rtimeout seconds\n";
            sleep($rtimeout);
            while(true) {
                $result = file_get_contents('http://rucaptcha.com/res.php?key=' . $apikey . '&action=get&id=' . $captcha_id);
                if(strpos($result, 'ERROR') !== false) {
                    if($is_verbose) echo "server returned error: $result\n";
                    return false;
                }
                if($result == "CAPCHA_NOT_READY") {
                    if($is_verbose) echo "captcha is not ready yet\n";
                    $waittime += $rtimeout;
                    if($waittime > $mtimeout) {
                        if($is_verbose) echo "timelimit ($mtimeout) hit\n";
                        break;
                    }
                    if($is_verbose) echo "waiting for $rtimeout seconds\n";
                    sleep($rtimeout);
                } else {
                    $ex = explode('|', $result);
                    if(trim($ex[0]) == 'OK') {
                        $return[0] = trim($ex[1]);
                        $return[1] = $captcha_id;
                        return $return;
                    }
                }
            }

            return false;
        }
    }


    function reportbadcaptcha($captcha_id)
    {
        $result = file_get_contents('http://rucaptcha.com/res.php?key=' . config('datame.rucaptcha_key') . '&action=reportbad&id=' . $captcha_id);
        return $result;


    }

}