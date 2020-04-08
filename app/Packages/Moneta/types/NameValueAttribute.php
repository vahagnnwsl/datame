<?php
/**
 * Created by PhpStorm.
 * User: lodar
 * Date: 12.07.16
 * Time: 23:17
 */

namespace App\Packages\Moneta\types;


class NameValueAttribute
{
    public $name;
    public $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function setName($val)
    {
        $this->name = $val;
        return $this;
    }

    public function setValue($val)
    {
        $this->value = $val;
        return $this;
    }
}