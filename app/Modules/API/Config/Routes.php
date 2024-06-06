<?php
$routes->group("api", ["namespace" => "\Modules\API\Master\Controllers"], function ($routes) {
    $routes->match(["get", "post"], 'country-list', 'Country::index');
    $routes->match(["get", "post"], 'country-details', 'Country::countryDetail');
    $routes->post("country-add", "Country::countryAdd");
    $routes->match(["get", "post" , "delete"], "country-delete", "Country::countryDelete");
    $routes->match(["get", "post" ,"put"], "country-update", "Country::countryUpdate");
    $routes->match(["get", "post" ,"put"], "country-change-status", "Country::countryChangeStatus");

});
