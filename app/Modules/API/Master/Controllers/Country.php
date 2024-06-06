<?php

namespace Modules\API\Master\Controllers;

use App\Models\CountryModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use Config\Services;

class Country extends ResourceController
{
    protected $db;
    public $count;
    public $per_page;
    public $curr_page;
    public $prev_page;
    public $next_page;
    public $response;
    public function __construct()
    {
        helper(["form", "url", "cookie"]);
        $this->count = 0;
        $this->per_page = 1000;
        $this->curr_page = 1;
        $this->prev_page = 0;
        $this->next_page = 0;
        $this->response = Services::response();
        $this->db = \Config\Database::connect();
    }
    public function countryListing($input_params = [])
    {
        $model = new CountryModel();
        $data = [
            'filters' => $input_params['filters'],
            'keyword' => $input_params['keyword'],
            'limit' => $input_params['limit'],
            'sort' => $input_params['sort'],
            'page' => $input_params['page'],
        ];
      $column_name= $data['sort']['column_name'];
      $column_order= $data['sort']['column_sort_order'];

        try {
            if (empty($data['limit'])) {
                throw new Exception("Please enter a value for the limit field.");
            }
            // $model->select('iCountryId AS country_id, vCountry AS country_name, vCountryCode AS country_code, vCountryCodeISO_3 AS country_code_iso3 , vDialCode AS dial_code , eStatus AS status');
            // if ($data['limit']) {
            //     $model->limit($data['limit']);
            // }
            // if ($data['page']) {
            //     $model->limit($data['limit'], ($data['page'] * $data['limit']) - $data['limit']);
            // }
            // if ($data['keyword']) {
            //     $model->like('vCountry', $data['keyword'], 'both');
            //     $model->or_like('vCountryCode', $data['keyword'], 'both');
            // }
            // $query = $model->get();
            // $result = $query->getResult();
            $start = $data['page'];
            $length = $data['limit'];
            $search_value = $data['keyword'];
            if (!empty($search_value)) {
                $total_count = $this->db->query("SELECT iCountryId AS country_id, vCountry AS country_name, vCountryCode AS country_code, vCountryCodeISO_3 AS country_code_iso3 , vDialCode AS dial_code , eStatus AS status from mod_country WHERE iCountryId like '%" . $search_value . "%' OR vCountry like '%" . $search_value . "%' OR vCountryCode like '%" . $search_value . "%' OR vCountryCodeISO_3 like '%" . $search_value . "%' OR vDialCode like '%" . $search_value . "%'")->getResultArray();
                $result = $this->db->query("SELECT iCountryId AS country_id, vCountry AS country_name, vCountryCode AS country_code, vCountryCodeISO_3 AS country_code_iso3 , vDialCode AS dial_code , eStatus AS status from mod_country WHERE iCountryId like '%" . $search_value . "%' OR vCountry like '%" . $search_value . "%' OR vCountryCode like '%" . $search_value . "%' OR vCountryCodeISO_3 like '%" . $search_value . "%' OR vDialCode like '%" . $search_value . "%' limit $start, $length")->getResultArray();
            } else {
                $total_count = $this->db->query("SELECT iCountryId AS country_id, vCountry AS country_name, vCountryCode AS country_code, vCountryCodeISO_3 AS country_code_iso3 , vDialCode AS dial_code , eStatus AS status  from mod_country")->getResultArray();
                $result = $this->db->query("SELECT iCountryId AS country_id, vCountry AS country_name, vCountryCode AS country_code, vCountryCodeISO_3 AS country_code_iso3 , vDialCode AS dial_code , eStatus AS status  from mod_country ORDER BY $column_name  $column_order limit $start, $length ")->getResultArray();
            }
            
            if (empty($result)) {
                throw new Exception("Country list not found.");
            }
           // $count = $query->getNumRows();
            $this->curr_page  =($data['page'] * $data['limit']) - $data['limit'];
            $this->per_page  =  $data['limit'];
            $this->count = $total_count;
            $this->prev_page = !empty($this->curr_page) ? true : false;
            $this->next_page = true;
            $response = [
                "settings" => [
                    'status' => 200,
                    'success' => 1,
                    'message' => "Country list found.",
                    'count' =>  count($total_count),
                    "per_page" => $this->per_page,
                    "last_page" => "",
                    "prev_page" => $this->prev_page,
                    "next_page" => $this->next_page,
                ],
                "data" => $result,
            ];
            //print_r(($response));exit;
            return $this->response->setBody(json_encode($response));
        } catch (Exception $e) {
            $response = [
                "settings" => [
                    'status' => 400,
                    'success' => 0,
                    'message' => $e->getMessage()
                ],
                "data" => []
            ];
            return $this->response->setBody(json_encode($response));
        }
    }
    public function countryDetail($input_params = [])
    {
        $id = $input_params['id'];
        $model = new CountryModel();
        try {
            if (empty($id)) {
                throw new Exception("Please enter a value for the country id field.");
            }
            $model->select('iCountryId AS country_id, vCountry AS country_name, vCountryCode AS country_code, vCountryCodeISO_3 AS country_code_iso3 , vDialCode AS dial_code , tDescription AS description , eStatus AS status');
            $result = $model->find($id);
            if (!empty($result)) {
                $response = [
                    "settings" => [
                        'status' => 200,
                        "success" => 1,
                        'messages' => 'Country details found',
                    ],
                    "data" => $result,
                ];
            } else {
                throw new Exception("Country details not found");
            }
            return $this->response->setBody(json_encode($response));
        } catch (Exception $e) {
            $response = [
                "settings" => [
                    'status' => 500,
                    'success' => 0,
                    'message' => $e->getMessage()
                ],
                "data" => []
            ];
            return $this->response->setBody(json_encode($response));
        }
    }
    public function countryAdd($input_params = [])
    {

        $model = new CountryModel();
        $data = [
            'vCountry' => $input_params['country_name'],
            'vCountryCode' => $input_params['country_code'],
            'vCountryCodeISO_3' => $input_params['country_code_iso3'],
            'tDescription' => $input_params['description'],
            'vDialCode' => $input_params['dial_code'],
            'eStatus' => $input_params['status'],
        ];

        try {
            if (empty($data['vCountry'])) {
                throw new Exception("Please enter a value for the country name field.");
            }
            if (empty($data['vCountryCode'])) {
                throw new Exception("Please enter a value for the country code field.");
            }
            if (empty($data['vCountryCodeISO_3'])) {
                throw new Exception("Please enter a value for the country code iso3 field.");
            }
            if (empty($data['vDialCode'])) {
                throw new Exception("Please enter a value for the dial code field.");
            }
            if (empty($data['eStatus'])) {
                throw new Exception("Please enter a value for the status field.");
            }

            $result = $model->insert($data, false);
            if ($result == FALSE) {
                throw new Exception("Country added failure.");
            }
            $last_inserted_id = $model->getInsertID();
            $response = [
                "settings" => [
                    'status' => 200,
                    'success' => 1,
                    'message' => "Country added successfully.",
                ],
                "data" => ["country_id" => $last_inserted_id]
            ];
            return $this->response->setBody(json_encode($response));
        } catch (Exception $e) {
            $response = [
                "settings" => [
                    'status' => 400,
                    'success' => 0,
                    'message' => $e->getMessage()
                ],
                "data" => []
            ];
            return $this->response->setBody(json_encode($response));
        }
    }

