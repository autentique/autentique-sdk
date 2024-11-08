<?php

declare(strict_types= 1);

namespace Autentique\SDK;

use Autentique\SDK\Utils\Query;
use Autentique\SDK\Utils\Api;

class Documents {
    private $token;

    private $api;

    private $query;

    private $sandbox;

    public function __construct(string $token = null, int $timeout = 60, bool $sandbox = false) {
        $this->sandbox = $sandbox;
        $this->token = $token;
        $this->api = new Api();
        $this->query = new Query("documents");
    }

    public function viewDocument(string $documentId) {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables("docId", $documentId, $graphQuery);
        return $this->api->request($this->token, $graphQuery);
    }

    public function listDocuments(int $page = 1, int $limit = 30) {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables(["limit", "page"], [$limit, $page], $graphQuery);
        return $this->api->request($this->token, $graphQuery);
    }

    public function listDocumentsByFolder(string $folderId, int $limit = 30, int $page = 1) {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables(["folderId", "limit", "page"], [$folderId, $limit, $page], $graphQuery);
        return $this->api->request($this->token, $graphQuery);
    }

    public function create(array $attributes) {
        $queryFile = __FUNCTION__;
        
        $variables = [
            "document" => $attributes["document"],
            "signers" => $attributes["signers"],
            "sandbox" => $this->sandbox
        ];

        switch ($attributes) {
            case (isset($attributes["folder"]) && !empty($attributes["folder"])) && (isset($attributes["organizationId"]) && !empty($attributes["organizationId"])):
                $variables["folder"] = $attributes["folder"];
                $variables["organizationId"] = $attributes["organizationId"];
                $queryFile = __FUNCTION__ . "InFolderWithOrganization";
                break;
            case isset($attributes["folder"]) && !empty($attributes["folder"]):
                $variables["folder"] = $attributes["folder"];
                $queryFile = __FUNCTION__ . "InFolder";
                break;
            case isset($attributes["organizationId"]) && !empty($attributes["organizationId"]):
                $variables["organizationId"] = $attributes["organizationId"];
                $queryFile = __FUNCTION__ . "WithOrganization";
                break;
        }
        
        $graphMutation = $this->query->query($queryFile);
        $graphMutation = $this->query->setVariables("variables", json_encode($variables), $graphMutation);

        return $this->api->request($this->token, $graphMutation, "form", $attributes["file"]);
    }

}   
