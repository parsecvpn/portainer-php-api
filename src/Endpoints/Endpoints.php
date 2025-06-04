<?php
namespace Portainer\Endpoints;
class Endpoints {
    private \Portainer\Portainer $portainer;

    public function __construct(\Portainer\Portainer $portainer){
        $this->portainer = $portainer;
    }

    /**
     * `delete_endpoint()` - Remove multiple environments and optionally clean-up associated resources
     * @param array $body Request body
     * @return bool Returns true if the endpoint is deleted
     */
    public function delete_endpoints(array $body){
        $response = $this->portainer->sendRequest(["endpoints" => $body], "endpoints", "DELETE", false, false);
        return !$response;
    }

    /**
     * `get_endpoints()` - List all endpoints
     * @param array $body Filter settings
     * @return array
     */
    public function get_endpoints(array $body){
        $response = $this->portainer->sendRequest([$body], "endpoints", "GET", false, false);
        return $response;
    }

    /**
     * `create_endpoint()` - Create a new endpoint
     * @param array $body endpoint configuration
     * @return array
     */
    public function create_endpoint(array $body){
        $response = $this->portainer->sendRequest([$body], "endpoints", "POST", false, false);
        return $response;
    }

    /**
     * `delete_endpoint()` - Remove an endpoint
     * @param int $id Endpoint ID
     * @return bool Returns true if the endpoint is deleted
     */
    public function delete_endpoint(int $id){
        $response = $this->portainer->sendRequest(["id" => $id], "endpoints/{$id}", "DELETE", false, true);
        return !$response;
    }

    /**
     * `get_endpoint()` - Get an endpoint
     * @param int $id Endpoint ID
     * @return array
     */
    public function get_endpoint(int $id){
        $response = $this->portainer->sendRequest(["id" => $id], "endpoints/{$id}", "GET", false, true);
        return $response;
    }

    /**
     * `update_endpoint()` - Update an endpoint
     * @param int $id Endpoint ID
     * @param array $body endpoint configuration
     * @return array
     */
    public function update_endpoint(int $id, array $body){
        $response = $this->portainer->sendRequest(["id" => $id, "body" => $body], "endpoints/{$id}", "PUT", false, true);
        return $response;
    }

    /**
     * `fetch_endpoint_registry_limits()` - Retrieve endpoint registry limits
     * @param int $id Endpoint ID
     * @return array
     */
    public function fetch_endpoint_registry_limits(int $id, int $registryId){
        $response = $this->portainer->sendRequest(["id" => $id, "registryId" => $registryId], "endpoints/{$id}/dockerhub/{$registryId}", "GET", false, true);
        return $response;
    }

    /**
     * `update_endpoint_registry_limits()` - Update endpoint docker service
     * @param int $id Endpoint ID
     * @param int $serviceId service ID
     * @param array $body configuration
     * @return array
     */
    public function force_update_dockerService_endpoint(int $id, string $serviceId, bool $pullImage = true){
        $response = $this->portainer->sendRequest(["id" => $id, "serviceId" => $serviceId, "pullImage" => $pullImage], "endpoints/{$id}/forceupdateservice", "PUT", false, true);
        return $response;
    }

    /**
     * `list_endpoint_registries()` - List endpoint registries
     * @param int $id Endpoint ID
     * @param string $namespace
     * @return array
     */
    public function list_endpoint_registries(int $id, string $namespace = ""){
        $response = $this->portainer->sendRequest(["id" => $id, "namespace" => $namespace], "endpoints/{$id}/registries", "GET", false, true);
        return $response;
    }

    /**
     * `update_endpoint_settings()` - Update endpoint settings
     * @param int $id Endpoint ID
     * @param array $body configuration
     * @return array
     */
    public function update_endpoint_settings(int $id, array $body){
        $response = $this->portainer->sendRequest(["id" => $id, "body" => $body], "endpoints/{$id}/settings", "PUT", false, true);
        return $response;
    }

    /**
     * `snapshot_endpoint()` - Snapshot an endpoint
     * @param int $id Endpoint ID
     * @return bool
     */
    public function snapshot_endpoint(int $id){
        $response = $this->portainer->sendRequest(["id" => $id], "endpoints/{$id}/snapshot", "POST", false, true);
        return !$response;
    }

    /**
     * `snapshot_all_endpoints()` - Snapshot all endpoints
     * @return bool
     */
    public function snapshot_all_endpoints(){
        $response = $this->portainer->sendRequest([], "endpoints/snapshot", "POST", false, false);
        return !$response;
    }
}
