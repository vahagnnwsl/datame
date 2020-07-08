<?php


namespace App;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CustomDataImport extends Model
{
    const STATUS_NEW = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAILED = 3;
    const STATUS_DELETING = 4;
    const STATUS_DELETED = 5;

    const MAP_TYPES = [
        'additional' => 'Дополнительная',
        'full_name' => 'ФИО',
        'first_name' => 'Имя',
        'last_name' => 'Фамилия',
        'patronymic' => 'Отчество',
        'birthday' => 'День рождения'
    ];

    protected $fillable = [
        'file',
        'delimiter',
        'short_description',
        'columns_map',
        'is_active',
        'nodFounded_coefficient',
        'founded_coefficient',
    ];

    protected $casts = [
        'columns_map' => 'array'
    ];

    /**
     * @param Builder $query
     * @param array $data
     * @return Builder
     */
    public function scopeWhereUnique($query, array $data)
    {
        return $query->where('short_description', $data['short_description'])
            ->orWhere('file', $data['file']);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithoutDeleted($query)
    {
        return $query->whereNotIn('status', [
            self::STATUS_DELETING,
            self::STATUS_DELETED
        ]);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }
}
