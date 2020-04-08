<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 13/05/2017
 * Time: 22:28
 */

namespace App\Packages\Moneta\types;


class Item
{
    public $name;
    public $id;

    public function parse($data) {
        $this->name = $data->_;
        $this->id = $data->id;
    }
}