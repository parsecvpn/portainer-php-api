<?php
namespace Portainer\Endpoints;
class CustomTemplates {
    private \Portainer\Portainer $portainer;

    public function __construct(\Portainer\Portainer $portainer){
        $this->portainer = $portainer;
    }

    public function list(){
        $response = $this->portainer->sendRequest([], "custom_templates", "GET");
        if(isset($response[0])){
            return $response;
        } else {
            return false;
        }
    }

    public function delete(int $id){
        $response = $this->portainer->sendRequest([], "custom_templates/$id", "DELETE");
        return !$response["error"];
    }

    public function get(int $id){
        $response = $this->portainer->sendRequest([], "custom_templates/$id", "GET");
        if(isset($response["Id"])){
            return $response;
        } else {
            return false;
        }
    }

    public function update(array $data, int $id){
        $response = $this->portainer->sendRequest($data, "custom_templates/$id", "PUT", false, true);
        return !$response["Id"];
    }

    public function getStackFile(int $id){
        $response = $this->portainer->sendRequest([], "custom_templates/$id/file", "GET", false, true);
        if(isset($response["fileContent"])){
            return $response["fileContent"];
        } else {
            return false;
        }
    }

    public function gitFetch(int $id){
        $response = $this->portainer->sendRequest([], "custom_templates/$id/git_fetch", "GET", false, true);
        if(isset($response["fileContent"])){
            return $response["fileContent"];
        } else {
            return false;
        }
    }

    public function create(string $title, string $description, string $note, int $platform = 1, int $type = 2, string $file, string $logoUrl = null, string $variables = null){
        $response = $this->portainer->sendRequest([
            "title" => $title,
            "description" => $description,
            "note" => $note,
            "platform" => $platform,
            "type" => $type,
            "file" => $file,
            "logoUrl" => $logoUrl,
            "variables" => $variables
        ], "custom_templates/create/file", "POST");
        if(isset($response["Id"])){
            return $response;
        } else {
            return false;
        }
    }

    public function createRepository(array $data){
        $response = $this->portainer->sendRequest($data, "custom_templates/create/repository", "POST");
        if(isset($response["Id"])){
            return $response;
        } else {
            return false;
        }
    }

    public function createFromString(array $data){
        $response = $this->portainer->sendRequest($data, "custom_templates/create/string", "POST");
        if(isset($response["Id"])){
            return $response;
        } else {
            return false;
        }
    }
}
