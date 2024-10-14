<?php


    $router = HyLRM::$router;
    $response = HyLRM::response();


    $router -> before(function () {

        // Validate sessions, status, etc.
        return true;

    }, function () use ($router, $response) {

        $router -> get("/", function () use ($response) {
            $response -> raw("Hello, World!");
        });


        $router -> get("/params/$", function ($pathname) use ($response) {
            $response -> raw($pathname);
        });


        $router -> get("/wildcard/*", function ($wildcard) use ($response) {
            $response -> raw($wildcard);
        });

    });


?>