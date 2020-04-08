<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Passport
 *
 * @property int $id
 * @property int $checking_state
 * @property int $app_id
 * @property bool|null $is_valid
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $age14
 * @property \Illuminate\Support\Carbon|null $age20
 * @property string|null $age45
 * @property \Illuminate\Support\Carbon|null $passport_date_replace
 * @property int|null $passport_serie_year
 * @property int|null $passport_serie_region
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport whereAge14($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport whereAge20($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport whereAge45($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport whereCheckingState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport whereIsValid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport wherePassportDateReplace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport wherePassportSerieRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport wherePassportSerieYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Passport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Passport extends Model
{
    const STATE_NEW = 1;
    const STATE_PROCESS = 2;
    const STATE_ERROR = 3;
    const STATE_SUCCESS = 4;

    protected $dates = [
        'age14', 'age20', 'age40', 'passport_date_replace'
    ];

    protected $casts = [
        'is_valid' => 'boolean',
    ];
}
