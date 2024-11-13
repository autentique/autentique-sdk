<?php

namespace Autentique\SDK\Tests;

use Autentique\SDK\Utils\Api;

it("has no token", function() {
    $api = new Api();
    $api->request("", "query");
})->throws(\Exception::class, "Token field is empty");

it("has no query", function() {
    $api = new Api();
    $api->request("token", "");
})->throws(\Exception::class,"Query is empty");

it("has invalid format", function() {
    $api = new Api();
    $api->request("token", "query", "invalidType");
})->throws(\Exception::class,"Content type doesn't exists");