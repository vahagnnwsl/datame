<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 13/05/2017
 * Time: 22:18
 */

namespace App\Packages\Moneta\types;


class Provider
{
    public $id;
    public $targetAccountId;
    public $subProviderId;
    public $name;
    public $fields;

    public function parse($data) {

        $this->id = $data->id;
        $this->targetAccountId = $data->targetAccountId;
        $this->subProviderId = $data->subProviderId;
        $this->name = $data->name;

        foreach($data->fields->field as $field) {
            $fd = new Field();
            $fd->parse($field);
            $this->fields[] = $fd;
        }

        return $this;
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