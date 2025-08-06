<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use App\Models\Post;
use Illuminate\Database\Eloquent\SoftDeletes;
class Category extends Model
{
    use BelongsToTenant,SoftDeletes;

    protected $fillable = ['name', 'slug', 'tenant_id','created_by','updated_by'];

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater() {
        return $this->belongsTo(User::class, 'updated_by');
    }

    protected static function booted()
    {
        // Set created_by and tenant_id automatically on creation
        static::creating(function ($category) {
            if (auth()->check()) {
                $category->created_by = auth()->id();
                $category->tenant_id  = auth()->user()->tenant_id;
            }
        });
         // Only update updated_by when updating
        static::updating(function ($category) {
            if (auth()->check()) {
                $category->updated_by = auth()->id();
            }
        });
    }
}
