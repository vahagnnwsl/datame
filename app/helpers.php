<?php

use Illuminate\Support\Collection;

if(!function_exists('dt_parse')) {

    /**
     * @param $dt
     * @return \Carbon\Carbon
     */
    function dt_parse($dt)
    {
        $parts = date_parse_from_format("d.m.Y", $dt);
        return \Carbon\Carbon::createFromDate($parts['year'], $parts['month'], $parts['day']);
    }
}

if(!function_exists('format_date')) {

    /**
     * @param \Carbon\Carbon $date
     * @return string
     */
    function format_date(\Carbon\Carbon $date)
    {
        return $date->format('d.m.Y');
    }
}

if(!function_exists('format_time')) {

    /**
     * @param \Carbon\Carbon $date
     * @return string
     */
    function format_time(\Carbon\Carbon $date)
    {
        return $date->format('H:i');
    }
}

if(!function_exists('format_date_time')) {

    /**
     * @param \Carbon\Carbon $date
     * @return string
     */
    function format_date_time(\Carbon\Carbon $date)
    {
        return $date->format('d.m.Y H:i');
    }
}

if(!function_exists('clear_phone')) {

    function clear_phone($phone)
    {
        $phone = str_replace('+', '', $phone);
        $phone = str_replace('(', '', $phone);
        $phone = str_replace(')', '', $phone);
        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('-', '', $phone);

        return $phone;
    }
}

if(!function_exists('add_seven_to_phone')) {

    function add_seven_to_phone($phone)
    {
        return "7" . $phone;
    }
}


if(!function_exists('is_active_page')) {

    function is_active_page($name)
    {
        return request()->segment(1) == $name ? 'active' : '';
    }
}

if(!function_exists('mb_ucfirst')) {

    function mb_ucfirst($value)
    {
        return mb_convert_case(mb_strtolower($value), MB_CASE_TITLE, 'UTF-8');
    }
}

if (!function_exists('latin_to_cyrillic')) {
    function latin_to_cyrillic($latinString) {
        $cyr = [
            'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
        ];
        $lat = [
            'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
        ];
        return str_replace($lat, $cyr, $latinString);
    }
}

if (!function_exists('cyrillic_to_latin')) {
    function cyrillic_to_latin($cyrillicString) {
        $cyr = [
            'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
        ];
        $lat = [
            'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
        ];
        return str_replace($cyr, $lat, $cyrillicString);
    }
}

if(!function_exists('trans_ru_to_en')) {

    function trans_ru_to_en($value)
    {
        $value = mb_strtolower($value);
        $alphabet_ru_to_en = array
        (
            "а" => "a",
            "б" => "b",
            "в" => "v",
            "г" => "g",
            "д" => "d",
            "е" => "e",
            "ё" => "yo",
            "ж" => "zh",
            "з" => "z",
            "и" => "i",
            "й" => "j",
            "к" => "k",
            "л" => "l",
            "м" => "m",
            "н" => "n",
            "о" => "o",
            "п" => "p",
            "р" => "r",
            "с" => "s",
            "т" => "t",
            "у" => "u",
            "ф" => "f",
            "х" => "h",
            "ц" => "c",
            "ч" => "ch",
            "ш" => "sh",
            "щ" => "sch",
            "ъ" => "\'",
            "ы" => "y",
            "ь" => "\'\'",
            "э" => "e\'",
            "ю" => "yu",
            "я" => "ya",
        );

        return strtr($value, $alphabet_ru_to_en);

    }
}

if(!function_exists('rus2translit')) {

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
}

if(!function_exists('formatNumberDecimal')) {
    function formatNumberDecimal($number)
    {
        $numberString = str_replace(".", ",", (string)$number);
        $parts = explode(",", $numberString);

        $formattedFirstPart = [];
        if(count($parts)) {
            $item = [];
            $partsFirst = str_split($parts[0]);
            for($i = count($partsFirst); $i > 0; $i--) {
                $num = $partsFirst[$i - 1];
                if(count($item) == 3) {
                    array_unshift($formattedFirstPart, implode("", $item));
                    $item = [];
                    array_unshift($item, $num);
                } else {
                    array_unshift($item, $num);
                }
            }
            array_unshift($formattedFirstPart, implode("", $item));
        }

        if(count($parts) == 2) {
            if(strlen($parts[1]) == 1)
                $parts[1] = "{$parts[1]}0";

            return implode(".", $formattedFirstPart) . "," . $parts[1];
        }

        return implode(".", $formattedFirstPart) . ",00";
    }
}

if(!function_exists('toDecimalFromFormatNumber')) {
    function toDecimalFromFormatNumber($number)
    {
        $numberString = str_replace(".", "", (string)$number);
        $numberString = str_replace(",", ".", $numberString);

        return doubleval($numberString);
    }
}

if(!function_exists('identity')) {
    function identity(string $identity, string $salt = null)
    {
        return is_null($salt) ? $identity : "{$identity}_$salt";
    }
}

if(!function_exists('serviceNotRespond')) {
    function serviceNotRespond($service_id, Collection $services)
    {
        $service = $services->first(function($item) use($service_id) {
            return $item['type'] == $service_id;
        });
        if(is_null($service))
           return false;

        return $service['status'] == 3;
    }
}


if(!function_exists('serviceMessage')) {
    function serviceMessage($service_id, Collection $services)
    {
        $service = $services->first(function($item) use($service_id) {
            return $item['type'] == $service_id;
        });
        if(is_null($service))
            return null;

        return !is_null($service['message']) ? $service['message'] : null;
    }
}

if (!function_exists('convert')) {
    function convert($content) {
        if (mb_convert_encoding($content, 'UTF-8') === $content) {
            return $content;
        }
        return mb_convert_encoding($content, 'utf-8', 'windows-1251');
    }
}

