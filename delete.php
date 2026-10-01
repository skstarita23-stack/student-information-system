<?php
session_start();
require 'db.php';
require_login(); // must be logged in

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $_SESSION['success'] = "Student deleted successfully!";
    } else {
        $_SESSION['error'] = "Student not found or could not be deleted.";
    }
    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid student ID.";
}
$conn->close();
header("Location: index.php");
exit;