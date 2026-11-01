<?php

namespace App\Support;

use Illuminate\Validation\Rule;

/**
 * Helper multi-tenant (Plan B).
 * Client aktif = client_id milik user yang sedang login. API perangkat (tanpa login) => null.
 */
class Tenant
{
    public static function id(): ?int
    {
        return auth()->check() ? (auth()->user()->client_id ? (int) auth()->user()->client_id : null) : null;
    }

    /** exists:{table},{column} yang dibatasi ke client aktif */
    public static function exists(string $table, string $column = 'id')
    {
        $rule = Rule::exists($table, $column);
        return self::id() ? $rule->where('client_id', self::id()) : $rule;
    }

    /** unique:{table},{column} per client (gabungan dengan client_id) */
    public static function unique(string $table, string $column, $ignoreId = null)
    {
        $rule = Rule::unique($table, $column);
        if (self::id()) {
            $rule->where('client_id', self::id());
        }
        return $ignoreId ? $rule->ignore($ignoreId) : $rule;
    }
}
