<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Container\Attributes\Auth;
class Post extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'title', 'content', 'slug', 'category_id','tenant_id', 'created_by', 'updated_by', 'image_path'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
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
        static::creating(function ($post) {
            if (auth()->check()) {
                $post->created_by = auth()->id();
                $post->tenant_id  = auth()->user()->tenant_id;
            }
        });
         // Only update updated_by when updating
        static::updating(function ($post) {
            if (auth()->check()) {
                $post->updated_by = auth()->id();
            }
        });
    }

}
