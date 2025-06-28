<?php

namespace App\Classes;

use App\Models\PhoenixPanel\Egg;
use App\Models\PhoenixPanel\Nest;
use App\Models\PhoenixPanel\Node;
use App\Models\Product;
use App\Models\Server;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Settings\PhoenixPanelSettings;
use App\Settings\ServerSettings;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PhoenixPanelClient
{
    //TODO: Extend error handling (maybe logger for more errors when debugging)

    private int $per_page_limit = 200;

    private int $allocation_limit = 200;

    public PendingRequest $client;

    public PendingRequest $application;

    public function __construct(PhoenixPanelSettings $phoenix_settings)
    {
        $server_settings = new ServerSettings();

        try {
            $this->client = $this->client($phoenix_settings);
            $this->application = $this->clientAdmin($phoenix_settings);
            $this->per_page_limit = $phoenix_settings->per_page_limit;
            $this->allocation_limit = $server_settings->allocation_limit;
        } catch (Exception $exception) {
            logger('Failed to construct PhoenixPanel client, Settings table not available?', ['exception' => $exception]);
        }
    }
    /**
     * @return PendingRequest
     */
    public function client(PhoenixPanelSettings $phoenix_settings)
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $phoenix_settings->user_token,
            'Content-type' => 'application/json',
            'Accept' => 'Application/vnd.phoenixpanel.v1+json',
        ])->baseUrl($phoenix_settings->getUrl() . 'api' . '/');
    }

    public function clientAdmin(PhoenixPanelSettings $phoenix_settings)
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $phoenix_settings->admin_token,
            'Content-type' => 'application/json',
            'Accept' => 'Application/vnd.phoenixpanel.v1+json',
        ])->baseUrl($phoenix_settings->getUrl() . 'api' . '/');
    }

    /**
     * @return HttpException
     */
    private function getException(string $message = '', int $status = null): HttpException
    {
        Log::Error('PhoenixPanelClient: ' . $message);
        if ($status == 404) {
            return new HttpException(404,'Resource does not exist on phoenixpanel - ' . $message . ' Was a Server deleted from PhoenixPanel but not from the Panel? Have an Admin Remove it from the Panel');
        }

        if ($status == 403) {
            return new HttpException(403, 'No permission on phoenixpanel, check phoenixpanel token and permissions - ' . $message);
        }

        if ($status == 401) {
            return new HttpException(401,'No phoenixpanel token set - ' . $message);
        }

        if ($status == 500) {
            return new HttpException(500,'PhoenixPanel server error - ' . $message);
        }

        if ($status == 0) {
            return new HttpException(500, 'Unable to connect to PhoenixPanel node - Please check if the node is online and accessible' . $message);
        }

        if ($status >= 500 && $status < 600) {
            return new HttpException($status,'PhoenixPanel node error (HTTP ' . $status . ') - ' . $message);
        }

        return new Exception('Request Failed, is phoenixpanel set-up correctly? - ' . $message);
    }

    /**
     * @param  Nest  $nest
     * @return mixed
     *
     * @throws Exception
     */
    public function getEggs(Nest $nest)
    {
        try {
            $response = $this->application->get("application/nests/{$nest->id}/eggs?include=nest,variables&per_page=" . $this->per_page_limit);
        } catch (Exception $e) {
            throw self::getException($e->getMessage());
        }
        if ($response->failed()) {
            throw self::getException('Failed to get eggs from phoenixpanel - ', $response->status());
        }

        return $response->json()['data'];
    }

    /**
     * @return mixed
     *
     * @throws Exception
     */
    public function getNodes()
    {
        try {
            $response = $this->application->get('application/nodes?per_page=' . $this->per_page_limit);
        } catch (Exception $e) {
            throw self::getException($e->getMessage());
        }
        if ($response->failed()) {
            throw self::getException('Failed to get nodes from phoenixpanel - ', $response->status());
        }

        return $response->json()['data'];
    }

    /**
     * @return mixed
     *
     * @throws Exception
     * @description Returns the infos of a single node
     */
    public function getNode($id)
    {
        try {
            $response = $this->application->get('application/nodes/' . $id);
        } catch (Exception $e) {
            throw self::getException($e->getMessage());
        }
        if ($response->failed()) {
            throw self::getException('Failed to get node id ' . $id . ' - ' . $response->status());
        }

        return $response->json()['attributes'];
    }

    public function getServers()
    {
        try {
            $response = $this->application->get('application/servers?per_page=' . $this->per_page_limit);
        } catch (Exception $e) {
            throw self::getException($e->getMessage());
        }
        if ($response->failed()) {
            throw self::getException('Failed to get list of servers - ', $response->status());
        }

        return $response->json()['data'];
    }

    /**
     * @return null
     *
     * @throws Exception
     */
    public function getNests()
    {
        try {
            $response = $this->application->get('application/nests?per_page=' . $this->per_page_limit);
        } catch (Exception $e) {
            throw self::getException($e->getMessage());
        }
        if ($response->failed()) {
            throw self::getException('Failed to get nests from phoenixpanel', $response->status());
        }

        return $response->json()['data'];
    }

    /**
     * @return mixed
     *
     * @throws Exception
     */
    public function getLocations()
    {
        try {
            $response = $this->application->get('application/locations?per_page=' . $this->per_page_limit);
        } catch (Exception $e) {
            throw self::getException($e->getMessage());
        }
        if ($response->failed()) {
            throw self::getException('Failed to get locations from phoenixpanel - ', $response->status());
        }

        return $response->json()['data'];
    }

    /**
     * @param  Node  $node
     * @return mixed
     *
     * @throws Exception
     */
    public function getFreeAllocationId(Node $node)
    {
        return self::getFreeAllocations($node)[0]['attributes']['id'] ?? null;
    }

    /**
     * @param  Node  $node
     * @return array|mixed|null
     *
     * @throws Exception
     */
    public function getFreeAllocations(Node $node)
    {
        $response = self::getAllocations($node);
        $freeAllocations = [];

        if (isset($response['data'])) {
            if (!empty($response['data'])) {
                foreach ($response['data'] as $allocation) {
                    if (!$allocation['attributes']['assigned']) {
                        array_push($freeAllocations, $allocation);
                    }
                }
            }
        }

        return $freeAllocations;
    }

    /**
     * @param  Node  $node
     * @return array|mixed
     *
     * @throws Exception
     */
    public function getAllocations(Node $node)
    {
        try {
            $response = $this->application->get("application/nodes/{$node->id}/allocations?per_page={$this->allocation_limit}");
        } catch (Exception $e) {
            throw self::getException($e->getMessage());
        }
        if ($response->failed()) {
            throw self::getException('Failed to get allocations from phoenixpanel - ', $response->status());
        }

        return $response->json();
    }

    /**
     * @param  Server  $server
     * @param  Egg  $egg
     * @param  int  $allocationId
     * @return Response
     */
    public function createServer(Server $server, Egg $egg, int $allocationId, mixed $eggVariables = null)
    {
       try {
            $response = $this->application->post('application/servers', [
                'name' => $server->name,
                'external_id' => $server->id,
                'user' => $server->user->phoenixpanel_id,
                'egg' => $egg->id,
                'docker_image' => $egg->docker_image,
                'startup' => $egg->startup,
                'environment' => $this->getEnvironmentVariables($egg, $eggVariables),
                'oom_disabled' => !$server->product->oom_killer,
                'limits' => [
                    'memory' => $server->product->memory,
                    'swap' => $server->product->swap,
                    'disk' => $server->product->disk,
                    'io' => $server->product->io,
                    'cpu' => $server->product->cpu,
                ],
                'feature_limits' => [
                    'databases' => $server->product->databases,
                    'backups' => $server->product->backups,
                    'allocations' => $server->product->allocations,
                ],
                'allocation' => [
                    'default' => $allocationId,
                ],
            ]);

            if ($response->failed()) {
                throw self::getException('Failed to create server on phoenixpanel', $response->status());
            }

            return $response;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function suspendServer(Server $server)
    {
        try {
            $response = $this->application->post("application/servers/$server->phoenixpanel_id/suspend");
        } catch (Exception $e) {
            throw self::getException($e->getMessage());
        }
        if ($response->failed()) {
            throw self::getException('Failed to suspend server from phoenixpanel - ', $response->status());
        }

        return $response;
    }

    public function unSuspendServer(Server $server)
    {
        try {
            $response = $this->application->post("application/servers/$server->phoenixpanel_id/unsuspend");
        } catch (Exception $e) {
            throw self::getException($e->getMessage());
        }
        if ($response->failed()) {
            throw self::getException('Failed to unsuspend server from phoenixpanel - ', $response->status());
        }

        return $response;
    }

    /**
     * Get user by phoenixpanel id
     *
     * @param  int  $phoenixpanelId
     * @return mixed
     */
    public function getUser(int $phoenixpanelId)
    {
        try {
            $response = $this->application->get("application/users/{$phoenixpanelId}");
        } catch (Exception $e) {
            throw self::getException($e->getMessage());
        }
        if ($response->failed()) {
            throw self::getException('Failed to get user from phoenixpanel - ', $response->status());
        }

        return $response->json()['attributes'];
    }

    /**
     * Get serverAttributes by phoenixpanel id
     *
     * @param  int  $phoenixpanelId
     * @return mixed
     */
    public function getServerAttributes(int $phoenixpanelId, bool $deleteOn404 = false)
    {
        try {
            $response = $this->application->get("application/servers/{$phoenixpanelId}?include=egg,node,nest,location");
        } catch (Exception $e) {
            throw self::getException($e->getMessage());
        }

        //print response body

        if ($response->failed()) {
            if ($deleteOn404) {  //Delete the server if it does not exist (server deleted on phoenixpanel)
                Server::where('phoenixpanel_id', $phoenixpanelId)->first()->delete();

                return;
            } else {
                throw self::getException('Failed to get server attributes from phoenixpanel - ', $response->status());
            }
        }

        return $response->json()['attributes'];
    }

    /**
     * Update Server Resources
     *
     * @param  Server  $server
     * @param  Product  $product
     * @return Response
     */
    public function updateServer(Server $server, Product $product)
    {
        return $this->application->patch("application/servers/{$server->phoenixpanel_id}/build", [
            'allocation' => $server->allocation,
            'memory' => $product->memory,
            'swap' => $product->swap,
            'disk' => $product->disk,
            'io' => $product->io,
            'cpu' => $product->cpu,
            'threads' => null,
            'oom_disabled' => !$server->product->oom_killer,
            'feature_limits' => [
                'databases' => $product->databases,
                'backups' => $product->backups,
                'allocations' => $product->allocations,
            ],
        ]);
    }

    /**
     * Update the owner of a server
     *
     * @param  int  $userId
     * @param  Server  $server
     * @return mixed
     */
    public function updateServerOwner(Server $server, int $userId)
    {
        return $this->application->patch("application/servers/{$server->phoenixpanel_id}/details", [
            'name' => $server->name,
            'user' => $userId,
        ]);
    }

    /**
     * Power Action Specific Server
     *
     * @param  Server  $server
     * @param  string  $action
     * @return Response
     */
    public function powerAction(Server $server, $action)
    {
        return $this->client->post("client/servers/{$server->identifier}/power", [
            'signal' => $action,
        ]);
    }

    /**
     * Get info about user
     */
    public function getClientUser()
    {
        return $this->client->get('client/account');
    }

    /**
     * Check if node has enough free resources to allocate the given resources
     *
     * @param  Node  $node
     * @param  int  $requireMemory
     * @param  int  $requireDisk
     * @return bool
     */
    public function checkNodeResources(Node $node, int $requireMemory, int $requireDisk)
    {
        try {
            $response = $this->application->get("application/nodes/{$node->id}");
        } catch (Exception $e) {
            throw self::getException($e->getMessage());
        }
        $node = $response['attributes'];
        $freeMemory = ($node['memory'] * ($node['memory_overallocate'] + 100) / 100) - $node['allocated_resources']['memory'];
        $freeDisk = ($node['disk'] * ($node['disk_overallocate'] + 100) / 100) - $node['allocated_resources']['disk'];
        if ($freeMemory < $requireMemory) {
            return false;
        }
        if ($freeDisk < $requireDisk) {
            return false;
        }

        return true;
    }

    private function getEnvironmentVariables(Egg $egg, $variables)
    {
        $environment = [];
        $variables = json_decode($variables, true);

        foreach ($egg->environment as $envVariable) {
            $matchedVariable = collect($variables)->firstWhere('env_variable', $envVariable['env_variable']);

            $environment[$envVariable['env_variable']] = $matchedVariable
                ? $matchedVariable['filled_value']
                : $envVariable['default_value'];
        }

        return $environment;
    }
}
