<?php
// shipment_creator.php

require_once __DIR__ . '/fedex_api.php';

/**
 * Creates a shipment with FedEx and returns tracking info and label
 *
 * @param array $orderData Order details for the shipment
 * @return array Tracking number and label data, or error
 */
function createShipment($orderData) {
    $token = getFedexToken();
    if (!$token) {
        return ['error' => 'Could not authenticate with FedEx API.'];
    }

    $endpoint = '/ship/v1/shipments';

    // Build the payload (simplified for beginner-friendly modular architecture)
    $payload = [
        "requestedShipment" => [
            "shipper" => [
                "address" => [
                    "streetLines" => ["10 FedEx Parkway"],
                    "city" => "Memphis",
                    "stateOrProvinceCode" => "TN",
                    "postalCode" => "38115",
                    "countryCode" => "US"
                ],
                "contact" => [
                    "personName" => "Store Shipper",
                    "phoneNumber" => "1234567890"
                ]
            ],
            "recipients" => [
                [
                    "address" => [
                        "streetLines" => [$orderData['address_line1']],
                        "city" => $orderData['city'],
                        "stateOrProvinceCode" => $orderData['state'],
                        "postalCode" => $orderData['zip'],
                        "countryCode" => $orderData['country'] ?? 'US'
                    ],
                    "contact" => [
                        "personName" => $orderData['customer_name'],
                        "phoneNumber" => $orderData['customer_phone']
                    ]
                ]
            ],
            "shipDatestamp" => date('Y-m-d'),
            "serviceType" => $orderData['shipping_service'], // e.g., FEDEX_GROUND
            "packagingType" => "YOUR_PACKAGING",
            "pickupType" => "USE_SCHEDULED_PICKUP",
            "blockInsightVisibility" => false,
            "shippingChargesPayment" => [
                "paymentType" => "SENDER"
            ],
            "labelSpecification" => [
                "imageType" => "PDF",
                "labelStockType" => "PAPER_85X11_TOP_HALF_LABEL"
            ],
            "requestedPackageLineItems" => [
                [
                    "weight" => [
                        "units" => "LB",
                        "value" => $orderData['weight'] ?? 1.0
                    ]
                ]
            ]
        ],
        "labelResponseOptions" => "LABEL",
        "accountNumber" => [
            "value" => FEDEX_ACCOUNT_NUMBER
        ]
    ];

    $result = fedexRequest($endpoint, 'POST', $payload, $token);

    if ($result['status_code'] == 200 && isset($result['response']['output']['transactionShipments'][0])) {
        $shipment = $result['response']['output']['transactionShipments'][0];
        
        $trackingNumber = $shipment['pieceResponses'][0]['trackingNumber'] ?? null;
        $labelData = $shipment['pieceResponses'][0]['packageDocuments'][0]['encodedLabel'] ?? null;
        
        if ($trackingNumber && $labelData) {
            return [
                'success' => true,
                'tracking_number' => $trackingNumber,
                'label' => $labelData // Base64 encoded string
            ];
        }
    }

    return [
        'error' => 'Failed to create shipment',
        'details' => $result['response']
    ];
}
