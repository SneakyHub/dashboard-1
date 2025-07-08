<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ProtectCordService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('PROTECTCORD_API_KEY');
        if (empty($this->apiKey)) {
            throw new \Exception("ProtectCord API Key not set in .env");
        }
    }

    public function checkIp($ip)
    {
        // Verify IP format for both IPv4 and IPv6
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new \InvalidArgumentException("Invalid IP address: {$ip}");
        }

        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->withOptions([
                'timeout' => 5
            ])->get("https://api.protectcord.com/checkip/{$ip}");

            if ($response->failed()) {
                throw new \Exception("Failed to check IP with primary ProtectCord API");
            }
        } catch (\Exception $e) {
            // Fallback to secondary API endpoint
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->withOptions([
                'timeout' => 5
            ])->get("https://api2.protectcord.com/checkip/{$ip}");

            if ($response->failed()) {
                throw new \Exception("Failed to check IP with fallback ProtectCord API");
            }
        }
        $data = $response->json();
        // Perform checks for the specified flags
        if (
            $data['is_crawler'] ||
            $data['is_datacenter'] ||
            $data['is_tor'] ||
            $data['is_proxy'] ||
            $data['is_vpn'] ||
            $data['is_abuser']
        ) {
            return [
                'block' => true,
                'reasonText' => $this->createBlockMessage($data),
                'reasonCode' => $this->createBlockCode($data)
            ];
        }

        return ['block' => false];
    }

    private function createBlockMessage($responseData)
    {
        $reasons = [];
        if ($responseData['is_crawler']) {
            $reasons[] = "Detected web crawler";
        }
        if ($responseData['is_datacenter']) {
            $reasons[] = "Data center IP";
        }
        if ($responseData['is_tor']) {
            $reasons[] = "Tor network";
        }
        if ($responseData['is_proxy']) {
            $reasons[] = "Proxy IP";
        }
        if ($responseData['is_vpn']) {
            $reasons[] = "VPN IP";
        }
        if ($responseData['is_abuser']) {
            $reasons[] = "Previous abuse history";
        }

        return implode(", ", $reasons);
    }

    private function createBlockCode($responseData)
    {
        $codes = [];
        if ($responseData['is_crawler']) $codes[] = 'crawler';
        if ($responseData['is_datacenter']) $codes[] = 'datacenter';
        if ($responseData['is_tor']) $codes[] = 'tor';
        if ($responseData['is_proxy']) $codes[] = 'proxy';
        if ($responseData['is_vpn']) $codes[] = 'vpn';
        if ($responseData['is_abuser']) $codes[] = 'abuser';

        return strtoupper(implode("-", $codes));
    }
}