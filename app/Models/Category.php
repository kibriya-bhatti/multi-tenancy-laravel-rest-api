<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use App\Models\Post;

class Category extends Model
{
    use BelongsToTenant;

    protected $fillable = ['name', 'slug', 'tenant_id'];

    public function posts() {
        return $this->hasMany(Post::class);
    }
}
