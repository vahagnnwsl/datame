<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-02-21
 * Time: 16:47
 */

namespace App\Packages\Moneta\Json\Response;


use App\Packages\Moneta\Json\Types\Field;

class GetNextStepResponse extends BaseResponse
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
        $dt = $data->Envelope->Body->GetNextStepResponse;

        $this->providerId = $dt->providerId;
        $this->nextStep = isset($data->nextStep);

        foreach($dt->fields->field as $field) {
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
    public function isError()
    {
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

    public function getFieldByName($name)
    {
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