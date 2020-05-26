<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\App
 *
 * @property int $id
 * @property int $user_id
 * @property string $lastname
 * @property string $name
 * @property string|null $patronymic
 * @property mixed $birthday
 * @property string|null $passport_code
 * @property mixed|null $date_of_issue
 * @property string|null $code_department
 * @property int $status
 * @property int $checking_count
 * @property int $checking_repeat
 * @property \Illuminate\Support\Carbon|null $checking_date_last
 * @property \Illuminate\Support\Carbon|null $checking_date_next
 * @property int $return_check_quantity
 * @property string|null $ip
 * @property string $identity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CheckingList[] $checkingList
 * @property-read \App\FindDepartment $department
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Disq[] $disq
 * @property-read \App\FedFsm $fedFsm
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FindFssp[] $fssp
 * @property-read \App\FsspWanted $fsspWanted
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FindInn[] $inn
 * @property-read \App\InterpolRed $interpolRed
 * @property-read \App\InterpolYellow $interpolYellow
 * @property-read \App\MvdWanted $mvdWanted
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Passport[] $passport
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereCheckingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereCheckingDateLast($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereCheckingDateNext($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereCheckingRepeat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereCodeDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereDateOfIssue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereIdentity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App wherePassportCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereReturnCheckQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\App whereUserId($value)
 * @mixin \Eloquent
 */
class App extends Model
{
    protected $hidden = ['updated_at'];

    protected $casts = [
        'birthday' => 'date:Y-m-d',
        'date_of_issue' => 'date:Y-m-d',
    ];

    protected $dates = [
        'birthday',
        'date_of_issue',
        'checking_date_next',
        'checking_date_last',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function checkingList() {
        return $this->hasMany('App\CheckingList', 'app_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function passport() {
        return $this->hasMany('App\Passport', 'app_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inn() {
        return $this->hasMany('App\FindInn', 'app_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fssp() {
        return $this->hasMany('App\FindFssp', 'app_id', 'id');
    }

    /**
     * Интерпол красные карточки
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function interpolRed() {
        return $this->hasOne('App\InterpolRed', 'app_id', 'id');
    }

    /**
     * Интерпол желтые карточки
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function interpolYellow() {
        return $this->hasOne('App\InterpolYellow', 'app_id', 'id');
    }

    /**
     * Федеральный розыск
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mvdWanted() {
        return $this->hasOne('App\MvdWanted', 'app_id', 'id');
    }

    /**
     * Местный розыск
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fsspWanted() {
        return $this->hasOne('App\FsspWanted', 'app_id', 'id');
    }


    public function fsin() {
        return $this->hasOne('App\FindFsin', 'app_id', 'id');
    }

    /**
     * Террористы и экстремисты
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function fedFsm() {
        return $this->hasOne('App\FedFsm', 'app_id', 'id');
    }

    /**
     * Дисквалицированные лица
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function disq() {
        return $this->hasMany('App\Disq', 'app_id', 'id');
    }

    public function department() {
        return $this->hasOne('App\FindDepartment', 'app_id', 'id');
    }

}
