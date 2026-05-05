<?php
// fedex_api.php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/logger.php';

/**
 * Retrieves the OAuth token from FedEx API
 *
 * @return string|null The access token, or null on failure
 */
function getFedexToken() {
    $tokenFile = sys_get_temp_dir() . '/fedex_token.json';
    
    // Check if valid token exists in cache
    if (file_exists($tokenFile)) {
        $cache = json_decode(file_get_contents($tokenFile), true);
        if (isset($cache['access_token']) && isset($cache['expires_at']) && time() < $cache['expires_at']) {
            return $cache['access_token'];
        }
    }

    $url = FEDEX_BASE_URL . '/oauth/token';
    
    // Prepare the payload
    $postData = http_build_query([
        'grant_type' => 'client_credentials',
        'client_id' => FEDEX_API_KEY,
        'client_secret' => FEDEX_SECRET_KEY
    ]);

    // Initialize cURL session
    $ch = curl_init($url);
    
    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    // Execute request and get response
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    // Close cURL session
    curl_close($ch);

    // Handle errors safely for production
    if ($error || $httpCode !== 200) {
        fedexLog("FedEx Token Error", ['error' => $error, 'httpCode' => $httpCode, 'response' => $response]);
        return null;
    }

    $responseData = json_decode($response, true);
    $token = $responseData['access_token'] ?? null;
    
    if ($token && isset($responseData['expires_in'])) {
        // Cache token, expiring 5 minutes before actual expiry
        $cacheData = [
            'access_token' => $token,
            'expires_at' => time() + $responseData['expires_in'] - 300
        ];
        file_put_contents($tokenFile, json_encode($cacheData));
    }
    
    return $token;
}

/**
 * Reusable helper for FedEx API requests
 *
 * @param string $endpoint The API endpoint (e.g., '/ship/v1/shipments')
 * @param string $method HTTP Method (GET, POST, PUT, DELETE)
 * @param array|null $body Request payload (will be JSON encoded)
 * @param string $token The Bearer token
 * @return array The decoded JSON response, HTTP status code, and any error
 */
function fedexRequest($endpoint, $method, $body, $token) {
    // Construct full URL
    $url = FEDEX_BASE_URL . $endpoint;
    
    // Initialize cURL session
    $ch = curl_init($url);
    
    // Setup Headers
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    // Set basic cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_ENCODING, ''); // Automatically decode gzip/deflate
    
    // Add body if it's not a GET request and body is provided
    if ($body !== null && strtoupper($method) !== 'GET') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }
    
    // Execute request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    // Close cURL session
    curl_close($ch);
    
    if ($error) {
        fedexLog("FedEx API Request Error", ['error' => $error, 'endpoint' => $endpoint]);
    }
    
    $decodedResponse = json_decode($response, true);
    
    if ($httpCode >= 400) {
        fedexLog("FedEx API Failed Response", ['endpoint' => $endpoint, 'httpCode' => $httpCode, 'response' => $decodedResponse ?: $response]);
    }
    
    return [
        'status_code' => $httpCode,
        'response' => json_decode($response, true) ?: $response, // Return raw response if not JSON
        'error' => $error
    ];
}
