<?php
require_once 'config.php';

function register_user($username, $email, $password) {
    global $conn;
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password_hash);
    return mysqli_stmt_execute($stmt);
}

function login_user($username, $password) {
    global $conn;
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            return $row['id'];
        }
    }
    return false;
}

function send_message($sender_id, $receiver_email, $subject, $body) {
    global $conn;

    // Отримуємо email відправника
    $sender_email = get_user_email_by_id($sender_id);

    // Отримуємо id отримувача, якщо він зареєстрований
    $receiver_id = null;
    $sql_select_receiver_id = "SELECT id FROM users WHERE email = ?";
    $stmt_select_receiver_id = mysqli_prepare($conn, $sql_select_receiver_id);
    mysqli_stmt_bind_param($stmt_select_receiver_id, "s", $receiver_email);
    mysqli_stmt_execute($stmt_select_receiver_id);
    mysqli_stmt_bind_result($stmt_select_receiver_id, $receiver_id);
    mysqli_stmt_fetch($stmt_select_receiver_id);
    mysqli_stmt_close($stmt_select_receiver_id);

    // Вставляємо повідомлення у базу даних
    $sql_insert = "INSERT INTO messages (sender_id, sender_email, receiver_email, receiver_id, subject, body, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    if (!$stmt_insert) {
        die('MySQL prepare error: ' . mysqli_error($conn));
    }

    // Визначаємо, як використовувати receiver_id в залежності від його наявності
    if ($receiver_id === null) {
        mysqli_stmt_bind_param($stmt_insert, "isssss", $sender_id, $sender_email, $receiver_email, $receiver_id, $subject, $body);
    } else {
        mysqli_stmt_bind_param($stmt_insert, "isssss", $sender_id, $sender_email, $receiver_email, $receiver_id, $subject, $body);
    }

    if (!mysqli_stmt_execute($stmt_insert)) {
        die('MySQL execute error: ' . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt_insert);

    // Використання функції mail для відправлення повідомлення
    $headers = "From: $sender_email\r\n";
    $headers .= "Reply-To: $sender_email\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $mail_body = nl2br($body);
    if (mail($receiver_email, $subject, $mail_body, $headers)) {
        return true;
    } else {
        die('Failed to send message. Check the recipient\'s email.');
    }
}





function get_user_email_by_id($user_id) {
    global $conn;
    $sql = "SELECT email FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['email'];
    }
    return null;
}


function get_inbox($user_id) {
    global $conn;
    $sql = "SELECT m.id, u.username as sender, m.subject, m.body, m.created_at
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.receiver_id = ? AND m.is_spam = 0
            ORDER BY m.created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function get_sent($user_id) {
    global $conn;
    $sql = "SELECT m.id, u.username as receiver, m.subject, m.body, m.created_at
            FROM messages m
            JOIN users u ON m.receiver_id = u.id
            WHERE m.sender_id = ?
            ORDER BY m.created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function get_spam($user_id) {
    global $conn;
    $sql = "SELECT m.id, u.username as sender, m.subject, m.body, m.created_at
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            WHERE m.receiver_id = ? AND m.is_spam = 1
            ORDER BY m.created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
?>
