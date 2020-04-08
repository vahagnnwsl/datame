<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-19
 * Time: 23:20
 */

namespace App\Packages\Providers;


interface IProvider
{
    /**
     * @return Result
     */
    function check();
}