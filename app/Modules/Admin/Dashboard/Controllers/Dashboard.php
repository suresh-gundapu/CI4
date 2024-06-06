<?php

namespace Modules\Admin\Dashboard\Controllers;

use App\Models\AdminMenuModel;
use App\Models\AdminPasswordsModel;

use App\Libraries\General;
use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function __construct()
    {
        helper(["form", "url", "cookie"]);
    }

    public function dashboard()
    {
        if (session()->get('adminData')) {
            $general = new General();
            $menu_data = $general->getMenuArray("Sitemap");
            return view('Modules\Admin\Dashboard\Views\dashboard',$menu_data);
        } else {
            return view('Modules\Admin\User\Views\login');
        }
    }
 
}
