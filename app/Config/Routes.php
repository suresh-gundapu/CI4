<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('\Modules\Admin\User\Controllers');
$routes->setDefaultController('Admin');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
/* 

include modules routes path

*/
if (file_exists(APPPATH . 'Modules')) {

    $uri = service('uri'); // Loading 'uri' service

    if (APPPATH . 'Modules') {

        $modulesPath = APPPATH . 'Modules/';

        $modulesList = scandir($modulesPath);

        if ($uri->getSegment(1) == "admin") {

            $modulesPathAdmin = APPPATH . 'Modules/Admin';

            $AdminModulesList = scandir($modulesPathAdmin);
          //  print_r($AdminModulesList);exit;
            foreach ($AdminModulesList as $module) {

                if ($module === '.' || $module === '..') {

                    continue;
                }
                if (is_dir($modulesPathAdmin) . '/' . $module) {

                    $routePath = $modulesPathAdmin . '/' . $module . '/Routes.php';
                    if (!is_file($routePath)) {

                        continue;
                    }

                    require($routePath);
                }
            }
        } else if ($uri->getSegment(1) == "users") {

            $modulesPathUser = APPPATH . 'Modules/Front';

            $UserModulesList = scandir($modulesPathUser);

            foreach ($UserModulesList as $module) {

                if ($module === '.' || $module === '..') {

                    continue;
                }
                if (is_dir($modulesPathUser) . '/' . $module) {

                    $routePath = $modulesPathUser . '/' . $module . '/Routes.php';
                    if (!is_file($routePath)) {

                        continue;
                    }
                    require($routePath);
                }
            }
        }
        else if ($uri->getSegment(1) == "api") {

            $modulesPathAPI = APPPATH . 'Modules/API';

            $APIModulesList = scandir($modulesPathAPI);

            foreach ($APIModulesList as $module) {

                if ($module === '.' || $module === '..') {

                    continue;
                }
                if (is_dir($modulesPathAPI) . '/' . $module) {

                    $routePath = $modulesPathAPI . '/' . $module . '/Routes.php';
                    if (!is_file($routePath)) {

                        continue;
                    }
                    require($routePath);
                }
            }
        }
    }
}
