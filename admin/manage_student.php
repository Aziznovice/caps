<?php
include_once 'config/Database.php';
include_once 'class/students.php'; 
$database = new Database();
$db = $database->getConnection();

$user = new student($db);

if(!empty($_POST['action']) && $_POST['action'] == 'studentListing') {
	$user->getstudentListing();
}
if (!empty($_POST['action']) && $_POST['action'] == 'studentDelete') {
    $student = new student($db); // Create an instance of the student class
    $student->id = isset($_POST['userId']) ? $_POST['userId'] : '0';
    $student->delete();
}

?>  