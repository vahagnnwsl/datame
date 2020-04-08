<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-02-23
 * Time: 00:10
 */

namespace App\Packages\Providers;


use App\App;
use App\Packages\Loggers\CustomLogger;
use DOMDocument;
use DOMXPath;
use PHPExcel_Exception;
use PHPExcel_IOFactory;
use PHPExcel_Reader_Exception;
use Throwable;

class FsspInformation
{
    /**
     * @var App
     */
    protected $app;
    protected $logger;

    public function __construct(App $app, CustomLogger $logger)
    {
        $this->app = $app;
        $this->logger = $logger;
    }

    /**
     * @return Result
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function check()
    {

        $retData = new Result();

        $last_name = $this->app->lastname;
        $first_name = $this->app->name;
        $patronymic = $this->app->patronymic;
        $date = $this->app->birthday->format('d.m.y');
        $out_arr = [];

        for($i = 0; $i < 3; $i++) {

            try {

                if(!file_exists(storage_path('tmp/cooks')))
                    mkdir(storage_path('tmp/cooks'), 0777, true);
//
                $storagePathCooks = storage_path('tmp/cooks') . "/";
                $storagePathTmp = storage_path('tmp') . "/";

                $session = date('YmdHis') . rand(100000, 999999);
                set_time_limit(300);
                $api_key = config('datame.rucaptcha_key');


                $fp = fopen($storagePathCooks . $session, 'w');
                $r = "$last_name, $first_name, $patronymic, $date";
                fwrite($fp, $r);


                $filelist = scandir($storagePathCooks);
                rsort($filelist);

                if(!$filelist[0]) {
                    $filelist[0] = $session;
                }
                $cookies_file = $storagePathCooks . $filelist[0];
                $new_cookies_file = $storagePathCooks . $session;

                $params = array(
                    'system' => 'ip',
                    'nocache' => 1,
                    'is' => array(
                        'extended' => 1,
                        'variant' => 1,
                        'region_id' => -1,
                        'last_name' => $last_name,
                        'first_name' => $first_name,
                        'patronymic' => $patronymic,
                        'date' => $date
                    )
                );

//print_r($params);
                $ch = curl_init();
                $curl_params = array(
                    CURLOPT_URL => 'http://is.fssprus.ru/ajax_search?' . http_build_query($params),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_COOKIEFILE => $cookies_file,
                    CURLOPT_COOKIEJAR => $new_cookies_file,
                    CURLOPT_TIMEOUT => 10
                );
                curl_setopt_array($ch, $curl_params);
                $count0 = 0; //$result='';
                while(empty($result) && $count0 < 4) {
                    $result = curl_exec($ch);
                    $count0 = $count0 + 1;
                }

//echo "-----------------------------1----------------------------";
//print_r($result);

                $count = 0;
                if(preg_match('#<img[^>]+src="([^"]+)#s', stripslashes($result))) {
                    while(preg_match('#<img[^>]+src="([^"]+)#s', stripslashes($result), $matches) && $count < 4) {
                        $this->logger->info("=========went for captcha===========");
                        file_put_contents($storagePathTmp . $session . '.jpg', file_get_contents($matches[1]));
                        $recogn = $this->recognizefssp($storagePathTmp . $session . '.jpg', $api_key, false, 'rucaptcha.com', 5, 60, 0, 0, 0, 5, 5, 1);
                        $params['code'] = $recogn[0];

                        curl_setopt($ch, CURLOPT_URL, 'http://is.fssprus.ru/ajax_search?' . http_build_query($params));
                        $result = curl_exec($ch);
                        $count = $count + 1;
                    }
                }
                if($count == 4) {
                    $result['error'] = "error";
                }
                curl_close($ch);

                //fwrite($fp, $result);

                $key = 0;
                $output = array();
                $result = json_decode($result, true);

                if(!empty($result['error'])) {
                    //$output[] = trim(strip_tags($result['error']));
                } elseif(strpos($result['data'], 'По вашему запросу ничего не найдено') !== false) {
                    //$output[] = trim(strip_tags($result['data']));
                    $retData->setStatusResult(true);
                    $retData->setResult([]);
                } elseif(strpos($result['data'], 'Неверно введен код') !== false) {
                    $output[] = 'captchaerror|' . $recogn[1];
                } else {
                    $result['data'] = '<?xml version="1.0" encoding="utf-8"?>' . $result['data'];


                    $dom = new DOMDocument();
                    @$dom->loadHTML($result['data']);
                    $xpath = new DOMXPath($dom);

                    $xres = $xpath->query('//table/tr[position() > 1 and @class!="region-title"]');
                    if($xres->length) {

                        foreach($xres as $item) {
                            $output[$key] = array();
                            $childs = $item->getElementsByTagName('td');

                            foreach($childs as $num => $td) {
                                if($num == 3 || $num == 4) {
                                    continue;
                                }
                                $output[$key][] = $td->nodeValue;
                            }

                            $number_tmp = $output[$key][1];


                            if(preg_match("/-/", $number_tmp)) {
                                $number_tmp = explode("-", $number_tmp);
                                $number = $number_tmp[0] . "-IP";
                                $protocol_date_tmp = explode(" ", $number_tmp[1]);
                                $protocol_date = $protocol_date_tmp[2];

                            } else {
                                $number_tmp = explode(" ", $number_tmp);
                                $number = $number_tmp[0];
                                $protocol_date = $number_tmp[2];
                            }

                            $filenumber = preg_replace('/[^0-9]/', '', $number);

                            $new_session = date('YmdHis') . rand(100000, 999999);
                            $new_cookies_file2 = $storagePathCooks . $new_session;

                            $ch2 = curl_init();
                            $curl_params2 = array(
                                CURLOPT_URL => "http://is.fssprus.ru/get_receipt/?receipt=" . $number,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_COOKIEFILE => $cookies_file,
                                CURLOPT_COOKIEJAR => $new_cookies_file2,
                                CURLOPT_TIMEOUT => 10
                            );
                            curl_setopt_array($ch2, $curl_params2);

                            $result2 = curl_exec($ch2);

                            $count2 = 0;
                            if(preg_match('#<img[^>]+src="([^"]+)#s', stripslashes($result2), $matches)) {
                                while((preg_match('#<img[^>]+src="([^"]+)#s', stripslashes($result2), $matches) || !$result2) && $count2 < 4) {
                                    file_put_contents($storagePathTmp . $new_session . '.jpg', file_get_contents($matches[1]));
                                    $recogn = $this->recognizefssp($storagePathTmp . $new_session . '.jpg', $api_key, false, 'rucaptcha.com', 5, 60, 0, 0, 0, 5, 5, 1);
                                    $params2['code'] = $recogn[0];
                                    $params2['receipt'] = $number;
                                    curl_setopt($ch2, CURLOPT_URL, 'http://is.fssprus.ru/get_receipt/?' . http_build_query($params2));
                                    $result2 = curl_exec($ch2);
                                    $count2 = $count2 + 1;

                                }
                            }

//////////////////////////////////////////////////////////////////////////get xls start

                            $restul2_tmp = explode(" ", $result2);

                            try {
                                $result3 = "";
                                while($result3 == '') {
                                    $result3 = file($restul2_tmp[4]);
                                }
                            } catch(\Exception $e) {
                                //что-то пошло не так
                                $this->logger->error($e->getMessage());
                                $this->logger->error($e->getTraceAsString());
                            }
                            $fp2 = fopen($storagePathTmp . $filenumber, 'w');

                            if($result3) {
                                foreach($result3 as $line_num => $line) {
                                    fwrite($fp2, $line);
                                }
                            }
                            fclose($fp2);

//////////////////////////////////////////////////////////////////////////get xls stop
/////////////////////////////////////////////////////////////////////////////start xls parse
                            $filename = $storagePathTmp . $filenumber;

                            $objReader = PHPExcel_IOFactory::createReaderForFile($filename);
                            $objPHPExcel = $objReader->load($filename);
                            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

                            $uin = $sheetData[2]['U'];
                            $poluchatel_name = $sheetData[3]['Q'];
                            $poluchatel_kpp = $sheetData[3]['AZ'];
                            $poluchatel_inn = $sheetData[5]['Q'];
                            $poluchatel_oktmo = $sheetData[5]['AW'];
                            $poluchatel_rs = $sheetData[7]['Q'];
                            $poluchatel_bik = $sheetData[9]['T'];
                            $naznachenie = $sheetData[10]['Q'];
                            $fio = $sheetData[12]['AB'];
                            $amount = $sheetData[15]['AA'];
                            $bank_name = $sheetData[7]['AL'];
                            $number = $output[$key][1];
                            $contact = $output[$key][5];


                            $out_arr[] = [
                                "fio" => trim($fio),
                                "amount" => trim("$amount"),
                                "oktmo" => trim("$poluchatel_oktmo"),
                                "nazn" => trim("$naznachenie"),
                                "bik" => trim("$poluchatel_bik"),
                                "name_poluch" => trim("$poluchatel_name"),
                                "rs" => trim("$poluchatel_rs"),
                                "bank" => trim("$bank_name"),
                                "kpp" => trim("$poluchatel_kpp"),
                                "inn" => trim("$poluchatel_inn"),
                                "date_protocol" => trim("$protocol_date"),
                                "contact" => trim("$contact"),
                                "number" => trim("$number")
                            ];
                            $retData->setStatusResult(true);

                            $this->logger->info("=========file 3===========" . $filename);
                            $this->logger->info("amount" . $amount);
                            $this->logger->info("kbk" . "");
                            $this->logger->info("oktmo" . "$poluchatel_oktmo");
                            $this->logger->info("nazn" . "$naznachenie");
                            $this->logger->info("bik" . "$poluchatel_bik");
                            $this->logger->info("name_poluch" . "$poluchatel_name");
                            $this->logger->info("rs" . "$poluchatel_rs");
                            $this->logger->info("bank" . "$bank_name");
                            $this->logger->info("kpp" . "$poluchatel_kpp");
                            $this->logger->info("inn" . "$poluchatel_inn");
                            $this->logger->info("date_protocol" . "$protocol_date");
                            $this->logger->info("contact" . "$contact");
                            $this->logger->info("number" . "$number");
/////////////////////////////////////////////////////////////////////////////xls parse end
                            $key++;
                        }
                    }
                    unset($params['code'], $params['t']);

                    $xres = $xpath->query('//div[@class="pagination"]/div/a[position()=(last()-1)]');
                    if($xres->length) {
                        $last_page = (int)$xres->item(0)->nodeValue;
                        if($last_page > 1) {

                            for($i = 2; $i <= $last_page; $i++) {
                                sleep(10);
                                $params['page'] = $i;

                                $ch = curl_init();
                                $curl_params = array(
                                    CURLOPT_URL => 'http://is.fssprus.ru/ajax_search?' . http_build_query($params),
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_COOKIEFILE => $cookies_file,
                                    CURLOPT_COOKIEJAR => $cookies_file,
                                    CURLOPT_TIMEOUT => 10
                                );
                                curl_setopt_array($ch, $curl_params);

                                $result = curl_exec($ch);
                                curl_close($ch);

                                if(!empty($result)) {
                                    $result = json_decode($result, true);
                                    $result['data'] = '<?xml version="1.0" encoding="utf-8"?>' . $result['data'];

                                    $dom = new DOMDocument();
                                    @$dom->loadHTML($result['data']);
                                    $xpath = new DOMXPath($dom);

                                    $xres = $xpath->query('//table/tr[position() > 1 and @class!="region-title"]');
                                    if($xres->length) {
//Log::info("=========foreach8===========");
                                        foreach($xres as $item) {
                                            $output[$key] = array();
                                            $childs = $item->getElementsByTagName('td');
//Log::info("=========foreach9===========");
                                            foreach($childs as $num => $td) {
                                                if($num == 3 || $num == 4) {
                                                    continue;
                                                }
                                                $output[$key][] = $td->nodeValue;
                                            }


                                            $number_tmp = $output[$key][1];
//                                    $number_tmp = explode("-", $number_tmp);
//                                    $number = $number_tmp[0] . "-IP";

//                                    $protocol_date_tmp = explode(" ", $number_tmp[1]);
//                                    $protocol_date = $protocol_date_tmp[2];
                                            if(preg_match("/-/", $number_tmp)) {
                                                $number_tmp = explode("-", $number_tmp);
                                                $number = $number_tmp[0] . "-IP";
                                                $protocol_date_tmp = explode(" ", $number_tmp[1]);
                                                $protocol_date = $protocol_date_tmp[2];

                                            } else {
                                                $number_tmp = explode(" ", $number_tmp);
                                                $number = $number_tmp[0];
                                                $protocol_date = $number_tmp[2];
                                            }
                                            $filenumber = preg_replace('/[^0-9]/', '', $number);

                                            $ch2 = curl_init();
                                            $curl_params2 = array(
                                                CURLOPT_URL => "http://is.fssprus.ru/get_receipt/?receipt=" . $number,
                                                CURLOPT_RETURNTRANSFER => true,
                                                CURLOPT_COOKIEFILE => $cookies_file,
                                                CURLOPT_COOKIEJAR => $cookies_file,
                                                CURLOPT_TIMEOUT => 10
                                            );
                                            curl_setopt_array($ch2, $curl_params2);
                                            $result2 = curl_exec($ch2);


//////////////////////////////////////////////////////////////////////////get xls start
                                            $restul2_tmp = explode(" ", $result2);
                                            $result3 = file($restul2_tmp[4]);
                                            $fp = fopen($storagePathTmp . $filenumber, 'w');

                                            foreach($result3 as $line_num => $line) {
                                                fwrite($fp, $line);
                                            }
                                            fclose($fp);

//////////////////////////////////////////////////////////////////////////get xls stop

/////////////////////////////////////////////////////////////////////////////start xls parse
                                            $filename = $storagePathTmp . $filenumber;
                                            $objReader = PHPExcel_IOFactory::createReaderForFile($filename);
                                            $objPHPExcel = $objReader->load($filename);
                                            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

                                            $uin = $sheetData[2]['U'];
                                            $poluchatel_name = $sheetData[3]['Q'];
                                            $poluchatel_kpp = $sheetData[3]['AZ'];
                                            $poluchatel_inn = $sheetData[5]['Q'];
                                            $poluchatel_oktmo = $sheetData[5]['AW'];
                                            $poluchatel_rs = $sheetData[7]['Q'];
                                            $poluchatel_bik = $sheetData[9]['T'];
                                            $naznachenie = $sheetData[10]['Q'];
                                            $fio = $sheetData[12]['AB'];
                                            $amount = $sheetData[15]['AA'];
                                            $bank_name = $sheetData[7]['AL'];
                                            $number = $output[$key][1];
                                            $contact = $output[$key][5];
//Общая сумма начисления
//КБК
//ОКТМО (ОКАТО)
//Назначение платежа
//БИК
//Наименование получателя платежа
//Номер счета получателя платежа
//Наименование банка получателя платежа
//КПП получателя
//ИНН получателя
//Дата начисления

                                            $out_arr[] = [
                                                "fio" => trim($fio),
                                                "amount" => trim("$amount"),
                                                "oktmo" => trim("$poluchatel_oktmo"),
                                                "nazn" => trim("$naznachenie"),
                                                "bik" => trim("$poluchatel_bik"),
                                                "name_poluch" => trim("$poluchatel_name"),
                                                "rs" => trim("$poluchatel_rs"),
                                                "bank" => trim("$bank_name"),
                                                "kpp" => trim("$poluchatel_kpp"),
                                                "inn" => trim("$poluchatel_inn"),
                                                "date_protocol" => trim("$protocol_date"),
                                                "contact" => trim("$contact"),
                                                "number" => trim("$number")
                                            ];
                                            $retData->setStatusResult(true);

/////////////////////////////////////////////////////////////////////////////xls parse end
                                            $key++;

                                        }
                                    }
                                }
                            }

                        }
                    }

                }

            } catch(Throwable $e) {
                $this->logger->error("error [{$i}]: {$e->getMessage()}, {$e->getTraceAsString()}");
            }

            $retData->setResult($out_arr);
            $this->logger->info("result", $retData->toArray());
            break;
        }

        return $retData;
    }


    protected function recognizefssp(
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

}