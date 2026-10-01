<?php
session_start();
require 'db.php';
require_login(); // must be logged in

$errors = [];
$data = ['first_name'=>'','last_name'=>'','gender'=>'','date_of_birth'=>'','course'=>'','year_level'=>'','email'=>'','contact_number'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = read_student_post();
    $errors = validate_student($data);                     // PHP validation
    if (empty($errors)) {
        $year = (int)$data['year_level'];
        $stmt = $conn->prepare("INSERT INTO students
            (first_name, last_name, gender, date_of_birth, course, year_level, email, contact_number)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");               // prepared statement
        $stmt->bind_param("sssssiss", $data['first_name'], $data['last_name'], $data['gender'],
            $data['date_of_birth'], $data['course'], $year, $data['email'], $data['contact_number']);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Student added successfully!";
            $stmt->close();
            header("Location: index.php");
            exit;
        }
        $errors[] = "Could not save the student. Please try again.";
        $stmt->close();
    }
}
page_header("Add Student");
?>
<h1>Add Student</h1>
<p class="subtitle">Fill in the form below. Fields marked * are required.</p>
<?php if ($errors): ?>
  <div class="alert alert-error"><strong>Please fix the following:</strong><ul>
  <?php foreach ($errors as $er): ?><li><?= htmlspecialchars($er) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<div class="card"><?= student_form($data, 'add.php', 'Save Student') ?></div>
<?php page_footer(); $conn->close();