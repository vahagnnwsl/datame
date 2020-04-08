<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserDataHistory
 *
 * @property int $id
 * @property int $confirmed
 * @property int $user_id
 * @property int $created_by
 * @property int|null $confirmed_by
 * @property string $name
 * @property string|null $lastname
 * @property int $phone
 * @property string|null $inn
 * @property string|null $ogrn
 * @property string|null $director
 * @property string|null $manager
 * @property string|null $date_service
 * @property int $check_quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereCheckQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereConfirmedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereDateService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereDirector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereInn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereOgrn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserDataHistory whereUserId($value)
 * @mixin \Eloquent
 */
class UserDataHistory extends Model
{
    protected $table = "users_data_history";

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
