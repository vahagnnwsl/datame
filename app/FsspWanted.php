<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FsspWanted
 *
 * @property int $id
 * @property int $app_id
 * @property string|null $result
 * @property string|null $city_birth
 * @property string|null $name_organ
 * @property string|null $contact_name_organ
 * @property string|null $name_organ_wanted
 * @property string|null $article_deal
 * @property string|null $error_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FsspWanted newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FsspWanted newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FsspWanted query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FsspWanted whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FsspWanted whereArticleDeal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FsspWanted whereCityBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FsspWanted whereContactNameOrgan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FsspWanted whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FsspWanted whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FsspWanted whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FsspWanted whereNameOrgan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FsspWanted whereNameOrganWanted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FsspWanted whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FsspWanted whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FsspWanted extends Model
{
    //
}
