<?php
namespace App\Repositories\Eloquent;
use App\Models\Models\Category as ModelsCategory;
use App\Repositories\Interfaces\CategoryInterface;

class CategoryEloquent implements CategoryInterface
{
    protected $model;

    public function __construct(ModelsCategory $category)
    {
        $this->model = $category;
    }

    public function all()
    {
        return $this->model->latest()->paginate(10);
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $post = $this->model->findOrFail($id);
        if ($post) {
            $post->update($data);
            return $post;
        }
        return null;
    }

    public function delete($id)
    {
        $post = $this->model->findOrFail($id);
        if ($post) {
            return $post->delete();
        }
        return null;
    }
}
