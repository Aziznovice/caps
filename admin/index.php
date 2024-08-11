<?php
include_once 'config/Database.php';
include_once 'class/User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if ($user->loggedIn()) {
    header("location: dashboard.php");
}

$loginMessage = '';
// if (!empty($_POST["login"]) && $_POST["email"] != '' && $_POST["password"] != '') {
//     $user->email = $_POST["email"];
//     $user->password = $_POST["password"];
//     if ($user->login()) {
//         header("location: dashboard.php");
//     } else {
//         $loginMessage = 'Invalid login! Please try again.';
//     }
// }
if (!empty($_POST["login"]) && $_POST["email"] != '' && $_POST["password"] != '') {
    $user->email = $_POST["email"];
    $user->password = $_POST["password"];
    $loginResult = $user->login();

    if ($loginResult === 1) {
        header("location: dashboard.php");
    } elseif ($loginResult === -1) {
        $loginMessage = 'Account is inactive. Please contact the administrator.';
    } else {
        $loginMessage = 'Invalid email or password. Please try again.';
    } 
}
include('inc/header.php');
?>
 
<head>
    <title>Archive admin</title> 
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<?php include('inc/container.php'); ?>
<div style=" display: flex;
    flex-direction: column;
    align-items: center;">
        <div class="logintitle" style="
    font-size: xx-large;
    color: white;
">
            <h1 style="
    font-size: revert;
">I.T CAPSTONE ARCHIVE</h1>
        </div>
<div class="login-container">
    <h1>ADMIN LOG IN</h1>
    <?php if ($loginMessage != '') { ?>
        <div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $loginMessage; ?></div>
    <?php } ?>
    <form id="loginform" class="form-horizontal" role="form" method="POST" action="">
        <div class="form-group">
            <label for="email"></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                <input type="text" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
        </div>
        <div class="form-group">
            <label for="password"></label>
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 controls">
                <input type="submit" name="login" value="Login" class="btn btn-info" style="background-color: maroon;">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12 controls">
                <!-- User: admin@archive.com<br>
                Password: 123 -->
            </div>
        </div>
    </form>
</div>
    </div>

<?php include('inc/footer.php'); ?>
