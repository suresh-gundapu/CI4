<?php

namespace app\Libraries;

use PHPMailer\PHPMailer\PHPMailer;

use App\Models\ModExecutedNotificationsModel;
use App\Models\AdminMenuModel;

class General
{
    private $CI;

    protected $_notify_error;
    protected $_email_subject;
    protected $_email_content;
    protected $_email_params;


    // This function fetch data from db table
    public function verifyEncryptData($data = '', $enc_data = '', $method = 'cit')
    {
        switch ($method) {
            case 'base64':
                $dec_data = base64_decode($enc_data);
                if ($data == $dec_data) {
                    return TRUE;
                }
                break;
            case 'password_hash':
                if (password_verify($data, $enc_data)) {
                    return TRUE;
                }
                break;
            case 'bcrypt':
                if (password_verify($data, $enc_data)) {
                    return TRUE;
                }
                break;
            case 'md5':
            case 'sha1':
            case 'sha256':
            case 'sha512':
                if (hash($method, $data) == $enc_data) {
                    return TRUE;
                }
                break;
            default:
                break;
        }
        return FALSE;
    }
    public function parseEmailTemplate($body = '', $user_name = "")
    {
        $parser = \Config\Services::parser();
        $render_arr = array();
        $render_arr['company_name'] =  config('AppConfig')->company_name;
        $render_arr['copyright_text'] = config('AppConfig')->copyright_text;
        $render_arr['content'] = $body;
        $render_arr['site_url'] = base_url();
        $render_arr['user_name'] = ucfirst($user_name);
        // $render_arr['logo'] = $this->CI->config->item('COMPANY_LOGO');
        $template = htmlspecialchars_decode($parser->setData($render_arr)->render('mail_template.php'));
        return $template;
    }

