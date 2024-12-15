# Portainer PHP API

Portainer PHP API client
For the full API documentation implemented, please take a look at [https://app.swaggerhub.com/apis/portainer/portainer-ce/2.21.4#/](https://app.swaggerhub.com/apis/portainer/portainer-ce/2.21.4#/)

## Supported endpoint groups

- Auth
- Backup
- CustomTemplates
- Docker

## Installation

Download the package using composer:

```bash

composer require ente/portainer-php-api

```

Then configure .env file with the following variables:

- API_URL (e.g. `localhost:9443`)
- USERNAME
- PASSWORD
- IGNORE_SSL (currently all requests are made with `verify` set to `false`)

## Usage

```php
require_once __DIR__ . "/vendor/autoload.php";
use Portainer\Portainer;
$portainer = new Portainer(__DIR__, ".env", "username", "password", "https://yourhost:9443");

echo var_dump($portainer->customTemplates()->list()); // array containing custom templates

```
