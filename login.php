<?php
session_start();
require 'db.php';

if (!empty($_SESSION['user_id'])) { header("Location: index.php"); exit; }

$error = '';
$username = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === '' || $password === '') {
        $error = "Please enter your username and password.";
    } else {
        $stmt = $conn->prepare("SELECT user_id, password, full_name FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        // Compare the typed password with the password stored in the database
        if ($user && $password === $user['password']) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['full_name'] = $user['full_name'];
            header("Location: index.php");
            exit;
        }
        $error = "Invalid username or password.";
    }
}
$loggedOut = isset($_GET['logout']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Staff Login | Student Information Management System</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="login.css">
</head>
<body class="uni-body">

  <!-- Parallax background layers -->
  <div class="uni-bg" id="uniBg"></div>
  <div class="uni-overlay"></div>

  <header class="uni-top">
    <span class="uni-crest">&#127963;</span>
    <span class="uni-name">Harvard Admin</span> <!-- CHANGE THIS -->
  </header>

  <section class="uni-hero">
    <div class="uni-hero-text" id="heroText">
      <p class="uni-eyebrow">Office of the Registrar</p>
      <h1>Student Information<br>Management System</h1>
      <div class="uni-line"></div>
      <p class="uni-motto">Excellence through Knowledge</p> <!-- CHANGE THIS -->
      <p class="uni-desc">A secure portal for authorized school staff to manage student records.</p>
    </div>

    <div class="uni-card">
      <h2>Staff Login</h2>
      <p class="uni-card-sub">Sign in to access the student records</p>
      <?php if ($loggedOut): ?><div class="alert alert-success">You have been logged out.</div><?php endif; ?>
      <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="post" action="login.php" id="loginForm" novalidate>
        <div class="field"><label for="username">Username</label>
          <input type="text" id="username" name="username" value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>" autofocus></div>
        <div class="field" style="margin-top:14px"><label for="password">Password</label>
          <input type="password" id="password" name="password"></div>
        <button type="submit" class="uni-btn">Sign In</button>
      </form>
    </div>
  </section>

  <!-- Scrolling section so the parallax effect is visible -->
  <section class="uni-info">
    <div class="uni-info-grid">
      <div><h3>Student Records</h3><p>Add, view, update, and remove student information in one place.</p></div>
      <div><h3>Secure Access</h3><p>Only authorized staff can sign in and manage the records.</p></div>
      <div><h3>Fast Search</h3><p>Find any student by ID, name, course, or email in seconds.</p></div>
    </div>
  </section>

  <footer class="uni-footer">&copy; Your University Name &ndash; School Project</footer>

  <script src="script.js"></script>
</body>
</html>