    public function CISendMail($to = '', $subject = '', $body = '', $from_email = '', $from_name = '', $cc = '', $bcc = '', $attach = array(), $params = array(), $reply_to = array())
    {
        $success = FALSE;
        try {
            if (empty($to)) {
                throw new \Exception("Receiver email address is missing..!");
            }
            if (empty($body) || trim($body) == "") {
                throw new \Exception("Email body content is missing..!");
            }
            $this->_email_subject = $subject;
            $this->_email_content = $body;
            $this->_email_params = array(
                'from_name' => $from_name,
                'from_email' => $from_email,
            );
            if (config('AppConfig')->email_sending_library == 'phpmailer') {
                $mail = new PHPMailer(true);
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->isHTML(true);
                $mail->SMTPAuth = true;
                $mail->Host = config('AppConfig')->USE_SMTP_SERVERHOST;
                $mail->Username = config('AppConfig')->USE_SMTP_SERVERUSERNAME;
                $mail->Password = config('AppConfig')->USE_SMTP_SERVERPASS;
                $mail->SMTPSecure = config('AppConfig')->USE_SMTP_SERVERCRYPTO;
                $mail->Port = config('AppConfig')->USE_SMTP_SERVERPORT;
                $mail->setFrom($from_email, $from_name);
                $mail->addAddress($to, $to);
                if (isset($reply_to['reply_name']) && isset($reply_to['reply_email'])) {
                    $mail->addReplyTo($reply_to['reply_email'], $reply_to['reply_name']);
                } else {
                    $mail->addReplyTo($from_email, $from_name);
                }
                if (!empty($cc)) {
                    $mail->addCC($cc);
                    $this->_email_params['cc'] = $cc;
                }
                if (!empty($bcc)) {
                    $mail->addBCC($bcc);
                    $this->_email_params['bcc'] = $bcc;
                }
                if (is_array($attach) && count($attach) > 0) {
                    foreach ($attach as $ak => $av) {
                        $mail->addAttachment($av['filename'], $av['newname']);
                    }
                }
                $mail->Subject = $subject;
                $mail->Body = $body;
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $success = $mail->send();
            } else {
                $email = \Config\Services::email();
                $email->setFrom($from_email, $from_name);
                $email->setTo($to);
                $email->setSubject($subject);
                $email->setMessage($body);

                if (is_array($attach) && count($attach) > 0) {
                    foreach ($attach as $ak => $av) {
                        $email->attach($av['filename'], $av['position'], $av['newname']);
                    }
                }
                $success = $email->send();
               
                if (is_array($attach) && count($attach) > 0) {
                    $email->clear(TRUE);
                }
                if (!$success) {
                    throw new \Exception($email->printDebugger(['headers']));
                }
            }

            $message = "Email send successfully..!";
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $this->_notify_error = $message;
        }
        return $success;
    }
    public function sendMail($data = array(), $code = "CONTACT_US", $params = array(), $subject = "", $content = "")
    {
        if (is_array($data) && count($data) > 0) {
            if (!empty($data['vEmail'])) {
                $to = $data['vEmail'];
            } else {
                $to = config('AppConfig')->EMAIL_ADMIN;
            }
            if (!empty($data['vUserName'])) {
                $username = $data['vUserName'];
            }else{
                $username = ""; 
            }
            $from_name = "CIT";
            $from = config('AppConfig')->EMAIL_ADMIN;
              $body = $this->parseEmailTemplate($content, $username);

            if (!empty($data['vCcEmail'])) {
                $cc = $data['vCcEmail'];
            } elseif (!empty($data['vCCEmail'])) {
                $cc = $data['vCCEmail'];
            } else {
                $cc = "";
            }
            if (!empty($data['vBccEmail'])) {
                $bcc = $data['vBccEmail'];
            } elseif (!empty($data['vBCCEmail'])) {
                $bcc = $data['vBCCEmail'];
            } else {
                $bcc = "";
            }
            $attach = null;
             $reply_to = array();
            if (!empty($data['vReplyToEmail']) && !empty($data['vReplyToName'])) {
                $reply_to['reply_email'] = $data['vReplyToEmail'];
                $reply_to['reply_name'] = $data['vReplyToName'];
            }

           $success = $this->CISendMail($to, $subject, $body, $from, $from_name, $cc, $bcc, $attach, $params, $reply_to);
            return $success;
        } else {
            return FALSE;
        }
    }

    /**
     * logExecutedEmails method is used to make an record of notification emails sent.
     */
    public function logExecutedEmails($type = 'Admin', $email_vars = array(), $success = FALSE)
    {
        $log_arr = array();
        $log_arr['eEntityType'] = $type;
        $log_arr['vReceiver'] = is_array($email_vars["vEmail"]) ? implode(",", $email_vars["vEmail"]) : $email_vars["vEmail"];
        $log_arr['eNotificationType'] = "EmailNotify";
        $log_arr['vSubject'] = $this->getEmailOutput("subject");
        $log_arr['tContent'] = $this->getEmailOutput("content");
        $log_arr['tParams'] = json_encode($this->getEmailOutput("params"));
        if (!$success) {
            $log_arr['tError'] = $this->getNotifyErrorOutput();
        }
        $log_arr['dtSendDateTime'] = date('Y-m-d H:i:s');
        $log_arr['eStatus'] = ($success) ? "Executed" : "Failed";
        $this->insertExecutedNotify($log_arr);
        return TRUE;
    }

    public function getEmailOutput($type = 'subject')
    {
        if ($type == "subject") {
            $ret_str = $this->_email_subject;
            $this->_email_subject = '';
        } elseif ($type == "content") {
            $ret_str = $this->_email_content;
            $this->_email_content = '';
        } elseif ($type == "params") {
            $ret_str = $this->_email_params;
            $this->_email_params = array();
        }
        return $ret_str;
    }

