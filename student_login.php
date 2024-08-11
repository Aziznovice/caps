<?php
session_start();
?>
<?php include_once 'class/Articles.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection and initialize the Articles class
    include_once 'config/Database.php';  
    include_once 'class/Articles.php';

    // Create an instance of the Articles class
    $database = new Database();
    $conn = $database->getConnection();
    $articles = new Articles($conn);

    // Get user input from the login form
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Call a method to authenticate the user
    $authenticationResult = $articles->authenticateUser($email, $password);

    // Check the authentication result
    if (isset($authenticationResult['error'])) {
        // Display the error message
        $error_message = $authenticationResult['error'];
    } elseif ($authenticationResult) {
        // Check if type_of_access is 1 (access denied)
        if ($authenticationResult['type_of_access'] == 1) {
            $error_message = "Access denied pending confirmation";
        } else {
            // User authenticated successfully
            $_SESSION['user_id'] = $authenticationResult['id'];
            header("Location: index.php");
            exit();
        }
    } else {
        // Authentication failed, you can redirect the user to the login page with a generic error message
        $error_message = "Invalid email or password. Please try again."; 
    }
}
?> 


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZCRAMS Login</title>
    <link rel="stylesheet" type="text/css" href="css/studentlogin.css"> 
    <link rel="icon" type="image/png" href="img/cicslogo.png"/>  
</head>
<body>
<div class="container" style=" display: flex;
    flex-direction: column;
    align-items: center;">
        <div class="logintitle" style=";
    font-size: xx-large;
    color: white;
">
            <h1>I.T CAPSTONE ARCHIVE</h1>
        </div>
    <div class="wrapper">
        <h2>Login</h2>
        <form action="student_login.php" method="post">
        <?php
        if (isset($error_message)) {
            echo '<div style="color: red;">' . $error_message . '</div>';
        }
        ?>
        <?php
         if (isset($_SESSION['registermessage'])) {
            echo '<div id="login-alert" class="alert alert-success col-sm-12" style="color:green">' . $_SESSION['registermessage'] . '</div>';
            unset($_SESSION['registermessage']);
        }
        ?>

            <div class="input-box">
                <input type="text" name="email" placeholder="Email" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="input-box button">
                <input type="submit" value="Login">
            </div>
            <div class="text">
                <h3>Don't have an account? <a href="student_registration.php">Register</a></h3>
            </div>
        </form>
    </div>
    </div>
</body>
</html> 
