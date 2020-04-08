<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-02-21
 * Time: 16:33
 */

namespace App\Packages\Moneta\Json\Request;


abstract class BaseRequest
{
    private $version = "VERSION_3";

    /**
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'Security' => [
                'UsernameToken' => [
                    'Username' => config('datame.moneta.user'),
                    'Password' => config('datame.moneta.password')
                ]
            ]
        ];

    }

    protected abstract function getBody();

    /**
     * @return array
     */
    public abstract function getFullRequest();

    /**
     * @return string
     */
    protected function getVersion(): string
    {
        return $this->version;
    }


}