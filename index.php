<?php 
	session_start();
	function update_users_status($arr,$str){
		$query ='SELECT * FROM users';
		$db = mysqli_connect('localhost', 'root', '', 'registration');
		$result = mysqli_query($db, $query);
		$rows = mysqli_num_rows($result);
		for ($i = 0 ; $i < $rows ; ++$i)
		{
			$row = mysqli_fetch_row($result);
			if(in_array($i,$arr)){
				$line = 'UPDATE users SET status='."'".$str."'".' WHERE username = '."'".$row[1]."'";
				mysqli_query($db, $line);
			}
		}
	}
	
	function delete_users($arr){
		$query ='SELECT * FROM users';
		$db = mysqli_connect('localhost', 'root', '', 'registration');
		$result = mysqli_query($db, $query);
		$rows = mysqli_num_rows($result);
		for ($i = 0 ; $i < $rows ; ++$i)
		{
			$row = mysqli_fetch_row($result);
			if(in_array($i,$arr)){
				$line = 'DELETE from users WHERE username = '."'".$row[1]."'";
				mysqli_query($db, $line);
			}
		}
	}
	
	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['username']);
		header("location: index.php");
	}
	if(isset($_POST['unblock'])){	
		if(!empty($_POST['Selected'])){		
			update_users_status($_POST['Selected'], 'unblocked');
		}
	}
	if(isset($_POST['block'])){
		if(!empty($_POST['Selected'])){		
			update_users_status($_POST['Selected'], 'blocked');	
			session_destroy();
			unset($_SESSION['username']);
			header("location: index.php");
	}
  }
	if(isset($_POST['delete'])){
		if(!empty($_POST['Selected'])){		
			delete_users($_POST['Selected']);	
			session_destroy();
			unset($_SESSION['username']);
			header("location: index.php");
		}
  }
  
?>
<script language="JavaScript">
function selectAll(source) {
    checkboxes = document.getElementsByName('Selected[]');
    for(var i in checkboxes)
        checkboxes[i].checked = source.checked;
}
</script>


<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"></head>
<body>

<form method="post" action=''>
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
<div class="content">
	<?php
		if(!isset($_SESSION['username'])){
			echo '<h3 class="text-center" style="color: #F97300">Welcome! Login or register to start.</h3>';
		}
		if (isset($_SESSION['username']) && $_SESSION['status'] == 'blocked'){
			echo '<h3 class="text-center" style="color: #FF0000">You are banned!</h3>';
		}
		$query ='SELECT * FROM users';
		$dbi = mysqli_connect('localhost', 'root', '', 'registration');
		$result = mysqli_query($dbi, $query) or die("Error " . mysqli_error($dbi));		
		if(isset($_SESSION['username']) && $_SESSION['status'] == 'unblocked'){
			$rows = mysqli_num_rows($result);
			echo<<<HTML
				<div class="table-responsive">
				<div class="btn-group btn-group-md pull-center" style=" text-align: center; width: inherit; ">
					<button name="block" value="Block" class="btn btn-warning">Block <i class="fa fa-lock"></i></button>
					<button name="unblock" value="Unblock" class="btn btn-info">Unblock <i class="fa fa-unlock-alt"></i></button>
					<button name="delete" value="Delete" class="btn btn-danger">Delete <i class="fa fa-trash"></i></button>       
				</div>
				<table class="table table-sm">
					<tr>
						<th><input type="checkbox" onClick="selectAll(this)"></th>
						<th>Id</th>
						<th>Username</th>
						<th>Email</th>
						<th>Registration date</th>
						<th>Last login date</th>
						<th>Status</th>
					</tr>
			HTML;
			$sql = "SHOW COLUMNS FROM users";
			$res = mysqli_query($dbi,$sql);
			$count = 0;
			while($row = mysqli_fetch_array($res)){
				$count++;
			} 
			for ($i = 0 ; $i < $rows ; ++$i){
				$row = mysqli_fetch_row($result);
				echo "<tr>";
					echo '<td><input type="checkbox" name="Selected[]" value="'.$i.'"></td>';
					for ($j = 0 ; $j < $count ; ++$j){
						if($j == 3) continue;
						echo "<td>$row[$j]</td>";
					}
				echo "</tr>";
			}
			echo "</table>";
			echo "</div>";
			mysqli_free_result($result);
		}
	?>
</div>

</form>
</body>
</html>
















