<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class Settings
{
    /** @var array<string, string|null> */
    private static array $cache = [];

    public static function get(string $key, ?string $default = null): ?string
    {
        if (array_key_exists($key, self::$cache)) {
            return self::$cache[$key];
        }

        try {
            $value = DB::table('settings')->where('key', $key)->value('value');
        } catch (\Throwable $e) {
            self::$cache[$key] = $default;
            return $default;
        }
        self::$cache[$key] = $value !== null ? (string) $value : $default;

        return self::$cache[$key];
    }

    public static function getInt(string $key, int $default = 0): int
    {
        $raw = self::get($key, null);
        if ($raw === null || $raw === '') {
            return $default;
        }

        if (! is_numeric($raw)) {
            return $default;
        }

        return (int) $raw;
    }

    public static function put(string $key, ?string $value): void
    {
        DB::table('settings')->updateOrInsert(
            ['key' => $key],
            ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
        );

        self::$cache[$key] = $value;
    }

    public static function putInt(string $key, int $value): void
    {
        self::put($key, (string) $value);
    }
}
