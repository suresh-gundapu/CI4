<?php

namespace Modules\Front\User\Controllers;
use App\Models\UsersModel;

use App\Libraries\General;

use App\Controllers\BaseController;

class Users extends BaseController
{
    public function __construct()
    {
        helper(["form", "url"]);
    }
    public function index()
    {
        return view('Modules\Front\User\Views\home');
    }
    public function test()
    {
        return view('Modules\Front\User\Views\test');
    }
    public function login()
    {
        $config = new \Config\AppConfig();

        //$data['config_ex'] = service('settings')->get('AppConfig.admin_account_lock_attempts');

        return view('Modules\Front\User\Views\login');
    }

    public function forgotPassword()
    {
        return view('Modules\Front\User\Views\change_password');
    }

    public function dashboard()
    {
        if (session()->get('userData')['isLoggedIn']) {
            $model = new UsersModel();
            $data['user'] = $model->where('iCustomerId', session()->get('userData')['id'])->first();
            return view('Modules\Front\User\Views\dashboard', $data);
        } else {
            return view('Modules\Front\User\Views\login');
        }
    }

    public function processLogin()
    {
        //$lang= config('AppConfig')->ada_default_lang ;
        //echo $lang;exit;
        // print_r($this->request->getVar());exit;
        if ($this->request->getPost()) {
            $data = [
                "username" => $this->request->getVar("username"),
                "password" => $this->request->getVar("password"),
            ];

            $encrypt_type = "bcrypt";
            $model = new UsersModel();
            $user = $model->where('vUserName', $data['username'])
                ->first();
            if ($user) {
                $emp = new General();
                $login_name = $this->request->getVar("username");

                $verify_password = $emp->verifyEncryptData($data['password'], $user['vPassword'], $encrypt_type);

                if ($verify_password == true) {
                  
                        if ($user['eStatus'] == "Active") {

                            $this->setUserSession($user);

                            $response = [
                                'success' => true,
                                'msg' => "Login successfully",
                            ];
                            return $this->response->setJSON($response);
                        } else {
                            $response = [
                                'success' => false,
                                'msg' => "Your account is inactive. Please contact administrator.",
                            ];
                            return $this->response->setJSON($response);
                        }
                } else {
                    
                    $response = [
                        'success' => false,
                        'msg' => "Invalid credentials. Please try again.",
                    ];
                    return $this->response->setJSON($response);
                }
            } else {
                $response = [
                    'success' => false,
                    'msg' => "This user is not registered. Please contact administrator.",
                ];

                return $this->response->setJSON($response);
            }
        }
    }

    public function processUpdateProfile()
    {
        if ($this->request->getPost()) {
            $data = [
                "vFirstName" => $this->request->getVar("name"),
                "vEmail" => $this->request->getVar("email"),
                "vPhonenumber" => $this->request->getVar("mobile_no"),
                "vUserName" => $this->request->getVar("user_name"),
            ];
            $model = new UsersModel();
            if ($model->update($this->request->getVar("user_id"), $data)) {
                $response = [
                    'success' => true,
                    'msg' => "Profile Update successfully",
                ];
                return $this->response->setJSON($response);
            } else {
                $response = [
                    'success' => false,
                    'msg' => "Failed..",
                ];
                return $this->response->setJSON($response);
            }
        }
    }

    public function processChangePassword()
    {
        if ($this->request->getPost()) {

            $old_pwd = $this->request->getVar("c_pass");
            $new_pwd = $this->request->getVar("n_pass");
            $user_id = $this->request->getVar("user_id");
            $encrypt_type = "bcrypt";
            $model = new UsersModel();
            $user = $model->where('iAdminId', $user_id)
                ->first();

            $emp = new General();

            $verify_password = $emp->verifyEncryptData($old_pwd, $user['vPassword'], $encrypt_type);
            if ($verify_password == true) {
                if ($model->update($user_id, ['vPassword' => password_hash($new_pwd, PASSWORD_BCRYPT)])) {
                    $response = [
                        'success' => true,
                        'msg' => "Password Update successfully",
                    ];
                    return $this->response->setJSON($response);
                } else {
                    $response = [
                        'success' => false,
                        'msg' => "Failed..",
                    ];
                    return $this->response->setJSON($response);
                }
            } else {

                $response = [
                    'success' => false,
                    'msg' => "Old Password is Incorrect !..",
                ];

                return $this->response->setJSON($response);
            }
        }
    }
    private function setUserSession($user)
    {
       
        $data = [
            'id' => $user['iCustomerId'],
            'name' =>  $user['vFirstName'] ." " . $user['vLastName'],
            'email' => $user['vEmail'],
            'userName' => $user['vUserName'],
            'isLoggedIn' => true,
        ];
        session()->set('userData',$data);
        return true;
    }

    public function logout()
    {
        session()->remove('userData');
        return redirect()->to('users');
    }

    public function processCheckEmail()
    {

        $email = $this->request->getVar("email");
        $model = new UsersModel();
        $user = $model->where('vEmail', $email)
            ->first();
        if ($user) {
            $response = [
                'success' => true,
                'msg' => "Email verified successfully",
            ];
            return $this->response->setJSON($response);
        } else {
            $response = [
                'success' => false,
                'msg' => "This email is not registered. Please contact administrator.",
            ];
            return $this->response->setJSON($response);
        }
    }
}
