<?php

namespace WC_BE\Core\Commands;

use WP_CLI;
use WC_BE\Core\Traits\DB;
class DropCommand
{
    use DB;
    /**
     * Drop the tables: municipalities, places, and streets.
     *
     * @when after_wp_load
     */
    public function run() : void
    {
        WP_CLI::log('Starting the table dropping process...');

        $tables = [
            'municipalities',
            'places',
            'streets'
        ];

        foreach ($tables as $table) {
            $table_name = $this->getTableName($table);

            try {
                $this->dropTable($table_name);
                WP_CLI::log("Table {$table_name} dropped successfully.");
            } catch (\Exception $e) {
                WP_CLI::error("Failed to drop {$table_name}: " . $e->getMessage());
            }
        }

        WP_CLI::success('Table dropping process completed.');
    }

    /**
     * Get the full table name including the WordPress prefix.
     *
     * @param string $table
     * @return string
     */
    private function getTableName(string $table): string
    {
        return $this->db()->prefix . $table;
    }

    /**
     * Drop a table from the database.
     *
     * @param string $table_name
     * @return void
     */
    private function dropTable(string $table_name): void
    {
        $sql = "DROP TABLE IF EXISTS {$table_name};";
        $this->db()->query($sql);
    }
}