    public function getNotifyErrorOutput()
    {
        $ret_str = $this->_notify_error;
        $this->_notify_error = '';
        return $ret_str;
    }
    public function insertExecutedNotify($insert_arr = array())
    {
        $model = new ModExecutedNotificationsModel();

        if (!isset($insert_arr['tParams'])) {
            $insert_arr['tParams'] = json_encode($this->getEmailOutput("params"));
        }
        $success = $model->insert($insert_arr);
        return $success;
    }
    public function getMD5EncryptString($type = '', $item = '')
    {
        $admin_url = base_url();
        if (in_array($type, array('JavaScript', 'ListPrefer', 'FlowAdd', 'FlowEdit', 'DetailView'))) {
            $str = $admin_url . "_" . session()->get('adminData')['id'];
        } else {
            $str = $admin_url;
        }
        switch ($type) {
                //local storage related
            case 'JavaScript':
                $suffix = "JS";
                break;
            case 'ListPrefer':
                $suffix = "LP";
                break;
                //file cache related
            case 'FlowAdd':
                $suffix = "FA";
                break;
            case 'FlowEdit':
                $suffix = "FE";
                break;
            case 'AppCache':
                $suffix = "AC";
                break;
            case 'AppCacheJS':
                $suffix = "AJ";
                break;
            case 'AppCacheCSS':
                $suffix = "AS";
                break;
                //cookie related
            case 'DetailView':
                $suffix = "DV";
                break;
            case 'RememberMe':
                $suffix = "RM";
                break;
            case 'DontAskMe':
                $suffix = "DM";
                break;
        }
        if ($suffix) {
            $str .= "_" . strtolower($suffix);
        }
        if ($item != "") {
            $str .= "_" . strtolower($item);
        }
        $enc_str = md5($str);
        return $enc_str;
    }

    public function encryptDataMethod($data = '', $method = 'cit')
    {
        $enc_data = '';
        switch ($method) {
            case 'base64':
                if (trim($data) != "") {
                    $enc_data = base64_encode($data);
                }
                break;
            case 'password_hash':
                if ($data == "*****" || trim($data) == "") {
                    $enc_data = FALSE;
                } else {
                    $enc_data = password_hash($data, PASSWORD_DEFAULT);
                }
                break;
            case 'bcrypt':
                if ($data == "*****" || trim($data) == "") {
                    $enc_data = FALSE;
                } else {
                    $enc_data = password_hash($data, PASSWORD_BCRYPT);
                }
                break;
            case 'md5':
            case 'sha1':
            case 'sha256':
            case 'sha512':
                if ($data == "*****" || trim($data) == "") {
                    $enc_data = FALSE;
                } else {
                    $enc_data = hash($method, $data);
                }
                break;
            default:
                // $this->CI->general->loadEncryptLibrary();
                // if (trim($data) != "") {
                //     $enc_data = $this->CI->ci_encrypt->dataEncrypt($data);
                // }
                break;
        }
        return $enc_data;
    }

    public function sendSMSNotification($to_no = '', $message = '')
    {
        $active_api = config('AppConfig')->SMS_ACTIVE_API;
        if ($active_api == "") {
            $this->_notify_error = "SMS API is not activated. Please configure SMS settings.";
            return FALSE;
        }
        $response = FALSE;
        $active_api = strtolower($active_api);
        if ($active_api == "nexmo") {
            $auth['api_key'] = config('AppConfig')->SMS_NX_API_KEY;
            $auth['api_secret'] = config('AppConfig')->SMS_NX_API_SECRET;
            $auth['from_no'] = config('AppConfig')->SMS_FROM_NUMBER;
            $response = $this->sendSMSNexmo($auth, $to_no, $message['message']);
        } elseif ($active_api == "clickatell") {
            $auth['token'] = config('AppConfig')->SMS_CA_API_TOKEN;
            $auth['from_no'] = config('AppConfig')->SMS_FROM_NUMBER;
            $response = $this->sendSMSClickatell($auth, $to_no, $message['message']);
        } elseif ($active_api == "twilio") {
            $auth['sid'] = config('AppConfig')->SMS_TW_API_SID;
            $auth['token'] = config('AppConfig')->SMS_TW_API_TOKEN;
            $auth['from_no'] = config('AppConfig')->SMS_FROM_NUMBER;
            $response = $this->sendSMSTwilio($auth, $to_no, $message['message']);
        }
        return $response;
    }
    public function sendSMSNexmo($auth = array(), $to = '', $message = '')
    {
        $this->CI->load->library('nexmo', $auth);
        $response = $this->CI->nexmo->sendMessage($to, $message);
        if ($response['success']) {
            return TRUE;
        } else {
            $this->_notify_error = $response['message'] || "SMS sending failed.";
            return FALSE;
        }
    }

