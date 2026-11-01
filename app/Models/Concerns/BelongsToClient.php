<?php

namespace App\Models\Concerns;

use App\Models\Client;
use App\Support\Tenant;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToClient
{
    protected static function bootBelongsToClient(): void
    {
        static::addGlobalScope('client', function (Builder $query) {
            if (auth()->check()) {
                $query->where($query->getModel()->getTable() . '.client_id', auth()->user()->client_id ?? 0);
            }
        });

        static::creating(function ($model) {
            if (empty($model->client_id)) {
                $model->client_id = Tenant::id();
            }
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}