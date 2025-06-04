<?php
namespace Portainer\Endpoints;
class Gitops {
    private \Portainer\Portainer $portainer;

    public function __construct(\Portainer\Portainer $portainer){
        $this->portainer = $portainer;
    }

    /**
     * `preview()` - Preview a compose file based on git repository configuration
     * @param string $username
     * @param string $password
     * @param string $reference
     * @param string $repository
     * @param string $targetFile
     * @param bool $tlsskipverify
     * @return bool
     */
    public function preview(string $username, string $password, string $reference = "refs/heads/master", string $repository, string $targetFile, bool $tlsskipverify = false): bool{
        $response = $this->portainer->sendRequest(["username" => $username, "password" => $password, "reference" => $reference, "repository" => $repository, "targetFile" => $targetFile, "tlsskipverify" => $tlsskipverify], "gitops/preview", "POST", false, false);
        return !$response;
    }
}
