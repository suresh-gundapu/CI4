<?php

namespace Modules\Admin\User\Controllers;

use App\Models\AdminModel;
use App\Models\AdminPasswordsModel;

use App\Libraries\General;
use App\Controllers\BaseController;

class Admin extends BaseController
{
    public function __construct()
    {
        helper(["form", "url", "cookie"]);
    }
    public function index()
    {
        if (session()->get('adminData')) {

            return view('Modules\Admin\User\Views\home');
        } else {
            return view('Modules\Admin\User\Views\login');
        }
    }
    public function test()
    {  
        $data = array();
        $general = new General();
        $menu_data = $general->getMenuArray("Sitemap");
        $data['parent_arr'] = $menu_data['parent_arr'];
        $data['menu_arr'] = $menu_data['menu_arr'];
        $data['home_arr'] = $menu_data['home_arr'];
        $data['profile_arr'] = $menu_data['profile_arr'];
        $data['password_arr'] = $menu_data['password_arr'];
        $data['logout_arr'] = $menu_data['logout_arr'];
        
        return view('Modules\Admin\User\Views\test' ,$data);
    }
    public function login()
    {
        if (!session()->get('adminData')) {
            return view('Modules\Admin\User\Views\login');
        } else {
            return view('Modules\Admin\User\Views\home');
        }
    }

    public function forgotPassword()
    {
        return view('Modules\Admin\User\Views\change_password');
    }
    public function resetPassword()
    {
        $reset_data = session()->getTempdata('reset_data');

        if ($reset_data == "") {
            session()->setFlashdata('failure', 'Session expired please login again');
            return redirect()->to('');
        }
        return view('Modules\Admin\User\Views\reset_password');
    }
    public function userProfile()
    {
        if (session()->get('adminData')) {
            $data = array();
            $general = new General();
            $model = new AdminModel();
            $data['user'] = $model->where('iAdminId', session()->get('adminData')['id'])->first();
            $changepassword_url = "change-password";
            $check_pwd_url = "";
            $enc_id = session()->get('adminData')['id'];
            $sess_data = session()->get('adminData');

            $data["id"] = session()->get('adminData')['id'];
            $data['name'] = $sess_data['name'];
            $data['email'] = $sess_data['email'];
            $data["changepassword_url"] = $changepassword_url;
            $data["check_pwd_url"] = $check_pwd_url;
            $menu_data = $general->getMenuArray("Sitemap");
            $data['parent_arr'] = $menu_data['parent_arr'];
            $data['menu_arr'] = $menu_data['menu_arr'];
            $data['home_arr'] = $menu_data['home_arr'];
            $data['profile_arr'] = $menu_data['profile_arr'];
            $data['password_arr'] = $menu_data['password_arr'];
            $data['logout_arr'] = $menu_data['logout_arr'];




            // $menu_data['menu_arr'] = $menu_list['menu'];
            // $menu_data['home_arr'] = $menu_list['home'];
            // $menu_data['profile_arr'] = $menu_list['profile'];
            // $menu_data['password_arr'] = $menu_list['password'];
            // $menu_data['logout_arr'] = $menu_list['logout'];
            // $menu_data['parent_arr'] = $menu_list['menu'][0];

            return view('Modules\Admin\User\Views\user_profile', $data);
        } else {
            return view('Modules\Admin\User\Views\login');
        }
    }

