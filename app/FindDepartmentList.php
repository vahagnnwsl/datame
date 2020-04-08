<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FindDepartmentList
 *
 * @property int $id
 * @property int $find_department_id
 * @property int $department_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Department $department
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartmentList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartmentList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartmentList query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartmentList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartmentList whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartmentList whereFindDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartmentList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FindDepartmentList whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FindDepartmentList extends Model
{
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function department() {
        return $this->hasOne('App\Department', 'id', 'department_id');
    }
}
