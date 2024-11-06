<?php

namespace Autentique\SDK;

use Autentique\SDK\Utils\Api;
use Autentique\SDK\Utils\Query;

class Organizations {
    private $token;

    private $api;

    private $query;

    public function __construct(string $token = null, int $timeout = 60) {
        $this->token = $token;
        $this->api = new Api();
        $this->query = new Query("organizations");
    }

    public function viewOrganization() {
        $graphQuery = $this->query->query(__FUNCTION__);
        return $this->api->request($this->token, $graphQuery, "json");
    }

    public function listOrganizations() {
        $graphQuery = $this->query->query(__FUNCTION__);
        return $this->api->request($this->token, $graphQuery, "json");
    }
}