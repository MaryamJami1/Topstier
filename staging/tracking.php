<?php
// tracking.php

require_once __DIR__ . '/fedex_integration/tracking_api.php';

$trackingResult = null;
$trackingNumber = $_GET['tracking_number'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trackingNumber = $_POST['tracking_number'] ?? '';
    if ($trackingNumber) {
        $trackingResult = trackShipment($trackingNumber);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Shipment</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        input[type="text"] { width: 100%; padding: 10px; box-sizing: border-box; font-size: 16px; }
        button { padding: 10px 15px; background-color: #2196F3; color: white; border: none; cursor: pointer; font-size: 16px;}
        button:hover { background-color: #0b7dda; }
        .result-box { margin-top: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 4px; }
        .status-badge { display: inline-block; padding: 5px 10px; background: #e0e0e0; border-radius: 12px; font-weight: bold; }
        .event-list { list-style: none; padding: 0; }
        .event-item { padding: 10px 0; border-bottom: 1px solid #eee; }
        .event-item:last-child { border-bottom: none; }
        .event-date { color: #666; font-size: 0.9em; }
    </style>
</head>
<body>
    <h2>Track Your Shipment</h2>
    
    <form method="POST">
        <div class="form-group">
            <input type="text" name="tracking_number" placeholder="Enter FedEx Tracking Number" value="<?php echo htmlspecialchars($trackingNumber); ?>" required>
        </div>
        <button type="submit">Track Package</button>
    </form>

    <?php if ($trackingResult): ?>
        <div class="result-box">
            <?php if (isset($trackingResult['error'])): ?>
                <p style="color:red;">Error: <?php echo htmlspecialchars($trackingResult['error']); ?></p>
            <?php else: ?>
                <h3>Current Status: <span class="status-badge"><?php echo htmlspecialchars($trackingResult['status']); ?></span></h3>
                <p><strong>Details:</strong> <?php echo htmlspecialchars($trackingResult['description']); ?></p>
                
                <?php if (!empty($trackingResult['events'])): ?>
                    <h4>Recent Scans</h4>
                    <ul class="event-list">
                        <?php foreach (array_slice($trackingResult['events'], 0, 5) as $event): ?>
                            <li class="event-item">
                                <strong><?php echo htmlspecialchars($event['eventDescription']); ?></strong><br>
                                <span class="event-date">
                                    <?php echo date('M d, Y - h:i A', strtotime($event['date'])); ?> 
                                    <?php if (isset($event['scanLocation']['city'])) echo ' - ' . htmlspecialchars($event['scanLocation']['city'] . ', ' . $event['scanLocation']['stateOrProvinceCode']); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>
