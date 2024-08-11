<?php
include_once 'config/Database.php';
include_once 'class/students.php';

$database = new Database();
$db = $database->getConnection();
$student = new student($db);

$newStudentCount = $student->getAllStudentsCountWithCondition();
echo json_encode(['count' => $newStudentCount]);
?>
