<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$studentsQuery = $pdo->query("SELECT * FROM students");
$students = $studentsQuery->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $checked_items = json_encode($_POST['materials']);
    $status = count($_POST['materials']) == count(json_decode($studentsQuery['materials_needed'])) ? 'checked_in' : 'missing_items';

    $stmt = $pdo->prepare("INSERT INTO checkins (student_id, checked_items, timestamp) VALUES (?, ?, NOW())");
    $stmt->execute([$student_id, $checked_items]);

    // Update student status
    $pdo->query("UPDATE students SET status='$status', arrival_time=NOW() WHERE id='$student_id'");

    // Send SMS
    $message = ($status == 'checked_in') ? "Your child has arrived with all materials." : "Your child has arrived but is missing some materials.";
    $phone = $_POST['parent_contact'];
    sendSMS($phone, $message);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check-In - SEMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="checkin-page">
    <h1>Student Check-In</h1>
    <form action="checkin.php" method="post">
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Materials</th>
                    <th>Parent Contact</th>
                    <th>Check-In</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= $student['name'] ?></td>
                    <td>
                        <?php $materials = json_decode($student['materials_needed']); ?>
                        <?php foreach ($materials as $material): ?>
                        <label>
                            <input type="checkbox" name="materials[]" value="<?= $material ?>"> <?= $material ?>
                        </label>
                        <?php endforeach; ?>
                    </td>
                    <td><?= $student['parent_contact'] ?></td>
                    <td>
                        <button type="submit" name="student_id" value="<?= $student['id'] ?>">Check In</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</body>
</html>

