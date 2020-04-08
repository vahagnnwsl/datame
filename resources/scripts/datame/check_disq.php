<?php 

include ('capresolve.php');
include('simple_html_dom.php');


$fam = 'АБАБКОВА';
$nam = 'МАРГАРИТА';
$otch = 'МИХАЙЛОВНА';
$bdate = '25.12.1957';

$bdate = str_replace('.','',$bdate);
$bdate_tmp=substr($bdate,0,2).".".substr($bdate,2,2).".".substr($bdate,4,6);
$bdate=$bdate_tmp;


$msg=2;

while ($msg == 2) {

$captcha_arr = getcapctha();

if ($captcha_arr == "fail") {
                            echo "Ошибка капчи"; 
                            $msg=4;
                            } else {

$captcha=$captcha_arr[1];
$captchanum=$captcha_arr[0];
$captchaID = $captcha_arr[2];

$host = "https://service.nalog.ru/";
        $path = "disqualified-ajax.do";
        $data = "fam=$fam&nam=$nam&otch=$otch&birthDate=$bdate&captcha=$captcha&captchaToken=$captchanum";



        $curl = curl_init($host.$path);
        curl_setopt($curl, CURLOPT_URL, $host.$path);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $content=curl_exec($curl);
	curl_close($curl);
$msg=0;


} /// while msg 2

}
print_r($content);


$output = json_decode($content);
print_r($output);

if ($output->rowsFound == 0) {
         $mvd = "Не найден";
	 echo $mvd;
                             } else if ($output->rowsFound > 0) {
                                    $mvd = json_encode($output->rows);
                                    echo $mvd;
                                    }
else {
         $mvd = "Ошибка";
	 echo $mvd;

     }


?>