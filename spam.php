<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';
redirect_if_not_logged_in();
include 'templates/header.php';

$messages = get_spam($_SESSION['user_id']);
?>
<h2>Spam</h2>
<table class="table">
  <thead>
    <tr>
      <th>From</th>
      <th>Subject</th>
      <th>Message</th>
      <th>Received</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($message = mysqli_fetch_assoc($messages)): ?>
    <tr>
      <td><?php echo htmlspecialchars($message['sender']); ?></td>
      <td><?php echo htmlspecialchars($message['subject']); ?></td>
      <td><?php echo htmlspecialchars($message['body']); ?></td>
      <td><?php echo htmlspecialchars($message['created_at']); ?></td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php
include 'templates/footer.php';
?>
