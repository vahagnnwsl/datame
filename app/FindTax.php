<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FindTax
 *
 * @property int $id
 * @property int $find_inn_id
 * @property string $article
 * @property string $number
 * @property \Illuminate\Support\Carbon $date_protocol
 * @property float $amount
 * @property string $name
 * @property string $inn
 * @property string $kpp
 * @property string $okato
 * @property string $bik
 * @property string $rs
 * @property string $kbk
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereArticle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereBik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereDateProtocol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereFindInnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereInn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereKbk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereKpp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereOkato($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereRs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindTax whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FindTax extends Model
{
    protected $guarded = [];

    protected $dates = [
        'date_protocol',
    ];
}
