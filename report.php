<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$checkinsQuery = $pdo->query("SELECT s.name, c.checked_items, c.timestamp, c.message_status 
    FROM checkins c
    JOIN students s ON c.student_id = s.id
    ORDER BY c.timestamp DESC");
$checkins = $checkinsQuery->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check-In Report - SEMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="report-page">
    <h1>Check-In Report</h1>
    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Checked Items</th>
                <th>Timestamp</th>
                <th>Message Sent</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($checkins as $checkin): ?>
            <tr>
                <td><?= $checkin['name'] ?></td>
                <td><?= implode(', ', json_decode($checkin['checked_items'])) ?></td>
                <td><?= $checkin['timestamp'] ?></td>
                <td><?= $checkin['message_status'] ? 'Yes' : 'No' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
