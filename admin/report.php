<?php
include_once 'config/Database.php';
include_once 'class/User.php';
include_once 'class/students.php';
include_once 'class/Post.php';
include_once 'class/Category.php';

$database = new Database();
$db = $database->getConnection();  
$student = new student($db);
$user = new User($db);
$post = new Post($db);
$category = new Category($db); 

if(!$user->loggedIn()) {
	header("location: index.php");
}
include('inc/header.php'); 
?>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/report.js"> </script>
<link href="css/style.css" rel="stylesheet" type="text/css" >  
<link href="css/disable.css" rel="stylesheet" type="text/css" >  
<link rel="icon" type="image/png" href="./css/cicslogo.png"> 
</head>
<body>
<?php include "menus.php"; ?>
<?php include 'inc/header2.php'?> 
<br>
<section id="main"> 
	<div class="container">
		<div class="row">	
			<?php include "left_menus.php"; ?>
			<div class="col-md-9">
				<div class="panel panel-default">
				<div class="panel-heading" style="background-color:maroon;">
					<h3 class="panel-title"style="color: white;">List of Archive</h3> 
				  </div>
				  <div class="panel-body">
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-10">
								<h3 class="panel-title"></h3>
							</div>
						</div> 
					</div> 
					<!-- for displaying the message after edit post -->
					<?php
if (isset($_SESSION['editpost'])) {
    echo '<div id="login-alert" class="alert alert-success col-sm-12">' . $_SESSION['editpost'] . '</div>';
    unset($_SESSION['editpost']); // Clear the session variable after displaying the message
    echo '<script>
            setTimeout(function(){
                var element = document.getElementById("login-alert");
                if(element) {
                    element.style.display = "none";
                }
            }, 3000); // Hide after 3 seconds
          </script>';
}
?>
<a href="wholeprintreport.php">
        <button type="button" class="btn btn-primary" style="position: absolute;">Print Report</button>
    </a>
					<table id="reportList" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Title</th>
								<th>Capstone Members</th>
                                <th>School Year</th>
								<th>Advisor</th>	
								<th>Chairperson</th>
                                <th>Panel Member</th>
								<th>Date of Published</th>	
								<th></th>																
							</tr>
						</thead>
					</table>
				  </div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php include('inc/footer.php');?>
