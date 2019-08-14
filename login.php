<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	// Your database details might be different
	$mysqli = mysqli_connect("localhost", "root", "", "dbUser");

	$email = isset($_POST["email"]) ? $_POST["email"] : false;
	$pass = isset($_POST["pass"]) ? $_POST["pass"] : false;

	$sql = "CREATE TABLE tbgallery (
	image_id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT,	
	filename VARCHAR(50)
	)";
	if (mysqli_query($mysqli, $sql)) {
	echo "Table tbgallery created successfully";
	} else {
	echo "Error creating table: " . mysqli_error($mysqli);
	}
	//$passfile = isset($_POST["fileToUpload[]"]) ? $_POST["fileToUpload[]"] : false;

?>
<?php

if(isset($_POST['submit']))
{

		
		$uploadFile = $_FILES["fileToUpload[]"];
	$numFiles = count($uploadFile["name"]);
	for($i = 0; $i < $numFiles; $i++){
	
	$target_dir = "gallery/";
	//$uploadFile = $_FILES["fileToUpload"];
	$target_file = $target_dir . basename($uploadFile["name"][$i]);
	$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	if(isset($_POST["submit"])){	
	$check = getimagesize($uploadFile["tmp_name"][$i]);
	if($check !== false){
	echo "File is an image – " . $check["mime"] . ".";
	}
	else {
	echo "File is not an image.";
	}
	}
if(move_uploaded_file($uploadFile["tmp_name"][$i], $target_file)){
echo "The file " . basename($uploadFile["name"][$i]) . "has been uploaded.";
} else {
echo "Sorry, there was an error uploading your file.";
}
//$uploadFile = $_FILES["fileToUpload"];
if(($uploadFile["type"][$i] == "image/jpeg"
|| $uploadFile["type"][$i] == "image/jpg")
&& $uploadFile["size"][$i] < 1000000){
move_uploaded_file($uploadFile["tmp_name"][$i],
"gallery/" . $uploadFile["name"][$i]);
echo "Stored in: " . "gallery/" . $uploadFile["name"][$i];

$sele = "SELECT id, firstname, lastname FROM MyGuests";
$result = mysqli_query($mysqli, $sele);
mysqli_close($mysqli);

$sqlll = "INSERT INTO tbgallery (user_id, filename)
VALUES ('" . $result . "',
'" .  $uploadFile["filename"][$i]  . "')";
if (mysqli_query($mysqli, $sqlll)) {
echo "New record created successfully";
} else {
echo "Error: " . $sqlll . "<br>" . mysqli_error($mysqli);
}
mysqli_close($mysqli);

} 
else {
echo "Invalid file";
}
}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 3</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Chane Hall">
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
							</table>
							<h1>Image Gallery</h3>
							<div class='container'>
								<div class='row' class='imageGallery'>";
								for ($x = 0; $x < $numFiles; $x++){
									echo "<div class='col-" . $x . "' style='background-image: url(gallery/" . $uploadFile["filename"][$x] . ")'></div>";
									}
								echo "</div>
							</div>
							
							
							
							
							";
				
					echo 	"<form enctype='multipart/form-data' action='login.php' method='post'>
								<div class='form-group'>
									<input type='hidden' name='email' value='" . $email . "' />
									<input type='hidden' name='password' value='" . $pass . "' />
									<input type='file' class='form-control' name='fileToUpload[]' id='fileToUpload[]' multiple='multiple'/><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
						  	</form>";
				}		
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>

	</div>
</body>
</html>