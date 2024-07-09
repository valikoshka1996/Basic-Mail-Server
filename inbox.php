<?php
session_start();
require_once 'includes/db.php';
include 'templates/header.php';

// Перевірка чи користувач авторизований
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_email = $_SESSION['user_email'];

// Отримання отриманих повідомлень
$sql_inbox = "SELECT id, sender_email, receiver_email, subject, body, created_at FROM messages WHERE receiver_email = ? ORDER BY created_at DESC";
$stmt_inbox = mysqli_prepare($conn, $sql_inbox);
if (!$stmt_inbox) {
    die('MySQL prepare error: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt_inbox, "s", $user_email);
if (!mysqli_stmt_execute($stmt_inbox)) {
    die('MySQL execute error: ' . mysqli_error($conn));
}

$result_inbox = mysqli_stmt_get_result($stmt_inbox);
if (!$result_inbox) {
    die('MySQL get result error: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Inbox</h2>
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
                <?php while ($row = mysqli_fetch_assoc($result_inbox)) : ?>
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
        <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>
</body>
</html>
