<?php
/**
 * Created by PhpStorm.
 * User: lodar
 * Date: 15.07.16
 * Time: 0:05
 */

namespace App\Packages\Moneta\types;


class Field
{
    public $steps;
    public $attribute_name;
    public $label;
    public $enum = [];
    public $type;
    public $error = "";
    public $readonly;
    public $id;
    public $orderBy;
    public $value;

    public function parse($data)
    {

        $this->steps = $data->steps;
        $this->attribute_name = $data->{'attribute-name'};
        $this->label = isset($data->label) ? $data->label : null;
        $this->value = isset($data->value) ? $data->value : null;
        $this->error = isset($data->error) ? $data->error : "";
        $this->type = $data->type;
        $this->readonly = $data->readonly;
        $this->id = $data->id;
        $this->orderBy = $data->orderBy;
        $this->type = $data->type;

        if($this->type == "ENUM") {
//            var_dump($data->enum);
//dd(1);

            if(isset($data->enum->complexItem)) {

                //если один платеж - возвращается обьект
                //иначе массив
                if(is_array($data->enum->complexItem)) {

                    foreach($data->enum->complexItem as $value) {
                        $item = new ComplexItem();
                        $item->parse($value);
                        $this->enum[] = $item;
                    }

                } else if(is_object($data->enum->complexItem)) {
                    $item = new ComplexItem();
                    $item->parse($data->enum->complexItem);
                    $this->enum[] = $item;
                }

            } else if(isset($data->enum->item)) {
                if(is_array($data->enum->item)) {

                    foreach($data->enum->item as $value) {
                        $item = new Item();
                        $item->parse($value);
                        $this->enum[] = $item;
                    }

                } else if(is_object($data->enum->item)) {
                    $item = new Item();
                    $item->parse($data->enum->item);
                    $this->enum[] = $item;
                }
            }


        }
    }

}