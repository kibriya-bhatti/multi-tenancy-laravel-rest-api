<?php
namespace App\Repositories\Eloquent;
use App\Repositories\Interfaces\PostInterface;
use App\Models\Post;

class PostEloquent implements PostInterface
{
    protected $model;

    public function __construct(Post $post)
    {
        $this->model = $post;
    }
    public function all($perPage)
    {
        return $this->model->with('category')
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function find($id)
    {
        return $this->model->with('category')->findOrFail($id);
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
