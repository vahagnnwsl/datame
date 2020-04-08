<?php

class cryptoHelpers
{
    public static function toHex($args)
    {
        if(func_num_args() != 1 || !is_array($args)) {
            $args = func_get_args();
        }
        $ret = '';
        for($i = 0; $i < count($args); $i++) $ret .= sprintf('%02x', $args[$i]);
        return $ret;
    }

    public static function toNumbers($s)
    {
        $ret = array();
        for($i = 0; $i < strlen($s); $i += 2) {
            $ret[] = hexdec(substr($s, $i, 2));
        }
        return $ret;
    }

    public static function getRandom($min, $max)
    {
        if($min === null) $min = 0;
        if($max === null) $max = 1;
        return mt_rand($min, $max);
    }

    public static function generateSharedKey($len)
    {
        if($len === null) $len = 16;
        $key = array();
        for($i = 0; $i < $len; $i++) $key[] = self::getRandom(0, 255);
        return $key;
    }

    /**
     * @param $s
     * @param $size
     * @return array
     * @throws Exception
     */
    public static function generatePrivateKey($s, $size)
    {
        if(function_exists('mhash') && defined('MHASH_SHA256')) {
            return self::convertStringToByteArray(substr(mhash(MHASH_SHA256, $s), 0, $size));
        } else {
            throw new Exception('cryptoHelpers::generatePrivateKey currently requires mhash');
        }
    }

    public static function convertStringToByteArray($s)
    {
        $byteArray = array();
        for($i = 0; $i < strlen($s); $i++) {
            $byteArray[] = ord($s[$i]);
        }
        return $byteArray;
    }

    public static function convertByteArrayToString($byteArray)
    {
        $s = '';
        for($i = 0; $i < count($byteArray); $i++) {
            $s .= chr($byteArray[$i]);
        }
        return $s;
    }

    public static function base64_encode_line($b)
    {
        return base64_encode(self::convertByteArrayToString($b));
    }

    public static function base64_encode($b)
    {
        $b64 = self::base64_encode_line($b);
        return chunk_split($b, 64, "\n");
    }

    public static function base64_decode($b)
    {
        return self::convertStringToByteArray(base64_decode($b));
    }
}

;

