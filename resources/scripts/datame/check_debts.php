<?php



$mysqli = mysqli_connect('u330493.mysql.masterhost.ru','u330493_peneynet','agas2iondr','u330493_peneynet');
$result00 = $mysqli->query("SELECT * FROM request_people where length(debts) =0 and length(inn)  = 12 order by id desc");

if (mysqli_num_rows($result00)>0) {

while ($people = $result00->fetch_object()){

$inn = $people->inn;
$id = $people->id;



$session=date('YmdHis').rand(100000,999999);
$cookies_file = dirname(__FILE__).'/cooks/cookies'.$session.'.txt';

//debtorsearch=typeofsearch=Persons&orgname=&orgaddress=&orgregionid=&orgogrn=&orginn=&orgokpo=&OrgCategory=&prslastname=&prsfirstname=&prsmiddlename=&prsaddress=&prsregionid=&prsinn=026908863083&prsogrn=&prssnils=&PrsCategory=&pagenumber=0
	$ch = curl_init();
	$curl_params = array(
		CURLOPT_URL => 'http://bankrot.fedresurs.ru/DebtorsSearch.aspx',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_COOKIE=> "debtorsearch=typeofsearch=Persons&orgname=&orgaddress=&orgregionid=&orgogrn=&orginn=&orgokpo=&OrgCategory=&prslastname=&prsfirstname=&prsmiddlename=&prsaddress=&prsregionid=&prsinn=$inn&prsogrn=&prssnils=&PrsCategory=&pagenumber=0",
		CURLOPT_COOKIEFILE => $cookies_file,
		CURLOPT_COOKIEJAR => $cookies_file,
		CURLOPT_TIMEOUT => 10
	);
	curl_setopt_array($ch, $curl_params);
	$result = curl_exec($ch);
	$counter = 0;

//print_r($result);

if(preg_match('/По заданным критериям не найдено ни одной записи. Уточните критерии поиска/', $result, $r)) {

         $mvd = iconv("UTF-8","windows-1251","Не найдено");
	 $result2 = $mysqli->query("update request_people set debts = '$mvd' where id=$id"); 

} else {
/*
include('simple_html_dom.php');
$html = new simple_html_dom();  
$html->load($result);

foreach($html->find('a[href]') as $links)
{

if(preg_match('/PrivatePersonCard.aspx\?ID=/', $links, $r)) {
$uid_arr = explode('=', $links->href);
$uids[]=$uid_arr[1];
}
*/
         $mvd = iconv("UTF-8","windows-1251","Банкрот");
	 $result2 = $mysqli->query("update request_people set debts = '$mvd' where id=$id"); 
} 

/*
foreach($uids as $uid) {
echo($uid);
	$ch = curl_init();
	$curl_params = array(
		CURLOPT_URL => 'http://bankrot.fedresurs.ru/PrivatePersonCard.aspx?ID='.$uid,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_COOKIEFILE => $cookies_file,
		CURLOPT_COOKIEJAR => $cookies_file,
		CURLOPT_TIMEOUT => 10
	);
	curl_setopt_array($ch, $curl_params);
	$result = curl_exec($ch);

                       }
*/

}

}
?>