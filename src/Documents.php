<?php

namespace Autentique\AutentiqueSDK;

use Autentique\AutentiqueSDK\Utils\Query;
use Autentique\AutentiqueSDK\Utils\Api;

class Documents {
    private $token;

    private $api;

    private $query;

    private $sandbox;

    public function __construct(string $token = null, int $timeout = 60) {
        $this->token = $token;
        $this->api = new API("https://api.autentique.com.br/v2/graphql", $timeout);
        $this->query = new Query("documents");
    }

    public function viewDocument(string $documentId) {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables("docId", $documentId, $graphQuery);
        return $this->api->request($this->token, $graphQuery, "json");
    }
}   
