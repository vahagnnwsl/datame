<?php
/**
 * Created by PhpStorm.
 * User: lodar
 * Date: 12.07.16
 * Time: 23:16
 */

namespace App\Packages\Moneta\Moneta\types;

use App\Packages\Moneta\types\NameValueAttribute;

class FieldsInfo
{
    /**
     * @var NameValueAttribute[]
     */
    public $attribute = [];

    public function addAttribute(NameValueAttribute $attr)
    {
        $this->attribute[] = $attr;
        return $this;
    }
}