namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProtectCordService
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = env('PROTECTCORD_API_KEY');
        if (empty($this->apiKey)) {
            throw new \Exception('PROTECTCORD_API_KEY not set in .env');
        }
    }

    public function checkIp($ip)
    {
        if (!$this->isValidIp($ip)) {
            throw new \InvalidArgumentException('Invalid IP address provided.');
        }

        $apiEndpoints = [
            'https://api.protectcord.com/checkip/',
            'https://api2.protectcord.com/checkip/'
        ];

        foreach ($apiEndpoints as $endpoint) {
            try {
                Log::debug('ProtectCord API Request to: ' . $endpoint . ' for IP: ' . $ip);

                $response = Http::withHeaders($this->getHeaders())
                    ->get($endpoint . '/' . $ip);

                if ($response->successful()) {
                    $result = $response->json();

                    $flags = [
                        'is_tor'      => $result['is_tor'] ?? false,
                        'is_proxy'    => $result['is_proxy'] ?? false,
                        'is_vpn'      => $result['is_vpn'] ?? false,
                        'is_datacenter' => $result['is_datacenter'] ?? false,
                        'is_abuser'   => $result['is_abuser'] ?? false
                    ];

                    if (in_array(true, $flags)) {
                        $blockReason = implode(',', array_keys(array_filter($flags)));
                        Log::warning("Flagged IP [{$ip}]: Blocked due to " . $blockReason);

                        return [
                            'block' => true,
                            'reason' => $blockReason,
                            'message' => 'IP has been blocked'
                        ];
                    }

                    Log::debug('IP verification succeeded for: ' . $ip);

                    return [
                        'block' => false,
                        'message' => 'IP verified and allowed'
                    ];
                } else {
                    Log::notice('Unexpected return from ProtectCord API. Endpoint: ' . $endpoint . ' HTTP Status: ' . $response->status());
                }
            } catch (\Exception $e) {
                Log::error('Error while accessing ProtectCord API. Endpoint: ' . $endpoint . ' IP: ' . $ip . ' Error: ' . $e->getMessage());
                continue; // Try next endpoint
            }
        }

        throw new \Exception('Failed to verify IP with any available ProtectCord API endpoint');
    }

    private function isValidIp($ip)
    {
        return filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE );
    }

    private function getHeaders()
    {
        return [
            'X-API-Key'    => $this->apiKey,
            'Accept'       => 'application/json'
        ];
    }
}
