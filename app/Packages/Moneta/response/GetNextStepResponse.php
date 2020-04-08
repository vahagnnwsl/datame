<?php

namespace App\Packages\Moneta\response;

use App\Packages\Moneta\types\Field;

class GetNextStepResponse
{
    /**
     * @var string айди провайдера
     */
    public $providerId;

    /**
     * @var string следующий шаг
     */
    public $nextStep;
    /**
     * @var [Field]
     */
    public $fields = [];
    public $amount;

    public function parse($data)
    {
        $this->providerId = $data->providerId;
        $this->nextStep = isset($data->nextStep);

        foreach($data->fields->field as $field) {
            $fd = new Field();
            $fd->parse($field);
            $this->fields[] = $fd;
        }

        return $this;
    }

    /**
     * Проверка наличия ошибок
     * @return null|string
     */
    public function isError() {
        /** @var Field $field */
        foreach($this->fields as $field) {
            if(!empty($field->error)) {
                return $field->error;
            }
        }
        return null;
    }

    public function getPayments()
    {
        $payments = [];

        foreach($this->fields as $field) {
            if($field->type == 'ENUM') {
                $payments = $field->enum;
                break;
            }
        }
        return $payments;
    }

    public function getFieldByName($name) {
        $searchField = null;
        /** @var Field $field */
        foreach($this->fields as $field) {

            if($field->attribute_name == $name) {
                $searchField = $field;
                break;
            }
        }
        return $searchField;
    }
}