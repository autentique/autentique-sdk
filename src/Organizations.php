<?php

declare(strict_types=1);

namespace Autentique\SDK;

use Autentique\SDK\Utils\Api;
use Autentique\SDK\Utils\LoadEnv;
use Autentique\SDK\Utils\Query;

class Organizations
{

    /** 
     * @var string 
     */
    private $token;

    /**
     *  @var Api 
     */
    private $api;

    /**
     *  @var Query
     */
    private $query;

    /** 
     * @param int $timeout 
     */
    public function __construct(int $timeout = 60)
    {
        $env = new LoadEnv();
        $this->token = $env->getToken();
        $this->api = new Api();
        $this->query = new Query("organizations");
    }

    /**
     * Show the current organization data
     * @return array
     */
    public function view()
    {
        $graphQuery = $this->query->query(__FUNCTION__);
        return $this->api->request($this->token, $graphQuery);
    }

    /**
     * List all the organizations and they data
     * @return array
     */
    public function list()
    {
        $graphQuery = $this->query->query(__FUNCTION__);
        return $this->api->request($this->token, $graphQuery);
    }

    /**
     * List all groups of the organization
     * @param int $organizationId
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function listGroups(int $organizationId, int $limit = 15, int $page = 1)
    {
        $graphQuery = $this->query->query(__FUNCTION__);
        $graphQuery = $this->query->setVariables(["organizationId", "limit", "page"], [$organizationId, $limit, $page], $graphQuery);

        return $this->api->request($this->token, $graphQuery);
    }
}