<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>{company_name}</title>
  <style>
    a {
      color: #3382c4;
      text-decoration: underline;
    }

    a:hover {
      color: #252525 !important;
    }

    a img:hover {
      opacity: 0.8;
    }

    @media screen and (max-width: 767px) {
      *[class='wrapper'] {
        width: 100% !important;
        margin: auto !important;
        max-width: inherit !important;
      }

      *[class='column'] {
        display: block !important;
        width: 100% !important;
        max-width: inherit !important;
        box-sizing: border-box !important;
      }
    }
  </style>
</head>

<body style="font-family: Segoe UI, Trebuchet MS, Arial,sans-serif; 
    background:#f6f6f6; color: #252525; font-size: 16px; margin: 0; padding:0;">
  <table class="wrapper" style="width:700px; margin: 50px auto; display:block;">
    <tr>
      <td class="column" style="width: 700px; display: block; clear: both;">
        <table class="wrapper" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td height="5" bgcolor="#17abaa"></td>
          </tr>
          <tr>
            <td bgcolor="#ffffff">
              <table class="wrapper" style="width: 640px; display: block; clear: both;" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="center" style="width: 640px; padding-top:15px;">
                    <img src="https://www.hiddenbrains.com/public/local-cdn/images/time-management-logo.png" alt="Hidden Brains" width='370px' />
                  </td>
                </tr>
                <tr>
                  <td width="100%">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:5px 15px;">
                      <tr>
                        <td style="padding-bottom:5px;">
                          <font face="Segoe UI,Trebuchet MS,Arial, Helvetica, sans-serif" color="#404040" style="font-size: 16px;line-height: 23px;line-height:25px;">
                            <div class='content' style='width:100%;padding: 15px 20px;'>
                              <p style='width:90%;'>Hello <span><b>{user_name}</b> , </span></p>
                              <p style='width:90%;'>{content}</p>
                          </font>
                        </td>
                      </tr>
                      <tr>
                        <td style="padding-bottom:15px;padding-top:25px;">
                          <font face="Segoe UI,Trebuchet MS,Arial, Helvetica, sans-serif" color="#404040" style="font-size: 16px;line-height: 23px;">
                            Regards, <br />
                            Team {company_name}<br />
                            {site_url}
                          </font>
                        </td>
                      </tr>
                      <tr>
                        <td height="15px"></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td height="140" align="center">
              <font face="Segoe UI, Trebuchet MS, Arial,sans-serif" color="#666666" style="font-size: 14px; line-height: 20px;">This email was sent to you because you signed up for {company_name}. <br />{copyright_text}.<br />
                <a href="{site_url}" style=" color:#17abab;">{site_url}</a>
              </font>
              <br /><br />
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>

</html>