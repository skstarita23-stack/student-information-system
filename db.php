<?php
// db.php - the ONE database connection file used by every page.
// Local XAMPP connection settings
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "student_information_db";

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    // Show a friendly message only; never print the username/password.
    die("<h2>Database connection failed.</h2><p>Please check db.php settings.</p>");
}
$conn->set_charset("utf8mb4");

// Login protection: call this at the top of every private page.
function require_login() {
    if (empty($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

// Shared validation used by add.php and edit.php (server-side validation).
function validate_student($d) {
    $errors = [];
    if ($d['first_name'] === '' || strlen($d['first_name']) > 50) $errors[] = "First name is required (max 50 characters).";
    if ($d['last_name'] === '' || strlen($d['last_name']) > 50) $errors[] = "Last name is required (max 50 characters).";
    if (!in_array($d['gender'], ['Male', 'Female', 'Other'], true)) $errors[] = "Please select a valid gender.";
    $dt = DateTime::createFromFormat('Y-m-d', $d['date_of_birth']);
    if (!$dt || $dt->format('Y-m-d') !== $d['date_of_birth']) {
        $errors[] = "Date of birth is not a valid date.";
    } elseif ($dt > new DateTime('today')) {
        $errors[] = "Date of birth cannot be in the future.";
    }
    if ($d['course'] === '' || strlen($d['course']) > 100) $errors[] = "Course/Program is required (max 100 characters).";
    if (!ctype_digit($d['year_level']) || (int)$d['year_level'] < 1 || (int)$d['year_level'] > 6) $errors[] = "Year level must be a number from 1 to 6.";
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL) || strlen($d['email']) > 100) $errors[] = "Please enter a valid email address.";
    if (!preg_match('/^[0-9+\-\s()]{7,30}$/', $d['contact_number'])) $errors[] = "Contact number must be 7-30 characters (digits, +, -, spaces).";
    return $errors;
}

// Reads and trims the form fields from $_POST.
function read_student_post() {
    $keys = ['first_name','last_name','gender','date_of_birth','course','year_level','email','contact_number'];
    $d = [];
    foreach ($keys as $k) $d[$k] = trim($_POST[$k] ?? '');
    return $d;
}

// Shared form HTML for add.php and edit.php.
function student_form($d, $action, $button) {
    $h = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
    $genders = ['Male','Female','Other'];
    ob_start(); ?>
    <form method="post" action="<?= $h($action) ?>" id="studentForm" novalidate>
      <div class="form-grid">
        <div class="field"><label for="first_name">First Name *</label>
          <input type="text" id="first_name" name="first_name" maxlength="50" value="<?= $h($d['first_name']) ?>"></div>
        <div class="field"><label for="last_name">Last Name *</label>
          <input type="text" id="last_name" name="last_name" maxlength="50" value="<?= $h($d['last_name']) ?>"></div>
        <div class="field"><label for="gender">Gender *</label>
          <select id="gender" name="gender"><option value="">-- Select --</option>
          <?php foreach ($genders as $g): ?>
            <option value="<?= $g ?>" <?= $d['gender'] === $g ? 'selected' : '' ?>><?= $g ?></option>
          <?php endforeach; ?></select></div>
        <div class="field"><label for="date_of_birth">Date of Birth *</label>
          <input type="date" id="date_of_birth" name="date_of_birth" value="<?= $h($d['date_of_birth']) ?>"></div>
        <div class="field"><label for="course">Course/Program *</label>
          <input type="text" id="course" name="course" maxlength="100" value="<?= $h($d['course']) ?>"></div>
        <div class="field"><label for="year_level">Year Level *</label>
          <select id="year_level" name="year_level"><option value="">-- Select --</option>
          <?php for ($i = 1; $i <= 6; $i++): ?>
            <option value="<?= $i ?>" <?= (string)$d['year_level'] === (string)$i ? 'selected' : '' ?>><?= $i ?></option>
          <?php endfor; ?></select></div>
        <div class="field"><label for="email">Email *</label>
          <input type="email" id="email" name="email" maxlength="100" value="<?= $h($d['email']) ?>"></div>
        <div class="field"><label for="contact_number">Contact Number *</label>
          <input type="text" id="contact_number" name="contact_number" maxlength="30" value="<?= $h($d['contact_number']) ?>"></div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $h($button) ?></button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
    <?php return ob_get_clean();
}

function page_header($title) { ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title) ?> | Student Information Management System</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="index.php">&#127979; Student Information Management System</a>
    <nav><a href="index.php">Dashboard</a><a href="add.php">Add Student</a>
      <span class="user-tag">&#128100; <?= htmlspecialchars($_SESSION['full_name'] ?? '') ?></span>
      <a href="logout.php">Logout</a></nav>
  </div>
</header>
<main class="container">
<?php }

function page_footer() { ?>
</main>
<footer class="site-footer">School Project &ndash; CRUD Web Application (PHP + MySQLi)</footer>
<script src="script.js"></script>
</body>
</html>
<?php }