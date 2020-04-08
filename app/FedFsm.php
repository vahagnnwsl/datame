<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FedFsm
 *
 * @property int $id
 * @property int $app_id
 * @property string|null $status
 * @property string|null $full_name
 * @property string|null $city_birth
 * @property string|null $error_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FedFsm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FedFsm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FedFsm query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FedFsm whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FedFsm whereCityBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FedFsm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FedFsm whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FedFsm whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FedFsm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FedFsm whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FedFsm whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FedFsm extends Model
{
    //
}
