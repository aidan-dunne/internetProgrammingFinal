<?php
	start_session();
//Database connection info
	$DATABASE_HOST = 'localhost';
	$DATABASE_USER = 'root';
	$DATABASE_PASS = '';
	$DATABASE_NAME = 'accounts';
	
	//Connecting to the shoppingcart database
	try
	{
		$connString = "mysql:host=$DATABASE_HOST;port=3306;dbname=$DATABASE_NAME";
		$pdo = new PDO($connString, $DATABASE_USER, $DATABASE_PASS);
	}
	catch (PDOException $e) { // exception handling
	echo "Database Connection Unsuccessful.<br>";
	die($e->getMessage());
	}
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the 'uname' and 'password' keys are set in the $_POST array
    if (isset($_POST['uname']) && isset($_POST['password'])) {
        $username = $_POST['uname'];
        $password = $_POST['password'];
	{
		$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
		$result = $pdo->query($sql); // committing the query
		if ($result->rowCount() > 0) {
			echo "Login successful!";
			$_SESSION['username'] = $username;
		} 
		else {
			echo "Login failed. Please check your username and password.";
}
	}}}
	// closing the connection object
	$pdo = null;
?>


<!DOCTYPE html>

<html lang ="en">
	<head>
		<link rel="Mobile Stylesheet" href="mobile.css" media="screen and (max-width: 480px)">
		<link rel="Default Stylesheet" href="styles.css" media="screen and (min-width: 481px)">
		<title>Login Page</title>
	</head>

	<body>
		<header>
			<h1>Please Login</h1>
		</header>
		<section class="centerParagraph">
			<p>Enter your username and password:</p>
			<br>
				<form action="login.php" method="post">
					<h2>LOGIN</h2>
					<?php if (isset($_GET['error'])) { ?>
						<p class="error"><?php echo $_GET['error']; ?></p>
					<?php } ?>
					<label>User Name</label>
					<input type="text" name="uname" placeholder="User Name"><br>
					<label>Password</label>
					<input type="password" name="password" placeholder="Password"><br> 
					<button type="submit">Login</button>
				</form>
			<br>
			<p>Don't have an account? Create one here!<p>
			<a href="create.php">
				<button type="button" onclick="create.php">Create Account</button>
			</a>
		</section>
		
		<footer>Website created by Aidan Dunne and Alex Switzer, 2023</footer>
	</body>
	
</html>