<?php

declare(strict_types= 1);

namespace Autentique\SDK;

use Autentique\SDK\Utils\Api;
use Autentique\SDK\Utils\LoadEnv;
use Autentique\SDK\Utils\Query;

class Folders {
    /**
     * @var string
     */
    private $token;

    /**
     * @var Api
     */
    private $api;

    /**
     * @var Query
     */
    private $query;

    /**
     * @param int $timeout
     */
    public function __construct(int $timeout = 60) {
        $env = new LoadEnv();
        $this->token = $env->getToken();
        $this->api = new Api($timeout);
        $this->query = new Query("folders");
    }

    /**
     * Data of the folder
     * @param string $folderId
     * @return array
     */
    public function view(string $folderId): array {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables("folderId", $folderId, $graphQuery);
        return $this->api->request($this->token, $graphQuery);
    }

    /**
     * List of folders
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function list(int $limit = 10, int $page = 1): array {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables(["limit", "page"], [$limit, $page], $graphQuery);
        return $this->api->request($this->token, $graphQuery);
    }

    /**
     * Create a folder
     * @param string $name
     * @return array
     */
    public function create(string $name): array {
        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("name", $name, $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    /**
     * Update a folder
     * @param string $folderId
     * @param string $name
     * @return array
     */
    public function update(string $folderId, string $name): array {
        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables(["folderId", "name"], [$folderId, $name], $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    /**
     * Delete a folder
     * @param string $folderId
     * @return array
     */
    public function delete(string $folderId): array {
        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("folderId", $folderId, $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }
}