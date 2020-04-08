<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Debtor
 *
 * @property int $id
 * @property int $find_inn_id
 * @property string|null $result
 * @property string|null $category
 * @property string|null $inn
 * @property string|null $ogrnip
 * @property string|null $snils
 * @property string|null $region
 * @property string|null $live_address
 * @property string|null $error_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor whereFindInnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor whereInn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor whereLiveAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor whereOgrnip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor whereSnils($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Debtor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Debtor extends Model
{
    //
}
