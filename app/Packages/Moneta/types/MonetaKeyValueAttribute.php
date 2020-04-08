<?php
/**
 * Created by PhpStorm.
 * User: lodar
 * Date: 26.07.16
 * Time: 2:01
 */

namespace App\Packages\Moneta\types;


/**
 * Тип, который позволяет работать с сущностями типа "ключ-значение".
 * Key-value pairs type.
 *
 */
class MonetaKeyValueAttribute
{

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     *
     *
     * @var string
     */
    public $key = null;

    /**
     *
     *
     * @var string
     */
    public $value = null;


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

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}