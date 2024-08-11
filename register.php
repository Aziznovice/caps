<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection and initialize the Articles class
    include_once 'config/Database.php';  // Adjust the file name based on your connection method
    include_once 'class/Articles.php';

    // Create an instance of the Database class to get the connection
    $database = new Database();
    $conn = $database->getConnection();

    // Create an instance of the Articles class 
    $articles = new Articles($conn);

    // Get user input from the registration form 
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $id_number = $_POST['id_number'];
    $type_of_access = $_POST['type_of_access'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password

    // Call a method to insert the user data into the database
    $result = $articles->registerUser($firstname, $lastname, $email, $id_number, $type_of_access, $password);

    if ($result) {
        header("Location:student_login.php");
        $saveMessage = "Registration Successful! Thank you for signing up. Your account is now awaiting approval. Please check back later to log in. ";
		$_SESSION['registermessage'] = $saveMessage;
        exit();
    } else {
        // Registration failed, you can redirect the user to an error page or show an error message
        header("Location: student_registration.php");
        exit();
    }
}
?> 
