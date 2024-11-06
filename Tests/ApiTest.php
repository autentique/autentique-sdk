<?php

namespace Autentique\SDK\Tests;

use Autentique\SDK\Utils\Api;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase {
    private $api;

    public function setUp() : void{
        $this->api = new Api();
    }

    public function testNoToken() {
        $this->expectExceptionMessage("Token field is empty");
        $this->api->request("","query","json");
    }

    public function testNoQuery() {
        $this->expectExceptionMessage("Query is empty");
        $this->api->request("token", "", "json");
    }
}