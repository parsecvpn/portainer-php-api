<?php
namespace Portainer\Endpoints;
class Backup {
    private \Portainer\Portainer $portainer;

    public function __construct(\Portainer\Portainer $portainer){
        $this->portainer = $portainer;
    }

    /**
     * `backup()` - Create a backup of the Portainer instance
     * @param string $password Password to encrypt the backup
     * @return bool|string Returns the backup file path or false
     * @note Using this function may cause high memory usage (around ~300MB, depending on the size of your Portainer instance)
     */
    public function backup(string $password){
        $response = $this->portainer->downloadFile("backup", false, ["password" => $password]);
        if(!empty($response)){
            return $response;
        } else {
            return false;
        }
    }

    public function restore(){
        // not yet implemented
    }
}
