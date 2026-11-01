<?php

namespace App\Models\Concerns;

use App\Models\Client;
use App\Support\Tenant;
use Illuminate\Database\Eloquent\Builder;

/**
 * Pisahkan data per client.
 * - Baca : user yang login hanya melihat data client-nya. User tanpa client_id tidak melihat apa pun (aman).
 * - API perangkat (tanpa login) tidak difilter oleh scope ini; filter dilakukan lewat perangkat/token.
 * - Tulis: client_id terisi otomatis dari user yang login.
 */
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
