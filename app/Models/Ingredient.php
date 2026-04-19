<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit',
        'opening_stock',
        'current_stock',
        'low_stock_threshold',
    ];

    protected function casts(): array
    {
        return [
            'opening_stock' => 'decimal:2',
            'current_stock' => 'decimal:2',
            'low_stock_threshold' => 'decimal:2',
        ];
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function stockStatus(): string
    {
        if ((float) $this->current_stock <= 0) {
            return 'habis';
        }

        if ((float) $this->current_stock <= (float) $this->low_stock_threshold) {
            return 'menipis';
        }

        return 'aman';
    }

    public function formatStock(?float $value = null): string
    {
        $number = (float) ($value ?? 0);

        return rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
    }
}
