<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_MEMBER = 'member';
    public const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'member_code',
        'avatar_path',
        'password',
        'role',
        'loyalty_stamps',
        'loyalty_redeemed',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isMember(): bool
    {
        return $this->role === self::ROLE_MEMBER;
    }

    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }

    public function rewardRedemptions()
    {
        return $this->hasMany(\App\Models\RewardRedemption::class);
    }

    public function avatarSrc(): ?string
    {
        if (! $this->avatar_path) {
            return null;
        }

        if (Str::startsWith($this->avatar_path, ['http://', 'https://'])) {
            return $this->avatar_path;
        }

        $path = ltrim($this->avatar_path, '/');

        return '/storage/'.$path;
    }

    public function totalRewardsEarned(int $stampsPerReward = 5): int
    {
        $stampsPerReward = max(1, $stampsPerReward);

        return intdiv((int) $this->loyalty_stamps, $stampsPerReward);
    }

    public function availableRewards(int $stampsPerReward = 5): int
    {
        return max(0, $this->totalRewardsEarned($stampsPerReward) - (int) $this->loyalty_redeemed);
    }

    public function availableRewardsAfter(int $earnedStamps, int $stampsPerReward = 5): int
    {
        $stampsPerReward = max(1, $stampsPerReward);
        $earnedStamps = max(0, (int) $earnedStamps);

        $earnedRewards = intdiv(((int) $this->loyalty_stamps + $earnedStamps), $stampsPerReward);

        return max(0, $earnedRewards - (int) $this->loyalty_redeemed);
    }
}
