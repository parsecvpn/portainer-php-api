<?php
namespace Portainer\Endpoints;
class Docker {
    private \Portainer\Portainer $portainer;

    public function __construct(\Portainer\Portainer $portainer){
        $this->portainer = $portainer;
    }

    /**
     * `gpus()` - Get the GPUs of a container
     * @param int $envId Environment ID
     * @param int $containerId Container ID
     * @return string|false Returns the GPUs or false
     */
    public function gpus(int $envId, int $containerId){
        $response = $this->portainer->sendRequest([], "endpoints/$envId/docker/containers/$containerId/gpus", "GET", false, true);
        if(isset($response["gpus"])){
            return $response["gpus"];
        } else {
            return false;
        }
    }

    /**
     * `images()` - Fetch images from the environment
     * @param int $envId Environment ID
     * @return array|bool Returns the images or false
     */
    public function images(int $envId){
        $response = $this->portainer->sendRequest([], "endpoints/$envId/docker/images", "GET", false, true);
        if(isset($response[0])){
            return $response;
        } else {
            return false;
        }
    }
}
