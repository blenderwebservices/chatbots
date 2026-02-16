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
        'last_check_status',
        'last_check_at',
        'configuration',
    ];

    protected function casts(): array
    {
        return [
            'api_key' => 'encrypted',
            'active' => 'boolean',
            'last_check_at' => 'datetime',
            'configuration' => 'array',
        ];
    }

    public function providerRelation()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }
}
