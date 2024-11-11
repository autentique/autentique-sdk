<?php

declare(strict_types= 1);

namespace Autentique\SDK;

use Autentique\SDK\Utils\Api;
use Autentique\SDK\Utils\Query;

class Folders {
    private $token;

    private $api;

    private $query;

    public function __construct(string $token = null, int $timeout = 60) {
        $this->token = $token;
        $this->api = new Api();
        $this->query = new Query("folders");
    }

    public function view(string $folderId) {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables("folderId", $folderId, $graphQuery);
        return $this->api->request($this->token, $graphQuery);
    }

    public function list(int $limit = 10, int $page = 1) {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables(["limit", "page"], [$limit, $page], $graphQuery);
        return $this->api->request($this->token, $graphQuery);
    }

    public function create(string $name) {
        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("name", $name, $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    public function update(string $folderId, string $name) {
        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables(["folderId", "name"], [$folderId, $name], $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    public function delete(string $folderId) {
        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("folderId", $folderId, $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }
}