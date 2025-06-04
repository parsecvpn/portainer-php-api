<?php
namespace Portainer\Endpoints;
class Registries {
    private \Portainer\Portainer $portainer;

    public function __construct(\Portainer\Portainer $portainer){
        $this->portainer = $portainer;
    }

    /**
     * `get_registries()` - Get all registries
     * @return array
     */
    public function get_registries(){
        $response = $this->portainer->sendRequest([], "registries", "GET", false, false);
        return $response;
    }

    /**
     * `create_registry()` - Create a registry
     * @param array $body Registry configuration
     * @return array
     */
    public function create_registry(array $body){
        $response = $this->portainer->sendRequest([$body], "registries", "POST", false, false);
        return $response;
    }

    /**
     * `delete_registry()` - Delete a registry
     * @param int $id Registry ID
     * @return bool
     */
    public function delete_registry(int $id){
        $response = $this->portainer->sendRequest([], "registries/" . $id, "DELETE", false, true);
        return $response;
    }

    /**
     * `get_registry()` - Get a registry
     * @param int $id Registry ID
     * @return array
     */
    public function get_registry(int $id){
        $response = $this->portainer->sendRequest([], "registries/" . $id, "GET", false, true);
        return $response;
    }

    /**
     * `update_registry()` - Update a registry
     * @param int $id Registry ID
     * @param array $body Registry configuration
     * @return array
     */
    public function update_registry(int $id, array $body){
        $response = $this->portainer->sendRequest([$body], "registries/" . $id, "PUT", false, true);
        return $response;
    }

    /**
     * `configure_registry()` - Configure a registry
     * @param int $id Registry ID
     * @param array $body Registry configuration
     * @return bool
     */
    public function configure_registry(int $id, array $body){
        $response = $this->portainer->sendRequest([$body], "registries/" . $id . "/configure", "POST", false, true);
        return !$response;
    }
}
