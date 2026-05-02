<?php
// api/get_rates.php

header('Content-Type: application/json');

require_once __DIR__ . '/../fedex_integration/shipping_rates.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method. POST required.']);
    exit;
}

// Read JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($data['from_zip']) || !isset($data['to_zip']) || !isset($data['weight'])) {
    echo json_encode(['error' => 'Missing required parameters (from_zip, to_zip, weight)']);
    exit;
}

// Structure data for the helper function
$from = [
    'postalCode' => $data['from_zip'],
    'countryCode' => $data['from_country'] ?? 'US'
];

$to = [
    'postalCode' => $data['to_zip'],
    'countryCode' => $data['to_country'] ?? 'US'
];

$weight = (float)$data['weight'];

// Fetch rates
$rates = getShippingRates($from, $to, $weight);

// Return structured JSON
echo json_encode($rates);
exit;
