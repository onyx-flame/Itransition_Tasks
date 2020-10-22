<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
	  <title>Login</title>
	  <link rel="stylesheet" type="text/css" href="style.css">
	  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>

<form method="post" action="login.php">

	<nav class="navbar navbar-expand fixed-top ">  
		<a class="nav-link" data-value="Home" href="index.php">Home</a>
		<?php  
			$mode = "";
			if (isset($_SESSION['username']) && $_SESSION['status'] == 'blocked'){
				$mode = "badge-danger";
			}
			if (isset($_SESSION['username']) && $_SESSION['status'] == 'unblocked'){
				$mode = "badge-success";
			}
			if (!isset($_SESSION['username'])){
				$mode = "badge-secondary";
			}
		
		?>
		<div class="badge <?php echo $mode; ?> text-break">
			<?php
				$name = "Anonym";
				if (isset($_SESSION['username'])){
					$name = $_SESSION['username'];
				}
			?>
			<h6><?php echo $name; ?></h6>
		</div>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav">
				<?php  if (!isset($_SESSION['username'])) : ?>
					<li class="nav-item"> 
						<a class="nav-link " data-value="login" href="login.php">Login</a>
					</li>   
					<li class="nav-item">  
						<a class="nav-link " data-value="register" href="register.php">Register</a>
					</li> 
				<?php endif ?>
				<?php  if (isset($_SESSION['username'])) : ?>
					<li class="nav-item"> 
						<a class="nav-link " data-value="logout" href="index.php?logout">Logout</a>
					</li>   
				<?php endif ?>
			</ul> 
		</div>
	</nav>

	<br><br><br>

	<div class="container">
		<div class="row">
			<div class="col-md-4 offset-md-4">
				<div class="card text-center mb-3">
					<div class="card-header" style="background-color: #F97300">
						<h3 class="text-light">Login</h3>
					</div>
					<div class="card-body">
						<?php if(count($errors) > 0) : ?>
						<div class="card mb-3" style="border-width: 3px; border-color: #F97300">
							<div class="card-body">
								<h6 class="text-center text-danger">
									<?php include('errors.php'); ?>
								</h6>
							</div>
						</div>
						<?php endif ?>
						<input type="text" name="username" class="form-control input-sm chat-input mb-3" placeholder="Username" />
						<input type="password" name="password" class="form-control input-sm chat-input" placeholder="Password" />
					</div>
					<div class="card-footer text-muted">
						<button type="submit" name="login_user" class="btn btn-secondary" style="background-color: #F97300">Login</a>
					</div>
				</div>
			</div>
		</div>
	</div>

</form>
</body>
</html>