<?php

namespace WC_BE\Core\Managers;

use WC_BE\Core\Traits\DB;

class TableManager
{
    use DB;

    /**
     * TableManager constructor.
     * Initialize the necessary tables when the class is instantiated.
     */
    public function __construct()
    {
        $this->initializeTables();
    }

    /**
     * Initialize the database tables.
     */
    private function initializeTables(): void
    {
        if ($this->shouldRunTableSetup())
        {
            $this->createMunicipalitiesTable();
            $this->createPlacesTable();
            $this->createStreetsTable();
            $this->addForeignKeys();
        }
    }

    /**
     * Check if the tables need to be set up.
     */
    private function shouldRunTableSetup(): bool
    {
        $table_name = $this->db()->prefix . 'municipalities';
        return !$this->tableExists($table_name);
    }

    /**
     * Check if a table exists in the database.
     */
    private function tableExists(string $table_name): bool
    {
        $query = $this->db()->prepare(
            "SHOW TABLES LIKE %s",
            $table_name
        );
        return (bool) $this->db()->get_var($query);
    }

    /**
     * Create the municipalities table.
     */
    private function createMunicipalitiesTable(): void
    {
        $table_name = $this->db()->prefix . 'municipalities';
        $charset_collate = $this->db()->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        ) {$charset_collate};";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Create the places table.
     */
    private function createPlacesTable(): void
    {
        $table_name = $this->db()->prefix . 'places';
        $charset_collate = $this->db()->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            municipalities_id BIGINT UNSIGNED NOT NULL,
            zip VARCHAR(20) NOT NULL,
            KEY (municipalities_id) -- For adding the foreign key
        ) {$charset_collate};";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Create the streets table.
     */
    private function createStreetsTable(): void
    {
        $table_name = $this->db()->prefix . 'streets';
        $charset_collate = $this->db()->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            place_id BIGINT UNSIGNED NOT NULL,
            KEY (place_id) -- For adding the foreign key
        ) {$charset_collate};";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Add foreign keys to the tables.
     */
    private function addForeignKeys(): void
    {
        $places_table = $this->db()->prefix . 'places';
        $municipalities_table = $this->db()->prefix . 'municipalities';
        $streets_table = $this->db()->prefix . 'streets';

        $queries = [
            // Add foreign key to `places` table
            "ALTER TABLE {$places_table} 
         ADD CONSTRAINT fk_places_municipalities 
         FOREIGN KEY (municipalities_id) 
         REFERENCES {$municipalities_table}(id) 
         ON DELETE CASCADE ON UPDATE CASCADE;",

            // Add foreign key to `streets` table
            "ALTER TABLE {$streets_table} 
         ADD CONSTRAINT fk_streets_places 
         FOREIGN KEY (place_id) 
         REFERENCES {$places_table}(id) 
         ON DELETE CASCADE ON UPDATE CASCADE;"
        ];

        foreach ($queries as $query)
        {
            $this->db()->query($query);
        }
    }
}
