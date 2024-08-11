<?php include_once 'register.php'; ?>
<?php include_once 'class/Articles.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZCRAMS Registration</title>
    <link rel="stylesheet" type="text/css" href="css/studentlogin.css"> 
    <link rel="icon" type="image/png" href="img/cicslogo.png"/> 
</head>
<body>
<div class="container" style=" display: flex;
    flex-direction: column;
    align-items: center;">
        <div class="logintitle" style="
    font-size: xx-large;
    color: white;
">
            <h1>I.T CAPSTONE ARCHIVE</h1>
        </div>
    <div class="wrapper">
        <h2>Registration</h2>
        <form action="register.php" method="post">  
          <div class="input-box">
            <input type="text" name="firstname" placeholder="Enter your name" required>
          </div>
          <div class="input-box">
            <input type="text" name="lastname" placeholder="Enter your lastname" required>
          </div>
          <div class="input-box">
          <input type="text" name="id_number" id="id_number" placeholder="Enter your ID number" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
          <input type="text" name="type_of_access" value="1" style="display: none;">

          </div>
          <div class="input-box">
        <input type="text" name="email" placeholder="Enter your email" required>
    </div>
    <div class="input-box">
        <input type="password" name="password" placeholder="Create a Password" required>
    </div>
          <!-- <div class="policy">
            <input type="checkbox">
            <h3>I accept all terms & condition</h3>
          </div> -->
          <div class="input-box button">
            <input type="Submit" value="Register Now">
          </div>
          <div class="text">
            <h3>Already have an account? <a href="student_login.php">Login now</a></h3>
          </div>
        </form>
      </div>
</body>
</html>