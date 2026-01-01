<?php

namespace App\Services;

use Illuminate\Support\Str;

class SanitizationService
{
    /**
     * Sanitize string input to prevent XSS
     */
    public function sanitizeString(string $input, bool $allowHtml = false): string
    {
        if (!$allowHtml) {
            return strip_tags($input);
        }

        // Allow only safe HTML tags
        $allowedTags = '<p><br><strong><em><u><a><ul><ol><li><h1><h2><h3><h4><h5><h6>';
        return strip_tags($input, $allowedTags);
    }

    /**
     * Sanitize HTML content
     */
    public function sanitizeHtml(string $html): string
    {
        // Remove potentially dangerous tags and attributes
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        $html = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $html);
        $html = preg_replace('/on\w+\s*=\s*["\'].*?["\']/i', '', $html);
        $html = preg_replace('/javascript:/i', '', $html);

        return $html;
    }

    /**
     * Sanitize email address
     */
    public function sanitizeEmail(string $email): string
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    /**
     * Sanitize URL
     */
    public function sanitizeUrl(string $url): string
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    /**
     * Validate and sanitize URL
     */
    public function validateUrl(string $url): ?string
    {
        $sanitized = $this->sanitizeUrl($url);

        if (filter_var($sanitized, FILTER_VALIDATE_URL)) {
            // Additional check for allowed protocols
            if (preg_match('/^https?:\/\//i', $sanitized)) {
                return $sanitized;
            }
        }

        return null;
    }

    /**
     * Sanitize phone number
     */
    public function sanitizePhone(string $phone): string
    {
        // Remove all non-numeric characters except +
        return preg_replace('/[^0-9+]/', '', $phone);
    }

    /**
     * Sanitize filename
     */
    public function sanitizeFilename(string $filename): string
    {
        // Remove path traversal attempts
        $filename = basename($filename);

        // Remove special characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);

        // Prevent double extensions
        $filename = preg_replace('/\.{2,}/', '.', $filename);

        return $filename;
    }

    /**
     * Check for SQL injection patterns
     */
    public function hasSqlInjectionPattern(string $input): bool
    {
        $patterns = [
            '/(\bUNION\b.*\bSELECT\b)/i',
            '/(\bDROP\b.*\bTABLE\b)/i',
            '/(\bINSERT\b.*\bINTO\b)/i',
            '/(\bDELETE\b.*\bFROM\b)/i',
            '/(\bUPDATE\b.*\bSET\b)/i',
            '/(\bEXEC\b|\bEXECUTE\b)/i',
            '/(\'|\"|;|--|\*|\||&)/i',
            '/(\bOR\b.*=.*)/i',
            '/(\bAND\b.*=.*)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for XSS patterns
     */
    public function hasXssPattern(string $input): bool
    {
        $patterns = [
            '/<script\b[^>]*>(.*?)<\/script>/is',
            '/<iframe\b[^>]*>(.*?)<\/iframe>/is',
            '/on\w+\s*=\s*["\'].*?["\']/i',
            '/javascript:/i',
            '/<embed\b/i',
            '/<object\b/i',
            '/onerror\s*=/i',
            '/onload\s*=/i',
            '/onclick\s*=/i',
            '/<svg\b.*onload/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate file upload
     */
    public function validateFileUpload($file, array $allowedExtensions, int $maxSize): array
    {
        $errors = [];

        // Check if file exists
        if (!$file || !$file->isValid()) {
            $errors[] = 'Invalid file upload';
            return $errors;
        }

        // Check file size
        if ($file->getSize() > $maxSize) {
            $errors[] = 'File size exceeds maximum allowed size';
        }

        // Check extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedExtensions)) {
            $errors[] = 'File type not allowed';
        }

        // Check MIME type
        $mimeType = $file->getMimeType();
        $allowedMimes = $this->getAllowedMimeTypes($allowedExtensions);

        if (!in_array($mimeType, $allowedMimes)) {
            $errors[] = 'File MIME type not allowed';
        }

        return $errors;
    }

    /**
     * Get allowed MIME types for extensions
     */
    protected function getAllowedMimeTypes(array $extensions): array
    {
        $mimeTypes = [
            'jpg' => ['image/jpeg', 'image/jpg'],
            'jpeg' => ['image/jpeg', 'image/jpg'],
            'png' => ['image/png'],
            'gif' => ['image/gif'],
            'webp' => ['image/webp'],
            'pdf' => ['application/pdf'],
            'doc' => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'mp4' => ['video/mp4'],
            'webm' => ['video/webm'],
            'mov' => ['video/quicktime'],
        ];

        $allowed = [];
        foreach ($extensions as $ext) {
            if (isset($mimeTypes[$ext])) {
                $allowed = array_merge($allowed, $mimeTypes[$ext]);
            }
        }

        return array_unique($allowed);
    }

    /**
     * Generate safe filename
     */
    public function generateSafeFilename(string $originalName, string $prefix = ''): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $timestamp = now()->timestamp;
        $random = Str::random(16);

        return ($prefix ? $prefix . '-' : '') . $timestamp . '-' . $random . '.' . $extension;
    }

    /**
     * Sanitize array recursively
     */
    public function sanitizeArray(array $data, bool $allowHtml = false): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeArray($value, $allowHtml);
            } elseif (is_string($value)) {
                $sanitized[$key] = $this->sanitizeString($value, $allowHtml);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Remove null bytes
     */
    public function removeNullBytes(string $input): string
    {
        return str_replace("\0", '', $input);
    }

    /**
     * Sanitize for safe display
     */
    public function sanitizeForDisplay(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Check if string contains profanity
     */
    public function containsProfanity(string $text): bool
    {
        // List of common profanity words (add more as needed)
        $profanityList = [
            'fuck', 'shit', 'bitch', 'asshole', 'damn', 'bastard',
            'cunt', 'dick', 'pussy', 'cock', 'whore', 'slut',
            // Add more words as needed
        ];

        $text = strtolower($text);

        foreach ($profanityList as $word) {
            if (stripos($text, $word) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Filter profanity from text
     */
    public function filterProfanity(string $text, string $replacement = '***'): string
    {
        $profanityList = [
            'fuck', 'shit', 'bitch', 'asshole', 'damn', 'bastard',
            'cunt', 'dick', 'pussy', 'cock', 'whore', 'slut',
        ];

        foreach ($profanityList as $word) {
            $text = preg_replace('/\b' . preg_quote($word, '/') . '\b/i', $replacement, $text);
        }

        return $text;
    }

    /**
     * Detect and extract URLs from text
     */
    public function extractUrls(string $text): array
    {
        preg_match_all(
            '/(https?:\/\/[^\s]+)/i',
            $text,
            $matches
        );

        return $matches[0] ?? [];
    }

    /**
     * Check if text contains suspicious links
     */
    public function hasSuspiciousLinks(string $text): bool
    {
        $urls = $this->extractUrls($text);

        $suspiciousDomains = [
            'bit.ly', 'tinyurl.com', 'goo.gl', 't.co',
            // Add more suspicious domains
        ];

        foreach ($urls as $url) {
            $domain = parse_url($url, PHP_URL_HOST);

            if (in_array($domain, $suspiciousDomains)) {
                return true;
            }

            // Check for IP addresses
            if (filter_var($domain, FILTER_VALIDATE_IP)) {
                return true;
            }
        }

        return false;
    }
}
