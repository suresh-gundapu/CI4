<?php
$routes->group("admin", ["namespace" => "\Modules\Admin\Dashboard\Controllers"], function ($routes) {
    $routes->get('dashboard', 'Dashboard::dashboard');
});

$routes->group("admin", ["namespace" => "\Modules\Admin\User\Controllers"], function ($routes) {
    $routes->get('/', 'Admin::login');
    $routes->get('user-profile', 'Admin::userProfile');
    $routes->get('logout', 'Admin::logout');
    $routes->get('forgot-pwd', 'Admin::forgotPassword');
    $routes->get('reset-pwd', 'Admin::resetPassword');
    $routes->post('reset-pwd-action', 'Admin::resetPasswordAction');
    $routes->post("check-user", "Admin::processLogin");
    $routes->post("update-profile", "Admin::processUpdateProfile");
    $routes->post("change-password", "Admin::processChangePassword");
    $routes->post("forgot-password-action", "Admin::forgotPasswordAction");
    $routes->get("otp-authentication", "Admin::otpAuthentication");
    $routes->post("authentication", "Admin::otpVerification");
    $routes->get("resend-otp/(:any)", "Admin::resendOtp/$1");
    $routes->get("try-another-way", "Admin::tryAnotherWay");
    $routes->get("test", "Admin::test");

});

$routes->group("admin", ["namespace" => "\Modules\Admin\Country\Controllers"], function ($routes) {
    $routes->get('country-listing', 'Country::index');
    $routes->post("ajax-load-data", "Country::CountryAjaxLoadData");
    $routes->get('country-add', 'Country::countryAdd');
    $routes->post('country-add-process', 'Country::countryAddProcess');
    $routes->get('country-edit/(:any)', 'Country::countryEdit/$1');
    $routes->post('country-edit-process', 'Country::countryEditProcess');
    $routes->post('country-status-change', 'Country::countryStatusChange');
    $routes->post('country-delete', 'Country::countryDelete');
});
