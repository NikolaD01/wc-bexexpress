<?php

namespace WC_BE\Core\Commands;

use WC_BE\Core\Factories\SeederFactory;
use WP_CLI;

class SeedCommand
{
    /**
     * Run all seeders.
     *
     * @when after_wp_load
     */
    public function run() : void
    {
        WP_CLI::log('Starting the seeding process...');

        // Initialize seeders
        $municipalities_seeder = SeederFactory::create('municipalities');
        $places_seeder = SeederFactory::create('places');
        $streets_seeder = SeederFactory::create('streets');

        try {
            WP_CLI::log('Seeding municipalities...');
            $municipalities_seeder->seed();

            WP_CLI::log('Seeding places...');
            $places_seeder->seed();

            WP_CLI::log('Seeding streets...');
            $streets_seeder->seed();

            WP_CLI::success('Seeding process completed successfully.');
        } catch (\Exception $e) {
            WP_CLI::error('Seeding failed: ' . $e->getMessage());
        }
    }
}
