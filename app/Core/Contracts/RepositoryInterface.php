<?php

namespace WC_BE\Core\Contracts;

interface RepositoryInterface
{
    public function insert(array $data) : int;
    public function update(array $data, array $where) : bool|int;
    public function getTable() : string;
    public function delete(array $where) : bool|int;
    public function getAll(?string $order_by = null): array;

    public function getAllWhere(array $where = null, array $select = null) : ?array;
    public function getOne(array $where) : ?object;
}