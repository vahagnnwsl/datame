<?php

namespace App\Services;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DBBulk
{
    /**
     * Bulk insert on duplicate key update
     *
     * @param string $table
     * @param array  $rows
     * @param array  $exclude The attributes to exclude in case of update.
     * @param bool   $withTransaction
     * @return string
     */
    public function insertOrUpdate(string $table, array $rows, array $exclude = [], bool $withTransaction = false): string
    {
        // We assume all rows have the same keys so we arbitrarily pick one of them.
        $columns = array_keys($rows[0]);

        $columnsString = implode('`,`', $columns);
        $values = $this->buildSQLValuesFrom($rows);
        $updates = $this->buildSQLUpdatesFrom($columns, $exclude);
        $params = Arr::flatten($rows);

        $query = "INSERT INTO {$table} (`{$columnsString}`) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";

        DB::connection()->disableQueryLog();
        if ($withTransaction) DB::beginTransaction();
        DB::statement($query, $params);
        if ($withTransaction) DB::commit();

        return $query;
    }

    /**
     * Bulk insert
     *
     * @param string $table
     * @param array  $rows
     * @param bool   $withTransaction
     * @return string
     */
    public function insert(string $table, array $rows, bool $withTransaction = false): string
    {
        $columns = array_keys($rows[0]);

        $columnsString = implode('`,`', $columns);
        $values = $this->buildSQLValuesFrom($rows);
        $params = Arr::flatten($rows);

        $query = "INSERT INTO {$table} (`{$columnsString}`) VALUES {$values}";

        if ($withTransaction) DB::beginTransaction();
        DB::statement($query, $params);
        if ($withTransaction) DB::commit();

        return $query;
    }

    /**
     * Build SQL string for the values.
     *
     * @param array $rows
     * @return string
     */
    protected function buildSQLValuesFrom(array $rows): string
    {
        $values = collect($rows)->reduce(function ($valuesString, $row) {
            return $valuesString .= '(' . rtrim(str_repeat("?,", count($row)), ',') . '),';
        }, '');

        return rtrim($values, ',');
    }

    /**
     * Build SQL string for the update
     *
     * @param       $columns
     * @param array $exclude
     * @return string
     */
    protected function buildSQLUpdatesFrom(array $columns, array $exclude): string
    {
        $updateString = collect($columns)->reject(function ($column) use ($exclude) {
            return in_array($column, $exclude);
        })->reduce(function ($updates, $column) {
            return $updates .= "`{$column}`=VALUES(`{$column}`),";
        }, '');

        return trim($updateString, ',');
    }

    public function __call($name, $arguments)
    {
        return DB::$name(...$arguments);
    }
}