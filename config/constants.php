<?php
$xs = '';

 function getURL()
{
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $url = "https://";
    else {



if($_SERVER['HTTP_HOST'] == 'localhost'){
    $url = "http://";
}else{
    $url = "https://";
}
    }
    // Append the host(domain name, ip) to the URL.
    $url.= $_SERVER['HTTP_HOST'];

    // Append the requested resource location to the URL
    $url.= $_SERVER['REQUEST_URI'];

    return $url;
}
  function gethostURL()
  {
      if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
          $url = "https://";
      else
          $url = "http://";
      // Append the host(domain name, ip) to the URL.
      $url .= $_SERVER['HTTP_HOST'];
      return $url;
  }
$domain_app = "http://localhost/bigat_control/bigat_control";
 $main_app_domain = "https://bigat.hopewwkenya.org";
//$domain_app = "http://bigat.bonificialtechnologies.com";
define("APP_CONTROLLER_URL", $domain_app.'/controller' );
define("MAIN_APP_CONTROLLER_URL", $main_app_domain.'/controller' );
define('ROOTPATH_', __DIR__);
define('LOGINPAGE', $xs . 'login');
define('LOGOUTPAGE', $xs . 'logout');

define('STYLES', $xs . 'assets/styles');
define('SCRIPTS', $xs . 'assets/scripts');
define('IMAGES', $xs . 'assets/img');
define('ASSET_ROOT', $xs . 'assets');

function asset($asset)
{
    echo $asset;
}
define("PROFPIC_UPLOADS", '/uploads' );
define("EVENT_UPLOADS", '/uploads' );

define("APPLICATION_NAME", "BIGAT");


/*define("HOSTNAME", "us-cdbr-east-02.cleardb.com");
define("USERID", "bcef23ecd96b4f");
define("PASSWORD", "45acb00e");
define("DATABASE", "heroku_ef24ecdfb6a8080");*/

define("HOSTNAME", "bonificialtechnologies.com");
define("USERID", "bonific1_project");
define("PASSWORD", "project@");
define("DATABASE", "bonific1_bigat");

date_default_timezone_set('Africa/Nairobi');
define('DATE_TIME_FORMAT','Y-m-d\TH:i:s');
error_reporting(E_ALL);
ini_set('display_errors', 1);