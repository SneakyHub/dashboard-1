<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

trait ProtectcordTrait
{
    /**
     * Check IP address using Protectcord API
     *
     * @param string $ip
     * @param string $context ('login' or 'register')
     * @return bool
     * @throws ValidationException
     */
    protected function checkIpWithProtectcord($ip, $context = 'access')
    {
        $apiKey = env('PROTECTCORD_API_KEY');
        
        if (!$apiKey) {
            Log::error('Protectcord API key not configured', [
                'ip' => $ip,
                'context' => $context
            ]);
            
            throw ValidationException::withMessages([
                'email' => [
                    "Unable to verify your IP address due to configuration issues. Please contact support."
                ],
            ]);
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                ])
                ->get("https://api.protectcord.com/checkip/{$ip}");

            if ($response->failed()) {
                // Log API failure for debugging
                Log::error('Protectcord API request failed', [
                    'ip' => $ip,
                    'context' => $context,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                // Deny access if API fails
                throw ValidationException::withMessages([
                    'email' => [
                        "Unable to verify your IP address at this time. Please try again later or contact support if this issue persists."
                    ],
                ]);
            }

            $data = $response->json();

            // Check for blocked conditions
            $blockedReasons = [];
            
            if ($data['is_crawler'] ?? false) {
                $blockedReasons[] = 'automated crawlers';
            }
            if ($data['is_datacenter'] ?? false) {
                $blockedReasons[] = 'datacenter IPs';
            }
            if ($data['is_tor'] ?? false) {
                $blockedReasons[] = 'Tor networks';
            }
            if ($data['is_proxy'] ?? false) {
                $blockedReasons[] = 'proxy servers';
            }
            if ($data['is_vpn'] ?? false) {
                $blockedReasons[] = 'VPN connections';
            }
            if ($data['is_abuser'] ?? false) {
                $blockedReasons[] = 'known malicious activity';
            }

            if (!empty($blockedReasons)) {
                $reasonText = count($blockedReasons) > 1 
                    ? implode(', ', array_slice($blockedReasons, 0, -1)) . ' and ' . end($blockedReasons)
                    : $blockedReasons[0];
                
                $contextText = $context === 'register' ? 'registration' : $context;
                
                // Log the blocking for audit purposes
                Log::warning('User blocked by Protectcord', [
                    'ip' => $ip,
                    'context' => $context,
                    'reasons' => $blockedReasons,
                    'location' => $data['location']['country'] ?? 'Unknown'
                ]);
                
                throw ValidationException::withMessages([
                    'email' => [
                        "Sorry, we don't allow {$contextText} from {$reasonText}. " .
                        "If you believe this is an error, please contact our support team."
                    ],
                ]);
            }

            // Log successful check for audit purposes
            Log::info('Protectcord IP check passed', [
                'ip' => $ip,
                'context' => $context,
                'location' => $data['location']['country'] ?? 'Unknown',
                'company' => $data['company']['name'] ?? 'Unknown'
            ]);

            return true;

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Protectcord API unexpected error', [
                'ip' => $ip,
                'context' => $context,
                'error' => $e->getMessage()
            ]);
            
            throw ValidationException::withMessages([
                'email' => [
                    "Unable to verify your IP address due to a technical issue. Please try again later or contact support."
                ],
            ]);
        }
    }
}