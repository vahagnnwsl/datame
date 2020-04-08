<?php

namespace App;

use App\Packages\Constants;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string|null $lastname
 * @property string $email
 * @property int $confirmed
 * @property string|null $email_verified_at
 * @property string $password
 * @property int $type_user
 * @property int $phone
 * @property string|null $inn
 * @property string|null $ogrn
 * @property string|null $director
 * @property string|null $manager
 * @property \Illuminate\Support\Carbon|null $date_service
 * @property int $check_quantity
 * @property string|null $ip
 * @property int $oferta_confirmed
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read mixed $date_service_value
 * @property-read mixed $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserMessage[] $messages
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserDataHistory[] $userDataHistory
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCheckQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDateService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDirector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereInn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereOfertaConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereOgrn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTypeUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'oferta_confirmed', 'name', 'email', 'phone', 'lastname', 'type_user', 'password', 'inn', 'ogrn', 'director', 'manager', 'confirmed', 'date_service', 'check_quantity'
    ];

    protected $dates = [
        'date_service',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];



    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailNotification);
    }

    public function sendPasswordResetNotification($token)
    {

        $this->notify(new  \App\Notifications\PasswordResetNotification($token));

    }

    public function getFullNameAttribute() {
        switch($this->type_user) {
            case Constants::USER_INDIVIDUAL:
                return $this->name." ".$this->lastname;
            case Constants::USER_LEGAL:
                return $this->name;
            default:
                return $this->name;
        }
    }

    public function getDateServiceValueAttribute() {
        return is_null($this->date_service) ? "Не установлена" : $this->date_service->format('d.m.Y');
    }

    public function userDataHistory() {
        return $this->hasMany('App\UserDataHistory', 'user_id', 'id');
    }

    public function isAdmin() {
        return $this->type_user == Constants::USER_ADMIN;
    }

    public function messages() {
        return $this->hasMany('App\UserMessage', 'to_user_id', 'id');

    }

    public function newMessagesCount() {
        return $this->hasMany('App\UserMessage', 'to_user_id', 'id')->where('is_read', false)->count();
    }
}
