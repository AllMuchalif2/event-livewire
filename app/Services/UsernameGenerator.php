<?php

namespace App\Services;

use App\Models\User;

class UsernameGenerator
{
    /**
     * Generate username untuk user type 'umum'.
     * Format: umum-001, umum-002, umum-003, ...
     * 
     * @return string Generated username
     */
    public static function generateForUmum(): string
    {
        // Get user terakhir dengan username 'umum-XXX'
        $lastUser = User::where('username', 'like', 'umum-%')
            ->orderByRaw('CAST(SUBSTRING(username, 6) AS UNSIGNED) DESC')
            ->first();

        if ($lastUser) {
            // Extract number dari username
            // Contoh: 'umum-001' -> '001' -> 1
            $lastNumber = (int) substr($lastUser->username, 5); // Skip 'umum-'
            $nextNumber = $lastNumber + 1;
        } else {
            // Ini user umum pertama
            $nextNumber = 1;
        }

        // Format dengan leading zeros (3 digit)
        return sprintf('umum-%03d', $nextNumber);
    }

    /**
     * Generate username dan pastikan unique.
     * Retry jika terjadi collision.
     * 
     * @param int $maxRetries
     * @return string
     */
    public static function generateUniqueForUmum(int $maxRetries = 5): string
    {
        for ($i = 0; $i < $maxRetries; $i++) {
            $username = self::generateForUmum();

            // Check apakah sudah ada
            if (!User::where('username', $username)->exists()) {
                return $username;
            }

            // Jika ada collision, tunggu sebentar dan retry
            usleep(100000); // 0.1 second
        }

        throw new \RuntimeException("Failed to generate unique username after {$maxRetries} attempts.");
    }

    /**
     * Validate NIM format (mahasiswa).
     * Format: 00.000.0000 (2 digit.3 digit.4 digit)
     * 
     * @param string $nim
     * @return bool
     */
    public static function isValidNim(string $nim): bool
    {
        return preg_match('/^\d{2}\.\d{3}\.\d{4}$/', $nim) === 1;
    }

    /**
     * Validate username untuk umum.
     * Format: umum-NNN
     * 
     * @param string $username
     * @return bool
     */
    public static function isValidUmumUsername(string $username): bool
    {
        return preg_match('/^umum-\d{3}$/', $username) === 1;
    }

    /**
     * Determine type dari username.
     * 
     * @param string $username
     * @return string 'mahasiswa', 'umum', or 'unknown'
     */
    public static function getUserType(string $username): string
    {
        if (self::isValidNim($username)) {
            return 'mahasiswa';
        }

        if (self::isValidUmumUsername($username)) {
            return 'umum';
        }

        return 'unknown';
    }

    /**
     * Sanitize NIM (remove spaces, validate format).
     * 
     * @param string $nim
     * @return string|null Sanitized NIM or null if invalid
     */
    public static function sanitizeNim(string $nim): ?string
    {
        // Remove whitespace
        $nim = trim($nim);

        // Check if valid
        if (!self::isValidNim($nim)) {
            return null;
        }

        return $nim;
    }
}