class AES
{
    const keySize_128 = 16;
    const keySize_192 = 24;
    const keySize_256 = 32;
    private static $sbox = array(0x63, 0x7c, 0x77, 0x7b, 0xf2, 0x6b, 0x6f, 0xc5, 0x30, 0x01, 0x67, 0x2b, 0xfe, 0xd7, 0xab, 0x76, 0xca, 0x82, 0xc9, 0x7d, 0xfa, 0x59, 0x47, 0xf0, 0xad, 0xd4, 0xa2, 0xaf, 0x9c, 0xa4, 0x72, 0xc0, 0xb7, 0xfd, 0x93, 0x26, 0x36, 0x3f, 0xf7, 0xcc, 0x34, 0xa5, 0xe5, 0xf1, 0x71, 0xd8, 0x31, 0x15, 0x04, 0xc7, 0x23, 0xc3, 0x18, 0x96, 0x05, 0x9a, 0x07, 0x12, 0x80, 0xe2, 0xeb, 0x27, 0xb2, 0x75, 0x09, 0x83, 0x2c, 0x1a, 0x1b, 0x6e, 0x5a, 0xa0, 0x52, 0x3b, 0xd6, 0xb3, 0x29, 0xe3, 0x2f, 0x84, 0x53, 0xd1, 0x00, 0xed, 0x20, 0xfc, 0xb1, 0x5b, 0x6a, 0xcb, 0xbe, 0x39, 0x4a, 0x4c, 0x58, 0xcf, 0xd0, 0xef, 0xaa, 0xfb, 0x43, 0x4d, 0x33, 0x85, 0x45, 0xf9, 0x02, 0x7f, 0x50, 0x3c, 0x9f, 0xa8, 0x51, 0xa3, 0x40, 0x8f, 0x92, 0x9d, 0x38, 0xf5, 0xbc, 0xb6, 0xda, 0x21, 0x10, 0xff, 0xf3, 0xd2, 0xcd, 0x0c, 0x13, 0xec, 0x5f, 0x97, 0x44, 0x17, 0xc4, 0xa7, 0x7e, 0x3d, 0x64, 0x5d, 0x19, 0x73, 0x60, 0x81, 0x4f, 0xdc, 0x22, 0x2a, 0x90, 0x88, 0x46, 0xee, 0xb8, 0x14, 0xde, 0x5e, 0x0b, 0xdb, 0xe0, 0x32, 0x3a, 0x0a, 0x49, 0x06, 0x24, 0x5c, 0xc2, 0xd3, 0xac, 0x62, 0x91, 0x95, 0xe4, 0x79, 0xe7, 0xc8, 0x37, 0x6d, 0x8d, 0xd5, 0x4e, 0xa9, 0x6c, 0x56, 0xf4, 0xea, 0x65, 0x7a, 0xae, 0x08, 0xba, 0x78, 0x25, 0x2e, 0x1c, 0xa6, 0xb4, 0xc6, 0xe8, 0xdd, 0x74, 0x1f, 0x4b, 0xbd, 0x8b, 0x8a, 0x70, 0x3e, 0xb5, 0x66, 0x48, 0x03, 0xf6, 0x0e, 0x61, 0x35, 0x57, 0xb9, 0x86, 0xc1, 0x1d, 0x9e, 0xe1, 0xf8, 0x98, 0x11, 0x69, 0xd9, 0x8e, 0x94, 0x9b, 0x1e, 0x87, 0xe9, 0xce, 0x55, 0x28, 0xdf, 0x8c, 0xa1, 0x89, 0x0d, 0xbf, 0xe6, 0x42, 0x68, 0x41, 0x99, 0x2d, 0x0f, 0xb0, 0x54, 0xbb, 0x16);
    private static $rsbox = array(0x52, 0x09, 0x6a, 0xd5, 0x30, 0x36, 0xa5, 0x38, 0xbf, 0x40, 0xa3, 0x9e, 0x81, 0xf3, 0xd7, 0xfb, 0x7c, 0xe3, 0x39, 0x82, 0x9b, 0x2f, 0xff, 0x87, 0x34, 0x8e, 0x43, 0x44, 0xc4, 0xde, 0xe9, 0xcb, 0x54, 0x7b, 0x94, 0x32, 0xa6, 0xc2, 0x23, 0x3d, 0xee, 0x4c, 0x95, 0x0b, 0x42, 0xfa, 0xc3, 0x4e, 0x08, 0x2e, 0xa1, 0x66, 0x28, 0xd9, 0x24, 0xb2, 0x76, 0x5b, 0xa2, 0x49, 0x6d, 0x8b, 0xd1, 0x25, 0x72, 0xf8, 0xf6, 0x64, 0x86, 0x68, 0x98, 0x16, 0xd4, 0xa4, 0x5c, 0xcc, 0x5d, 0x65, 0xb6, 0x92, 0x6c, 0x70, 0x48, 0x50, 0xfd, 0xed, 0xb9, 0xda, 0x5e, 0x15, 0x46, 0x57, 0xa7, 0x8d, 0x9d, 0x84, 0x90, 0xd8, 0xab, 0x00, 0x8c, 0xbc, 0xd3, 0x0a, 0xf7, 0xe4, 0x58, 0x05, 0xb8, 0xb3, 0x45, 0x06, 0xd0, 0x2c, 0x1e, 0x8f, 0xca, 0x3f, 0x0f, 0x02, 0xc1, 0xaf, 0xbd, 0x03, 0x01, 0x13, 0x8a, 0x6b, 0x3a, 0x91, 0x11, 0x41, 0x4f, 0x67, 0xdc, 0xea, 0x97, 0xf2, 0xcf, 0xce, 0xf0, 0xb4, 0xe6, 0x73, 0x96, 0xac, 0x74, 0x22, 0xe7, 0xad, 0x35, 0x85, 0xe2, 0xf9, 0x37, 0xe8, 0x1c, 0x75, 0xdf, 0x6e, 0x47, 0xf1, 0x1a, 0x71, 0x1d, 0x29, 0xc5, 0x89, 0x6f, 0xb7, 0x62, 0x0e, 0xaa, 0x18, 0xbe, 0x1b, 0xfc, 0x56, 0x3e, 0x4b, 0xc6, 0xd2, 0x79, 0x20, 0x9a, 0xdb, 0xc0, 0xfe, 0x78, 0xcd, 0x5a, 0xf4, 0x1f, 0xdd, 0xa8, 0x33, 0x88, 0x07, 0xc7, 0x31, 0xb1, 0x12, 0x10, 0x59, 0x27, 0x80, 0xec, 0x5f, 0x60, 0x51, 0x7f, 0xa9, 0x19, 0xb5, 0x4a, 0x0d, 0x2d, 0xe5, 0x7a, 0x9f, 0x93, 0xc9, 0x9c, 0xef, 0xa0, 0xe0, 0x3b, 0x4d, 0xae, 0x2a, 0xf5, 0xb0, 0xc8, 0xeb, 0xbb, 0x3c, 0x83, 0x53, 0x99, 0x61, 0x17, 0x2b, 0x04, 0x7e, 0xba, 0x77, 0xd6, 0x26, 0xe1, 0x69, 0x14, 0x63, 0x55, 0x21, 0x0c, 0x7d);

