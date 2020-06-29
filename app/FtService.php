<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FindTax
 *
 * @property int $id
 * @property int $app_id
 * @property string $status
 * @property string $message
 * @property string $inn
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
class FtService extends Model
{
    protected $guarded = [];


}
