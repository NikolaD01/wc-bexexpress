<?php

namespace WC_BE\Core\Repositories\Seeders;


use WC_BE\Core\Factories\RepositoryFactory;

use WP_CLI;
class PlacesSeeder extends BaseSeeder
{
    public function __construct()
    {
        parent::__construct(RepositoryFactory::create('places'));
    }

    public function seed(): void
    {
        $this->truncate();
        $rows = $this->readCSV('Places.csv');

        foreach ($rows as $row) {
            $data = [
                'id' => $row['id'],
                'name' => $row['name'],
                'municipalities_id' => $row['municipalities_id'],
                'zip' => $row['zip'],
            ];

            $this->repository->insert($data);

            WP_CLI::log("Inserted into places: " . json_encode($data));
        }

        WP_CLI::success("Seeding complete for Places.csv");
    }
}
