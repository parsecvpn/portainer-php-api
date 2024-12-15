<?php 
namespace Portainer;
use Portainer\Helper\Log;
use Portainer\Exceptions\InvalidCredentialsException;
use \Dotenv\Dotenv;
use MirazMac\DotEnv\Writer;

# Endpoints
use Portainer\Endpoints\Auth;
use Portainer\Endpoints\Backup;
use Portainer\Endpoints\CustomTemplates;
use Portainer\Endpoints\Docker;
class Portainer {

    private $username;
    private $password;
    private $jwt;
    private $host;


    private $auth;
    private $backup;
    private $customTemplates;
    private $docker;

    public function __construct(string $envPath, string $envName , string $username = null, string $password = null, string $host = null){
        $this->loader();
        $this->loadConf($envPath, $envName, $username, $password, $host);
    }

    public function loadConf(string $path, string $envName = null, string $username = null, string $password = null, string $host = null){
     
        if(isset($username) && isset($password) && isset($host)){
            $this->username = $username;
            $this->password = $password;
            $this->host = $host;
        }else{
            $env = \Dotenv\Dotenv::createUnsafeImmutable($path, $envName);
            $env->load();

            $env->required("USERNAME");
            $env->required("PASSWORD");
            $env->required("HOST");
    
            $this->username = @getenv("USERNAME");
            $this->password = @getenv("PASSWORD");
            $this->host = @getenv("HOST");
        }

        $this->jwt = $this->login();
    }

    public function loader(){
        require_once dirname(__DIR__) . '/vendor/autoload.php';
        require_once __DIR__ . "/Helper/Log.php";
        require_once __DIR__ . "/Exceptions/InvalidCredentials.exceptions.php";
        require_once __DIR__ . "/Endpoints/Auth/Auth.php";
        require_once __DIR__ . "/Endpoints/Backup/Backup.php";
        require_once __DIR__ . "/Endpoints/CustomTemplates/CustomTemplates.php";
        require_once __DIR__ . "/Endpoints/Docker/Docker.php";
    }

    public function login(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->host . "/api/auth");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array("Username" => $this->username, "Password" => $this->password)));
        // disable ssl check
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        $result = json_decode($result);
        if(isset($result->jwt)){
            return $result->jwt;
        }elseif(isset($result->message)){
            throw new Exceptions\InvalidCredentialsException($result->message);
        }
    }

    /**
     * `sendRequest()` - Send a request to the Portainer API.
     * @param array $data The data to send to the API
     * @param string $endpoint The endpoint to send the data to, e.g. "backup"
     * @param mixed $method The HTTP method to use. Default is "POST".
     * @param mixed $skip Set to `true` to skip the authentication URI append.
     * @param mixed $bypass Set to `true` to bypass the endpoint check allowing to access not (yet) implemented methods.
     * @return array Returns the response from the API or an error (["status" => "error"]) as an array.
     */
    public function sendRequest(array $data, $endpoint, $method = "POST", $skip = false, $bypass = false){
        $c = curl_init();
        $endpoint = $this->prepareEndpoint($endpoint, $bypass);
        switch($method){
            case "POST":
                curl_setopt($c, CURLOPT_URL, $endpoint);
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($c, CURLOPT_POST, true);
                curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case "GET":
                $data = http_build_query($data);
                curl_setopt($c, CURLOPT_URL, $endpoint . "?" . $data);
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                break;
            default:
                $data = http_build_query($data);
                curl_setopt($c, CURLOPT_URL, $endpoint);
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($c, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($data));
                break;
        }
        if(!$skip){
            curl_setopt($c, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->jwt
            ));
        }
        // disable ssl check
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        $result = ["status" => "error", "error" => "Unknown error"];
        try {
            $response = curl_exec($c);
            if(!$response){
                Log::error_rep("Failed to send request: " . curl_error($c));
                $result = ["status" => "error", "error" => curl_error($c)];
            }
        } catch (\Throwable $e){
            Log::error_rep("Failed to send request: " . $e->getMessage());
            $result = ["status" => "error", "error" => $e->getMessage()];
        }
        curl_close($c);
        if($this->checkResponse($response)){
            Log::error_rep("Successfully accessed endpoint: " . $endpoint);
            $result = json_decode($response, true);
        }
        return $result;
    }

    public function downloadFile($endpoint, $bypass, $data): string {
        $c = curl_init();
        $endpoint = $this->prepareEndpoint($endpoint, $bypass);
    
        curl_setopt_array($c, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->jwt,
            ],
        ]);
    
        $response = curl_exec($c);
    
        if ($response === false) {
            $error = curl_error($c);
            curl_close($c);
            throw new \Exception("cURL error: $error");
        }
    
        $headerSize = curl_getinfo($c, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
    
        curl_close($c);
        $isOctetStream = strpos($headers, 'Content-Type: application/octet-stream') !== false;
    
        if ($isOctetStream || !empty($body)) {
            $fileName = "portainer_" . bin2hex(random_bytes(8)) . ".bin";
            $filePath = __DIR__ . "/Helper/data/downloads/" . $fileName;
    
            if (file_put_contents($filePath, $body) === false) {
                throw new \Exception("Failed to save the downloaded file.");
            }
    
            Log::error_rep("Downloaded file: " . $filePath . " from endpoint: " . $endpoint);
            return $filePath;
        }
        Log::error_rep("Unexpected response format: " . $body);
        throw new \Exception("API did not return a downloadable file.");
    }
    

    public function checkResponse(string $response){
        if(is_null($response)){
            return false;
        } else {
            $re = json_decode($response);
            return $re != null || $re !== false || @$re->message != null;
        }
    }

    private function prepareEndpoint($endpoint, $bypass = false){
        if($bypass){
            return $this->host . "/api/" . $endpoint;
        }
        $endpoints = json_decode(file_get_contents(__DIR__ . "/Helper/endpoints.json"));

        if(in_array($endpoint, $endpoints)){
            return $this->host . "/api/" . $endpoint;
        } else {
            return false;
        }
    }

    public function auth(){
        if(!$this->auth) $this->auth = new Auth($this);
        return $this->auth;
    }

    public function backup(){
        if(!$this->backup) $this->backup = new Backup($this);
        return $this->backup;
    }

    public function customTemplates(){
        if(!$this->customTemplates) $this->customTemplates = new CustomTemplates($this);
        return $this->customTemplates;

    }

    public function docker(){
        if(!$this->docker) $this->docker = new Docker($this);
        return $this->docker;
    }
}