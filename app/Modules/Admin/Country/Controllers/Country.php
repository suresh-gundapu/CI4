<?php

namespace Modules\Admin\Country\Controllers;

use App\Models\AdminMenuModel;
use App\Models\AdminPasswordsModel;

use \Modules\API\Master\Controllers\Country as CountryController;

use App\Libraries\General;
use App\Controllers\BaseController;

class Country extends BaseController
{
    protected $db;
    protected $countryController;
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper(["form", "url", "cookie"]);
        $this->countryController = new CountryController();
    }
    public function index()
    {
        if (session()->get('adminData')) {
            
            $data = array();
            $general = new General();
            $menu_data = $general->getMenuArray("Sitemap");
            $data['parent_arr'] = $menu_data['parent_arr'];
            $data['menu_arr'] = $menu_data['menu_arr'];
            $data['home_arr'] = $menu_data['home_arr'];
            $data['profile_arr'] = $menu_data['profile_arr'];
            $data['password_arr'] = $menu_data['password_arr'];
            $data['logout_arr'] = $menu_data['logout_arr'];
            return view('Modules\Admin\Country\Views\country_listing', $data);
        } else {
            return view('Modules\Admin\User\Views\login');
        }
    }
    public function countryAjaxLoadData()
    {
        $columnIndex = $this->request->getPost()['order'][0]['column']; // Column index
        $columnName = $this->request->getPost()['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $this->request->getPost()['order'][0]['dir']; // asc or desc
        $sort_params = [
            "column_name" => $columnName,
            "column_sort_order" => $columnSortOrder
        ];
        $params['draw'] = $_REQUEST['draw'];
        $start = $_REQUEST['start'];
        $length = $_REQUEST['length'];
        /* Value we will get from typing in search */
        $search_value = $_REQUEST['search']['value'];
        $form_params =  [
            "filters" => "",
            "limit" => $length,
            "page" => $start,
            "keyword" => $search_value,
            "sort" => $sort_params
        ];

        $posts_data =  $this->countryController->countryListing($form_params);

        //  print_r(json_decode($posts_data->getBody(), true));exit;
        $posts_data_result = json_decode($posts_data->getBody(), true);

        foreach ($posts_data_result['data'] as $k => $v) {
            if ($v['status'] == "Active") {
                $btn = "btn-success";
            } else {
                $btn = "btn-warning";
            }
            $id = $v['country_id'];
            $posts_data_result['data'][$k]['number_state'] = "--";
            $posts_data_result['data'][$k]['status_c'] = '<button class="btn ' . $btn . '" onClick="changeStatus(' . $id . ')">' . $v['status'] . '</button>';
            $posts_data_result['data'][$k]['action'] = '<a href="country-edit/' . $v['country_id'] . '">
            <button class="btn btn-primary">
                Edit
            </button> 
        </a> <button class="btn btn-danger" onClick="processDelete(' . $v['country_id'] . ')">
                Delete
             </button>';
        }
        $json_data = array(
            "draw" => intval($params['draw']),
            "recordsTotal" => $posts_data_result['settings']['count'],
            "recordsFiltered" => $posts_data_result['settings']['count'],
            "data" => $posts_data_result['data']   // total data array
        );
         // print_r (json_encode($json_data));exit;
        echo json_encode($json_data);
    }

    public function countryAdd()
    {
        if (session()->get('adminData')) {
            $data = array();
            $general = new General();
            $menu_data = $general->getMenuArray("Sitemap");
            $data['parent_arr'] = $menu_data['parent_arr'];
            $data['menu_arr'] = $menu_data['menu_arr'];
            $data['home_arr'] = $menu_data['home_arr'];
            $data['profile_arr'] = $menu_data['profile_arr'];
            $data['password_arr'] = $menu_data['password_arr'];
            $data['logout_arr'] = $menu_data['logout_arr'];

            return view('Modules\Admin\Country\Views\country_add', $data);
        } else {
            return view('Modules\Admin\User\Views\login');
        }
    }
    public function countryAddProcess()
    {
        if ($this->request->getPost()) {
            $data = [
                "country_name" => $this->request->getVar("country_name"),
                "country_code" => $this->request->getVar("country_code"),
                "country_code_iso3" => $this->request->getVar("country_code_iso3"),
                "description" => $this->request->getVar("country_desc"),
                "dial_code" => $this->request->getVar("country_daily_code"),
                "status" => $this->request->getVar("status"),
            ];
            $posts_data =  $this->countryController->countryAdd($data);
            return $posts_data->getBody();
        }
    }

    public function countryEdit($id = 0)
    {

        if (session()->get('adminData')) {
            $data = array();
            $general = new General();
            $menu_data = $general->getMenuArray("Sitemap");
            $data['parent_arr'] = $menu_data['parent_arr'];
            $data['menu_arr'] = $menu_data['menu_arr'];
            $data['home_arr'] = $menu_data['home_arr'];
            $data['profile_arr'] = $menu_data['profile_arr'];
            $data['password_arr'] = $menu_data['password_arr'];
            $data['logout_arr'] = $menu_data['logout_arr'];
            $form_params = [
                "id" => $id,
            ];
            $posts_data =  $this->countryController->countryDetail($form_params);


            $data['data'] = json_decode($posts_data->getBody(), true);
            return view('Modules\Admin\Country\Views\country_edit', $data);
        } else {
            return view('Modules\Admin\User\Views\login');
        }
    }
    public function countryEditProcess()
    {
        if ($this->request->getPost()) {
            $data = [
                "id" => $this->request->getVar("country_id"),
                "country_name" => $this->request->getVar("country_name"),
                "country_code" => $this->request->getVar("country_code"),
                "country_code_iso3" => $this->request->getVar("country_code_iso3"),
                "description" => $this->request->getVar("country_desc"),
                "dial_code" => $this->request->getVar("country_daily_code"),
                "status" => $this->request->getVar("status"),
            ];
            $posts_data =  $this->countryController->countryUpdate($data);

            return $posts_data->getBody();
        }
    }

    public function countryStatusChange()
    {
        if ($this->request->getPost()) {

            $data = [
                "ids" => $this->request->getVar("id"),
            ];
            $posts_data =  $this->countryController->countryChangeStatus($data);

            return $posts_data->getBody();
        }
    }
    public function countryDelete()
    {
        if ($this->request->getPost()) {
            $data = [
                "id" => $this->request->getVar("id"),
            ];
            $posts_data =  $this->countryController->countryDelete($data);
            return $posts_data->getBody();
        }
    }
}
