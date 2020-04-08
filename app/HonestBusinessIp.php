<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\HonestBusinessIp
 *
 * @property int $id
 * @property int $find_inn_id
 * @property string|null $business_id
 * @property string|null $tip_document
 * @property string|null $naim_vid_ip
 * @property string|null $familia
 * @property string|null $imia
 * @property string|null $otchestvo
 * @property string|null $activnost
 * @property string|null $innfl
 * @property \Illuminate\Support\Carbon|null $data_ogrnip
 * @property string|null $naim_stran
 * @property string|null $kod_okved
 * @property string|null $naim_okved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereActivnost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereDataOgrnip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereFamilia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereFindInnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereImia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereInnfl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereKodOkved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereNaimOkved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereNaimStran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereNaimVidIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereOtchestvo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereTipDocument($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\HonestBusinessIp whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HonestBusinessIp extends Model
{
    protected $dates = [
        'data_ogrnip',
    ];
}
