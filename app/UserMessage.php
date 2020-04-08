<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserMessage
 *
 * @property int $id
 * @property int $from_user_id
 * @property int $to_user_id
 * @property int $is_read
 * @property \Illuminate\Support\Carbon|null $is_read_date
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $fromUser
 * @property-read \App\User $toUser
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMessage whereFromUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMessage whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMessage whereIsReadDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMessage whereToUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserMessage extends Model
{
    protected $dates = [
      'is_read_date'
    ];

    public function fromUser() {
        return $this->belongsTo('App\User', 'from_user_id', 'id');
    }

    public function toUser() {
        return $this->belongsTo('App\User', 'to_user_id', 'id');
    }
}
