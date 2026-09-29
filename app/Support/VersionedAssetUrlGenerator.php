<?php

namespace App\Support;

use Illuminate\Routing\UrlGenerator;

/*
 * Menempelkan ?v=<mtime> pada URL aset lokal di folder assets/.
 * Tujuannya: setelah git pull, hanya file yang benar-benar berubah yang
 * mendapat URL baru, jadi user tidak perlu Ctrl+F5.
 * URL absolut (CDN) dan path yang sudah punya ?v= tidak disentuh.
 */
class VersionedAssetUrlGenerator extends UrlGenerator
{
    private static array $versions = [];

    public function asset($path, $secure = null)
    {
        $url = parent::asset($path, $secure);

        if (!is_string($path) || !str_starts_with($path, 'assets/') || str_contains($path, '?v=')) {
            return $url;
        }

        return $url . '?v=' . $this->version($path);
    }

    private function version(string $path): int
    {
        return self::$versions[$path] ??= is_file($file = public_path($path))
            ? (int) filemtime($file)
            : 1;
    }
}
