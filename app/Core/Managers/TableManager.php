<?php

namespace WC_BE\Core\Managers;

use WC_BE\Core\Factories\SeederFactory;
use WC_BE\Core\Traits\DB;

class TableManager
{
    use DB;

    public function __construct()
    {
        $this->initializeTables();

        if ($this->shouldSeed()) {
            $this->seedTables();
        }
    }

    public function shouldSeed(): bool
    {
        $tables = [
            'municipalities' => $this->db()->prefix . 'municipalities',
            'places' => $this->db()->prefix . 'places',
            'streets' => $this->db()->prefix . 'streets',
        ];

        foreach ($tables as $table) {
            if ($this->tableExists($table) && $this->isTableEmpty($table)) {
                return true;
            }
        }

        return false;
    }

    private function tableExists(string $table_name): bool
    {
        $query = $this->db()->prepare("SHOW TABLES LIKE %s", $table_name);
        return (bool)$this->db()->get_var($query);
    }

    private function isTableEmpty(string $table_name): bool
    {
        $query = $this->db()->prepare("SELECT COUNT(*) FROM {$table_name}");
        $count = $this->db()->get_var($query);
        return (int)$count === 0;
    }

    private function seedTables(): void
    {
        $municipalities_seeder = SeederFactory::create('municipalities');
        $places_seeder = SeederFactory::create('places');
        $streets_seeder = SeederFactory::create('streets');

        $municipalities_seeder->seed();
        $places_seeder->seed();
        $streets_seeder->seed();
    }

    private function initializeTables(): void
    {
        if ($this->shouldRunTableSetup()) {
            $this->createMunicipalitiesTable();
            $this->createPlacesTable();
            $this->createStreetsTable();
           // $this->createParcelsTable();
            $this->addForeignKeys();
        }
    }

    private function shouldRunTableSetup(): bool
    {
        $table_name = $this->db()->prefix . 'municipalities';
        return !$this->tableExists($table_name);
    }

    private function createMunicipalitiesTable(): void
    {
        $table_name = $this->db()->prefix . 'municipalities';
        $charset_collate = $this->db()->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci {$charset_collate};";

        $this->db()->query($sql);
    }

    private function createPlacesTable(): void
    {
        $table_name = $this->db()->prefix . 'places';
        $charset_collate = $this->db()->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            municipalities_id BIGINT UNSIGNED NOT NULL,
            zip VARCHAR(20) NOT NULL,
            KEY (municipalities_id)
        ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci {$charset_collate};";

        $this->db()->query($sql);
    }

    private function createStreetsTable(): void
    {
        $table_name = $this->db()->prefix . 'streets';
        $charset_collate = $this->db()->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            place_id BIGINT UNSIGNED NOT NULL,
            KEY (place_id)
        ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci {$charset_collate};";

        $this->db()->query($sql);
    }

    private function createParcelsTable(): void
    {
        $table_name = $this->db()->prefix . 'parcels';
        $charset_collate = $this->db()->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            code INT NOT NULL,
            regionId INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            state VARCHAR(100) NOT NULL,
            postalCode INT NOT NULL,
            city VARCHAR(255) NOT NULL,
            street VARCHAR(255) NOT NULL,
            houseNumber VARCHAR(50) NOT NULL,
            address TEXT NOT NULL,
            businessHours VARCHAR(100) NOT NULL,
            businessHoursOnSaturday VARCHAR(100) NOT NULL,
            xcoordinate DECIMAL(10, 8) NOT NULL,
            ycoordinate DECIMAL(11, 8) NOT NULL
        ) ENGINE=InnoDB CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci {$charset_collate};";

        $this->db()->query($sql);
    }

    private function addForeignKeys(): void
    {
        $places_table = $this->db()->prefix . 'places';
        $municipalities_table = $this->db()->prefix . 'municipalities';
        $streets_table = $this->db()->prefix . 'streets';

        $queries = [
            "ALTER TABLE {$places_table} 
             ADD CONSTRAINT fk_places_municipalities 
             FOREIGN KEY (municipalities_id) 
             REFERENCES {$municipalities_table}(id) 
             ON DELETE CASCADE ON UPDATE CASCADE;",

            "ALTER TABLE {$streets_table} 
             ADD CONSTRAINT fk_streets_places 
             FOREIGN KEY (place_id) 
             REFERENCES {$places_table}(id) 
             ON DELETE CASCADE ON UPDATE CASCADE;"
        ];

        foreach ($queries as $query) {
            $this->db()->query($query);
        }
    }
}
