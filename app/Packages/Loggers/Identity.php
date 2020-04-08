<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-14
 * Time: 23:07
 */

namespace App\Packages\Loggers;


class Identity
{
    /**
     * Генератор уникального идентификатора
     * @return string
     * @throws \Exception
     */
    public function GenIdentity()
    {
        return bin2hex(random_bytes(10));
    }
}