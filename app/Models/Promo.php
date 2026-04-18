<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'discount_type',
        'discount_value',
        'min_subtotal',
        'starts_at',
        'ends_at',
        'is_enabled',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_enabled' => 'boolean',
            'discount_value' => 'integer',
            'min_subtotal' => 'integer',
        ];
    }

    public function discountLabel(): string
    {
        if ($this->discount_type === 'percent') {
            return (int) $this->discount_value.'%';
        }

        return 'Rp '.number_format((int) $this->discount_value, 0, ',', '.');
    }

    public function calculateDiscount(int $subtotal): int
    {
        $subtotal = max(0, $subtotal);

        if ($this->discount_type === 'percent') {
            $pct = max(0, min(100, (int) $this->discount_value));
            return (int) floor(($subtotal * $pct) / 100);
        }

        return max(0, min($subtotal, (int) $this->discount_value));
    }

    public function isEligibleForSubtotal(int $subtotal): bool
    {
        return $subtotal >= (int) $this->min_subtotal;
    }

    public function isActive(?Carbon $now = null): bool
    {
        $now ??= now();

        if (! $this->is_enabled) {
            return false;
        }

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        return true;
    }
}