    private static function rotate($word)
    {
        $c = $word[0];
        for($i = 0; $i < 3; $i++) $word[$i] = $word[$i + 1];
        $word[3] = $c;
        return $word;
    }

    private static $Rcon = array(0x8d, 0x01, 0x02, 0x04, 0x08, 0x10, 0x20, 0x40, 0x80, 0x1b, 0x36, 0x6c, 0xd8, 0xab, 0x4d, 0x9a, 0x2f, 0x5e, 0xbc, 0x63, 0xc6, 0x97, 0x35, 0x6a, 0xd4, 0xb3, 0x7d, 0xfa, 0xef, 0xc5, 0x91, 0x39, 0x72, 0xe4, 0xd3, 0xbd, 0x61, 0xc2, 0x9f, 0x25, 0x4a, 0x94, 0x33, 0x66, 0xcc, 0x83, 0x1d, 0x3a, 0x74, 0xe8, 0xcb, 0x8d, 0x01, 0x02, 0x04, 0x08, 0x10, 0x20, 0x40, 0x80, 0x1b, 0x36, 0x6c, 0xd8, 0xab, 0x4d, 0x9a, 0x2f, 0x5e, 0xbc, 0x63, 0xc6, 0x97, 0x35, 0x6a, 0xd4, 0xb3, 0x7d, 0xfa, 0xef, 0xc5, 0x91, 0x39, 0x72, 0xe4, 0xd3, 0xbd, 0x61, 0xc2, 0x9f, 0x25, 0x4a, 0x94, 0x33, 0x66, 0xcc, 0x83, 0x1d, 0x3a, 0x74, 0xe8, 0xcb, 0x8d, 0x01, 0x02, 0x04, 0x08, 0x10, 0x20, 0x40, 0x80, 0x1b, 0x36, 0x6c, 0xd8, 0xab, 0x4d, 0x9a, 0x2f, 0x5e, 0xbc, 0x63, 0xc6, 0x97, 0x35, 0x6a, 0xd4, 0xb3, 0x7d, 0xfa, 0xef, 0xc5, 0x91, 0x39, 0x72, 0xe4, 0xd3, 0xbd, 0x61, 0xc2, 0x9f, 0x25, 0x4a, 0x94, 0x33, 0x66, 0xcc, 0x83, 0x1d, 0x3a, 0x74, 0xe8, 0xcb, 0x8d, 0x01, 0x02, 0x04, 0x08, 0x10, 0x20, 0x40, 0x80, 0x1b, 0x36, 0x6c, 0xd8, 0xab, 0x4d, 0x9a, 0x2f, 0x5e, 0xbc, 0x63, 0xc6, 0x97, 0x35, 0x6a, 0xd4, 0xb3, 0x7d, 0xfa, 0xef, 0xc5, 0x91, 0x39, 0x72, 0xe4, 0xd3, 0xbd, 0x61, 0xc2, 0x9f, 0x25, 0x4a, 0x94, 0x33, 0x66, 0xcc, 0x83, 0x1d, 0x3a, 0x74, 0xe8, 0xcb, 0x8d, 0x01, 0x02, 0x04, 0x08, 0x10, 0x20, 0x40, 0x80, 0x1b, 0x36, 0x6c, 0xd8, 0xab, 0x4d, 0x9a, 0x2f, 0x5e, 0xbc, 0x63, 0xc6, 0x97, 0x35, 0x6a, 0xd4, 0xb3, 0x7d, 0xfa, 0xef, 0xc5, 0x91, 0x39, 0x72, 0xe4, 0xd3, 0xbd, 0x61, 0xc2, 0x9f, 0x25, 0x4a, 0x94, 0x33, 0x66, 0xcc, 0x83, 0x1d, 0x3a, 0x74, 0xe8, 0xcb);

    private static function core($word, $iteration)
    {
        $word = self::rotate($word);
        for($i = 0; $i < 4; ++$i) $word[$i] = self::$sbox[$word[$i]];
        $word[0] = $word[0] ^ self::$Rcon[$iteration];
        return $word;
    }

