<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LlmModel extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'identifier',
        'api_key',
        'provider', // Keep for backward compatibility during migration
        'provider_id',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'api_key' => 'encrypted',
            'active' => 'boolean',
        ];
    }

    public function providerRelation()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
}
