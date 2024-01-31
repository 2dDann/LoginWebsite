<?php
// to connnect to database
include 'connection.php';
// if user click submit, run all functions
if(isset($_POST['submit'])){

   $fname = mysqli_real_escape_string($conn, $_POST['fname']);
   $lname = mysqli_real_escape_string($conn, $_POST['lname']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $phone = mysqli_real_escape_string($conn, $_POST['phone']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password'])); 
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword'])); 
   // md5 is used to encrypt the information in the database ^^
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   // save query in variable
   $select = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   //check if user already exist by checking if the row is more than 0
   if(mysqli_num_rows($select) > 0){
      $message[] = 'user already exist'; 
   }else{
      if($pass != $cpass){ // compare password and confirm password
         $message[] = 'confirm password not matched!';
      }elseif($image_size > 2000000){ //limit file size
         $message[] = 'image size is too large!';
      }else{
      	//insert into database 
         $query = mysqli_query($conn, "INSERT INTO `users`(fname, lname, email, phone, password, image) VALUES('$fname', '$lname', '$email', '$phone', '$pass', '$image')") or die('query failed');

         if($query){ //if query is true, run codes, else return error message
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'registered successfully!';
            header('location:login.php');
         }else{
            $message[] = 'registeration failed!';
         }
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
	<link rel="icon" type="image/jpg" href="imgs/logo.jpg"/>
	<title>Register</title>
</head>
<body>
	<div class="form-container">
		<form action="" method="post" enctype="multipart/form-data">
			<h3>register now</h3>
			<?php
			if(isset($message)){ //if there is a error message set from php, run it, else dont do anything
			 foreach($message as $message){
			    echo '<div class="message">'.$message.'</div>';
			 }
			}
			?>
			<!-- enter input for users -->
			<input type="text" name="fname" placeholder="enter first name" class="box" required>
			<input type="text" name="lname" placeholder="enter last name" class="box" required>
			<input type="email" name="email" placeholder="enter email" class="box" required>
			<input type="tel" name="phone" placeholder="enter phone number" class="box" required>
			<input type="password" name="password" placeholder="enter password" class="box" required>
			<input type="password" name="cpassword" placeholder="confirm password" class="box" required>
			<input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
			<input type="submit" name="submit" value="register now" class="btn">
			<p>already have an account? <a href="login.php">login now</a></p>
			<!-- go back to login page link -->
		</form>
	</div>

</body>
</html>