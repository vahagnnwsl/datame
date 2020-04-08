<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 07/06/2017
 * Time: 14:35
 */

namespace App\Packages\Moneta\types;


/**
 * Тип, который позволяет работать с сущностями типа "ключ-значение".
 * Specifies additional attributes. Each attribute contains the key
 * element, which specifies the name of the attribute, and the value element
 * that specifies the value of the attribute.
 *
 */
class KeyValueAttribute
{

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

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return KeyValueAttribute
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return KeyValueAttribute
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


}
