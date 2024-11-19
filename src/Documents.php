<?php

declare(strict_types= 1);

namespace Autentique\SDK;

use Autentique\SDK\Utils\LoadEnv;
use Autentique\SDK\Utils\Query;
use Autentique\SDK\Utils\Api;

class Documents {
    private $token;

    private $sandbox;

    private $api;

    private $query;

    public function __construct(int $timeout = 60) {
        $env = new LoadEnv();
        $this->sandbox = $env->useSandbox();
        $this->token = $env->getToken();
        $this->api = new Api();
        $this->query = new Query("documents");
    }

    public function view(string $documentId) {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables("docId", $documentId, $graphQuery);
        return $this->api->request($this->token, $graphQuery);
    }

    public function list(int $page = 1, int $limit = 30) {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables(["limit", "page", "sandbox"], [$limit, $page, json_encode($this->sandbox)], $graphQuery);
        return $this->api->request($this->token, $graphQuery);
    }

    public function listByFolder(string $folderId, int $limit = 30, int $page = 1) {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables(["folderId", "limit", "page", "sandbox"], [$folderId, $limit, $page, json_encode($this->sandbox)], $graphQuery);
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

    public function update(string $id, array $attributes) {
        $variables = [
            "id" => $id,
            "document"=> $attributes
        ];

        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("variables", json_encode($variables), $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    public function delete(string $id) {
        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("id", $id, $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    public function move(string $documentId, string|null $folderId) {
        $variables = [
            "documentId" => $documentId,
            "folderId" => $folderId
        ];

        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("variables", json_encode($variables), $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    public function transfer(string $documentId, int $organizationId, int $groupId) {
        $variables = [
            "documentId" => $documentId,
            "organizationId" => $organizationId,
            "groupId" => $groupId,
        ];

        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("variables", json_encode($variables), $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    public function sign(string $documentId) {
        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("documentId", $documentId, $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    public function createSigner(string $documentId, array $signer) {
        $variables = [
            "documentId" => $documentId,
            "signer" => $signer
        ];

        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("variables", json_encode($variables), $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    public function deleteSigner(string $signerId, string $documentId) {
        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables(["signerId", "documentId"], [$signerId, $documentId], $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }
}   
