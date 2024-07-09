<?php
session_start();
require_once 'includes/db.php';
include 'templates/header.php';

// Перевірка чи користувач авторизований
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Отримання відправлених повідомлень
$sql_sent = "SELECT id, sender_email, receiver_email, subject, body, created_at FROM messages WHERE sender_id = ? ORDER BY created_at DESC";
$stmt_sent = mysqli_prepare($conn, $sql_sent);
if (!$stmt_sent) {
    die('MySQL prepare error: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt_sent, "i", $user_id);
if (!mysqli_stmt_execute($stmt_sent)) {
    die('MySQL execute error: ' . mysqli_error($conn));
}

$result_sent = mysqli_stmt_get_result($stmt_sent);
if (!$result_sent) {
    die('MySQL get result error: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sent Messages</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        
        <h2 class="mt-4 mb-4">Sent Messages</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>Subject</th>
                    <th>Date Sent</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result_sent)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['sender_email']); ?></td>
                    <td><?php echo htmlspecialchars($row['receiver_email']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($row['body']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
