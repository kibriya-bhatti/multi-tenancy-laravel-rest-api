<?php

namespace App\Repositories\Interfaces;

interface PostInterface
{
    public function all($perPage);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}
