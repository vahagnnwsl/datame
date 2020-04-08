<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-02-21
 * Time: 16:47
 */

namespace App\Packages\Moneta\Json\Response;


abstract class BaseResponse
{
    abstract public function parse($data);
}