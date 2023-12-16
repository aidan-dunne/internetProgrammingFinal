<?php
	session_start();
//Database connection info
	$DATABASE_HOST = 'localhost';
	$DATABASE_USER = 'root';
	$DATABASE_PASS = 'root';
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
			echo <<< MULTILINE
				<script>
					alert("Login successful!");
				</script>
			MULTILINE;
			$_SESSION['username'] = $username;
		} 
		else {
			echo <<< MULTILINE
				<script>
					alert("Login failed! Please check your username and password.");
				</script>
			MULTILINE;
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
					<input type="submit" value="Login">
				</form>
			<br>
			<p>Don't have an account? Create one here!<p>
			<form action="create.php"><input type="submit" value="Create Account"></form>
			<br>
			<p>Back to Menu<p>
			<form action="index.html"><input type="submit" value="Menu"></form>
		</section>
		
		<footer>Website created by Aidan Dunne and Alex Switzer, 2023</footer>
	</body>
	
</html>