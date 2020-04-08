<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\HonestBusinessUl
 *
 * @property int $id
 * @property int $find_inn_id
 * @property string|null $business_id
 * @property string|null $tip_document
 * @property string|null $naim_ul_sokr
 * @property string|null $naim_ul_poln
 * @property string|null $activnost
 * @property string|null $inn
 * @property string|null $kpp
 * @property \Illuminate\Support\Carbon|null $obr_data
 * @property string|null $adres
 * @property string|null $kod_okved
 * @property string|null $naim_okved
 * @property string|null $rukovoditel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereActivnost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereAdres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereFindInnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereInn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereKodOkved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereKpp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereNaimOkved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereNaimUlPoln($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereNaimUlSokr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereObrData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereRukovoditel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereTipDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessUl whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HonestBusinessUl extends Model
{
    protected $dates = [
        'obr_data',
    ];
}
