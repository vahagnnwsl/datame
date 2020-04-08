<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ForAllMessage
 *
 * @property int $id
 * @property int $message_type
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForAllMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForAllMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForAllMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForAllMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForAllMessage whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForAllMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForAllMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForAllMessage whereMessageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForAllMessage whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForAllMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ForAllMessage extends Model
{
    protected $dates = [
        'start_date',
        'end_date'
    ];
}
