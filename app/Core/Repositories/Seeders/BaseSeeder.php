<?php

namespace WC_BE\Core\Repositories\Seeders;

use WC_BE\Core\Contracts\RepositoryInterface;
use WC_BE\Core\Contracts\SeederInterface;
use WC_BE\Core\Traits\CSVReader;

abstract class BaseSeeder implements SeederInterface
{
    use CSVReader;
    public function __construct(protected RepositoryInterface $repository)
    {}

    abstract public function seed(): void;

    protected function truncate(): void
    {
        global $wpdb;
        $wpdb->query("TRUNCATE TABLE {$this->repository->getTable()}");
    }
}