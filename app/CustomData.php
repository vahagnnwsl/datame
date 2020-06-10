<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class CustomData extends Model
{
    protected $table = 'custom_data';

    protected $fillable = [
        'full_name',
        'birthday',
        'additional'
    ];

    protected $casts = [
        'additional' => 'array'
    ];

    public $timestamps = false;
}