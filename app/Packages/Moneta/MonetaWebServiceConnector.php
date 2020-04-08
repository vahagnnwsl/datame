<?php

namespace App\Packages\Moneta;


use App\Packages\Loggers\ApiLog;

class  MonetaWebServiceConnector
{

    /**
     * Версия API Moneta.ru
     *
     * @var string
     */
    public $version = "VERSION_2";

    private $userName;

    private $password;

    /**
     * NSoapClient instance
     *
     * @var NSoapClient
     */
    protected $client = null;

    /**
     * An array of headers to be sent along with the SOAP request
     *
     * @var array
     */
    protected $inputHeaders = null;

    /**
     * If supplied, this array will be filled with the headers from the SOAP response
     *
     * @var array
     */
    protected $outputHeaders = null;

    public function __construct($wsdl, $userName, $password)
    {
        $this->init($wsdl, $userName, $password);
    }

    protected function init($wsdl, $userName, $password)
    {
        $this->userName = $userName;
        $this->password = $password;

        $options = [
            'keep_alive' => false,
            'connection_timeout' => 30,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'trace' => 1
        ];

        $this->client = new NSoapClient($wsdl, $options);
        $this->client->setIsDebug(config('datame.moneta_debug'));
        $this->inputHeaders[] = $this->createSecurityHeader($this->userName, $this->password);
    }

    private function createSecurityHeader($userName, $password)
    {
        $sns = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd";
        // формируем параметр username
        $username = new \SoapVar($userName, XSD_STRING, NULL, $sns, NULL, $sns);
        // формируем параметр password
        $password = new \SoapVar($password, XSD_STRING, NULL, $sns, NULL, $sns);
        // Для того чтобы выразить тег <UserNameToken> с вложенными внутри него тегами <Username> и <Password>,
        // мы должны определить промежуточный класс
        $tmp = new \stdClass();
        $tmp->Username = $username;
        $tmp->Password = $password;
        // содержимое сложного XML-тега <UsernameToken> в виде SoapVar,
        // тип которого не XSD_STRING, а SOAP_ENC_OBJECT
        $authData = new \SoapVar($tmp, SOAP_ENC_OBJECT, null, $sns, 'wsse:UsernameToken', $sns);
        // формируем содержимое тега Security , т.е. сам UsernameToken
        $tmp = new \stdClass();
        $tmp->UsernameToken = $authData;
        $usernameToken = new \SoapVar($tmp, SOAP_ENC_OBJECT, null, $sns, 'wsse:UsernameToken', $sns);
        $secHeaderValue = new \SoapVar($usernameToken, SOAP_ENC_OBJECT, NULL, $sns, 'wsse:Security', $sns);
        return new \SoapHeader($sns, 'Security', $secHeaderValue, true);
    }


    public function call($method, $data, $options = null)
    {
        $soapClient = $this->client;
//        if (is_object($data[0]))
//            $data[0]->version = $this->version;
        if($this->isDebug()) {
            ApiLog::newInstance()->info("soapCall method: {$method}");
            ApiLog::newInstance()->info("soapCall data: " . print_r($data, true));
        }

        $result = $soapClient->__soapCall($method, $data, $options, $this->inputHeaders, $this->outputHeaders);

        if($this->isDebug()) {
            ApiLog::newInstance()->info("soapCall result: " . print_r($result, true));
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->client->isDebug();
    }

    /**
     * Устанавливает дебаг режим для записи в лог всех запросов и ответов
     * @param bool $isDebug
     * @return $this
     */
    public function setIsDebug($isDebug)
    {
        $this->client->setIsDebug($isDebug);
        return $this;
    }


}