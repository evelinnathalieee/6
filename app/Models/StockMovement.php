<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_id',
        'type',
        'quantity',
        'note',
        'moved_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'moved_at' => 'datetime',
        ];
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function formatQuantity(): string
    {
        return rtrim(rtrim(number_format((float) $this->quantity, 2, '.', ''), '0'), '.');
    }
}
