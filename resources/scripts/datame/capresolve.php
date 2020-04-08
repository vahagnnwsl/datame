<?php


function getcapctha() {
$session=date('YmdHis').rand(100000,999999);

$cookies_file = dirname(__FILE__).'/inncooks/'.$session.'.txt';

$host = "https://service.nalog.ru/disqualified.do";
        $path = "";
        $data = "";
        $curl = curl_init($host.$path);
        curl_setopt($curl, CURLOPT_URL, $host.$path);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookies_file);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookies_file);

        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $content=curl_exec($curl);
	curl_close($curl);

if (preg_match('/captcha.bin\?a=(.*)\"/', $content,$arr)) { $captchanum=explode("\"", $arr[1]);  }
$host = "https://service.nalog.ru/";
        $path = "static/captcha.bin";
        $data = "a=".$captchanum[0];
        $curl = curl_init($host.$path);
        curl_setopt($curl, CURLOPT_URL, $host.$path);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $cookies_file);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookies_file);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $content2=curl_exec($curl);
	curl_close($curl);

$fp = fopen('captcha/'.$captchanum[0].'.gif', 'w');
fwrite($fp, $content2);
fclose($fp);

if ($captchanum) {
$return[0] = $captchanum[0];
$returntmp = recognize('captcha/'.$captchanum[0].'.gif',"329eb3a5a0619564fd18fc3d12a6ff69","6", "6","1");  
$return[1] = $returntmp[0];
$return[2] = $returntmp[1];
$return[3] = $session;
return $return;
                 } else {
                         return "fail";
                        }

                }


