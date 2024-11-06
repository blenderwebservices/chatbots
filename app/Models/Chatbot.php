<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chatbot extends Model
{
    /** @use HasFactory<\Database\Factories\ChatbotFactory> */
    use HasFactory;

    use HasUuids;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