    private static function expandKey($key, $size)
    {
        $expandedKeySize = (16 * (self::numberOfRounds($size) + 1));
        $currentSize = 0;
        $rconIteration = 1;
        $t = array();
        $expandedKey = array();
        for($i = 0; $i < $expandedKeySize; $i++) $expandedKey[$i] = 0;
        for($j = 0; $j < $size; $j++) $expandedKey[$j] = $key[$j];
        $currentSize += $size;
        while($currentSize < $expandedKeySize) {
            for($k = 0; $k < 4; $k++) $t[$k] = $expandedKey[($currentSize - 4) + $k];
            if($currentSize % $size == 0) $t = self::core($t, $rconIteration++);
            if($size == self::keySize_256 && (($currentSize % $size) == 16)) for($l = 0; $l < 4; $l++) $t[$l] = self::$sbox[$t[$l]];
            for($m = 0; $m < 4; $m++) {
                $expandedKey[$currentSize] = $expandedKey[$currentSize - $size] ^ $t[$m];
                $currentSize++;
            }
        }
        return $expandedKey;
    }

    private static function addRoundKey($state, $roundKey)
    {
        for($i = 0; $i < 16; $i++) $state[$i] = $state[$i] ^ $roundKey[$i];
        return $state;
    }

    private static function createRoundKey($expandedKey, $roundKeyPointer)
    {
        $roundKey = array();
        for($i = 0; $i < 4; $i++) for($j = 0; $j < 4; $j++) $roundKey[$j * 4 + $i] = $expandedKey[$roundKeyPointer + $i * 4 + $j];
        return $roundKey;
    }

    private static function subBytes($state, $isInv)
    {
        for($i = 0; $i < 16; $i++) $state[$i] = $isInv ? self::$rsbox[$state[$i]] : self::$sbox[$state[$i]];
        return $state;
    }

    private static function shiftRows($state, $isInv)
    {
        for($i = 0; $i < 4; $i++) $state = self::shiftRow($state, $i * 4, $i, $isInv);
        return $state;
    }

    private static function shiftRow($state, $statePointer, $nbr, $isInv)
    {
        for($i = 0; $i < $nbr; $i++) {
            if($isInv) {
                $tmp = $state[$statePointer + 3];
                for($j = 3; $j > 0; $j--) $state[$statePointer + $j] = $state[$statePointer + $j - 1];
                $state[$statePointer] = $tmp;
            } else {
                $tmp = $state[$statePointer];
                for($j = 0; $j < 3; $j++) $state[$statePointer + $j] = $state[$statePointer + $j + 1];
                $state[$statePointer + 3] = $tmp;
            }
        }
        return $state;
    }

    private static function galois_multiplication($a, $b)
    {
        $p = 0;
        for($counter = 0; $counter < 8; $counter++) {
            if(($b & 1) == 1) $p ^= $a;
            if($p > 0x100) $p ^= 0x100;
            $hi_bit_set = ($a & 0x80);
            $a <<= 1;
            if($a > 0x100) $a ^= 0x100;
            if($hi_bit_set == 0x80) $a ^= 0x1b;
            if($a > 0x100) $a ^= 0x100;
            $b >>= 1;
            if($b > 0x100) $b ^= 0x100;
        }
        return $p;
    }

    private static function mixColumn($column, $isInv)
    {
        if($isInv) $mult = array(14, 9, 13, 11); else $mult = array(2, 1, 1, 3);
        $cpy = array();
        for($i = 0; $i < 4; $i++) $cpy[$i] = $column[$i];
        $column[0] = self::galois_multiplication($cpy[0], $mult[0]) ^ self::galois_multiplication($cpy[3], $mult[1]) ^ self::galois_multiplication($cpy[2], $mult[2]) ^ self::galois_multiplication($cpy[1], $mult[3]);
        $column[1] = self::galois_multiplication($cpy[1], $mult[0]) ^ self::galois_multiplication($cpy[0], $mult[1]) ^ self::galois_multiplication($cpy[3], $mult[2]) ^ self::galois_multiplication($cpy[2], $mult[3]);
        $column[2] = self::galois_multiplication($cpy[2], $mult[0]) ^ self::galois_multiplication($cpy[1], $mult[1]) ^ self::galois_multiplication($cpy[0], $mult[2]) ^ self::galois_multiplication($cpy[3], $mult[3]);
        $column[3] = self::galois_multiplication($cpy[3], $mult[0]) ^ self::galois_multiplication($cpy[2], $mult[1]) ^ self::galois_multiplication($cpy[1], $mult[2]) ^ self::galois_multiplication($cpy[0], $mult[3]);
        return $column;
    }

