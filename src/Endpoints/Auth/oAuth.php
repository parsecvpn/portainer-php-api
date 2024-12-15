<?php
namespace Portainer\Endpoints\Auth;
class oAuth {
    public \Portainer\Portainer $portainer;

    public function __construct(\Portainer\Portainer $portainer){
        $this->portainer = $portainer;
    }

    public function validate(string $code){
        $response = $this->portainer->sendRequest(["code" => $code], "auth/validate");
        if($response["jwt"]){
            return $response["jwt"];
        } else {
            return false;
        }
    
    }
}
