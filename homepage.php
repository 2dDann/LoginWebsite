<?php
// connect to database
include 'connection.php';
// session is used to make sure user is still logged into same account
session_start();
$user_id = $_SESSION['user_id'];

// check if user is logged in, if not, redirect to log in
if(!isset($user_id)){
   header('location:login.php');
};

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css"> <!-- link to css -->
	<link rel="icon" type="image/jpg" href="imgs/logo.jpg"/>
	<title>Homepage</title>

</head>
<body>
	<header>
		<nav class="navigation-bar">		
			<a href="profile.php">Profile</a>
			<a href="login.php">Log in</a>
			<a href="homepage.php" class="navSelected">Homepage</a>
		</nav>
	</header>
	<main>
		<div class="container">
			<div class="profile">
				<?php
				// get the user's info by getting the id from the session used earlier
				$select = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
				// if there is a user, put his/her info into variable for easy calling
				if(mysqli_num_rows($select) > 0){
					$fetch = mysqli_fetch_assoc($select);
				}
				// if user dont have a profile pic as it is not required, use the default avatar, else use user's picture 
				if($fetch['image'] == ""){
					echo "<img src='images/default-avatar.png'>";
				}else{
					echo '<img src="uploaded_img/'.$fetch['image'].'">';
				}
				?>

			</div>
			<div class="profile-name">
				<h3><?php echo "Welcome, " . $fetch['fname'] . " " . $fetch['lname'] . ".";?></h3> 
				<!-- get user's first name and last name and concate both  -->
			</div>
		</div>

		<div class="homepage-details">
			<div class="column">
				<img src="imgs/email.png" href="emailIcon"/>
				<h3><?php echo $fetch['email'];?></h3> <!-- get user's email -->
			</div>
			<div class="column">
				<img src="imgs/phone.png" href="phoneIcon"/>
				<h3><?php echo $fetch['phone'];?></h3> <!-- get user's phone no. -->
			</div>
		</div>
		<div class="homepage-exp">
			<br><br>
			<h2>Hello, welcome to this website</h2>
			
			<p>
				The purpose of this website is to practice the use of SQL Database in normal website. Here you can find the functions of creating(insert), read, and update(modify) being applied and performed by the browser. This also alters the information in the SQL Databse witought accessing the phpMyAdmin. The webpages that are mentioned(homepage, login page, and profile page) all have both navigation bar and footer.(though the footer is basically empty)
			</p>
			<p>
				Although this website is far from being perfect as there is still some more improvement to be made, this website is functional and (hopefully) little to no bugs. Moreover, due to me being too focused on one thing, I have failed to check the time remaining to finish the project which lead to some missing component eg. dynamic list of friends.
			</p>
			<p>
				The homepage here shows the account details(read from database) while the profile is where the user can update/modify their account. The making of an account can be access through the log in page where users can click the "register now" link.
			</p>
			<p><!-- echoing the password will be encrypted just like in the database -->
				Final say, if i was to echo the password for the profile, it would show as encrypted message, therefore, even in the database(mySQL) password table is encrypted. This is to ensure security of the profile and as a demonstration, here is the echoing of encrypted password through echo function; <?php echo $fetch["password"];?>.(trust me this is the echo, not me writing down random stuff)
			</p>
			<p>
				As I have nothing more to say, please enjoy the picture below.
			</p>
			<br>
		</div>
		<div style="background-color: #eee; padding: 3rem;">
			<img src="imgs/windowsbg.jpg" href="nicescenery" style="display: block; margin-left: auto; margin-right: auto; width: 50%;"/>
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