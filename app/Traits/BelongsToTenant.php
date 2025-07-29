<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::creating(function ($model) {
            if (multitenancy()->tenant()) {
                $model->tenant_id = multitenancy()->tenant()->id;
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder) {
            if (multitenancy()->tenant()) {
                $builder->where('tenant_id', multitenancy()->tenant()->id);
            }
        });
    }

    public function scopeForTenant($query, $tenantId = null)
    {
        return $query->where('tenant_id', $tenantId ?? multitenancy()->tenant()->id);
    }
}