    public function sendSMSClickatell($auth = array(), $to = '', $message = '')
    {
        $this->CI->load->library('clickatel', $auth);
        $response = $this->CI->clickatel->sendMessage($to, $message);
        if ($response['success']) {
            return TRUE;
        } else {
            $this->_notify_error = $response['message'] || "SMS sending failed.";
            return FALSE;
        }
    }

    public function sendSMSTwilio($auth = array(), $to = '', $message = '')
    {
        require_once APPPATH . "/ThirdParty/twilio/vendor/autoload.php";

        $response = $this->CI->twilio->sendMessage($to, $message);
        if ($response['success']) {
            return TRUE;
        } else {
            $this->_notify_error = $response['message'] || "SMS sending failed.";
            return FALSE;
        }
    }

    /**
     * logExecutedSMS method is used to make an record of SMS's sent.
     */
    public function logExecutedSMS($type = 'Admin', $sms_vars = array(), $success = FALSE)
    {
        $log_arr = array();
        $log_arr['eEntityType'] = $type;
        $log_arr['vReceiver'] = $sms_vars['to'];
        $log_arr['eNotificationType'] = "SMS";
        $log_arr['tContent'] = $sms_vars['message'];
        if (!$success) {
            $log_arr['tError'] = $this->getNotifyErrorOutput();
        }
        $log_arr['dtSendDateTime'] = date('Y-m-d H:i:s');
        $log_arr['eStatus'] = ($success) ? "Executed" : "Failed";
        $this->insertExecutedNotify($log_arr);
        return TRUE;
    }

    public function isExternalURL($url = '')
    {
        $flag = FALSE;
        if ($url != "") {
            $url = strtolower(trim($url));
            if (substr($url, 0, 8) == 'https://' || substr($url, 0, 7) == 'http://') {
                $flag = TRUE;
            }
        }
        return $flag;
    }

    public function makeLableNameFromString($str = '')
    {
        // spcial chars code generation
        $special_char_find = array(
            "!", "#", "$", "%", "&", "(", ")", "*", "+",
            ",", "-", ".", "/", ":", ";", "<", "=", ">",
            "?", "@", "[", "]", "^", "{", "|", "}", "~"
        );
        $special_char_replace = array(
            "_c33", "_c35", "_c36", "_c37", "_c38", "_c40", "_c41", "_c42", "_c43",
            "_c44", "_c45", "_c46", "_c47", "_c58", "_c59", "_c60", "_c61", "_c62",
            "_c63", "_c64", "_c91", "_c93", "_c94", "_c123", "_c124", "_c125", "_c126"
        );
        $str = str_replace($special_char_find, $special_char_replace, $str);

        $str = strtolower(preg_replace("/[^A-Za-z0-9_]/", '', str_replace(' ', '_', trim($str))));
        $str = trim($str, "_");
        return $str;
    }

    public function getDisplayLabel($module = '', $label_text = '', $type = 'tpl')
    {
        $return_label = $label_text;
        if ($label_text != "") {
            $lablename = $this->makeLableNameFromString($label_text);
            $final_label = strtoupper($module) . "_" . strtoupper($lablename);
            if ($type == "label") {
                $return_label = $final_label;
            } elseif ($type == "php") {
                $return_label =  $final_label ;
            } else {
                $return_label = $final_label;
            }
        }

        return $return_label;
    }


