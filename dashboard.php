<?php
require_once 'includes/functions.php';
require_once 'includes/db.php';
redirect_if_not_logged_in();
include 'templates/header.php';

$user_email = get_user_email_by_id($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receiver_email = $_POST['receiver_email'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];
    $sender_id = $_SESSION['user_id'];

    if (send_message($sender_id, $receiver_email, $subject, $body)) {
        $success = "Message sent successfully.";
    } else {
        $error = "Failed to send message. Check the recipient's email.";
    }
}
?>
<h2>Dashboard</h2>
<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form action="dashboard.php" method="post">
  <div class="form-group">
    <label for="receiver_email">To</label>
    <input type="email" class="form-control" id="receiver_email" name="receiver_email" required>
  </div>
  <div class="form-group">
    <label for="subject">Subject</label>
    <input type="text" class="form-control" id="subject" name="subject" required>
  </div>
  <div class="form-group">
    <label for="body">Message</label>
    <textarea class="form-control" id="body" name="body" rows="5" required></textarea>
  </div>
  <input type="hidden" name="from_email" value="<?php echo htmlspecialchars($user_email); ?>">
  <button type="submit" class="btn btn-primary">Send</button>
</form>
<?php
include 'templates/footer.php';
?>
