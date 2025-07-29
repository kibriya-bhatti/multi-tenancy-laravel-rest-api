<?php
namespace App\Services;

use App\Repositories\Interfaces\PostInterface;
use App\Services\Support\TransactionService;

class PostService
{
    public function __construct(
        protected PostInterface $postRepo,
        protected TransactionService $transaction
    ) {}

    public function getAll(int $perPage = 10)
    {
        return $this->transaction->run(function () use ($perPage) {
            return $this->postRepo->all();
        });
    }

    public function find(int $id)
    {
        return $this->transaction->run(function () use ($id) {
            return $this->postRepo->find($id);
        });
    }

    public function store(array $data)
    {
        return $this->transaction->run(function () use ($data) {
            return $this->postRepo->create($data);
        });
    }

    public function update(int $id, array $data)
    {
        return $this->transaction->run(function () use ($id, $data) {
            return $this->postRepo->update($id, $data);
        });
    }

    public function destroy(int $id): void
    {
        $this->transaction->run(function () use ($id) {
            $this->postRepo->delete($id);
        });
    }
}