    public function processLogin()
    {
        if ($this->request->getPost()) {

            $data = [
                "username" => $this->request->getVar("username"),
                "password" => $this->request->getVar("password"),
            ];
            $skip_2fa = false;
            $encrypt_type = "bcrypt";
            $model = new AdminModel();
            $user = $model->where('vUserName', $data['username'])
                ->first();
            session()->setTempdata('tmp_username', $this->request->getVar("username"), 300);
            session()->setTempdata('tmp_password', $this->request->getVar("password"), 300);
            if ($user) {
                $emp = new General();
                $login_name = $this->request->getVar("username");

                $verify_password = $emp->verifyEncryptData($data['password'], $user['vPassword'], $encrypt_type);

                if ($verify_password == true) {
                    //check account lock status
                    $account_status = $this->checkAccountStatus($login_name, $user);
                    if ($account_status['success'] == 0) {
                        $response = [
                            'success' => $account_status['success'],
                            'msg' => $account_status['message'],
                        ];
                        return $this->response->setJSON($response);
                    } else {
                        if ($user['eStatus'] == "Active") {

                            $skip_2fa = true;
                        } else {
                            $response = [
                                'success' => false,
                                'msg' => "Your account is inactive. Please contact administrator.",
                            ];
                            return $this->response->setJSON($response);
                        }
                    }
                } else {
                    $account_status = $this->checkAccountStatus($login_name, $user);
                    if ($account_status['success'] == 0) {

                        $response = [
                            'success' => $account_status['success'],
                            'msg' => $account_status['message'],
                        ];
                        return $this->response->setJSON($response);
                    }
                    $this->updateLockDetails($login_name, $user, TRUE);

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
            $auth_arr = explode(",", $user["eAuthType"]);

            $dm_data = get_cookie($emp->getMD5EncryptString("DontAskMe", $login_name));

            $dm_data_arr = json_decode($dm_data, true);


            if ($skip_2fa == true) {
                if (is_array($dm_data_arr) && count($dm_data_arr) > 0 && $dm_data_arr['dont_ask_me'] == "Yes") {
                    $this->setUserSession($user);
                    $response = [
                        'success' => true,
                        'msg' => "Login successfully",
                    ];
                    $this->updateLockDetails($login_name, $user, FALSE);
                    return $this->response->setJSON($response);
                }
                if (in_array('Google', $auth_arr) && $user['vAuthCode'] != "" && config('AppConfig')->admin_2fa_authentication == TRUE) {
                    $response = [
                        'success' => 3,
                        'msg' => "",
                    ];
                    return $this->response->setJSON($response);
                } else if ((in_array('Email', $auth_arr) || in_array('SMS', $auth_arr)) && config('AppConfig')->admin_2fa_authentication == TRUE) {
                    if (in_array('Email', $auth_arr)) {
                        $this->sendOtp("Email", $user);
                    } else {
                        $this->sendOtp("SMS", $user);
                    }
                    $response = [
                        'success' => 3,
                        'msg' => "",
                    ];
                    return $this->response->setJSON($response);
                } else {
                    $this->setUserSession($user);
                    $response = [
                        'success' => true,
                        'msg' => "Login successfully",
                    ];
                    $this->updateLockDetails($login_name, $user, FALSE);
                    return $this->response->setJSON($response);
                }
            }
        }
    }
    public function processUpdateProfile()
    {
        if ($this->request->getPost()) {
            $data = [
                "vName" => $this->request->getVar("name"),
                "vEmail" => $this->request->getVar("email"),

                "vPhonenumber" => $this->request->getVar("mobile_no"),
                "vUserName" => $this->request->getVar("user_name"),
            ];
            $model = new AdminModel();
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
        // if ($this->request->getPost()) {

        //     $old_pwd = $this->request->getVar("c_pass");
        //     $new_pwd = $this->request->getVar("n_pass");
        //     $user_id = $this->request->getVar("user_id");
        //     $encrypt_type = "bcrypt";
        //     $model = new AdminModel();
        //     $user = $model->where('iAdminId', $user_id)
        //         ->first();

        //     $emp = new General();

        //     $verify_password = $emp->verifyEncryptData($old_pwd, $user['vPassword'], $encrypt_type);
        //     if ($verify_password == true) {
        //         if ($model->update($user_id, ['vPassword' => password_hash($new_pwd, PASSWORD_BCRYPT)])) {
        //             $response = [
        //                 'success' => true,
        //                 'msg' => "Password Update successfully",
        //             ];
        //             return $this->response->setJSON($response);
        //         } else {
        //             $response = [
        //                 'success' => false,
        //                 'msg' => "Failed..",
        //             ];
        //             return $this->response->setJSON($response);
        //         }
        //     } else {

        //         $response = [
        //             'success' => false,
        //             'msg' => "Old Password is Incorrect !..",
        //         ];

        //         return $this->response->setJSON($response);
        //     }
        // }
        $general = new General();
        $admin_id = $this->request->getVar("user_id");
        $old_password = $this->request->getVar("c_pass");
        $password = $this->request->getVar("n_pass");
        $confirm_password = $this->request->getVar("confirm_pass");
        $password_expiration_period = config("AppConfig")->admin_password_expiry;
        $password_expiration_period = ($password_expiration_period > 0) ? $password_expiration_period : "90";
        $curr_datetime = date('Y-m-d H:i:s');

        try {

            if ($old_password == $password) {
                throw new \Exception("New password should not be old password");
            }


            if ($admin_id != session()->get('adminData')['id']) {
                throw new \Exception("You are not authorized to view this page");
            }

            $passwords_limit = config("AppConfig")->admin_password_history;
            $pmodel = new AdminPasswordsModel();
            $old_passwords_list = $pmodel->where('iAdminId', $admin_id)->findAll($passwords_limit);

            $model = new AdminModel();
            $db_user_details = $model->where('iAdminId', $admin_id)
                ->first();
            $cur_password = $db_user_details['vPassword'];
            $pwd_settings = $this->getPasswordSettings();
            $is_encryptdata = strtolower($pwd_settings['encrypt']);
            $encrypt_method = strtolower($pwd_settings['enctype']);

            if ($is_encryptdata == 'yes') {
                $password_res = $general->verifyEncryptData($old_password, $cur_password, $encrypt_method);
                if ($old_password == "" || !$password_res) {
                    throw new \Exception("Old password is incorrect");
                } else if (count($old_passwords_list) > 0) {
                    foreach ($old_passwords_list as $key => $value) {
                        $old_password_res = $general->verifyEncryptData($password, $value['vPassword'], $encrypt_method);
                        if ($old_password_res) {
                            throw new \Exception("New password already used please enter another password");
                        }
                    }
                }
            } else {
                $db_password = $cur_password;
                if ($old_password == "" || $db_password != $old_password) {
                    throw new \Exception("Old password is incorrect");
                } else if (count($old_passwords_list) > 0) {
                    foreach ($old_passwords_list as $key => $value) {
                        if ($value['vPassword'] == $password) {
                            throw new \Exception("New password already used please enter another password");
                        }
                    }
                }
            }

            if ($password == "") {
                throw new \Exception("Enter New password");
            }

            if ($is_encryptdata == 'yes') {
                $new_password = $general->encryptDataMethod($password, $encrypt_method);
            } else {
                $new_password = $password;
            }
            $update_arr = array();
            $update_arr["vPassword"] = $new_password;
            $update_arr['isTemporaryPassword'] = 'No';
            $update_arr["dPasswordExpiredOn"] = date('Y-m-d H:i:s', strtotime('+' . $password_expiration_period . ' days', strtotime($curr_datetime)));
            $model = new AdminModel();
            $res = $model->update($admin_id, $update_arr);
            if (!$res) {
                throw new \Exception("Changing password has failed");
            }

            $password_arr = array();
            $password_arr['iAdminId'] = $admin_id;
            $password_arr['vPassword'] = $cur_password; //$new_password;
            $password_arr['dtAddedDate'] = date('y-m-d H:i:s');
            $password_arr['eStatus'] = 'Active';
            $pmodel->insert($password_arr);


            $email_vars = array(
                "vEmail" => $db_user_details['vEmail'],
                "NAME" => $db_user_details['vName'],
                'vUserName' => $db_user_details['vUserName']
            );
            // $mail_success = $general->sendMail($email_vars, 'ADMIN_PASSWORD_CHANGED');
            // $general->logExecutedEmails('Admin', $email_vars, $mail_success);
            $parser = \Config\Services::parser();

            $template = '<p>{text1}</p><p>{text2}</p>';
            $data     = [
                'text1' => "The password for your account on Hidden Brains Admin Panel is changed successfully.",
                "text2" => "If you didn't change your password, contact admin immediately."
            ];

            $body_content = $parser->setData($data)->renderString($template);
            $mail_success = $general->sendMail($email_vars, 'ADMIN_PASSWORD_CHANGED', "", "Password changed successfully on Hidden Brains", $body_content);
            $general->logExecutedEmails('Admin', $email_vars, $mail_success);

            if (!$mail_success) {
                throw new \Exception('Failure in sending mail');
            }
            $success = 1;
            $message = "Password changed successfully";
        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $return_arr['message'] = $message;
        $return_arr['success'] = $success;

        return $this->response->setJSON($return_arr);
    }
    private function setUserSession($user)
    {
        $data = [
            'id' => $user['iAdminId'],
            'name' => $user['vName'],
            'email' => $user['vEmail'],
            'userName' => $user['vUserName'],
            'isLoggedIn' => true,
        ];
        session()->set('adminData', $data);
        return true;
    }

    public function logout()
    {
        session()->remove('adminData');
        return redirect()->to('');
    }


    /**
     * check_account_status method is used to check account lock status.
     */
    public function checkAccountStatus($login_name = '', $data = array())
    {
        $emp = new General();

        if (!is_array($data) || count($data) == 0) {
            $success = 0;
            $message = "User data is missing. Something went wrong.";
        }

        $curr_datetime = date('Y-m-d H:i:s');
        $max_fail_attempts = config('AppConfig')->admin_account_lock_attempts;

        $user_email_vars = array(
            "vUserName" => $login_name,
            "vEmail" => $data['vEmail'],
            "NAME" => $data['vName'],
        );
        $admin_email_vars = array(
            "vUserName" => $login_name,
            "vEmail" => $data['vEmail'],
            "NAME" => 'Admin',
        );
        $login_failed_attempts = intval($data['iLoginFailedAttempts']);
        $login_locked_until = (!empty($data['dLoginLockedUntil'])) ? $data['dLoginLockedUntil'] : '';
        if ($login_failed_attempts == $max_fail_attempts) {
            $email_res = $emp->sendMail($user_email_vars, 'USER_LOGIN_ALERT', "", "Alert, Too many login attempts", "There are too many failed logins, Please check");
            $emp->logExecutedEmails($data['vName'], $admin_email_vars, $email_res);

            $email_res = $emp->sendMail($admin_email_vars, 'ADMIN_LOGIN_ALERT', "", "Alert, Too many login attempts", "There are too many failed logins, Please check");
            $emp->logExecutedEmails($data['vName'], $admin_email_vars, $email_res);
        }
        if ($login_failed_attempts >= $max_fail_attempts && $login_locked_until != '' && $login_locked_until > $curr_datetime) {
            $success = 0;
            $message = "Your account has been locked please try after some time.";
        } else {
            $success = 1;
            $message = '';
        }
        $return_arr = array();
        $return_arr['success'] = $success;
        $return_arr['message'] = $message;

        return $return_arr;
    }
    /**
     * update_lock_details method is used to udpdate invalid login attenpts in db.
     */
    public function updateLockDetails($login_name = '', $data = array(), $status = FALSE)
    {
        $curr_date_time = date('Y-m-d H:i:s');
        $max_fail_attempts = config('AppConfig')->admin_account_lock_attempts;
        $lock_time_duration = config('AppConfig')->admin_account_locked_duration;
        $lock_arr = array();
        if ($status) {
            $lock_arr['iLoginFailedAttempts'] = $data['iLoginFailedAttempts'] + 1;
            $lock_arr['dLoginLockedUntil'] = date('Y-m-d H:i:s', strtotime('+' . $lock_time_duration . ' minutes', strtotime($curr_date_time)));
        } else {
            $lock_arr['iLoginFailedAttempts'] = 0;
            $lock_arr['dLoginLockedUntil'] = NULL;
        }
        $model = new AdminModel();
        $model->update($data['iAdminId'], $lock_arr);

        return TRUE;
    }
    /**
     * otp_authentication method is used to display otp screen for 2-factor login.
     */
    public function otpAuthentication()
    {
        $login_name = session()->getTempdata('tmp_username');
        if ($login_name == "") {
            session()->setFlashdata('failure', 'Session expired please login again');
            return redirect()->to('');
        } else {
            $model = new AdminModel();
            $result = $model->where('vUserName', $login_name)
                ->first();
            $type =  $this->request->getVar("auth_type");
            $auth_arr = explode(",", $result["eAuthType"]);
            if ($type != '') {
                unset($auth_arr);
                $auth_arr[0] = $type;
            }
            $render_arr = array();
            $render_arr['placeholder'] = "Enter OTP";
            $render_arr['username'] = $login_name;
            $render_arr['login_url'] = base_url();
            $render_arr['try_another_way_url'] = "try-another-way";
            $render_arr['dont_ask_again'] = "";
            $render_arr['resend_otp_url'] = "";

            if (in_array('Google', $auth_arr)) {
                $render_arr['auth_type'] = "Google";
                $render_arr['title'] = "Enter verification code sent from google authenticator application";
                $render_arr['placeholder'] = "Enter security code";
            } else if (in_array('Email', $auth_arr)) {
                $render_arr['auth_type'] = "Email";
                $render_arr['title'] = "Enter verification code sent to registered email";
                $render_arr['resend_otp_url'] = "resend-otp";
                if ($type == 'Email') {
                    $this->sendOtp("Email", $result);
                }
            } else if (in_array('SMS', $auth_arr)) {
                $render_arr['auth_type'] = "SMS";
                $render_arr['title'] = "Enter verification code sent to registered mobile";
                $render_arr['resend_otp_url'] = "resend-otp";
                if ($type == 'SMS') {
                    $this->sendOtp("SMS", $result);
                }
            }
        }
        return view('Modules\Admin\User\Views\otp_authentication', $render_arr);
    }

    public function otpVerification()
    {
        $code =  $this->request->getVar("2fa_code");
        $auth_type = $this->request->getVar("auth_type");
        $dont_ask_me =  $this->request->getVar("dont_ask_again");
        $user = session()->getTempdata('tmp_username');
        $general = new General();
        $cookie_str = $general->getMD5EncryptString("DontAskMe", $user);
        if (isset($dont_ask_me) && $dont_ask_me == "Yes") {
            $dm_arr = array();
            $dm_arr['_user'] = $user;
            $dm_arr["dont_ask_me"] = "Yes";

            $dm_arr_json = json_encode($dm_arr);
            set_cookie($cookie_str, $dm_arr_json);
        } else {
            delete_cookie($cookie_str);
        }
        $login_name =  session()->getTempdata('tmp_username');
        $model = new AdminModel();

        $result = $model->where('vUserName', $login_name)
            ->first();

        if ($auth_type == 'Google') { //google verification

            $secret = $result['vAuthCode'];

            require_once APPPATH . "/ThirdParty/google_lib/GoogleAuthenticator.php";

            $googleAuthenticator = new \GoogleAuthenticator();

            $check_result = $googleAuthenticator->verifyCode($secret, $code, 2);
            if ($check_result == TRUE) {

                $this->setUserSession($result);

                $response = [
                    'success' => true,
                    'msg' => "Login successfully",
                ];
                $this->updateLockDetails($login_name, $result, FALSE);
                return $this->response->setJSON($response);
            } else {
                $response = [
                    'success' => false,
                    'msg' => "You have entered incorrect code.",
                ];
                return $this->response->setJSON($response);
            }
        } else {
            // Email & SMS verification
            $secert = session()->getTempdata('tmp_otp');
            if ($code != "" && $code == $secert) {
                $this->setUserSession($result);
                $response = [
                    'success' => true,
                    'msg' => "Login successfully",
                ];
                $this->updateLockDetails($login_name, $result, FALSE);
                return $this->response->setJSON($response);
            } else {
                $response = [
                    'success' => false,
                    'msg' => "You have entered incorrect code.",
                ];
                return $this->response->setJSON($response);
            }
        }
    }
    public function resendOtp($name = "")
    {
        $type = $name;
        $this->sendOtp($type, "", "resend");
        session()->setFlashdata('success', 'OTP has sent successfully');
        return redirect()->to('admin/otp-authentication' . "?auth_type=" . $type);
    }

    /**
     * try_another_way method is used to show available option in 2FA.
     */
    public function tryAnotherWay()
    {
        $model = new AdminModel();
        $login_name = session()->getTempdata('tmp_username');
        if ($login_name == "") {
            session()->setFlashdata('failure', 'Session expired please login again');
            return redirect()->to('');
        }
        $result = $model->where('vUserName', $login_name)
            ->first();
        $auth_arr = explode(",", $result["eAuthType"]);

        if (in_array('Google', $auth_arr) && $result['vAuthCode'] != '') {
            $google_label = "Get verification code form google authenticator application";
            $options['Google'] = str_replace('#GOOGLE#', '<strong>Google Authenticator</strong>', $google_label);
        }

        $email = $result['vEmail'];
        if (in_array('Email', $auth_arr) && $email != '') {
            $email_end = explode('@', $email);
            $options['Email'] = "Get verification code sent to registered email" . '<strong>' . substr($email, 0, 3) . '*****@' . substr($email, 0, -3) . '</strong>';
        }

        $phone = $result['vPhonenumber'];
        if (in_array('SMS', $auth_arr) && $phone != '') {
            $options['SMS'] = "Get verification code sent to registered mobile" . '<strong>' . substr($phone, 0, 2) . '*****' . substr($phone, -3) . '</strong>';
        }
        $render_arr = array();
        $render_arr['username'] = $login_name;
        $render_arr['options'] = $options;
        $render_arr['try_another_url'] = "otp-authentication";
        return view('Modules\Admin\User\Views\try_another_way', $render_arr);
    }
    public function sendOtp($type = '', $result = array(), $mode = '')
    {
        $general = new General();
        $login_name = session()->getTempdata('tmp_username');
        $model = new AdminModel();

        if ($mode == "resend") {
            $result = $model->where('vUserName',  $login_name)
                ->first();
        }
        $numeric = range(1, 9);
        $length = count($numeric) - 1;
        $results = array();
        for ($i = 0; $i < 6;) {
            $num = $numeric[mt_rand(0, $length)];
            if (!in_array($num, $results)) {
                $results[] = $num;
                $i++;
            }
        }
        $secertcode = implode("", $results);
        session()->setTempdata('tmp_otp', $secertcode, 300);
        if ($type == "Email") {
            $email_vars = array(
                "vEmail" => $result['vEmail'],
                "vUserName" => $result['vUserName'],
                "OTP_NUMBER" => $secertcode
            );

            $parser = \Config\Services::parser();

            $template = '<p>{blog_title}</p><p><strong>OTP:</strong>{otp}</p><p>{other_text}</p>';
            $data     = [
                'blog_title' => 'As per your request. the generated OTP for your login on Hidden Brains Admin Panel is,',
                'otp' => " $secertcode",
                "other_text" => "Please use the above OTP to login into Admin Panel. If you didn’t make this request, simply ignore this email."
            ];
            $body_content = $parser->setData($data)->renderString($template);
            $success = $general->sendMail($email_vars, 'ADMIN_LOGIN_OTP', "", "OTP for login on Hidden Brains", $body_content);
            $general->logExecutedEmails('Admin', $email_vars, $success);
            return $success;
        } elseif ($type == "SMS") {
            $sms_vars['to'] = $result['vPhonenumber'];
            $sms_vars['message'] = "Use " . $secertcode . " as your login OTP on " . config('AppConfig')->company_name . " Admin Panel. OTP is confidential.";
            $success = $general->sendSMSNotification($sms_vars['to'], $sms_vars);
            $general->logExecutedSMS('Front', $sms_vars, $success);
            return $success;
        }
        return FALSE;
    }

    public function processCheckEmail()
    {
        $email = $this->request->getVar("email");
        $model = new AdminModel();
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
    public function forgotPasswordAction()
    {
        $username = $this->request->getVar("username");
        try {
            if ($username == '') {
                $error_msg = "Please enter login name";
                throw new \Exception($error_msg);
            }

            $model = new AdminModel();
            $user = $model->where('vUserName', $username)
                ->first();
            if (empty($user) || $user == "") {
                $error_msg = "This email is not registered. Please contact administrator.";
                // throw new \Exception($error_msg);
                $return_arr['message'] = $error_msg;
                $return_arr['success'] = 0;
                return $this->response->setJSON($return_arr);
            }

            $numeric = range(1, 9);
            $length = count($numeric) - 1;
            $results = array();
            for ($i = 0; $i < 6;) {
                $num = $numeric[mt_rand(0, $length)];
                if (!in_array($num, $results)) {
                    $results[] = $num;
                    $i++;
                }
            }

            $reset_code = implode("", $results);
            $reset_url = base_url() . "admin/reset-pwd";
            $reset_data = array();
            $reset_data['id'] = base64_encode($user['iAdminId']);
            $reset_data['code'] = base64_encode($reset_code);
            $reset_data['time'] = base64_encode(time());
            session()->setTempdata('reset_data', $reset_data, 300);

            $email_vars = array();
            $email_vars['vUserName'] = $user['vUserName'];
            $email_vars['vEmail'] = $user['vEmail'];
            $email_vars['NAME'] = $user['vName'];
            $email_vars['RESET_CODE'] = $reset_code;
            $general = new General();
            $parser = \Config\Services::parser();

            $template = '<p>{other_text}</p><p><strong>Reset password code:</strong>{otp}</p>';
            $data     = [
                'otp' => $reset_code,
                "other_text" => "We received a request to reset your password. If you didn’t make this request, simply ignore this email."
            ];

            $body_content = $parser->setData($data)->renderString($template);
            $mail_success = $general->sendMail($email_vars, 'ADMIN_RESET_PASSWORD', "", "Change your password on Hidden Brains", $body_content);
            $general->logExecutedEmails('Admin', $email_vars, $mail_success);

            if (!$mail_success) {
                throw new \Exception('Failure in sending mail');
            }
            $success = 1;
            $message = "Please check the code which has been sent to your mail";
        } catch (\Exception $e) {
            $success = 0;
            $message =  $e->getMessage();
        }
        $return_arr['message'] = $message;
        $return_arr['success'] = $success;
        $return_arr['url'] = $reset_url;
        return $this->response->setJSON($return_arr);
    }

    public function resetPasswordAction()
    {
        $general = new General();
        $password =  $this->request->getVar("password");;
        $securitycode =  $this->request->getVar("reset_code");;

        try {
            $reset_data = session()->getTempdata('reset_data');

            $admin_id = $reset_data['id'];
            $code = $reset_data['code'];
            $time = $reset_data['time'];
            $password_expiration_period =  config('AppConfig')->admin_password_expiry;
            $password_expiration_period = ($password_expiration_period > 0) ? $password_expiration_period : "90";
            $curr_datetime = date('Y-m-d H:i:s');

            if (empty($reset_data)) {
                throw new \Exception("Session expired please try again");
            }

            $admin_id = base64_decode($admin_id);
            $time = base64_decode($time);
            $code = base64_decode($code);

            if ($code != $securitycode) {
                throw new \Exception("Security code failed please try again");
            }
            $currenttime = time();
            $resettime = config('AppConfig')->ADMIN_RESET_PASSWORD_TIME * 60 * 60 * 1000; //check 1sec
            $delay = $currenttime - $time;
            if ($admin_id > 0 && $delay < $resettime) {
                $pwd_settings = $this->getPasswordSettings();
                $is_encryptdata = strtolower($pwd_settings['encrypt']);
                $encrypt_method = strtolower($pwd_settings['enctype']);

                $passwords_limit = config('AppConfig')->admin_password_history;

                $pmodel = new AdminPasswordsModel();
                $old_passwords_list = $pmodel->where('iAdminId', $admin_id)->findAll($passwords_limit);

                if ($old_passwords_list) {
                    foreach ($old_passwords_list as $key => $value) {

                        if ($is_encryptdata == 'yes') {
                            $old_password_res = $general->verifyEncryptData($password, $value['vPassword'], $encrypt_method);

                            if ($old_password_res) {
                                throw new \Exception("New password already used please enter another password");
                            }
                        } else {
                            if ($value['vPassword'] == $password) {
                                throw new \Exception("New password already used please enter another password");
                            }
                        }
                    }
                }

                if ($is_encryptdata == 'yes') {
                    $new_password = $general->encryptDataMethod($password, $encrypt_method);
                } else {
                    $new_password = $password;
                }

                $update_arr = array();
                $update_arr["vPassword"] = $new_password;
                $update_arr['isTemporaryPassword'] = 'No';
                $update_arr["dPasswordExpiredOn"] = date('Y-m-d H:i:s', strtotime('+' . $password_expiration_period . ' days', strtotime($curr_datetime)));
                $model = new AdminModel();
                $res = $model->update($admin_id, $update_arr);
                if (!$res) {
                    throw new \Exception("Reset password failed");
                }

                $password_arr = array();
                $password_arr['iAdminId'] = $admin_id;
                $password_arr['vPassword'] = $new_password;
                $password_arr['dtAddedDate'] = date('y-m-d H:i:s');
                $password_arr['eStatus'] = 'Active';
                $pmodel->insert($password_arr);
                $success = 1;
                $message = "Please login with your new password";
                //session()->setFlashdata('success', $message);

            } else {
                throw new \Exception("Time exceeded to reset the password");
            }
            $success = 1;
            $message = "Please login with your new password";
        } catch (\Exception $e) {
            $success = 0;
            $message = $e->getMessage();
        }

        $redirect = base_url();
        $ret_arr['success'] = $success;
        $ret_arr['message'] = $message;
        $ret_arr['url'] = $redirect;
        return $this->response->setJSON($ret_arr);
    }

    public function getPasswordSettings()
    {
        $pwd_setings = array(
            "encrypt" => "Yes",
            "enctype" => "bcrypt",
            "pattern" => "Yes",
        );
        return $pwd_setings;
    }
}