    public function countryUpdate($input_params = [])
    {
        $model = new CountryModel();
        $id = $input_params['id'];
        $data = [
            'vCountry' => $input_params['country_name'],
            'vCountryCode' => $input_params['country_code'],
            'vCountryCodeISO_3' => $input_params['country_code_iso3'],
            'tDescription' => $input_params['description'],
            'vDialCode' => $input_params['dial_code'],
            'eStatus' => $input_params['status'],
        ];

        try {
            if (empty($id)) {
                throw new Exception("Please enter a value for the country id field.");
            }
            if (empty($data['vCountry'])) {
                throw new Exception("Please enter a value for the country name field.");
            }
            if (empty($data['vCountryCode'])) {
                throw new Exception("Please enter a value for the country code field.");
            }
            if (empty($data['vCountryCodeISO_3'])) {
                throw new Exception("Please enter a value for the country code iso3 field.");
            }
            if (empty($data['vDialCode'])) {
                throw new Exception("Please enter a value for the dial code field.");
            }
            if (empty($data['eStatus'])) {
                throw new Exception("Please enter a value for the status field.");
            }
            if ($model->find($id)) {
                $model->update($id, $data);
                $response = [
                    "settings" => [
                        'status' => 200,
                        'success' => 1,
                        'message' => "Country updated successfully.",
                    ],
                    "data" => []
                ];
                return $this->response->setBody(json_encode($response));
            } else {
                throw new Exception("Country not found.");
            }
        } catch (Exception $e) {
            $response = [
                "settings" => [
                    'status' => 500,
                    'success' => 0,
                    'message' => $e->getMessage()
                ],
                "data" => []
            ];
            return $this->response->setBody(json_encode($response));
        }
    }
    public function countryDelete($input_params = [])
    {
        $id = $input_params['id'];
        $model = new CountryModel();
        try {
            if (empty($id)) {
                throw new Exception("Please enter a value for the country id field.");
            }
            $result = $model->find($id);
            if (!empty($result)) {
                $model->delete($id);
                $response = [
                    "settings" => [
                        'status' => 200,
                        "success" => 1,
                        'message' => 'Country deleted successfully',
                    ],
                    "data" => [],
                ];
            } else {
                throw new Exception("Country details not found");
            }
            return $this->response->setBody(json_encode($response));
        } catch (Exception $e) {
            $response = [
                "settings" => [
                    'status' => 500,
                    'success' => 0,
                    'message' => $e->getMessage()
                ],
                "data" => []
            ];
            return $this->response->setBody(json_encode($response));
        }
    }
    public function countryChangeStatus($input_params = [])
    {
        $model = new CountryModel();
        $ids = $input_params['ids'];
        $id_array = explode(",", $ids);
        if (!empty($input_params['status'])) {
            $data = [
                'eStatus' => $input_params['status'] == "Active" ? "Inactive" : "Active",
            ];
        } else {
            $result = $model->find($ids);
            $data = [
                'eStatus' =>  $result['eStatus'] == "Active" ? "Inactive" : "Active",
            ];
        }

        try {
            if (empty($ids)) {
                throw new Exception("Please enter a value for the country id field.");
            }
            $model->update($id_array, $data);
            $model->affectedRows();
            if ($model->affectedRows()) {
                $response = [
                    "settings" => [
                        'status' => 200,
                        'success' => 1,
                        'message' => "Country status updated successfully.",
                    ],
                    "data" => []
                ];
                return $this->response->setBody(json_encode($response));
            } else {
                throw new Exception("Country status updated failed.");
            }
        } catch (Exception $e) {
            $response = [
                "settings" => [
                    'status' => 500,
                    'success' => 0,
                    'message' => $e->getMessage()
                ],
                "data" => []
            ];
            return $this->response->setBody(json_encode($response));
        }
    }
}
