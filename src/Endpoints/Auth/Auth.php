<?php
namespace Portainer\Endpoints;
use Portainer\Endpoints\Auth\oAuth;
class Auth {
    public \Portainer\Portainer $portainer;

    private $oauth;

    public function __construct(\Portainer\Portainer $portainer){
        $this->portainer = $portainer;
        $this->loader();
    }

    public function loader(){
        require_once __DIR__ . "/oAuth.php";
    }

    public function auth($username, $password){
        $response = $this->portainer->sendRequest(["Username" => $username, "Password" => $password], "auth");
        if($response["jwt"]){
            return $response["jwt"];
        } else {
            return false;
        }
    }

    public function logout(){
        $response = $this->portainer->sendRequest([], "auth/logout");
        return (bool)$response;
    }

    public function oauth(){
        if($this->oauth) $this->oauth = new oAuth($this->portainer);
        return $this->oauth;
    }
}
