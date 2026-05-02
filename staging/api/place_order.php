<?php
// api/place_order.php

header('Content-Type: application/json');

require_once __DIR__ . '/../fedex_integration/shipment_creator.php';
require_once __DIR__ . '/../fedex_integration/email_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method. POST required.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Basic validation
if (empty($data['customer_email']) || empty($data['shipping_service'])) {
    echo json_encode(['error' => 'Missing required fields.']);
    exit;
}

// TODO: Replace with actual database credentials and connection logic
$dbHost = 'localhost';
$dbName = 'your_database';
$dbUser = 'username';
$dbPass = 'password';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Start transaction
    $pdo->beginTransaction();

    // 1. Save order to database
    $stmt = $pdo->prepare("INSERT INTO orders (customer_email, shipping_service, shipping_price, total_price) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $data['customer_email'],
        $data['shipping_service'],
        $data['shipping_price'] ?? 0,
        $data['total_price'] ?? 0
    ]);
    
    $orderId = $pdo->lastInsertId();

    // 2. Call FedEx Shipment API
    $orderData = [
        'customer_name' => $data['customer_name'] ?? 'Guest',
        'customer_phone' => $data['customer_phone'] ?? '0000000000',
        'address_line1' => $data['address_line1'],
        'city' => $data['city'],
        'state' => $data['state'],
        'zip' => $data['zip'],
        'country' => $data['country'] ?? 'US',
        'shipping_service' => $data['shipping_service'], // e.g., FEDEX_GROUND
        'weight' => $data['weight'] ?? 1.0
    ];

    $shipmentResult = createShipment($orderData);

    if (isset($shipmentResult['error'])) {
        throw new Exception("FedEx API Error: " . json_encode($shipmentResult));
    }

    // 3. Store Tracking and Label
    $stmt = $pdo->prepare("INSERT INTO shipments (order_id, tracking_number, label_data, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $orderId,
        $shipmentResult['tracking_number'],
        $shipmentResult['label'],
        'shipped'
    ]);

    // Update order status to shipped
    $pdo->prepare("UPDATE orders SET status = 'shipped' WHERE id = ?")->execute([$orderId]);

    // Commit transaction
    $pdo->commit();

    // 4. Send Email via PHPMailer
    sendShipmentEmail($data['customer_email'], $shipmentResult['tracking_number']);

    // Return Success
    echo json_encode([
        'success' => true,
        'order_id' => $orderId,
        'tracking_number' => $shipmentResult['tracking_number'],
        'message' => 'Order placed and shipment created successfully.'
    ]);

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Order Flow Error: " . $e->getMessage());
    echo json_encode([
        'error' => 'Failed to process order',
        'details' => $e->getMessage()
    ]);
}
