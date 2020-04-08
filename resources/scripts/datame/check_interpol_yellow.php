<?php

$family = 'арролл';
$name = 'донал';

$family = rus2translit($family);
$name = rus2translit($name);


echo $family.$name;

	$ch3 = curl_init();
	$curl_params3 = array(
		CURLOPT_URL => 'https://ws-public.interpol.int/notices/v1/yellow?name='.$family.'&forename='.$name,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYHOST=> false,
		CURLOPT_SSL_VERIFYPEER=> 0,
        	CURLOPT_FOLLOWLOCATION => 1

	);
	curl_setopt_array($ch3, $curl_params3);
	$result3 = curl_exec($ch3);
        $result3 = json_decode($result3);

if ($result3->total == 0) {
                          echo 'not found';
                          }
else {
print_r($result3);
     }



function rus2translit($text)
{
	// Русский алфавит
	$rus_alphabet = array(
		'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й',
		'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф',
		'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
		'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й',
		'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф',
		'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'
	);
	
	// Английская транслитерация
	$rus_alphabet_translit = array(
		'A', 'B', 'V', 'G', 'D', 'E', 'IO', 'ZH', 'Z', 'I', 'I',
		'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F',
		'H', 'C', 'CH', 'SH', 'SH', '`', 'Y', '`', 'E', 'IU', 'IA',
		'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'i',
		'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f',
		'h', 'c', 'ch', 'sh', 'sh', '`', 'y', '`', 'e', 'iu', 'ia'
	);
	
	return str_replace($rus_alphabet, $rus_alphabet_translit, $text);
}


?>