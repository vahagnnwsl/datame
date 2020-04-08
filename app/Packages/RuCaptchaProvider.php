<?php

namespace App\Packages;

use jumper423\decaptcha\services\RuCaptcha;

/**
 * Использует сервис rucaptcha для распознования капчи
 *
 * Class RuCaptchaProvider
 * @package App\Http
 */
class RuCaptchaProvider
{

    /**
     * @var RuCaptcha
     */
    protected $service;
    protected $recognizeStatus = false;
    protected $params = [];

    public function __construct($params = [])
    {
        $this->params[RuCaptcha::ACTION_FIELD_KEY] = config('datame.rucaptcha_key');
        $this->setParams($params);
    }

    protected function setParams(array $params ) {
        foreach($params as $key => $value) {
            $this->params[$key] = $value;
        }
        return $this;
    }

    /**
     * Распозначание капчи по указанному пути
     * @param $path
     * @return $this
     */
    public function recognize($path)
    {
        $this->recognizeStatus = false;
        $this->service = new RuCaptcha($this->params);

        $this->recognizeStatus = $this->service->recognize($path);
        return $this;
    }

    /**
     * Возвращает код с распознаной капчи
     * @return array|null|string
     */
    public function getCode()
    {
        if(!is_null($this->service) && $this->recognizeStatus) {
            return $this->service->getCode();
        }
        return null;
    }

    /**
     * Возвращает ошибку распознавания капчи
     * @return null|string
     */
    public function getError()
    {
        if(!is_null($this->service))
            return $this->service->getError();
        return null;
    }

    /**
     * Сигналим об ошибке распознавания капчи
     * @return bool|null
     */
    public function nonTrue()
    {
        if(!is_null($this->service))
            return $this->service->notTrue();
        return null;
    }
}