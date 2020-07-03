<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * Class CustomData
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomData whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomData whereBirthday($value)
 * @mixin \Eloquent
 */
class CustomData extends Model
{
    protected $table = 'custom_data';

    protected $fillable = [
        'hash',
        'database',
        'full_name',
        'birthday',
        'additional'
    ];

    protected $casts = [
        'additional' => 'array'
    ];

    public $timestamps = false;

    protected $hidden = ['hash'];
}