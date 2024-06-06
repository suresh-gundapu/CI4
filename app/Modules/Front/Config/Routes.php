<?php
$routes->group("users", ["namespace" => "\Modules\Front\User\Controllers"], function ($routes) {
    $routes->get('home', 'Users::index');
    $routes->get('', 'Users::login');
    $routes->get('dashboard', 'Users::dashboard');
    $routes->get('logout', 'Users::logout');
    $routes->get('forgot-pwd', 'Users::forgotPassword');
    $routes->post("check-user", "Users::processLogin");
    $routes->post("update-profile", "Users::processUpdateProfile");
    $routes->post("change-password", "Users::processChangePassword");
    $routes->post("check-user-email", "Users::processCheckEmail");
    $routes->post("test", "Users::test");
});
