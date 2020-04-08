<?php
/**
 * Created by PhpStorm.
 * User: lodar
 * Date: 15.07.16
 * Time: 1:42
 */

namespace App\Packages\Moneta\Json\Types;

class ComplexItem
{

    /**
     * @var float общая сумма начисления
     */
    public $totalAmount;

    /**
     * @var string Назначение платежа
     */
    public $article;

    /**
     * @var string Дата постановления
     */
    public $dateProtocol;

    /**
     * @var string Наименование поставщика
     */
    public $name;

    /**
     * @var string ИНН получателя
     */
    public $inn;

    /**
     * @var string КПП получателя
     */
    public $kpp;

    /**
     * @var string WIREOKTMO
     */
    public $okato;

    /**
     * @var string БИК
     */
    public $bik;

    /**
     * @var string Номер счета получателя платежа
     */
    public $rs;

    /**
     * @var string
     */
    public $kbk;

    public $id;

    public $label;

    /**
     * @var float сумма с учетом скидки
     */
    public $amount;

    /**
     * @var NameValueAttribute[]
     */
    public $attribute = [];

    public function parse($data)
    {
        $this->id = $data->id;
        $this->label = $data->label;
        $this->amount = isset($data->amount) ? $data->amount : null;

        if(isset($data->content)) {
            foreach($data->content as $val) {
                if(isset($val->name) && isset($val->value))
                    $this->attribute[] = (new NameValueAttribute($val->name, $val->value));
//                else
//                    ApiLog::newInstance()->info('not valid, does not have value or name: ' . print_r($val, true));

            }
        }

        $this->totalAmount = $this->getParameters("CUSTOMFIELD:TOTALAMOUNT");
        $this->article = $this->getParameters("WIREPAYMENTPURPOSE");
        $this->dateProtocol = $this->getParameters("CUSTOMFIELD:BILLDATE");
        $this->name = $this->getParameters("CUSTOMFIELD:SOINAME");
        $this->inn = $this->getParameters("WIREUSERINN");
        $this->kpp = $this->getParameters("WIREKPP");
        $this->okato = $this->getParameters("WIREOKTMO");
        $this->bik = $this->getParameters("WIREBANKBIK");
        $this->rs = $this->getParameters("WIREBANKACCOUNT");
        $this->kbk = $this->getParameters("WIREKBK");
    }

    public function getParameters($name)
    {
        $amount = null;

        foreach($this->attribute as $val) {
            if($val->name == $name) {
                $amount = $val->value;
                break;
            }
        }
        return $amount;

    }

}