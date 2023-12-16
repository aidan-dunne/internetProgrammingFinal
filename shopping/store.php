<?php
	session_start();
	
	//Creating session variable for total number of products in cart if it is not alreay set
	if (!isset($_SESSION['totalInCart'])) {
		$_SESSION['totalInCart'] = 0;
	}
	
	//Database connection info
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
	
	//Retrieving all products to be displayed later
	$statement = $pdo->query('SELECT * FROM products');
	$products = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<link rel="Mobile Stylesheet" href="../mobile.css" media="screen and (max-width: 480px)">
		<link rel="Default Stylesheet" href="../styles.css" media="screen and (min-width: 481px)">
		<title>Store Home</title>
	</head>

	<body>
		<header>
			<h1>Store</h1>
			<nav>
				<a href="../index.html">Home</a>
				<a href="../form.html">Form</a>
				<a href="store.php">Store</a>
				<a href="../create.php">Create Account</a>
				<a href="../login.php">Login</a>
			</nav>
			
			<a href="cart.php">
			<picture class="cart">
				<source media="(min-width: 481px)" srcset="../images/shoppingCart.png">
				<img src="../images/shoppingCartSmall.png">
			</picture>
			</a>
			
			<p class="cart">
			<?= $_SESSION['totalInCart'] ?>
			</p>
		</header>
		
		<section class="storePageBody">
			<?php foreach ($products as $product): ?>
				<a href="product.php?id=<?= $product['id'] ?>">
				<figure>
					<img src="<?= $product['image'] ?>">
					<figcaption><?= $product['name'] ?></figcaption>
				</figure>
				</a>
			<?php endforeach; ?>
		</section>
		
		<footer>Website created by Aidan Dunne and Alex Switzer, 2023</footer>
	</body>
</html>