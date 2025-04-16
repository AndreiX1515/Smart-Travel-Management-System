<?php
session_start();
require "../../../conn.php"; // DB connection

$apiKey = '77dc42e0276c97b3f723a125'; // ExchangeRate-API key
$base = 'USD';
$target = 'PHP';
$fallbackRate = 56.50;
$provider = 'ExchangeRate-API';

$logFile = __DIR__ . "/currency_rate_log.txt"; // Logs all activity
date_default_timezone_set('Asia/Taipei'); // Set the timezone to Taipei
$currentDate = date('Y-m-d');
$currentTime = date('Y-m-d H:i:s');

// Function to log messages
function logMessage($message) {
    global $logFile;
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// Function to fetch exchange rate from API
function fetchRate($apiKey, $base, $target) {
    $url = "https://v6.exchangerate-api.com/v6/$apiKey/pair/$base/$target";
    $response = @file_get_contents($url);
    return $response ? json_decode($response, true) : null;
}

// Echo current time to browser console
echo "<script>console.log('Current Time: $currentTime');</script>";

// Fetch exchange rate from API
$data = fetchRate($apiKey, $base, $target);

if ($data && $data['result'] === 'success') {
    $rate = $data['conversion_rate'];

    $insert = $conn->prepare("INSERT INTO currencyrates (base_currency, target_currency, exchange_rate, date_recorded, time_recorded, provider) VALUES (?, ?, ?, ?, ?, ?)");
    $insert->bind_param("ssdsss", $base, $target, $rate, $currentDate, $currentTime, $provider);
    $insert->execute();
    $insert->close();

    $message = "✅ USD to PHP rate saved: ₱$rate ($currentDate $currentTime)";
    echo $message;
    logMessage($message);
} else {
    // Fallback
    $rate = $fallbackRate;
    $fallbackProvider = 'Fallback Manual Rate';

    $insert = $conn->prepare("INSERT INTO currencyrates (base_currency, target_currency, exchange_rate, date_recorded, time_recorded, provider) VALUES (?, ?, ?, ?, ?, ?)");
    $insert->bind_param("ssdsss", $base, $target, $rate, $currentDate, $currentTime, $fallbackProvider);
    $insert->execute();
    $insert->close();

    $message = "⚠️ API failed. Inserted fallback rate ₱$rate for $currentDate at $currentTime.";
    echo $message;
    logMessage($message);
}

$conn->close();
?>
