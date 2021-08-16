<?PHP
ini_set("log_errors", 1);
ini_set("error_log", "/php-error.log");
error_log( "Hello, errors!" );
include_once __DIR__ . '/../../config/config.php';
$uploads_path = PROFPIC_UPLOADS;
header('Content-Type: application/json');
require __DIR__.'/../../vendor/autoload.php';




echo json_encode($value);
/*$recipient= 'ExponentPushToken[T6cfL-N_8gAvzX5Jvjg4pD]';

// You can quickly bootup an expo instance
$expo = \ExponentPhpSDK\Expo::normalSetup();

// Subscribe the recipient to the server
$expo->subscribe($channelName, $recipient);

// Build the notification data
$notification = ['body' => 'Hello World!',
    'sound'=>'default', 'title'=>'Original Title Here',
    'data'=> json_encode(array('someData' => 'goes here'))];

// Notify an interest with a notification
$expo->notify([$channelName], $notification);*/
?>