    private static function mixColumns($state, $isInv)
    {
        $column = array();
        for($i = 0; $i < 4; $i++) {
            for($j = 0; $j < 4; $j++) $column[$j] = $state[($j * 4) + $i];
            $column = self::mixColumn($column, $isInv);
            for($k = 0; $k < 4; $k++) $state[($k * 4) + $i] = $column[$k];
        }
        return $state;
    }

    private static function round($state, $roundKey)
    {
        $state = self::subBytes($state, false);
        $state = self::shiftRows($state, false);
        $state = self::mixColumns($state, false);
        $state = self::addRoundKey($state, $roundKey);
        return $state;
    }

    private static function invRound($state, $roundKey)
    {
        $state = self::shiftRows($state, true);
        $state = self::subBytes($state, true);
        $state = self::addRoundKey($state, $roundKey);
        $state = self::mixColumns($state, true);
        return $state;
    }

    private static function main($state, $expandedKey, $nbrRounds)
    {
        $state = self::addRoundKey($state, self::createRoundKey($expandedKey, 0));
        for($i = 1; $i < $nbrRounds; $i++) $state = self::round($state, self::createRoundKey($expandedKey, 16 * $i));
        $state = self::subBytes($state, false);
        $state = self::shiftRows($state, false);
        $state = self::addRoundKey($state, self::createRoundKey($expandedKey, 16 * $nbrRounds));
        return $state;
    }

    private static function invMain($state, $expandedKey, $nbrRounds)
    {
        $state = self::addRoundKey($state, self::createRoundKey($expandedKey, 16 * $nbrRounds));
        for($i = $nbrRounds - 1; $i > 0; $i--) $state = self::invRound($state, self::createRoundKey($expandedKey, 16 * $i));
        $state = self::shiftRows($state, true);
        $state = self::subBytes($state, true);
        $state = self::addRoundKey($state, self::createRoundKey($expandedKey, 0));
        return $state;
    }

    private static function numberOfRounds($size)
    {
        $nbrRounds = null;
        switch($size) {
            case self::keySize_128:
                $nbrRounds = 10;
                break;
            case self::keySize_192:
                $nbrRounds = 12;
                break;
            case self::keySize_256:
                $nbrRounds = 14;
                break;
            default:
                return null;
                break;
        }
        return $nbrRounds;
    }

    private static function encryptBlock($input, $key, $size)
    {
        $output = array();
        $block = array();
        $nbrRounds = self::numberOfRounds($size);
        for($i = 0; $i < 4; $i++) for($j = 0; $j < 4; $j++) $block[($i + ($j * 4))] = $input[($i * 4) + $j];
        $expandedKey = self::expandKey($key, $size);
        $block = self::main($block, $expandedKey, $nbrRounds);
        for($k = 0; $k < 4; $k++) for($l = 0; $l < 4; $l++) $output[($k * 4) + $l] = $block[($k + ($l * 4))];
        return $output;
    }

    private static function decryptBlock($input, $key, $size)
    {
        $output = array();
        $block = array();
        $nbrRounds = self::numberOfRounds($size);
        for($i = 0; $i < 4; $i++) for($j = 0; $j < 4; $j++) $block[($i + ($j * 4))] = $input[($i * 4) + $j];
        $expandedKey = self::expandKey($key, $size);
        $block = self::invMain($block, $expandedKey, $nbrRounds);
        for($k = 0; $k < 4; $k++) for($l = 0; $l < 4; $l++) $output[($k * 4) + $l] = $block[($k + ($l * 4))];
        return $output;
    }

    const modeOfOperation_OFB = 0;
    const modeOfOperation_CFB = 1;
    const modeOfOperation_CBC = 2;

    private static function getPaddedBlock($bytesIn, $start, $end, $mode)
    {
        if($end - $start > 16) $end = $start + 16;
        $xarray = array_slice($bytesIn, $start, $end - $start);
        $cpad = 16 - count($xarray);
        while(count($xarray) < 16) {
            array_push($xarray, $cpad);
        }
        return $xarray;
    }