function recognize(
		$filename,
		$apikey,
		$min_len,
		$max_len,
		$is_numeric,
		$is_verbose = true,
		$sendhost = "rucaptcha.com",
		$rtimeout = 5,
		$mtimeout = 120,
		$is_phrase = 0,
		$is_regsense = 0,

		$language = 0)
{
	if (!file_exists($filename))
	{
		if ($is_verbose) echo "";//file $filename not found\n";
		return false;
	}
	$fp=fopen($filename,"r");
	if ($fp!=false)
	{
		$body="";
		while (!feof($fp)) $body.=fgets($fp,1024);
		fclose($fp);
                $ext=strtolower(substr($filename,strpos($filename,".")+1));
	}
	else
	{
		if ($is_verbose) echo "";//could not read file $filename\n";
		return false;
	}
    
    if ($ext=="jpg") $conttype="image/pjpeg";
    if ($ext=="gif") $conttype="image/gif";
    if ($ext=="png") $conttype="image/png";
    
    
    $boundary="---------FGf4Fh3fdjGQ148fdh";
    
    $content="--$boundary\r\n";
    $content.="Content-Disposition: form-data; name=\"method\"\r\n";
    $content.="\r\n";
    $content.="post\r\n";
    $content.="--$boundary\r\n";
    $content.="Content-Disposition: form-data; name=\"key\"\r\n";
    $content.="\r\n";
    $content.="$apikey\r\n";
    $content.="--$boundary\r\n";
    $content.="Content-Disposition: form-data; name=\"phrase\"\r\n";
    $content.="\r\n";
    $content.="$is_phrase\r\n";
    $content.="--$boundary\r\n";
    $content.="Content-Disposition: form-data; name=\"regsense\"\r\n";
    $content.="\r\n";
    $content.="$is_regsense\r\n";
    $content.="--$boundary\r\n";
    $content.="Content-Disposition: form-data; name=\"numeric\"\r\n";
    $content.="\r\n";
    $content.="$is_numeric\r\n";
    $content.="--$boundary\r\n";
    $content.="Content-Disposition: form-data; name=\"min_len\"\r\n";
    $content.="\r\n";
    $content.="$min_len\r\n";
    $content.="--$boundary\r\n";
    $content.="Content-Disposition: form-data; name=\"max_len\"\r\n";
    $content.="\r\n";
    $content.="$max_len\r\n";
    $content.="--$boundary\r\n";
    $content.="Content-Disposition: form-data; name=\"language\"\r\n";
    $content.="\r\n";
    $content.="$language\r\n";
    $content.="--$boundary\r\n";
    $content.="Content-Disposition: form-data; name=\"file\"; filename=\"capcha.$ext\"\r\n";
    $content.="Content-Type: $conttype\r\n";
    $content.="\r\n";
    $content.=$body."\r\n"; //тело файла
    $content.="--$boundary--";
    
    
    $poststr="POST http://$sendhost/in.php HTTP/1.0\r\n";
    $poststr.="Content-Type: multipart/form-data; boundary=$boundary\r\n";
    $poststr.="Host: $sendhost\r\n";
    $poststr.="Content-Length: ".strlen($content)."\r\n\r\n";
    $poststr.=$content;
    
   // echo $poststr;
    
    if ($is_verbose) echo "";//connecting $sendhost...";
    $fp=fsockopen($sendhost,80,$errno,$errstr,30);
    if ($fp!=false)
    {
    	if ($is_verbose) echo "";//OK\n";
    	if ($is_verbose) echo "";//sending request ".strlen($poststr)." bytes...";
    	fputs($fp,$poststr);
    	if ($is_verbose) echo "";//OK\n";
    	if ($is_verbose) echo "";//getting response...";
    	$resp="";
    	while (!feof($fp)) $resp.=fgets($fp,1024);
    	fclose($fp);
    	$result=substr($resp,strpos($resp,"\r\n\r\n")+4);
    	if ($is_verbose) echo "";//OK\n";
    }
    else 
    {
    	if ($is_verbose) echo "";//could not connect to anti-captcha\n";
        if ($is_verbose) echo "";//socket error: $errno ( $errstr )\n";
    	return false;
    }
    
    if (strpos($result, "ERROR")!==false or strpos($result, "<HTML>")!==false)
    {
    	if ($is_verbose) echo "";//server returned error: $result\n";
        return false;
    }
    else
    {
        $ex = explode("|", $result);
        $captcha_id = $ex[1];
    	if ($is_verbose) echo "";//captcha sent, got captcha ID $captcha_id\n";
        $waittime = 0;
        if ($is_verbose) echo "";//waiting for $rtimeout seconds\n";
        sleep($rtimeout);
        while(true)
        {
            $result = file_get_contents('http://rucaptcha.com/res.php?key='.$apikey.'&action=get&id='.$captcha_id);
            if (strpos($result, 'ERROR')!==false)
            {
            	if ($is_verbose) echo "";//server returned error: $result\n";
                return false;
            }
            if ($result=="CAPCHA_NOT_READY")
            {
            	if ($is_verbose) echo "";//captcha is not ready yet\n";
            	$waittime += $rtimeout;
            	if ($waittime>$mtimeout) 
            	{
            		if ($is_verbose) echo "";//timelimit ($mtimeout) hit\n";
            		break;
            	}
        		if ($is_verbose) echo "";//waiting for $rtimeout seconds\n";
            	sleep($rtimeout);
            }
            else
            {
            	$ex = explode('|', $result);
            	//if (trim($ex[0])=='OK') return trim($ex[1]);
		if (trim($ex[0])=='OK') { $return[0] = trim($ex[1]); $return[1] = $captcha_id; return $return; };
            }
        }
        
        return false;
    }
}










function getcaptchafssp($r,$fam,$nam,$otch,$bdate) {

$captchanum[0]=$r;
$host = "http://is.fssprus.ru/";
        $path = "ajax_search";
        $data = "system=ip&is[extended]=1&is[variant]=1&is[last_name]=$fam&is[first_name]=$nam&is[patronymic]=$otch&is[date]=$bdate";
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
if (preg_match('|jpeg;base64,(.*)=|sei', $content, $arr)) $t = $arr[1];
   else $t='';
$newcap = split("\"", $t);
$newcap2 = substr($newcap[0], 0,-1);
$fp = fopen('captcha/'.$captchanum[0].'.jpeg', 'w');
fwrite($fp, base64_decode($newcap2));
fclose($fp);

$return[0] = $captchanum[0];
$returntmp = recognize('captcha/'.$captchanum[0].'.jpeg',"329eb3a5a0619564fd18fc3d12a6ff69","5", "5","0");  
$return[1] = $returntmp[0];
$return[2] = $returntmp[1];
return $return;
                }

function reportbadcaptcha($captcha_id) {
$apikey = '329eb3a5a0619564fd18fc3d12a6ff69';
            $result = file_get_contents('http://rucaptcha.com/res.php?key='.$apikey.'&action=reportbad&id='.$captcha_id);
        return $result;


}

?> 

           
