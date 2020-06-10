<?php


namespace App;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CustomDataImport extends Model
{
    const STATUS_NEW = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_SUCCESS = 2;
    const STATUS_FAILED = 3;

    const MAP_TYPES = [
        'full_name' => 'ФИО',
        'first_name' => 'Имя',
        'last_name' => 'Фамилия',
        'patronymic' => 'Отчество',
        'birthday' => 'День рождения',
        'additional' => 'Дополнительная'
    ];

    protected $fillable = [
        'file',
        'delimiter',
        'short_description',
        'columns_map'
    ];

    protected $casts = [
        'columns_map' => 'array'
    ];

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }
}