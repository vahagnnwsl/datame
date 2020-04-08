<?php

$mysqli = mysqli_connect('u330493.mysql.masterhost.ru','u330493_peneynet','agas2iondr','u330493_peneynet');

$result = $mysqli->query("SELECT * FROM request_people where length(mvd)=0 and length(pasport) > 6 order by id desc");

if (mysqli_num_rows($result)>0) {

while ($people = $result->fetch_object()){
$id = $people->id;
$docn = $people->pasport;
$ser=substr($docn,0,4);
$num=substr($docn,4,6);

$session=date('YmdHis').rand(100000,999999);
$api_key = '329eb3a5a0619564fd18fc3d12a6ff69';
$cookies_file = dirname(__FILE__).'/pass/cookies'.$session.'.txt';

	$ch2 = curl_init();
	$curl_params2 = array(
		CURLOPT_URL => 'http://xn--b1afk4ade4e.xn--b1ab2a0a.xn--b1aew.xn--p1ai/services/captcha.jpg',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "GET",
        	CURLOPT_FOLLOWLOCATION => 1,
		CURLOPT_COOKIEFILE => $cookies_file,
		CURLOPT_COOKIEJAR => $cookies_file,
		CURLOPT_HTTPHEADER => array("Cache-Control: no-cache"),
		CURLOPT_TIMEOUT => 10

	);
	curl_setopt_array($ch2, $curl_params2);
	$result2 = curl_exec($ch2);

	$counter = 0;

		if (file_put_contents(dirname(__FILE__).'/pass/'.$session.'captcha.jpg', $result2)) {
			$recogn = recognizefssp(dirname(__FILE__).'/pass/'.$session.'captcha.jpg', $api_key, false, 'rucaptcha.com', 5, 60, 0, 0, 0, 6, 6, 1);
                        $params['code'] = $recogn[0]; 
		}

	curl_close($ch2);

$content2 = 'sid=2000&form_name=form&DOC_SERIE='.$ser.'&DOC_NUMBER='.$num.'&captcha-input='.$recogn[0];

	$ch3 = curl_init();
	$curl_params3 = array(
        	CURLOPT_POST => true,
        	CURLOPT_POSTFIELDS => $content2,
		CURLOPT_URL => 'http://xn--b1afk4ade4e.xn--b1ab2a0a.xn--b1aew.xn--p1ai/info-service.htm',
		CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_FOLLOWLOCATION => 1,
		CURLOPT_COOKIEFILE => $cookies_file,
		CURLOPT_COOKIEJAR => $cookies_file,
		CURLOPT_TIMEOUT => 10
	);
	curl_setopt_array($ch3, $curl_params3);
	$result3 = curl_exec($ch3);

		$dom = new DOMDocument();
		@$dom->loadHTML($result3);
		$res = $dom->getElementsByTagName('h4')->item(0)->nodeValue;
		//echo $res;

	if (preg_match('/не значится/sei', $res)) {
         $mvd = iconv("UTF-8","windows-1251","Среди недействительных не значится");
	 $result2 = $mysqli->query("update request_people set mvd = '$mvd' where id=$id"); 
	                                         } else {
						         $mvd = iconv("UTF-8","windows-1251",$res);
							 $result2 = $mysqli->query("update request_people set mvd = '$mvd' where id=$id"); 
	                                                }
}
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
	if (!file_exists($filename))
	{
		if ($is_verbose) echo "file $filename not found\n";
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
		if ($is_verbose) echo "could not read file $filename\n";
		return false;
	}
    
    $conttype="image/pjpeg";
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
    
    if ($is_verbose) echo "connecting $sendhost...";
    $fp=fsockopen($sendhost,80,$errno,$errstr,30);
    if ($fp!=false)
    {
    	if ($is_verbose) echo "OK\n";
    	if ($is_verbose) echo "sending request ".strlen($poststr)." bytes...";
    	fputs($fp,$poststr);
    	if ($is_verbose) echo "OK\n";
    	if ($is_verbose) echo "getting response...";
    	$resp="";
    	while (!feof($fp)) $resp.=fgets($fp,1024);
    	fclose($fp);
    	$result=substr($resp,strpos($resp,"\r\n\r\n")+4);
    	if ($is_verbose) echo "OK\n";
    }
    else 
    {
    	if ($is_verbose) echo "could not connect to anti-captcha\n";
        if ($is_verbose) echo "socket error: $errno ( $errstr )\n";
    	return false;
    }
    
    if (strpos($result, "ERROR")!==false or strpos($result, "<HTML>")!==false)
    {
    	if ($is_verbose) echo "server returned error: $result\n";
        return false;
    }
    else
    {
        $ex = explode("|", $result);
        $captcha_id = $ex[1];
    	if ($is_verbose) echo "captcha sent, got captcha ID $captcha_id\n";
        $waittime = 0;
        if ($is_verbose) echo "waiting for $rtimeout seconds\n";
        sleep($rtimeout);
        while(true)
        {
            $result = file_get_contents('http://rucaptcha.com/res.php?key='.$apikey.'&action=get&id='.$captcha_id);
            if (strpos($result, 'ERROR')!==false)
            {
            	if ($is_verbose) echo "server returned error: $result\n";
                return false;
            }
            if ($result=="CAPCHA_NOT_READY")
            {
            	if ($is_verbose) echo "captcha is not ready yet\n";
            	$waittime += $rtimeout;
            	if ($waittime>$mtimeout) 
            	{
            		if ($is_verbose) echo "timelimit ($mtimeout) hit\n";
            		break;
            	}
        		if ($is_verbose) echo "waiting for $rtimeout seconds\n";
            	sleep($rtimeout);
            }
            else
            {
            	$ex = explode('|', $result);
		if (trim($ex[0])=='OK') { $return[0] = trim($ex[1]); $return[1] = $captcha_id; return $return; }
            }
        }
        
        return false;
    }
}



?>