<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FindFssp
 *
 * @property int $id
 * @property int $app_id
 * @property string $fio
 * @property string $number
 * @property float $amount
 * @property string $nazn
 * @property string $name_poluch
 * @property string $bik
 * @property string $rs
 * @property string $bank
 * @property string $kpp
 * @property string $inn
 * @property \Illuminate\Support\Carbon $date_protocol
 * @property string $contact
 * @property string|null $error_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereBik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereDateProtocol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereFio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereInn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereKpp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereNamePoluch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereNazn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereRs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindFssp whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FindFssp extends Model
{
    protected $dates = [
        'date_protocol',
    ];
}
