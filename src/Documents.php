<?php

namespace Autentique\SDK;

use Autentique\SDK\Utils\Query;
use Autentique\SDK\Utils\Api;

class Documents {
    private $token;

    private $api;

    private $query;

    private $sandbox;

    public function __construct(string $token = null, int $timeout = 60) {
        $this->token = $token;
        $this->api = new API();
        $this->query = new Query("documents");
    }

    public function viewDocument(string $documentId) {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables("docId", $documentId, $graphQuery);
        return $this->api->request($this->token, $graphQuery, "json");
    }

    public function listDocuments(int $page = 1, int $limit = 30) {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables(["limit", "page"], [$limit, $page], $graphQuery);
        return $this->api->request($this->token, $graphQuery,"json");
    }

    public function listDocumentsByFolder(string $folderId, int $limit = 30, int $page = 1) {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables(["folderId", "limit", "page"], [$folderId, $limit, $page], $graphQuery);
        return $this->api->request($this->token, $graphQuery, "json");
    }
}   
