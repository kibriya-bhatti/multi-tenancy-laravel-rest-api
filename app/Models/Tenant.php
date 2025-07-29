<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Tenant extends Model
{
    use UsesTenantConnection;

    protected $fillable = ['name', 'slug', 'domain'];

     public function users() {
        return $this->hasMany(User::class);
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function categories() {
        return $this->hasMany(Category::class);
    }
}
