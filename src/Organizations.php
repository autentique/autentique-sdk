<?php

declare(strict_types= 1);

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

    public function view() {
        $graphQuery = $this->query->query(__FUNCTION__);
        return $this->api->request($this->token, $graphQuery);
    }

    public function list() {
        $graphQuery = $this->query->query(__FUNCTION__);
        return $this->api->request($this->token, $graphQuery);
    }

    public function listGroups(int $organizationId, int $limit = 15, int $page = 1) {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables(["organizationId", "limit", "page"], [$organizationId, $limit, $page], $graphQuery);

        return $this->api->request($this->token, $graphQuery);
    }
}