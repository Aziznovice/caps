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

if(!$user->loggedIn() || $_SESSION["user_type"] != 1) {
    header("location: index.php");
    exit();
}


include('inc/header.php');
?>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/categories.js"></script>	
<link href="css/style.css" rel="stylesheet" type="text/css" >  
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
					<div class="panel-heading"style="background-color: maroon;">
						<h3 class="panel-title"style="color: white;">School Year</h3>
					</div>
					<div class="panel-body">
						<div class="panel-heading">
							<div class="row">
								<div class="col-md-12" align="right">
									<a href="add_categories.php" class="btn btn-default btn-xs">Add New</a>				
								</div>
							</div>
						</div>
						<?php 
						if (isset($_SESSION['editcategories'])) {
							echo '<div id="login-alert" class="alert alert-success col-sm-12">' . $_SESSION['editcategories'] . '</div>';
							unset($_SESSION['editcategories']); // Clear the session variable after displaying the message
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
						<table id="categoryList" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Id</th>									
									<th>School Year</th>
									<th>Archive under this School Year</th>																								
									<th></th>
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
