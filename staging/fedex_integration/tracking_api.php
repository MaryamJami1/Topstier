<?php
// tracking_api.php

require_once __DIR__ . '/fedex_api.php';

/**
 * Tracks a shipment using FedEx API
 *
 * @param string $trackingNumber The tracking number
 * @return array Status details or error
 */
function trackShipment($trackingNumber) {
    $token = getFedexToken();
    if (!$token) {
        return ['error' => 'Could not authenticate with FedEx API.'];
    }

    $endpoint = '/track/v1/trackingnumbers';

    $payload = [
        "includeDetailedScans" => true,
        "trackingInfo" => [
            [
                "trackingNumberInfo" => [
                    "trackingNumber" => $trackingNumber
                ]
            ]
        ]
    ];

    $result = fedexRequest($endpoint, 'POST', $payload, $token);

    if ($result['status_code'] == 200 && isset($result['response']['output']['completeTrackResults'][0])) {
        $trackResult = $result['response']['output']['completeTrackResults'][0]['trackResults'][0] ?? null;
        
        if ($trackResult && isset($trackResult['latestStatusDetail'])) {
            return [
                'success' => true,
                'status' => $trackResult['latestStatusDetail']['statusByLocale'] ?? 'Unknown',
                'description' => $trackResult['latestStatusDetail']['description'] ?? '',
                'events' => $trackResult['scanEvents'] ?? []
            ];
        }
    }

    return [
        'error' => 'Failed to track shipment',
        'details' => $result['response']['errors'] ?? 'Unknown error'
    ];
}
