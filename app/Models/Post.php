<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
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
}
