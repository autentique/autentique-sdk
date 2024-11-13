<?php

namespace Autentique\SDK\Tests;

use Autentique\SDK\Utils\Query;

it("has invalid query file", function() { 
    $query = new Query("documents");
    $query->query("file");
})->throws(\Exception::class, "The file 'file' is not found");

it("has invalid resource", function() {
    $query = new Query("invalidDir");
    $query->query("file");
})->throws(\Exception::class,"The given resource directory does not exist");