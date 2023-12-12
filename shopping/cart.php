<?php
	session_start();
	
	$_SESSION["inCart"] = "0";
	
	//SQL database connection info
	$DATABASE_HOST = 'localhost';
	$DATABASE_USER = 'root';
	$DATABASE_PASS = 'root';
	$DATABASE_NAME = 'shoppingcart';
	
	//Connecting to the shoppingcart database
	try
	{
		$connString = "mysql:host=$DATABASE_HOST;port=3306;dbname=$DATABASE_NAME";
		$pdo = new PDO($connString, $DATABASE_USER, $DATABASE_PASS);
	}
	catch (PDOException $exception) //Displaying error message if connection unsuccessful
	{
		echo "Database Connection Unsuccessful.<br>";
		exit('Failed to connect to database!');
	}
?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<link rel="Mobile Stylesheet" href="../mobile.css" media="screen and (max-width: 480px)">
		<link rel="Default Stylesheet" href="../styles.css" media="screen and (min-width: 481px)">
		<title>Shopping Cart</title>
	</head>

	<body>
		<header>
			<h1>Shopping Cart</h1>
			<nav>
				<a href="../index.html">Home</a>
				<a href="../form.html">Form</a>
				<a href="store.php">Store</a>
			</nav>
			
			<a href="cart.php">
			<picture class="cart">
				<source media="(min-width: 481px)" srcset="../images/shoppingCart.png">
				<img src="../images/shoppingCartSmall.png">
			</picture>
			</a>
			
			<p class="cart">
			<?= $_SESSION["inCart"] ?>
			</p>
		</header>
		
		<footer>Website created by Aidan Dunne and Alex Switzer, 2023</footer>
	</body>
</html>