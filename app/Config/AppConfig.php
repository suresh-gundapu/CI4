<?php

namespace Config;

use Config\Database;

use CodeIgniter\Config\BaseConfig;

class AppConfig extends BaseConfig
{
    public $admin_account_lock_attempts = 5;
    public $admin_account_locked_duration = 15;
    public $admin_password_change_remainder = 90;
    public $email_sending_library = 'system'; //phpmailer
    public $company_name = "Configure.IT";
    public $copyright_text = "";
    public $admin_password_history = 5;
    public $admin_password_expiry = 90;

    public $USE_SMTP_SERVERHOST = "ssl://smtp.gmail.com";

    public $USE_SMTP_SERVERUSERNAME = "cit.email002@gmail.com";

    public $USE_SMTP_SERVERPASS = "yxvukgtjfvhawdmm";

    public $USE_SMTP_SERVERCRYPTO = "";

    public $USE_SMTP_SERVERPORT = 465;

    public $EMAIL_ADMIN = "suresh.gundapu@hiddenbrains.in";

    public  $admin_2fa_authentication = TRUE;

    public $SMS_ACTIVE_API = "twilio";

    public $SMS_NX_API_KEY = "a7b1071a";

    public $SMS_NX_API_SECRET = "c6613a167dcf1a81";

    public $SMS_FROM_NUMBER = "";

    public $SMS_CA_API_TOKEN = "";

    public $SMS_TW_API_SID = "";

    public $SMS_TW_API_TOKEN = "";

    public $ADMIN_RESET_PASSWORD_TIME = 12; //12 hours

    //framework params
    public $FRAMEWORK_ENCRYPTS = array(
        "mode", "id", "parMod", "parMod2", "parID", "parID2", "parKey", "parKey2",
        "rfMod", "rfFod", "SGID", "renderMod", "switchIDs"
    );

    public $ENABLE_ROLES_CAPABILITIES = FALSE;

   public  $menu_callback = '';


}
