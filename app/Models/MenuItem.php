<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'price',
        'is_featured',
        'description',
        'image_url',
    ];

    public function imageSrc(): ?string
    {
        if (! $this->image_url) {
            return null;
        }

        if (Str::startsWith($this->image_url, ['http://', 'https://'])) {
            return $this->image_url;
        }

        $path = ltrim($this->image_url, '/');

        // Use relative URL to avoid APP_URL/port mismatch issues on local dev.
        return '/storage/'.$path;
    }
}
