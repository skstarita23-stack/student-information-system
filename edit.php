<?php
session_start();
require 'db.php';
require_login(); // must be logged in

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { $_SESSION['error'] = "Invalid student ID."; header("Location: index.php"); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = read_student_post();
    $errors = validate_student($data);
    if (empty($errors)) {
        $year = (int)$data['year_level'];
        $stmt = $conn->prepare("UPDATE students SET first_name=?, last_name=?, gender=?, date_of_birth=?,
            course=?, year_level=?, email=?, contact_number=? WHERE student_id=?");
        $stmt->bind_param("sssssissi", $data['first_name'], $data['last_name'], $data['gender'],
            $data['date_of_birth'], $data['course'], $year, $data['email'], $data['contact_number'], $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Student updated successfully!";
            $stmt->close();
            header("Location: index.php");
            exit;
        }
        $errors[] = "Could not update the student. Please try again.";
        $stmt->close();
    }
} else {
    // Load the existing record so the form is pre-filled.
    $stmt = $conn->prepare("SELECT first_name,last_name,gender,date_of_birth,course,year_level,email,contact_number
                            FROM students WHERE student_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$data) { $_SESSION['error'] = "Student not found."; header("Location: index.php"); exit; }
}
page_header("Edit Student");
?>
<h1>Edit Student</h1>
<p class="subtitle">Editing Student ID: <?= $id ?></p>
<?php if ($errors): ?>
  <div class="alert alert-error"><strong>Please fix the following:</strong><ul>
  <?php foreach ($errors as $er): ?><li><?= htmlspecialchars($er) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<div class="card"><?= student_form($data, 'edit.php?id=' . $id, 'Update Student') ?></div>
<?php page_footer(); $conn->close();