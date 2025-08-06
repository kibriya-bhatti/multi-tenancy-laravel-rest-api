<?php
namespace App\Services;

use App\Repositories\Eloquent\CategoryEloquent;
use App\Services\Support\TransactionService;

class CategoryService
{
    protected $transaction;
    protected $categoryRepo;
    public function __construct(CategoryEloquent $categoryRepo) {
        $this->categoryRepo = $categoryRepo;
        $this->transaction = new TransactionService();
    }

    public function getAll(int $perPage)
    {
        return $this->transaction->run(function () use ($perPage) {
            return $this->categoryRepo->all($perPage);
        });
    }

    public function find(int $id)
    {
        return $this->transaction->run(function () use ($id) {
            return $this->categoryRepo->find($id);
        });
    }

    public function store(array $data)
    {
        return $this->transaction->run(function () use ($data) {
            return $this->categoryRepo->create($data);
        });
    }

    public function update(int $id, array $data)
    {
        return $this->transaction->run(function () use ($id, $data) {
            return $this->categoryRepo->update($id, $data);
        });
    }

    public function destroy(int $id): void
    {
        $this->transaction->run(function () use ($id) {
            $this->categoryRepo->delete($id);
        });
    }
}
