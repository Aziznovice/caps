	<?php

	include_once 'config/Database.php'; 
	include_once 'class/students.php'; 
	include_once 'class/User.php';  
	include_once 'class/Post.php';
	include_once 'class/Category.php';

	$database = new Database();
	$db = $database->getConnection();
	$student = new student($db);
	$user = new user($db);
	$post = new Post($db);
	$category = new Category($db);

	if(!$user->loggedIn() || $_SESSION["user_type"] != 1) {
		header("location: index.php");
		exit();
	}

	$student->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0'; 
	$saveMessage = '';
	if(!empty($_POST["savestudent"]) && $_POST["email"]!='') {
		
		$student->firstname = $_POST["firstname"];
		$student->lastname = $_POST["lastname"];	
		$student->email = $_POST["email"];
		$student->id_number = $_POST["id_number"];	
		$student->type_of_access = $_POST["type_of_access"];		
		if($student->id) {	
			$student->updated = date('Y-m-d H:i:s');
			if($student->update()) {
				$saveMessage = "Student updated successfully!";
				$_SESSION['editatstudent'] = $saveMessage;
				header("Location: students_acc.php");
				exit();
			}
		}
	}
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	$userDetails = $student->getstudent();
	
	include('inc/header.php');
	?>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>		
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
	<script src="js/posts.js"></script>	
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
					<div class="panel-heading">
						<h3 class="panel-title"> Edit Edit</h3>
					</div>
					<div class="panel-body">
					
						<form method="post" id="postForm">							
							<?php if ($saveMessage != '') { ?>
								<div id="login-alert" class="alert alert-success col-sm-12"><?php echo $saveMessage; ?></div>                            
							<?php } ?>
							<div class="form-group">
								<label for="title" class="control-label">First Name</label>
								<input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $userDetails['firstname']; ?>" placeholder="First name..">							
							</div>
							
							<div class="form-group"> 
								<label for="title" class="control-label">Last Name</label>
								<input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $userDetails['lastname']; ?>" placeholder="Last name..">							
							</div>
							
							<div class="form-group">
								<label for="title" class="control-label">Email</label>
								<input type="email" class="form-control" id="email" name="email" value="<?php echo $userDetails['email']; ?>" placeholder="email..">							
							</div>					
							<?php 
							if(!$student->id) {	
							?>
							<div class="form-group">
								<label for="title" class="control-label">Password</label>
								<input type="password" class="form-control" id="password" name="password" value="<?php echo $userDetails['password']; ?>" placeholder="password..">							
							</div>	
							<?php } ?>						
												
							
							<div class="form-group">
								<label for="title" class="control-label">Studet ID</label>
								<input type="text" class="form-control" id="id_number" name="id_number" value="<?php echo $userDetails['id_number']; ?>" placeholder="Enter Student ID">							
							</div>				

							<div class="form-group">
								<label for="status" class="control-label">User Status </label>							
								<label class="radio-inline">
									<input type="radio" name="type_of_access" id="active" value="0" <?php if(!$userDetails['type_of_access']) { echo "checked";} ?>>Allow
								</label>
								<label class="radio-inline">
									<input type="radio" name="type_of_access" id="inactive" value="1" <?php if($userDetails['type_of_access']) { echo "checked";} ?>>Denied
								</label>												
							</div>	
							
							<input type="submit" name="savestudent" id="savestudent" class="btn btn-info" value="Save" />											
						</form>				
					</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php include('inc/footer.php');?>
