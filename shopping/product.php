<?php
	session_start();
	
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
	
	//Retrieving product selected by user
	//Making sure product id has been specified in the URL
	if (isset($_GET['id'])) {
		//Preparing and executing statement to retrieve product from SQL table based on product id
		$statement = $pdo->prepare('SELECT * FROM products WHERE id = ?');
		$statement->execute([$_GET['id']]);
		$product = $statement->fetch(PDO::FETCH_ASSOC);
		
		//Display error message if product specified by id does not exist
		if (!$product) {
			exit("Product does not exist!");
		}
	}
	else {
		//Dislay error message if no id was specified in the URL
		exit("No product selected!");
	}
?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<link rel="Mobile Stylesheet" href="../mobile.css" media="screen and (max-width: 480px)">
		<link rel="Default Stylesheet" href="../styles.css" media="screen and (min-width: 481px)">
		<title>Product Page - <?php echo $product['name']; ?></title>
	</head>

	<body>
		<header>
			<h1><?php echo $product['name']; ?></h1>
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
		
		<section class="productPageBody">
			<section class="productPageMain">
				<img src="<?= $product['image'] ?>">
				<h2><?= $product['name'] ?></h2>
				<p><?= $product['desc'] ?></p>
			</section>
			
			<section class="productPageMain">
				<?php
					//Storing price and retail price of product to be displayed
					$price = $product['price'];
					$rrp = $product['rrp'];
				
					//Displaying special member price if user is logged in
					if (isset($_SESSION['username'])) {
						echo "<p><strong>Member Price: </strong>$$price</p>";
					}
					else {
						echo "<p><strong>Retail Price: </strong>$$rrp</p>";
					}
				?>
				<p><strong>Items in Stock: </strong><?= $product['quantity'] ?></p>
				<form action="cart.php" method="post" class="productForm">
					<input type="number" name="quantity" value="1" min="1" max="<?= $product['quantity'] ?>">
					<input type="hidden" name="id" value="<?= $product['id'] ?>">
					<input type="submit" value="Add to Cart">
				</form>
			</section>
		</section>
		
		<footer>Website created by Aidan Dunne and Alex Switzer, 2023</footer>
	</body>
</html>