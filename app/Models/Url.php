<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Url extends Model
{
    use HasFactory;

    protected $fillable = ['original_url', 'short_code', 'user_id', 'company_id', 'clicks'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function clickLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClickLog::class);
    }
}
