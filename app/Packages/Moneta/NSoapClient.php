<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-21
 * Time: 17:11
 */

namespace App\Packages\Moneta;

use App\Packages\Loggers\ApiLog;
use SoapClient;

class NSoapClient extends SoapClient
{

    protected $change = false;
    protected $version = "VERSION_2";
    protected $request = "";
    protected $response = "";
    protected $isDebug = false;

    public function setChangeVersion($st, $version = "VERSION_3")
    {
        $this->change = $st;
        $this->version = $version;
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {

        if($this->change) {
            $request = str_replace('<ns1:GetNextStepRequest>', '<ns1:GetNextStepRequest version="VERSION_3">', $request);
        }
        $this->request = $request;
        if($this->isDebug())
            ApiLog::newInstance()->info("request: {$this->request}");

        $ret = parent::__doRequest($request, $location, $action, $version, $one_way);

        $this->response = print_r($ret, true);
        if($this->isDebug())
            ApiLog::newInstance()->info("request: {$this->response}");

        $this->__last_request = $request;
        return $ret;
    }

    /**
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->isDebug;
    }

    /**
     * @param bool $isDebug
     * @return $this
     */
    public function setIsDebug($isDebug)
    {
        $this->isDebug = $isDebug;
        return $this;
    }
}