<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FindDepartment
 *
 * @property int $id
 * @property int $app_id
 * @property int $type
 * @property string|null $error_message
 * @property int|null $selected_department_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FindDepartmentList[] $branches
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartment whereAppId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartment whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartment whereSelectedDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FindDepartment extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branches() {
        return $this->hasMany('App\FindDepartmentList', 'find_department_id', 'id');
    }
}
