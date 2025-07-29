<?php
namespace App\Repositories\Eloquent;
use App\Repositories\Interfaces\TenantInterface;
use App\Models\Tenant;

class TenantEloquent implements TenantInterface
{
    protected $model;

    public function __construct(Tenant $tenant)
    {
        $this->model = $tenant;
    }

    public function all()
    {
        return $this->model->latest()->paginate(10);
    }

    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)->firstOrFail();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
