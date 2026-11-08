<?php
/**
 * Abdullah Property Dealer - Admin Login
 */
require_once __DIR__ . '/../includes/config.php';

// If already logged in, redirect to dashboard
if (is_admin_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please provide both username and password.';
    } else {
        if (verify_admin_login($username, $password)) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid administrative credentials. Please verify and try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | Abdullah Property Dealer</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-login-body">

  <div class="login-card">
    <div class="login-header">
      <div class="login-badge">APD</div>
      <h2>Administrative Portal</h2>
      <p>Abdullah Property Dealer Management System</p>
    </div>

    <!-- Testing Credentials Hint -->
    <div class="credentials-hint">
      <strong>Default Admin Credentials:</strong><br>
      Username: <code>admin</code><br>
      Password: <code>admin123</code>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-error">
        <span><?php echo htmlspecialchars($error); ?></span>
      </div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <div class="form-group">
        <label class="form-label" for="loginUser">Username</label>
        <input type="text" id="loginUser" name="username" class="form-control" placeholder="Enter admin username" required autofocus value="<?php echo htmlspecialchars($_POST['username'] ?? 'admin'); ?>">
      </div>

      <div class="form-group" style="margin-bottom: 25px;">
        <label class="form-label" for="loginPass">Password</label>
        <input type="password" id="loginPass" name="password" class="form-control" placeholder="••••••••" required>
      </div>

      <button type="submit" class="btn btn-burgundy btn-block" style="padding: 13px;">
        Sign In to Admin Portal
      </button>

      <div style="text-align: center; margin-top: 20px;">
        <a href="../index.php" style="font-size: 0.85rem; color: var(--c-grey-medium);">
          ← Return to Public Website
        </a>
      </div>
    </form>
  </div>

</body>
</html>

