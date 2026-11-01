<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelSignage extends Model
{
    use HasFactory;

    protected $table = 'model_signage';

    protected $guarded = ['id'];

    /**
     * Pastikan pemanggilan ->nama_model_tv selalu mengembalikan nilai
     * meskipun nama kolom di database adalah nama_model / nama / model.
     */
    public function getNamaModelTvAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        return $this->attributes['nama_model']
            ?? $this->attributes['nama']
            ?? $this->attributes['model_tv']
            ?? $this->attributes['model']
            ?? $this->attributes['merk']
            ?? null;
    }

    /**
     * Sebaliknya, jika ada kode yang memanggil ->nama_model padahal kolomnya nama_model_tv:
     */
    public function getNamaModelAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        return $this->attributes['nama_model_tv']
            ?? $this->attributes['nama']
            ?? $this->attributes['model']
            ?? null;
    }

    public function digitalSignage()
    {
        return $this->hasMany(DigitalSignage::class, 'model_signage_id');
    }
}