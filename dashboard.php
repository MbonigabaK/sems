<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

// Fetch summary
$summaryQuery = $pdo->query("SELECT COUNT(id) AS total, 
    SUM(CASE WHEN status = 'checked_in' THEN 1 ELSE 0 END) AS checked_in 
    FROM students");
$summary = $summaryQuery->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SEMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-page">
    <h1>Welcome to SEMS Dashboard</h1>
    <div class="summary">
        <p>Total Students: <?= $summary['total'] ?></p>
        <p>Checked In: <?= $summary['checked_in'] ?></p>
    </div>
    <div class="quick-actions">
        <a href="checkin.php">Start Check-In</a>
        <a href="report.php">View Reports</a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>

