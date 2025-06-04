<?php
namespace Portainer\Endpoints;
class LDAP {
    private \Portainer\Portainer $portainer;

    public function __construct(\Portainer\Portainer $portainer){
        $this->portainer = $portainer;
    }

    /**
     * `check()` - Check LDAP settings
     * @param array $body LDAP settings
     * @return bool
     */
    public function check(array $body){
        $response = $this->portainer->sendRequest([$body], "ldap/check", "POST", false, false);
        return $response;
    }
}
