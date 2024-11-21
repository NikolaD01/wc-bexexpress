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