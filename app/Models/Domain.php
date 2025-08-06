<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
class Domain extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'domain', 'tenant_id'
    ];
}
