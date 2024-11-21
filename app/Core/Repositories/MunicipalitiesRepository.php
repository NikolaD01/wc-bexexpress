<?php

namespace WC_BE\Core\Repositories;

class MunicipalitiesRepository extends BaseRepository
{
    public function __construct() {
        parent::__construct('municipalities');
    }
}