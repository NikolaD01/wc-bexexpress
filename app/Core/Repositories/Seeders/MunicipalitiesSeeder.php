<?php

namespace WC_BE\Core\Repositories\Seeders;

use WC_BE\Core\Factories\RepositoryFactory;
use WP_CLI;
class MunicipalitiesSeeder extends BaseSeeder
{
    public function __construct()
    {
        parent::__construct(RepositoryFactory::create('municipalities'));
    }

    public function seed(): void
    {
        $this->truncate();
        $rows = $this->readCSV('Municipalities.csv');

        foreach ($rows as $row) {
            $data = [
                'id' => $row['id'],
                'name' => $row['name'],
            ];

            $this->repository->insert($data);

            if (class_exists('WP_CLI')) {
                WP_CLI::log("Inserted into municipalities: " . json_encode($data));
            }        }

        if (class_exists('WP_CLI')) {
            WP_CLI::success("Seeding complete for Municipalities.csv");
        }
    }
}