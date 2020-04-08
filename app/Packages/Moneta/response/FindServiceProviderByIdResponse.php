<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 13/05/2017
 * Time: 22:01
 */

namespace App\Packages\Moneta\response;

use App\Packages\Moneta\types\Provider;

class FindServiceProviderByIdResponse
{
    /**
     * @var Provider
     */
    protected $provider = null;

    public function parse($data)
    {
        $this->provider = (new Provider())->parse($data->provider);
        return $this;
    }

    /**
     * @return Provider
     */
    public function getProvider() {
        return $this->provider;
    }
}