<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class DBBulkFacade
 *
 * @method static string insert(string $table, array $rows, bool $withTransaction = false)
 * @method static string insertOrUpdate(string $table, array $rows, array $exclude = [], bool $withTransaction = false)
 * @package App\Facades
 */
class DBBulkFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'db-bulk';
    }
}