<?php
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mail Server</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="index.php">Mail Server</a>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <?php if(is_logged_in()): ?>
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Send mail</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="inbox.php">Inbox</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="sent.php">Sent</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="spam.php">Spam</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      <?php else: ?>
        <li class="nav-item">
          <a class="nav-link" href="login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="register.php">Register</a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
<div class="container mt-4">
