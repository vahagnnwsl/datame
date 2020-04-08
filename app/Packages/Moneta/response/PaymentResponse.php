<?php

namespace App\Packages\Moneta\response;

use App\Packages\Moneta\types\MonetaKeyValueAttribute;
use App\Packages\Moneta\types\MonetaOperationInfo;

class PaymentResponse extends MonetaOperationInfo
{
    public function parse($data)
    {
        $this->id = $data->id;

        foreach($data->attribute as $item) {
            $attr = new MonetaKeyValueAttribute($item->key, $item->value);
            $this->addAttribute($attr);
        }

        return $this;
    }

    /**
     * Возвращает статус платежа
     * @return bool
     */
    public function isSucceed()
    {
        $st = false;
        foreach($this->attribute as $attr) {
            if($attr->key == 'statusid' && $attr->value == "SUCCEED") {
                $st = true;
                break;
            }
        }
        return $st;
    }
}