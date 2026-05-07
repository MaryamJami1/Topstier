<?php
// shipping_rates.php

require_once __DIR__ . '/fedex_api.php';

/**
 * Retrieves shipping rates based on from/to addresses and weight.
 */
function getShippingRates($from, $to, $weight) {
    $token = getFedexToken();
    if (!$token) {
        return [];
    }

    $endpoint = '/rate/v1/rates/quotes';

    // Minimal payload based on FedEx Rate API requirements
    $payload = [
        "accountNumber" => [
            "value" => FEDEX_ACCOUNT_NUMBER
        ],
        "requestedShipment" => [
            "shipper" => [
                "address" => [
                    "postalCode" => $from['postalCode'],
                    "countryCode" => $from['countryCode']
                ]
            ],
            "recipient" => [
                "address" => [
                    "postalCode" => $to['postalCode'],
                    "countryCode" => $to['countryCode']
                ]
            ],
            "pickupType" => "DROPOFF_AT_FEDEX_LOCATION",
            "rateRequestType" => [
                "LIST",
                "ACCOUNT"
            ],
            "requestedPackageLineItems" => [
                [
                    "weight" => [
                        "units" => "LB",
                        "value" => $weight
                    ]
                ]
            ]
        ]
    ];

    $result = fedexRequest($endpoint, 'POST', $payload, $token);
    $rates = [];

    if ($result['status_code'] == 200 && isset($result['response']['output']['rateReplyDetails'])) {
        $blockedServices = [
            'FIRST_OVERNIGHT',
            'PRIORITY_OVERNIGHT',
            'FEDEX_2_DAY_AM'
        ];

        foreach ($result['response']['output']['rateReplyDetails'] as $rateDetail) {
            $serviceName = $rateDetail['serviceName'] ?? 'Standard';
            $serviceType = $rateDetail['serviceType'] ?? 'FEDEX_GROUND';
            
            // Skip blocked services
            if (in_array($serviceType, $blockedServices)) {
                continue;
            }

            $price = 0;
            
            // Extract the net charge for the shipment
            if (isset($rateDetail['ratedShipmentDetails'][0]['totalNetCharge'])) {
                 $price = $rateDetail['ratedShipmentDetails'][0]['totalNetCharge'];
            }
            
            if ($price > 0) {
                 $rates[] = [
                     'serviceName' => $serviceName,
                     'serviceType' => $serviceType,
                     'price' => (float)$price
                 ];
            }
        }
    }

    return $rates;
}
