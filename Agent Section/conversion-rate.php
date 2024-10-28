<?php
// Define the time interval in seconds (3600 seconds = 1 hour)
$timeInterval = 3600;
$lastFetchFile = 'last_fetch_time.txt';

// Check when the last fetch was performed
if (file_exists($lastFetchFile)) {
    $lastFetchTime = file_get_contents($lastFetchFile);
    if (time() - $lastFetchTime < $timeInterval) {
        // Fetch the rates from the saved file instead
        $exchangeRates = json_decode(file_get_contents('exchange_rates.json'), true);
    } else {
        // Your API key
        $apiKey = '77dc42e0276c97b3f723a125';

        // Fetching the latest conversion rates
        function getExchangeRates($apiKey) {
            $url = "https://v6.exchangerate-api.com/v6/$apiKey/latest/USD"; // USD as the base currency
            $response = file_get_contents($url);
            return json_decode($response, true);
        }

        // Get conversion rates
        $exchangeRates = getExchangeRates($apiKey);
        file_put_contents('exchange_rates.json', json_encode($exchangeRates));
        // Update the last fetch time
        file_put_contents($lastFetchFile, time());
    }
} else {
    // Fetch rates for the first time
    $apiKey = '77dc42e0276c97b3f723a125';
    $exchangeRates = getExchangeRates($apiKey);
    file_put_contents('exchange_rates.json', json_encode($exchangeRates));
    file_put_contents($lastFetchFile, time());
}

// Use conversion rates
$usd_to_php = $exchangeRates['conversion_rates']['PHP'] ?? 56.50;
$usd_to_krw = $exchangeRates['conversion_rates']['KRW'] ?? 1320;
$usd_to_euro = $exchangeRates['conversion_rates']['EUR'] ?? 0.85;
?>
