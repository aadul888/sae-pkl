<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'role',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function sanitizeValues(mixed $values): mixed
    {
        if (!is_array($values)) {
            return $values;
        }

        $sensitiveKeys = [
            'password',
            'password_confirmation',
            'remember_token',
            'token',
            'secret',
            'api_key',
            'api_secret',
            'access_token',
        ];

        $sanitized = [];
        foreach ($values as $key => $val) {
            if (in_array(strtolower($key), $sensitiveKeys, true)) {
                $sanitized[$key] = '[REDACTED]';
            } elseif (is_array($val)) {
                $sanitized[$key] = static::sanitizeValues($val);
            } else {
                $sanitized[$key] = $val;
            }
        }

        return $sanitized;
    }

    public static function record(
        string $action,
        ?Model $auditable = null,
        mixed $oldValues = null,
        mixed $newValues = null
    ): ?self {
        try {
            $user = Auth::user();

            return static::create([
                'user_id' => $user?->id,
                'role' => $user?->role,
                'action' => $action,
                'auditable_type' => $auditable ? get_class($auditable) : null,
                'auditable_id' => $auditable ? (string) $auditable->getKey() : null,
                'old_values' => static::sanitizeValues($oldValues),
                'new_values' => static::sanitizeValues($newValues),
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error('AuditLog record failed: ' . $e->getMessage(), [
                'action' => $action,
                'exception' => $e,
            ]);
            return null;
        }
    }
}
