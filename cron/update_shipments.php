<?php
// update_shipments.php
// This file should be called by a cron job (e.g. hourly)

require_once __DIR__ . '/../fedex_integration/tracking_api.php';
require_once __DIR__ . '/../fedex_integration/logger.php';

// Prevent direct web access for security
if (php_sapi_name() !== 'cli' && !isset($_GET['secure_cron_key'])) {
    die("Access denied.");
}

// TODO: Replace with actual database credentials
$dbHost = 'localhost';
$dbName = 'your_database';
$dbUser = 'username';
$dbPass = 'password';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch active shipments that are not delivered yet
    $stmt = $pdo->query("SELECT id, tracking_number FROM shipments WHERE status NOT IN ('delivered', 'cancelled')");
    $activeShipments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $updatedCount = 0;

    foreach ($activeShipments as $shipment) {
        $trackingNumber = $shipment['tracking_number'];
        
        $result = trackShipment($trackingNumber);
        
        if (isset($result['success']) && $result['success']) {
            $newStatus = strtolower($result['status']);
            
            // Map FedEx statuses to DB statuses (simplified)
            $dbStatus = 'in_transit';
            if (strpos($newStatus, 'delivered') !== false) {
                $dbStatus = 'delivered';
            } elseif (strpos($newStatus, 'exception') !== false) {
                $dbStatus = 'exception';
            }
            
            $updateStmt = $pdo->prepare("UPDATE shipments SET status = ? WHERE id = ?");
            $updateStmt->execute([$dbStatus, $shipment['id']]);
            
            $updatedCount++;
        } else {
            fedexLog("Cron: Failed to track shipment", ['tracking_number' => $trackingNumber, 'error' => $result['error'] ?? 'Unknown']);
        }
        
        // Optional: Avoid hitting rate limits (e.g., sleep for 1 second)
        sleep(1);
    }
    
    fedexLog("Cron Completed", ['shipments_updated' => $updatedCount], 'INFO');
    echo "Cron completed successfully. Updated $updatedCount shipments.\n";

} catch (Exception $e) {
    fedexLog("Cron Fatal Error", ['message' => $e->getMessage()]);
    echo "Cron failed: " . $e->getMessage() . "\n";
}
