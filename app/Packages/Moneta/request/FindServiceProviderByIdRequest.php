<?php

namespace App\Packages\Moneta\request;

/**
 * Created by PhpStorm.
 * User: lodar
 * Date: 12.07.16
 * Time: 21:20
 */
class FindServiceProviderByIdRequest
{
    public $providerId;

    /**
     * @param mixed $providerId
     * @return FindServiceProviderByIdRequest
     */
    public function setProviderId($providerId)
    {
        $this->providerId = $providerId;
        return $this;
    }


}