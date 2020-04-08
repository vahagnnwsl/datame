<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Disq
 *
 * @property int $id
 * @property int $app_id
 * @property string|null $result
 * @property \Illuminate\Support\Carbon|null $data_con_discv
 * @property string|null $naim_org_prot
 * @property string|null $dolgnost
 * @property string|null $naim_org
 * @property \Illuminate\Support\Carbon|null $data_nach_discv
 * @property string|null $dolgnost_sud
 * @property string|null $mesto_rogd
 * @property string|null $discv_srok
 * @property string|null $kvalivikaciya_tekst
 * @property string|null $nom_zap
 * @property string|null $fio_sud
 * @property string|null $error_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereDataConDiscv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereDataNachDiscv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereDiscvSrok($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereDolgnost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereDolgnostSud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereFioSud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereKvalivikaciyaTekst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereMestoRogd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereNaimOrg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereNaimOrgProt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereNomZap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Disq whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Disq extends Model
{
    protected $dates = [
        'data_con_discv',
        'data_nach_discv'
    ];
}
