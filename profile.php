<?php

include "connection.php";
session_start();
$user_id = $_SESSION['user_id'];
// take user information from previous session

//if users is not logged in, redirect to login page
if(!isset($user_id)){
   header('location:login.php');
};

// if user click the logout button, destroy session and direct to login page
if(isset($_GET['logout'])){
   unset($user_id);
   session_destroy();
   header("location:login.php");
}

// if user click the update profile button, run codes
if(isset($_POST['update_profile'])){
	//save the changes into these variables
	$update_fname = mysqli_real_escape_string($conn, $_POST['update_fname']);
	$update_lname = mysqli_real_escape_string($conn, $_POST['update_lname']);
	$update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
	$update_phone = mysqli_real_escape_string($conn, $_POST['update_phone']);
	// run query in mysql to change the informations
	mysqli_query($conn, "UPDATE `users` SET fname = '$update_fname', lname = '$update_lname', email = '$update_email', phone = '$update_phone' WHERE id = '$user_id'") or die('query failed');
	//variables for changing the password
	$old_pass = $_POST['old_pass'];
	$update_pass = mysqli_real_escape_string($conn, md5($_POST['update_pass']));
	$new_pass = mysqli_real_escape_string($conn, md5($_POST['new_pass']));
	$confirm_pass = mysqli_real_escape_string($conn, md5($_POST['confirm_pass']));

	//error detections for the passwords, and if no error, change the info through a query
	if(!empty($update_pass) || !empty($new_pass) || !empty($confirm_pass)){
		if($update_pass != $old_pass){
			$message[] = 'old password not matched!';
		}elseif($new_pass != $confirm_pass){
			$message[] = 'confirm password not matched!';
		}else{
			mysqli_query($conn, "UPDATE `users` SET password = '$confirm_pass' WHERE id = '$user_id'") or die('query failed');
			$message[] = 'password updated successfully!';
		}
	}
	//variables for changing the image
	$update_image = $_FILES['update_image']['name'];
	$update_image_size = $_FILES['update_image']['size'];
	$update_image_tmp_name = $_FILES['update_image']['tmp_name'];
	$update_image_folder = 'uploaded_img/'.$update_image;

	if(!empty($update_image)){
		if($update_image_size > 2000000){ //limit the size
			$message[] = 'image is too large';
		}else{
			$image_update_query = mysqli_query($conn, "UPDATE `users` SET image = '$update_image' WHERE id = '$user_id'") or die('query failed');
			if($image_update_query){
				move_uploaded_file($update_image_tmp_name, $update_image_folder);
			}
			$message[] = 'image updated successfully!';
		}
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" type="image/jpg" href="imgs/logo.jpg"/>
	<title>Profile</title>

</head>
<body>
	<header>
		<nav class="navigation-bar">		
			<a href="profile.php" class="navSelected">Profile</a>
			<a href="login.php">Log in</a>
			<a href="homepage.php">Homepage</a>
		</nav>
	</header>
	<main>
		<div class="update-profile">
			<?php //save taking user info into variable for ease
			$select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
			if(mysqli_num_rows($select) > 0){
				$fetch = mysqli_fetch_assoc($select);
			}

			?>
			<form action="" method="post" enctype="multipart/form-data">
				<?php //show image, and give error message
				if($fetch['image'] == ''){
					echo '<img src="images/default-avatar.png">';
				}else{
					echo '<img src="uploaded_img/'.$fetch['image'].'">';
				}
				if(isset($message)){
					foreach($message as $message){
						echo '<div class="message">'.$message.'</div>';
					}
				}
				?>

				<div class="flex">
					<div class="inputBox"> 
						<!-- change information for user with the placeholder for each input is the data except password -->
						<span>First name: </span>
						<input type="text" name="update_fname" value="<?php echo $fetch['fname']; ?>" class="box">
						<span>Your email:</span>
						<input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box">
						<span>Your phone number:</span>
						<input type="tel" name="update_phone" value="<?php echo $fetch['phone']; ?>" class="box">
						<span>Update your picture:</span>
						<input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
					</div>
					<div class="inputBox">
						<span>Last name: </span>
						<input type="text" name="update_lname" value="<?php echo $fetch['lname']; ?>" class="box">
						<input type="hidden" name="old_pass" value="<?php echo $fetch['password']; ?>">
						<span>Old password:</span>
						<input type="password" name="update_pass" placeholder="enter previous password" class="box">
						<span>New password :</span>
						<input type="password" name="new_pass" placeholder="enter new password" class="box">
						<span>Confirm password :</span>
						<input type="password" name="confirm_pass" placeholder="confirm new password" class="box">
					</div>
				</div>
				<!-- button to submit the changes -->
				<input type="submit" value="Update profile" name="update_profile" class="btn">
				<br>
				<!-- logout button, connected to the php above -->
				<a href="profile.php?logout=<?php echo $user_id; ?>" class="delete-btn">Log out</a>
   			</form>
		</div>

	</main>
	<footer>
		<div class="footer-heading footer-1">
                <h3>Copyright Information &copy;</h3>
                <a href="https://www.w3schools.com/default.asp">W3School</a>
            </div>
            <div class="footer-heading footer-2">
                <h3>Contact Us &#9743;</h3>
                <a href="#">Email us</a>
                <a href="#">Instagram</a>
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">Whatsapp</a>
            </div>
            <div class="footer-email-form">
                <h3>Get newsletter</h3>
                <input type="email" placeholder="Enter your email address" id="footer-email">
                <input type="submit" value="Sign Up" id="footer-email-btn">
            </div>
	</footer>
</body>
</html>