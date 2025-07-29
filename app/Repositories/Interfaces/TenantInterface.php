<?php

namespace App\Repositories\Interfaces;

interface TenantInterface
{
    public function all();
    public function findBySlug(string $slug);
    public function create(array $data);
}
