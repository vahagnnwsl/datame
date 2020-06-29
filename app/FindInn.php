<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FindInn
 *
 * @property int $id
 * @property int $app_id
 * @property string|null $inn
 * @property int $type_inn
 * @property string|null $error_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\App $app
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Debtor[] $debtor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\HonestBusinessIp[] $honestBusinessIp
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\HonestBusinessUl[] $honestBusinessUl
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FindTax[] $tax
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindInn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindInn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindInn query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindInn whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindInn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindInn whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindInn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindInn whereInn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindInn whereTypeInn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindInn whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FindInn extends Model
{
    const INDIVIDUAL_INN = 1;
    const LEGAL_INN = 2;

    public function app() {
        return $this->belongsTo('App\App');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tax() {
        return $this->hasMany('App\FindTax', 'find_inn_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function debtor() {
        return $this->hasMany('App\Debtor', 'find_inn_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function honestBusinessUl() {
        return $this->hasMany('App\HonestBusinessUl', 'find_inn_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function honestBusinessIp() {
        return $this->hasMany('App\HonestBusinessIp', 'find_inn_id', 'id');
    }

    public function fsn() {
        return $this->hasOne('App\FtService', 'inn_id', 'id');
    }
}
