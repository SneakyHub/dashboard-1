<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TurnstileRule implements Rule
{
    /**
     * The error message for validation failure.
     *
     * @var string
     */
    private $errorMessage;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // If no secret key is configured, skip validation
        $secretKey = env('TURNSTILE_SECRET_KEY');
        if (empty($secretKey)) {
            Log::debug('Turnstile validation skipped: No secret key configured');
            return true;
        }

        // If no response token provided, fail validation
        if (empty($value)) {
            $this->errorMessage = __('Please complete the security verification.');
            Log::info('Turnstile validation failed: No response token provided', [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            return false;
        }

        // Check if this token was already validated (prevent replay attacks)
        $tokenCacheKey = 'turnstile_token_' . md5($value);
        if (Cache::has($tokenCacheKey)) {
            $this->errorMessage = __('Security verification has already been used. Please refresh and try again.');
            Log::warning('Turnstile validation failed: Token replay attempt', [
                'token_hash' => md5($value),
                'ip' => request()->ip()
            ]);
            return false;
        }

        try {
            $clientIp = $this->getClientIp();
            
            Log::debug('Turnstile validation attempt', [
                'ip' => $clientIp,
                'token_length' => strlen($value),
                'user_agent' => request()->userAgent()
            ]);

            // Make request to Cloudflare Turnstile API
            $response = Http::timeout(15)
                ->retry(2, 1000) // Retry twice with 1 second delay
                ->asForm()
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $secretKey,
                    'response' => $value,
                    'remoteip' => $clientIp,
                ]);

            // Check if HTTP request was successful
            if (!$response->successful()) {
                $this->errorMessage = __('Security verification service is temporarily unavailable. Please try again.');
                Log::error('Turnstile API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'ip' => $clientIp
                ]);
                
                // In case of API failure, you can choose to:
                // - return false for strict security (blocks users)
                // - return true for availability (allows users through)
                // Adjust based on your security requirements
                return config('turnstile.fail_open', false);
            }

            $data = $response->json();
            
            // Validate response structure
            if (!is_array($data) || !isset($data['success'])) {
                $this->errorMessage = __('Invalid security verification response. Please try again.');
                Log::error('Turnstile API returned invalid response', [
                    'response' => $data,
                    'ip' => $clientIp
                ]);
                return false;
            }

            $success = $data['success'] === true;
            $errorCodes = $data['error-codes'] ?? [];
            $challengeTimestamp = $data['challenge_ts'] ?? null;
            $hostname = $data['hostname'] ?? null;

            // Log validation attempt details
            Log::info('Turnstile validation response received', [
                'success' => $success,
                'error_codes' => $errorCodes,
                'challenge_timestamp' => $challengeTimestamp,
                'hostname' => $hostname,
                'ip' => $clientIp,
                'response_time' => $response->transferStats?->getTransferTime()
            ]);

            // Handle specific error codes
            if (!$success && !empty($errorCodes)) {
                $this->handleErrorCodes($errorCodes, $clientIp);
                return false;
            }

            // Additional security checks
            if ($success) {
                // Check if challenge is too old (prevent replay attacks)
                if ($challengeTimestamp && $this->isChallengeExpired($challengeTimestamp)) {
                    $this->errorMessage = __('Security verification has expired. Please try again.');
                    Log::warning('Turnstile validation failed: Expired challenge', [
                        'challenge_timestamp' => $challengeTimestamp,
                        'ip' => $clientIp
                    ]);
                    return false;
                }

                // Verify hostname if configured
                if ($hostname && !$this->isValidHostname($hostname)) {
                    $this->errorMessage = __('Security verification failed: Invalid hostname.');
                    Log::warning('Turnstile validation failed: Invalid hostname', [
                        'hostname' => $hostname,
                        'expected' => request()->getHost(),
                        'ip' => $clientIp
                    ]);
                    return false;
                }

                // Cache the token to prevent replay (valid for 5 minutes)
                Cache::put($tokenCacheKey, true, 300);

                Log::info('Turnstile validation successful', [
                    'ip' => $clientIp,
                    'hostname' => $hostname,
                    'challenge_timestamp' => $challengeTimestamp
                ]);
            }

            return $success;

        } catch (\Exception $e) {
            $this->errorMessage = __('Security verification failed due to a technical error. Please try again.');
            
            Log::error('Turnstile validation exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'ip' => request()->ip()
            ]);
            
            // In case of exception, return based on fail-open configuration
            return config('turnstile.fail_open', false);
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage ?: __('The security verification failed. Please try again.');
    }

    /**
     * Get the client IP address with proper proxy handling.
     *
     * @return string
     */
    private function getClientIp(): string
    {
        // Check for shared internet/proxy
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        // Check for IP passed from proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Can contain multiple IPs, get the first one
            $forwardedIps = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($forwardedIps[0]);
        }
        // Check for IP from remote address
        elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        
        // Fallback to Laravel's request IP
        return request()->ip() ?: '127.0.0.1';
    }

    /**
     * Handle specific Turnstile error codes.
     *
     * @param array $errorCodes
     * @param string $clientIp
     */
    private function handleErrorCodes(array $errorCodes, string $clientIp): void
    {
        $errorMessages = [
            'missing-input-secret' => 'Security configuration error. Please contact support.',
            'invalid-input-secret' => 'Security configuration error. Please contact support.',
            'missing-input-response' => 'Please complete the security verification.',
            'invalid-input-response' => 'Security verification failed. Please try again.',
            'bad-request' => 'Invalid security verification request. Please refresh and try again.',
            'timeout-or-duplicate' => 'Security verification has expired or been used. Please try again.',
            'internal-error' => 'Security verification service error. Please try again.',
        ];

        $primaryError = $errorCodes[0] ?? 'unknown';
        $this->errorMessage = __($errorMessages[$primaryError] ?? 'Security verification failed. Please try again.');

        Log::warning('Turnstile validation failed with error codes', [
            'error_codes' => $errorCodes,
            'primary_error' => $primaryError,
            'ip' => $clientIp
        ]);

        // Log specific errors that might indicate configuration issues
        if (in_array($primaryError, ['missing-input-secret', 'invalid-input-secret'])) {
            Log::critical('Turnstile configuration error detected', [
                'error' => $primaryError,
                'site_key_configured' => !empty(env('TURNSTILE_SITE_KEY')),
                'secret_key_configured' => !empty(env('TURNSTILE_SECRET_KEY'))
            ]);
        }
    }

    /**
     * Check if the challenge timestamp is expired.
     *
     * @param string $timestamp
     * @return bool
     */
    private function isChallengeExpired(string $timestamp): bool
    {
        try {
            $challengeTime = \Carbon\Carbon::parse($timestamp);
            $maxAge = config('turnstile.max_challenge_age', 300); // 5 minutes default
            
            return $challengeTime->addSeconds($maxAge)->isPast();
        } catch (\Exception $e) {
            Log::warning('Failed to parse Turnstile challenge timestamp', [
                'timestamp' => $timestamp,
                'error' => $e->getMessage()
            ]);
            
            // If we can't parse the timestamp, consider it expired for security
            return true;
        }
    }

    /**
     * Validate the hostname from Turnstile response.
     *
     * @param string $hostname
     * @return bool
     */
    private function isValidHostname(string $hostname): bool
    {
        $allowedHosts = config('turnstile.allowed_hostnames', []);
        
        // If no allowed hosts configured, use the current request host
        if (empty($allowedHosts)) {
            $allowedHosts = [request()->getHost()];
        }

        // Normalize hostnames for comparison (remove www, convert to lowercase)
        $normalizedHostname = strtolower(preg_replace('/^www\./', '', $hostname));
        
        foreach ($allowedHosts as $allowedHost) {
            $normalizedAllowed = strtolower(preg_replace('/^www\./', '', $allowedHost));
            if ($normalizedHostname === $normalizedAllowed) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get debug information for troubleshooting.
     *
     * @return array
     */
    public static function getDebugInfo(): array
    {
        return [
            'site_key_configured' => !empty(env('TURNSTILE_SITE_KEY')),
            'secret_key_configured' => !empty(env('TURNSTILE_SECRET_KEY')),
            'fail_open_mode' => config('turnstile.fail_open', false),
            'max_challenge_age' => config('turnstile.max_challenge_age', 300),
            'allowed_hostnames' => config('turnstile.allowed_hostnames', []),
            'current_hostname' => request()->getHost(),
            'client_ip' => request()->ip(),
        ];
    }
}