    public static function encrypt($bytesIn, $mode, $key, $size, $iv)
    {
        if(count($key) % $size) {
            throw new Exception('Key length does not match specified size.');
        }
        if(count($iv) % 16) {
            throw new Exception('iv length must be 128 bits.');
        }
        $byteArray = array();
        $input = array();
        $output = array();
        $ciphertext = array();
        $cipherOut = array();
        $firstRound = true;
        if($bytesIn !== null) {
            for($j = 0; $j < ceil(count($bytesIn) / 16); $j++) {
                $start = $j * 16;
                $end = $j * 16 + 16;
                if($j * 16 + 16 > count($bytesIn)) $end = count($bytesIn);
                $byteArray = self::getPaddedBlock($bytesIn, $start, $end, $mode);
                if($mode == self::modeOfOperation_CFB) {
                    if($firstRound) {
                        $output = self::encryptBlock($iv, $key, $size);
                        $firstRound = false;
                    } else $output = self::encryptBlock($input, $key, $size);
                    for($i = 0; $i < 16; $i++) $ciphertext[$i] = $byteArray[$i] ^ $output[$i];
                    for($k = 0; $k < $end - $start; $k++) array_push($cipherOut, $ciphertext[$k]);
                    $input = $ciphertext;
                } else if($mode == self::modeOfOperation_OFB) {
                    if($firstRound) {
                        $output = self::encryptBlock($iv, $key, $size);
                        $firstRound = false;
                    } else $output = self::encryptBlock($input, $key, $size);
                    for($i = 0; $i < 16; $i++) $ciphertext[$i] = $byteArray[$i] ^ $output[$i];
                    for($k = 0; $k < $end - $start; $k++) array_push($cipherOut, $ciphertext[$k]);
                    $input = $output;
                } else if($mode == self::modeOfOperation_CBC) {
                    for($i = 0; $i < 16; $i++) $input[$i] = $byteArray[$i] ^ (($firstRound) ? $iv[$i] : $ciphertext[$i]);
                    $firstRound = false;
                    $ciphertext = self::encryptBlock($input, $key, $size);
                    for($k = 0; $k < 16; $k++) array_push($cipherOut, $ciphertext[$k]);
                }
            }
        }
        return array('mode' => $mode, 'originalsize' => count($bytesIn), 'cipher' => $cipherOut);
    }

    public static function decrypt($cipherIn, $originalsize = 16, $mode, $key, $size = 16, $iv)
    {
        if(count($key) % $size) {
            throw new Exception('Key length does not match specified size.');
        }
        if(count($iv) % 16) {
            throw new Exception('iv length must be 128 bits.');
        }
        $ciphertext = array();
        $input = array();
        $output = array();
        $byteArray = array();
        $bytesOut = array();
        $firstRound = true;
        if($cipherIn !== null) {
            for($j = 0; $j < ceil(count($cipherIn) / 16); $j++) {
                $start = $j * 16;
                $end = $j * 16 + 16;
                if($j * 16 + 16 > count($cipherIn)) $end = count($cipherIn);
                $ciphertext = self::getPaddedBlock($cipherIn, $start, $end, $mode);
                if($mode == self::modeOfOperation_CFB) {
                    if($firstRound) {
                        $output = self::encryptBlock($iv, $key, $size);
                        $firstRound = false;
                    } else $output = self::encryptBlock($input, $key, $size);
                    for($i = 0; $i < 16; $i++) $byteArray[$i] = $output[$i] ^ $ciphertext[$i];
                    for($k = 0; $k < $end - $start; $k++) array_push($bytesOut, $byteArray[$k]);
                    $input = $ciphertext;
                } else if($mode == self::modeOfOperation_OFB) {
                    if($firstRound) {
                        $output = self::encryptBlock($iv, $key, $size);
                        $firstRound = false;
                    } else $output = self::encryptBlock($input, $key, $size);
                    for($i = 0; $i < 16; $i++) $byteArray[$i] = $output[$i] ^ $ciphertext[$i];
                    for($k = 0; $k < $end - $start; $k++) array_push($bytesOut, $byteArray[$k]);
                    $input = $output;
                } else if($mode == self::modeOfOperation_CBC) {
                    $output = self::decryptBlock($ciphertext, $key, $size);
                    for($i = 0; $i < 16; $i++) $byteArray[$i] = (($firstRound) ? $iv[$i] : $input[$i]) ^ $output[$i];
                    $firstRound = false;
                    if($originalsize < $end) for($k = 0; $k < $originalsize - $start; $k++) array_push($bytesOut, $byteArray[$k]); else for($k = 0; $k < $end - $start; $k++) array_push($bytesOut, $byteArray[$k]);
                    $input = $ciphertext;
                }
            }
        }
        return $bytesOut;
    }
}

?>