<?php
namespace Portainer\Endpoints;
class Motd {
    private \Portainer\Portainer $portainer;

    public function __construct(\Portainer\Portainer $portainer){
        $this->portainer = $portainer;
    }

    /**
     * `motd()` - Get the message of the day
     * @return array
     */
    public function motd(){
        $response = $this->portainer->sendRequest([], "motd", "GET", false, false);
        return $response;
    }
}
