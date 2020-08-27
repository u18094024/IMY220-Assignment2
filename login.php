<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Mbuso Shakoane">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
						
					echo 	"<form method='post' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='file' class='form-control' name='fileToUpload' id='fileToUpload' /><br/>
									<input type='hidden' id='email' name='loginEmail' value='$email'><br/>
									<input type='hidden' id='pwd' name='loginPass' value='$pass'><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
							  </form>";

							  if(isset($_POST["submit"])) {
							  $target_dir = "Gallery/";
							  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
							  $uploadFile = basename($_FILES["fileToUpload"]["name"]);
							  $user = $row['user_id'];
							  $uploadOk = 1;
							  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
							  
							  if(isset($_POST["submit"])) {
								$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
								if($check !== false) {
								  $uploadOk = 1;
								} else {
									//"Given file is not an image.";
								  $uploadOk = 0;
								}
							  }
						
							  if ($_FILES["fileToUpload"]["size"] > (1024*1024)) {
								//"File is too large.";
								$uploadOk = 0;
							  }
							  
							  if($imageFileType != "jpg" && $imageFileType != "jpeg") {
								//"Sorry, only JPG or JPEG files are allowed.";
								$uploadOk = 0;
							  }
							  
							  if ($uploadOk == 0) {
								//"Your file was not uploaded.";
							  } else {
								if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
									
								  $sql ="INSERT INTO tbgallery (user_id,filename)
								  VALUES('$user','$uploadFile')";
								  $res = mysqli_query($mysqli, $sql) == TRUE;

								} else {
								  //"Sorry, there was an error uploading your file.";
								}
							  }
							  }
							 
							  
				}
				else{
					echo '<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}

			$gUser = $row['user_id'];
			echo "<h1>Image Gallery</h1>";
			echo	"<div class='row imageGallery'>";
			$sql2 = "SELECT DISTINCT filename FROM tbgallery WHERE user_id ='$gUser'";
			$result1 = $mysqli->query($sql2);
			while($row = mysqli_fetch_array($result1))
			{
				$cName = $row['filename'];
				$thing = "Gallery/".$cName;
			
			echo "<div class='col-3' style='background-image: url($thing);'></div>";
			}
		   echo	"</div>";
		?>
</body>
</html>