    public function processRequestPregMatch($param = '', $input_params = array())
    {
        if ($param != "") {
            if (strstr($param, '{%REQUEST') !== FALSE) {
                preg_match_all("/{%REQUEST\.([a-zA-Z0-9_-]{1,})/i", $param, $preg_all_arr);
                if (isset($preg_all_arr[1]) && is_array($preg_all_arr[1]) && count($preg_all_arr[1]) > 0) {
                    foreach ((array) $preg_all_arr[1] as $key => $value) {
                        if (is_array($input_params[$value])) {
                            continue;
                        }
                        if (strstr($param, '{%REQUEST') !== FALSE) {
                            $param = str_replace("{%REQUEST." . $value . "%}", $input_params[$value], $param);
                        }
                    }
                }
            }
        }
        return $param;
    }

    public function processServerPregMatch($param = '', $input_params = array())
    {
        if ($param != "") {
            if (strstr($param, '{%SERVER') !== FALSE) {
                preg_match_all("/{%SERVER\.([a-zA-Z0-9_-]{1,})/i", $param, $preg_all_arr);
                if (isset($preg_all_arr[1]) && is_array($preg_all_arr[1]) && count($preg_all_arr[1]) > 0) {
                    foreach ((array) $preg_all_arr[1] as $key => $value) {
                        if (strstr($param, '{%SERVER') !== FALSE) {
                            $param = str_replace('{%SERVER.' . $value . '%}', $_SERVER[$value], $param);
                        }
                    }
                }
            }
        }
        return $param;
    }

    public function processSessionPregMatch($param = '', $input_params = array())
    {
        if ($param != "") {
            if (strstr($param, '{%SESSION') !== FALSE) {
                preg_match_all("/{%SESSION\.([a-zA-Z0-9_-]{1,})/i", $param, $preg_all_arr);
                if (isset($preg_all_arr[1]) && is_array($preg_all_arr[1]) && count($preg_all_arr[1]) > 0) {
                    foreach ((array) $preg_all_arr[1] as $key => $value) {
                        if (strstr($param, '{%SESSION') !== FALSE) {
                            $param = str_replace('{%SESSION.' . $value . '%}',"", $param);
                        }
                    }
                }
            }
        }
        return $param;
    }

