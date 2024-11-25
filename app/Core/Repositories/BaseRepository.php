<?php

namespace WC_BE\Core\Repositories;

use WC_BE\Core\Contracts\RepositoryInterface;
use WC_BE\Core\Traits\DB;

abstract class BaseRepository implements RepositoryInterface {
    use DB;

    protected string $table;

    public function __construct($table_name) {
        $this->table = $this->table($table_name);
    }

    public function getTable(): string
    {
        return $this->table;
    }
    /**
     * Get the table name with prefix.
     *
     * @param string $table_name
     * @return string
     */
    protected function table(string $table_name): string
    {
        return $this->db()->prefix . $table_name;
    }


    /**
     * Insert a record into the table.
     *
     * @param array $data
     * @return int The inserted ID.
     */
    public function insert(array $data): int
    {
        $this->db()->insert($this->table, $data);
        return $this->db()->insert_id;
    }

    /**
     * Update a record in the table.
     *
     * @param array $data
     * @param array $where
     * @return int|false Number of rows affected or false on failure.
     */
    public function update(array $data, array $where): bool|int
    {
        return $this->db()->update($this->table, $data, $where);
    }

    /**
     * Delete a record from the table.
     *
     * @param array $where
     * @return int|false Number of rows affected or false on failure.
     */
    public function delete(array $where): bool|int
    {
        return $this->db()->delete($this->table, $where);
    }

    /**
     * Get all records from the table.
     *
     * @param string|null $order_by Column name to order by.
     * @return array
     */
    public function getAll(string $order_by = null): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($order_by) {
            $sql .= " ORDER BY {$order_by}";
        }
        return $this->db()->get_results($sql);
    }


    /**
     * Get all records from the table with a WHERE clause and customizable SELECT columns.
     *
     * @param array|null $where Associative array of conditions ['column' => 'value'].
     * @param array|null $select Array of column names to retrieve, or null to select all columns.
     * @return array|null Array of results as objects, or null if no records are found.
     */
    public function getAllWhere(array $where = null, array $select = null): ?array
    {

        $columns = '*';
        if ($select) {
            $columns = implode(', ', array_map(function ($column) {
                return "`{$column}`";
            }, $select));
        }

        $conditions = [];
        $values = [];
        if ($where) {
            foreach ($where as $key => $value) {
                $conditions[] = "`{$key}` = %s";
                $values[] = $value;
            }
        }

        $sql = "SELECT {$columns} FROM {$this->table}";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $prepared_query = $this->db()->prepare($sql, $values);

        return $this->db()->get_results($prepared_query);
    }


    /**
     * Get a single record by conditions.
     *
     * @param array $where
     * @return object|null
     */
    public function getOne(array $where): ?object
    {
        $conditions = [];
        foreach ($where as $key => $value) {
            $conditions[] = $this->db()->prepare("{$key} = %s", $value);
        }
        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $conditions) . " LIMIT 1";
        return $this->db()->get_row($sql);
    }
}