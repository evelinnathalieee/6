<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Transaction extends Model
{
    use HasFactory;

    public const CHANNEL_POS = 'pos';
    public const CHANNEL_ONLINE = 'online';

    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_CANCELED = 'canceled';

    public const METHOD_CASH = 'cash';
    public const METHOD_QRIS = 'qris';

    protected $fillable = [
        'transaction_code',
        'user_id',
        'promo_id',
        'promo_name_snapshot',
        'purchased_at',
        'payment_status',
        'payment_method',
        'paid_at',
        'canceled_at',
        'order_type',
        'order_number',
        'sales_channel',
        'subtotal',
        'promo_discount',
        'reward_discount',
        'reward_redeemed_count',
        'discount',
        'total',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'purchased_at' => 'datetime',
            'paid_at' => 'datetime',
            'canceled_at' => 'datetime',
        ];
    }

    public static function nextOrderNumber(string $orderType, ?Carbon $forTime = null): string
    {
        $forTime ??= now();

        $prefix = $orderType === 'take_away' ? 'TA' : 'DI';
        $date = $forTime->toDateString();

        // "Nomor pemesanan" sebagai nomor antrian harian per tipe order.
        // Dibuat 3 digit (001, 002, ...).
        $count = self::query()
            ->whereDate('purchased_at', $date)
            ->where('order_type', $orderType)
            ->lockForUpdate()
            ->count();

        return $prefix.'-'.str_pad((string) ($count + 1), 3, '0', STR_PAD_LEFT);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }
}
