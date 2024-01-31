<?php 
// connect to database
include "connection.php";
session_start();
// session used to store information to be used in different pages

//if user click submit on the log in button, use query to check if they are the same
if(isset($_POST['submit'])){
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$pass = mysqli_real_escape_string($conn, md5($_POST['password']));

	$query = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

	// authentication by comapring id
	if(mysqli_num_rows($query) > 0){
		$row = mysqli_fetch_assoc($query);
		$_SESSION['user_id'] = $row['id'];
		header('location:homepage.php');
	} else {
		$message[] = "incorrect email or password";
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" type="image/jpg" href="imgs/logo.jpg"/>
	<title>Login Page</title>

</head>
<body>
	<header>
		<nav class="navigation-bar">		
			<a href="profile.php">Profile</a>
			<a href="login.php" class="navSelected">Log in</a>
			<a href="homepage.php">Homepage</a>
		</nav>
	</header>
	<main>
		<div class="form-container">
			<form action="" method="post" enctype="multipart/form-data">
			    <h3>login now</h3>
			    <?php
			    // if there is an error message from php, show here, else dont show anything
			    if(isset($message)){
			        foreach($message as $message){
			        	echo '<div class="message">' . $message . '</div>';
			        }
			    }
			    ?>
			    <!-- input area for user -->
			    <input type="email" name="email" placeholder="enter email" class="box" required>
			    <input type="password" name="password" placeholder="enter password" class="box" required>
			    <input type="submit" name="submit" value="login now" class="btn">
			    <p>don't have an account? <a href="register.php">register now</a></p>
			    <!-- ^^^ if user dont have an account, link is provided to make a new one -->
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