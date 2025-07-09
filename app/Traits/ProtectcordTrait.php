<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

trait ProtectcordTrait
{
    /**
     * Check IP address using Protectcord API with failover support
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

        // Define API endpoints in order of preference
        $endpoints = [
            'primary' => "https://api.protectcord.com/checkip/{$ip}",
            'secondary' => "https://api2.protectcord.com/checkip/{$ip}"
        ];

        $lastException = null;
        $lastResponse = null;

        foreach ($endpoints as $endpointName => $url) {
            try {
                Log::info("Attempting Protectcord API call", [
                    'endpoint' => $endpointName,
                    'url' => $url,
                    'ip' => $ip,
                    'context' => $context
                ]);

                $response = Http::timeout(10)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                    ])
                    ->get($url);

                if ($response->successful()) {
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
                            'location' => $data['location']['country'] ?? 'Unknown',
                            'endpoint_used' => $endpointName
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
                        'company' => $data['company']['name'] ?? 'Unknown',
                        'endpoint_used' => $endpointName
                    ]);

                    // Log failover usage if we're using secondary endpoint
                    if ($endpointName === 'secondary') {
                        Log::notice('Protectcord failover endpoint used successfully', [
                            'ip' => $ip,
                            'context' => $context,
                            'primary_endpoint_failed' => true
                        ]);
                    }

                    return true;
                } else {
                    // API returned non-success status code
                    Log::warning("Protectcord {$endpointName} endpoint failed", [
                        'ip' => $ip,
                        'context' => $context,
                        'endpoint' => $endpointName,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    
                    $lastResponse = $response;
                    
                    // Continue to next endpoint if available
                    continue;
                }

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // Network/connection errors - try next endpoint
                Log::warning("Protectcord {$endpointName} endpoint connection failed", [
                    'ip' => $ip,
                    'context' => $context,
                    'endpoint' => $endpointName,
                    'error' => $e->getMessage()
                ]);
                
                $lastException = $e;
                continue;
                
            } catch (\Illuminate\Http\Client\RequestException $e) {
                // HTTP request errors - try next endpoint
                Log::warning("Protectcord {$endpointName} endpoint request failed", [
                    'ip' => $ip,
                    'context' => $context,
                    'endpoint' => $endpointName,
                    'error' => $e->getMessage()
                ]);
                
                $lastException = $e;
                continue;
                
            } catch (ValidationException $e) {
                // User is blocked - don't try other endpoints, just re-throw
                throw $e;
                
            } catch (\Exception $e) {
                // Unexpected errors - try next endpoint
                Log::error("Protectcord {$endpointName} endpoint unexpected error", [
                    'ip' => $ip,
                    'context' => $context,
                    'endpoint' => $endpointName,
                    'error' => $e->getMessage()
                ]);
                
                $lastException = $e;
                continue;
            }
        }

        // If we get here, all endpoints failed
        Log::error('All Protectcord API endpoints failed', [
            'ip' => $ip,
            'context' => $context,
            'endpoints_tried' => array_keys($endpoints),
            'last_error' => $lastException ? $lastException->getMessage() : null,
            'last_status' => $lastResponse ? $lastResponse->status() : null
        ]);
        
        throw ValidationException::withMessages([
            'email' => [
                "Unable to verify your IP address at this time. Please try again later or contact support if this issue persists."
            ],
        ]);
    }
}