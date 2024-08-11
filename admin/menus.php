<nav class="navbar navbar-default">
  <div class="container">
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	  
	</div>
	<div id="navbar" class="collapse navbar-collapse">
	
	  <!-- menus.php -->
<ul class="nav navbar-nav" style="
    color: white;
    padding: 6px;>
    <?php
        $userTypeLabel = ($_SESSION["user_type"] == 1) ? "Administrator" : "Author";
        $userTypeClass = ($_SESSION["user_type"] == 1) ? "Authorofadmin" : "author";
    ?>
    <li class="<?php echo $userTypeClass; ?>"><?php echo $userTypeLabel; ?> Management</li>
   
</ul>

	  <?php if(!empty($_SESSION["userid"])) { ?>
	  <ul class="nav navbar-nav navbar-right">
		<li class="active"><a href="">Welcome, <?php echo $_SESSION["name"]; ?></a></li>
		<li><a href="logout.php">Logout</a></li>          
	  </ul>
	  <?php } ?>
	</div>
  </div>
</nav>
