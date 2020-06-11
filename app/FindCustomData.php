<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class FindCustomData extends Model
{
    protected $table = 'find_custom_data';

    protected $casts = [
        'additional' => 'array'
    ];
}