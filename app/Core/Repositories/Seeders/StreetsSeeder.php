<?php

namespace WC_BE\Core\Repositories\Seeders;

use WC_BE\Core\Factories\RepositoryFactory;

USE WP_CLI;
class StreetsSeeder extends BaseSeeder
{
    public function __construct()
    {
        parent::__construct(RepositoryFactory::create('streets'));
    }

    public function seed(): void
    {
        $this->truncate();
        $rows = $this->readCSV('Streets.csv');

        foreach ($rows as $row) {
            $data = [
                'id' => $row['id'],
                'name' => $row['name'],
                'place_id' => $row['place_id'],
            ];

            $this->repository->insert($data);
            if (class_exists('WP_CLI')) {
                WP_CLI::log("Inserted: " . json_encode($data));
            }
        }
        if (class_exists('WP_CLI')) {
            WP_CLI::success("Seeding complete for Streets.csv");
        }
    }

}