    public function processSystemPregMatch($param = '', $input_params = array())
    {
        if ($param != "") {
            if (strstr($param, '{%SYSTEM') !== FALSE) {
                preg_match_all("/{%SYSTEM\.([a-zA-Z0-9_-]{1,})/i", $param, $preg_all_arr);
                if (isset($preg_all_arr[1]) && is_array($preg_all_arr[1]) && count($preg_all_arr[1]) > 0) {
                    foreach ((array) $preg_all_arr[1] as $key => $value) {
                        if (strstr($param, '{%SYSTEM') !== FALSE) {
                            $param = str_replace('{%SYSTEM.' . $value . '%}', $this->CI->config->item($value), $param);
                        }
                    }
                }
            }
        }
        return $param;
    }
    public function getMenuArray($type = ""){

        $general = new General();
        $model = new AdminMenuModel();
        $encrypt_arr = config("AppConfig")->FRAMEWORK_ENCRYPTS;
        $admin_profile_links = TRUE;
        $menu_query_req = TRUE;
        $extra_cond = array();
        $menu_arr = array();

        $result_arr =  $model->where('eStatus', 'Active')->orderBy('iParentId', 'ASC')->orderBy('iSequenceOrder', 'ASC')->findAll();
        $home_arr = $profile_arr = $password_arr = $logout_arr = array();
        for ($i = 0; $i < count($result_arr); $i++) {
            $admin_menu_id = $result_arr[$i]['iAdminMenuId'];
            $parent_id = $result_arr[$i]['iParentId'];
            $access_code = $result_arr[$i]['vCapabilityCode'];
            $menu_display = $result_arr[$i]['vMenuDisplay'];
            $column_number = $result_arr[$i]['iColumnNumber'];
            $url = $result_arr[$i]['vURL'];
            $icon = $result_arr[$i]['vIcon'];
            $open = isset($result_arr[$i]['eOpen']) ? $result_arr[$i]['eOpen'] : "same";
            $is_external = $general->isExternalURL($url);

            $menu_display_lang = $general->getDisplayLabel("Generic", $menu_display, "label");
            $menu_display_text = $menu_display_lang;
            if ($icon == "") {
                $icon = ($parent_id > 0) ? "icomoon-icon-file" : "icomoon-icon-stats-up";
            }
            $data_arr = array();
            $data_arr['id'] = $admin_menu_id;
            $data_arr['parent_id'] = $parent_id;
            $data_arr['label'] = $menu_display;
            $data_arr['label_lang'] = ($menu_display_text == '') ? $menu_display : $menu_display_text;
            $data_arr['icon'] = $icon;
            $data_arr['code'] = strtolower($access_code);
            $data_arr['class'] = 'menu-navigation-link';
            if ($is_external) {
                $data_arr['url'] = $url;
                $data_arr['url_enc'] = $url;
                $data_arr['target'] = "_blank";
            } else {
                $extra_attr = '';
                $url_arr = explode("|", $url);
                if (is_array($url_arr) && count($url_arr) > 1) {
                    $url_dec = $url_arr[0];
                    for ($j = 1; $j < count($url_arr); $j += 2) {
                        $param_key = $url_arr[$j];
                        $param_val = $url_arr[$j + 1];
                        if (strstr($param_val, "{%REQUEST") !== FALSE) {
                            $param_val = $general->processRequestPregMatch($param_val);
                        }
                        if (strstr($param_val, "{%SERVER") !== FALSE) {
                            $param_val = $general->processServerPregMatch($param_val);
                        }
                        if (strstr($param_val, "{%SYSTEM") !== FALSE) {
                            $param_val = $general->processSystemPregMatch($param_val);
                        }
                        if (strstr($param_val, "{%SESSION") !== FALSE) {
                            $param_val = $general->processSessionPregMatch($param_val);
                        }
                        if (is_array($encrypt_arr) && in_array($param_key, $encrypt_arr)) {
                            $param_val = $param_val;
                        }
                        $extra_attr .= "|" . $param_key . "|" . $param_val;
                    }
                } else {
                    $url_dec = $url;
                }
                $url_enc = $url_dec;
                $data_arr['url'] = base_url() . "#" . $url_enc . "" . $extra_attr;

                $data_arr['target'] = "_self";
                if ($open == "new") {
                    $data_arr['target'] = "_blank";
                } else if ($open == "popup_ajax") {
                    $data_arr['class'] = "fancybox-ajax " . $data_arr['class'];
                } else if ($open == "popup_iframe") {
                    $data_arr['class'] = "fancybox-popup " . $data_arr['class'];
                }
            }
            if (!$admin_profile_links && $type != "Sitemap" && $access_code == 'admin_edit_profile') {
                $profile_arr = $data_arr;
            } elseif (!$admin_profile_links && $type != "Sitemap" && $access_code == 'admin_change_password') {
                $password_arr = $data_arr;
            } else {

                if ($type == "Top") {
                    if ($column_number < 0 || $column_number > 3) {
                        $column_number = 1;
                    }
                    $menu_arr[$parent_id][$column_number][] = $data_arr;
                } else {
                    $menu_arr[$parent_id][] = $data_arr;
                }
            }
        }

        if (config('AppConfig')->ENABLE_ROLES_CAPABILITIES) {

            $result_arr =  $model->where('eStatus', 'Active')->where('iParentId', 0)->orderBy('iSequenceOrder', 'ASC')->findAll();
            for ($i = 0; $i < count($result_arr); $i++) {
                $parent_menu_id = $result_arr[$i]['iAdminMenuId'];
                if (is_array($menu_arr[$parent_menu_id]) && count($menu_arr[$parent_menu_id]) > 0) {
                    $admin_menu_id = $result_arr[$i]['iAdminMenuId'];
                    $menu_display = $result_arr[$i]['vMenuDisplay'];
                    $icon = $result_arr[$i]['vIcon'];
                    $menu_display_lang = $general->getDisplayLabel("Generic", $menu_display, "label");
                    $menu_display_text = $menu_display_lang;
                    if ($icon == "") {
                        $icon = "icomoon-icon-stats-up";
                    }
                    $data_arr = array();
                    $data_arr['id'] = $admin_menu_id;
                    $data_arr['label'] = $menu_display;
                    $data_arr['label_lang'] = ($menu_display_text == '') ? $menu_display : $menu_display_text;
                    $data_arr['icon'] = $icon;
                    $data_arr['code'] = $result_arr[$i]['vCapabilityCode'];
                    if ($type == "Top") {
                        $menu_arr[0][1][] = $data_arr;
                    } else {
                        $menu_arr[0][] = $data_arr;
                    }
                }
            }
        }

        $hurl_enc = "dashboard/dashboard/sitemap";
        $home_arr['url'] = base_url() . "#" . $hurl_enc;

        if ($admin_profile_links) {
            $purl_enc = "user/admin/add";
            $pmode_enc = "Update";
            $pid_enc = session()->get('adminData')['id'];

            $profile_arr['url'] = base_url() . "#" . $purl_enc . "|mode|" . $pmode_enc . "|id|" . $pid_enc . "|tEditFP|true|hideCtrl|true";
            $profile_arr['icon'] = "icomoon-icon-user-3";
            $profile_arr['label'] = "Edit Profile";
            $profile_arr['label_lang'] = 'GENERIC_EDIT_PROFILE';
            $profile_arr['code'] = "admin_edit_profile";
        }

        if ($admin_profile_links) {
            $curl_enc = "user/login/changepassword";
            $password_arr['url'] = base_url() . "#" . $curl_enc;
            $password_arr['icon'] = "icomoon-icon-key";
            $password_arr['label'] = "Change Password";
            $password_arr['label_lang'] = 'GENERIC_CHANGE_PASSWORD';
            $password_arr['code'] = "admin_change_password";
        }

        $lurl_enc = "user/login/logout";
        $logout_arr['url'] = base_url() . $lurl_enc;
        $logout_arr['icon'] = "icomoon-icon-exit";
        $logout_arr['label'] = "Log Out";
        $logout_arr['label_lang'] = 'GENERIC_LOGOUT';
        $logout_arr['code'] = "session_logout";

       

        $menu_list = array(
            'menu' => $menu_arr,
            'home' => $home_arr,
            'password' => $password_arr,
            'profile' => $profile_arr,
            'logout' => $logout_arr,
        );

        $menu_callback = config('AppConfig')->menu_callback;
        if ($menu_callback != "" && method_exists($general, $menu_callback)) {
            $menu_list = $general->$menu_callback($menu_list, $type);
        }
        // echo "<pre>";
        // print_r($menu_list);exit;
        $menu_data['total_arr'] = $menu_list;
        $menu_data['menu_arr'] = $menu_list['menu'];
        $menu_data['home_arr'] = $menu_list['home'];
        $menu_data['profile_arr'] = $menu_list['profile'];
        $menu_data['password_arr'] = $menu_list['password'];
        $menu_data['logout_arr'] = $menu_list['logout'];
        $menu_data['parent_arr'] = $menu_list['menu'][0];

            return $menu_data;
    }
}
