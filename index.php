<?php
session_start();
require 'db.php';
require_login(); // must be logged in

// READ: fetch students, optionally filtered by search text.
$search = trim($_GET['search'] ?? '');
if ($search !== '') {
    $like = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT * FROM students
        WHERE CAST(student_id AS CHAR) LIKE ? OR first_name LIKE ? OR last_name LIKE ?
           OR CONCAT(first_name,' ',last_name) LIKE ? OR course LIKE ? OR email LIKE ?
           OR contact_number LIKE ? OR gender LIKE ?
        ORDER BY student_id DESC");
    $stmt->bind_param("ssssssss", $like, $like, $like, $like, $like, $like, $like, $like);
} else {
    $stmt = $conn->prepare("SELECT * FROM students ORDER BY student_id DESC");
}
$stmt->execute();
$result = $stmt->get_result();
$total = $result->num_rows;

$msg = $_SESSION['success'] ?? '';
$err = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
page_header("Dashboard");
?>
<h1>Student Information Management System</h1>
<p class="subtitle">Manage student records: add, view, edit, and delete.</p>

<?php if ($msg): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error"><?= e($err) ?></div><?php endif; ?>

<div class="stats">
  <div class="stat-card"><span class="stat-number"><?= (int)$total ?></span>
    <span><?= $search !== '' ? 'Matching Students' : 'Total Students' ?></span></div>
</div>

<div class="toolbar">
  <form method="get" action="index.php" class="search-form">
    <input type="text" name="search" placeholder="Search by ID, name, course, email..." value="<?= e($search) ?>">
    <button type="submit" class="btn btn-primary">Search</button>
    <?php if ($search !== ''): ?><a href="index.php" class="btn btn-secondary">Clear</a><?php endif; ?>
  </form>
  <a href="add.php" class="btn btn-success">+ Add Student</a>
</div>

<div class="table-wrap">
<table>
  <thead><tr>
    <th>Student ID</th><th>Full Name</th><th>Gender</th><th>Date of Birth</th>
    <th>Course/Program</th><th>Year Level</th><th>Email</th><th>Contact Number</th><th>Actions</th>
  </tr></thead>
  <tbody>
  <?php if ($total > 0): while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td data-label="Student ID"><?= (int)$row['student_id'] ?></td>
      <td data-label="Full Name"><?= e($row['first_name'] . ' ' . $row['last_name']) ?></td>
      <td data-label="Gender"><?= e($row['gender']) ?></td>
      <td data-label="Date of Birth"><?= e($row['date_of_birth']) ?></td>
      <td data-label="Course/Program"><?= e($row['course']) ?></td>
      <td data-label="Year Level"><?= (int)$row['year_level'] ?></td>
      <td data-label="Email"><?= e($row['email']) ?></td>
      <td data-label="Contact Number"><?= e($row['contact_number']) ?></td>
      <td data-label="Actions" class="actions">
        <a class="btn btn-small btn-edit" href="edit.php?id=<?= (int)$row['student_id'] ?>">Edit</a>
        <a class="btn btn-small btn-delete confirm-delete" href="delete.php?id=<?= (int)$row['student_id'] ?>">Delete</a>
      </td>
    </tr>
  <?php endwhile; else: ?>
    <tr><td colspan="9" class="empty">No student records found.</td></tr>
  <?php endif; ?>
  </tbody>
</table>
</div>
<?php
$stmt->close();
$conn->close();
page_footer();