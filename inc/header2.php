<?php 
include_once 'config/Database.php';
include_once 'class/Articles.php';
$database = new Database();
$db = $database->getConnection();

$article = new Articles($db);

$article->id = 0;

$result = $article->getArticles();
$titlesResult  = $article->getRecentArticleTitles();
$yearSectionData = $article->getYearSectionData();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css\front.css">
    <link rel="stylesheet" href="css\fontawesome\fontawesome-free-6.5.1-web\css\all.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="css\jquery.min.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICS Archive</title>
    <link rel="icon" type="image/png" href="./img/cicslogo.png"/> 
</head>
<body>
  <!-- <div class="topbanner">
    <img src="img/BANNER-zppsu2.png" alt="">   
</div> -->
<section class="hero">
  <header id="header">
    <a id="logo" href="<?php echo SITEURL; ?>index.php">ZAMBOANGA PENINSULA POLYTECHNIC STATE UNIVERSITY COLLEGE OF INFORMATION AND COMPUTING SCIENCE</a>
    <div class="topnav">
    <div class="profile-dropdown">
  <a class="nav-link" id="profileIcon" href="#" style="margin: auto;">
    <i class="fa fa-user"></i>
  </a>
  <div class="dropdown-content">
    <!-- Dropdown content, you can add user-related links or information here -->
    <?php
      $currentUser = $article->getCurrentUserProfile(); 
      if ($currentUser !== null) {
        echo '<p style="color: black;place-self: center;text-align-last: center;">Welcome, <br>' . $currentUser['firstname'] . ' ' . $currentUser['lastname'] . '!</p>'; 
      } 
    ?>
    <hr>
    <a href="bookmark.php" style="margin: 1vh;">Bookmarks</a>
    <a href="logout.php" style="margin: 1vh;" >Logout</a>
  </div>
</div>
    
    <a class="<?php echo ($_SERVER['PHP_SELF'] == '/zppsu_archive-v1/Projects_indexes.php') ? 'active' : ''; ?>" href="<?php echo SITEURL; ?>Projects_indexes.php">Capstone Indexes</a>
    <a class="<?php echo ($_SERVER['PHP_SELF'] == '/zppsu_archive-v1/index.php') ? 'active' : ''; ?>" href="<?php echo SITEURL; ?>index.php">Home</a> 
    
  <!-- <a href="#about">About</a> --> 
</div>
  </header>

  <!-- Include jQuery library -->


<script>
$(document).ready(function() {
    // Listen for scroll events
    $(window).scroll(function() {
        // Check if the user has scrolled past a certain point
        if ($(this).scrollTop() > 100) {
            $('#header').addClass('header-scrolled');
        } else {
            $('#header').removeClass('header-scrolled');
        }
    });

    // Hide the dropdown content by default
    $('.dropdown-content').hide();

    // Handle click event on profile icon
    $('#profileIcon').click(function(event) {
        // Prevent the default behavior of the anchor element
        event.preventDefault();

        // Toggle the visibility of the dropdown content
        $('.dropdown-content').toggle();
    });

    // Close the dropdown when clicking outside of it
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.profile-dropdown').length && !$(event.target).closest('.dropdown-content').length) {
            // If the click is outside of the profile-dropdown and dropdown-content, hide the dropdown
            $('.dropdown-content').hide();
        }
    });
});


</script>

  <header class="hero-header">
    <h1 style="font-size: 9vh;">I.T CAPSTONE ARCHIVE</h1>
    <h3 style="font-size: x-large; font-weight: 300">Exploring the Compilation of Systems Produced by the Students of Zamboanga Peninsula Polytechnic State University College of Information and Computing Science</h3>
    <a href="#explorecaps">
    <div class="scroll-container">
    <div class="scroll-text">Explore Capstone Projects Below </div>
  <div class="scroll"></div>
     
    
</div>
</a>

  </header>
  <footer class="hero-footer">
    <!-- Remove the buttons from here -->
  </footer>
</section>
