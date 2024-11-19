<?php

declare(strict_types= 1);

namespace Autentique\SDK;

use Autentique\SDK\Utils\LoadEnv;
use Autentique\SDK\Utils\Query;
use Autentique\SDK\Utils\Api;

class Documents {
    /**
     * @var string
     */
    private $token;

    /**
     * @var boolean
     */
    private $sandbox;

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
        $this->sandbox = $env->useSandbox();
        $this->token = $env->getToken();
        $this->api = new Api($timeout);
        $this->query = new Query("documents");
    }

    /**
     * Show the document data
     * @param string $documentId
     * @return array
     */
    public function view(string $documentId): array {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables("docId", $documentId, $graphQuery);
        return $this->api->request($this->token, $graphQuery);
    }

    /**
     * List all the documents
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function list(int $page = 1, int $limit = 30): array {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables(["limit", "page", "sandbox"], [$limit, $page, json_encode($this->sandbox)], $graphQuery);
        return $this->api->request($this->token, $graphQuery);
    }

    /**
     * List all the documents inside a folder
     * @param string $folderId
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function listByFolder(string $folderId, int $limit = 30, int $page = 1): array {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables(["folderId", "limit", "page", "sandbox"], [$folderId, $limit, $page, json_encode($this->sandbox)], $graphQuery);
        return $this->api->request($this->token, $graphQuery);
    }

    /**
     * Create a document
     * @param array $attributes
     * @return array
     */
    public function create(array $attributes): array {
        $variables = [
            "document" => $attributes["document"],
            "signers" => $attributes["signers"],
            "sandbox" => $this->sandbox
        ];

        switch ($attributes) {
            case (isset($attributes["folder"]) && !empty($attributes["folder"])) && (isset($attributes["organizationId"]) && !empty($attributes["organizationId"])):
                $variables["folder"] = $attributes["folder"];
                $variables["organizationId"] = $attributes["organizationId"];
                break;
            case isset($attributes["folder"]) && !empty($attributes["folder"]):
                $variables["folder"] = $attributes["folder"];
                break;
            case isset($attributes["organizationId"]) && !empty($attributes["organizationId"]):
                $variables["organizationId"] = $attributes["organizationId"];
                break;
        }
        
        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("variables", json_encode($variables), $graphMutation);

        return $this->api->request($this->token, $graphMutation, "form", $attributes["file"]);
    }

    /**
     * Update a document
     * @param string $id
     * @param array $attributes
     * @return array
     */
    public function update(string $id, array $attributes): array {
        $variables = [
            "id" => $id,
            "document"=> $attributes
        ];

        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("variables", json_encode($variables), $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    /**
     * Delete a document
     * @param string $id
     * @return array
     */
    public function delete(string $id): array {
        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("id", $id, $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    /**
     * Move a document to a folder
     * @param string $documentId
     * @param string|null $folderId
     * @return array
     */
    public function move(string $documentId, string|null $folderId): array {
        $variables = [
            "documentId" => $documentId,
            "folderId" => $folderId
        ];

        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("variables", json_encode($variables), $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    /**
     * Transfer the document to another group
     * @param string $documentId
     * @param int $organizationId
     * @param int $groupId
     * @return array
     */
    public function transfer(string $documentId, int $organizationId, int $groupId): array {
        $variables = [
            "documentId" => $documentId,
            "organizationId" => $organizationId,
            "groupId" => $groupId,
        ];

        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("variables", json_encode($variables), $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    /**
     * Sign a document
     * @param string $documentId
     * @return array
     */
    public function sign(string $documentId): array {
        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("documentId", $documentId, $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    /**
     * Create a signer in a document
     * @param string $documentId
     * @param array $signer
     * @return array
     */
    public function createSigner(string $documentId, array $signer): array {
        $variables = [
            "documentId" => $documentId,
            "signer" => $signer
        ];

        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables("variables", json_encode($variables), $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }

    /**
     * Delete a signer from a document
     * @param string $signerId
     * @param string $documentId
     * @return array
     */
    public function deleteSigner(string $signerId, string $documentId): array {
        $graphMutation = $this->query->query(__FUNCTION__);
        $graphMutation = $this->query->setVariables(["signerId", "documentId"], [$signerId, $documentId], $graphMutation);

        return $this->api->request($this->token, $graphMutation);
    }
}   
