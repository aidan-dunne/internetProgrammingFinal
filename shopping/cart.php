<?php
	session_start();
	
	//Sets total number of items in cart to 0 each time page is visited before counting them later
	$_SESSION['totalInCart'] = 0;
	
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
	
	//Retrieving information about product added to cart
	//Making sure product id and quantity (amount added) were specified and are numeric
	if (isset($_POST['id'], $_POST['quantity']) && is_numeric($_POST['id']) && is_numeric($_POST['quantity'])) {
		//Converting id and quantity of product to int and storing them in variables
		$id = (int) $_POST['id'];
		$quantity = (int) $_POST['quantity'];
		
		//Adding quantity of products added to cart to total number of products in cart
		
		//Preparing and executing statement to retrieve product from SQL table based on product id
		$statement = $pdo->prepare('SELECT * FROM products WHERE id = ?');
		$statement->execute([$_POST['id']]);
		$product = $statement->fetch(PDO::FETCH_ASSOC);
		
		//Checking if product exists in SQL database and that product was added to cart (amount added > 0)
		if ($product && $quantity > 0) {
			//Checking if cart session variable is set and is an array (able to store multiple products)
			if (isset($_SESSION['cart']) &&  is_array($_SESSION['cart'])) {
				if (array_key_exists($id, $_SESSION['cart'])) {
					//If product exists in cart array, update quantity added to cart
					$_SESSION['cart'][$id] += $quantity;
				}
				else {
					//Add product to cart if it is not already stored in cart array
					$_SESSION['cart'][$id] = $quantity;
				}
			}
			else {
				//If cart session variable does not exist, create new cart array
				$_SESSION['cart'] = array($id => $quantity);
			}
		}
	}
	
	//Removing item from shopping cart if the remove from cart button is pressed
	//Making sure remove is set and contains an id (numeric value)
	if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
		unset($_SESSION['cart'][$_GET['remove']]);
	}
	
	//Updating number of each product in cart
	if (isset($_POST['update'])) {
		//Accessing each key and value stored in POST to retrieve id and updated quantity
		foreach ($_POST as $k => $v) {
			//Making sure that key contains quantity and value is an id (is numeric)
			if (strpos($k, 'quantity') !== false && is_numeric($v)) {
				$idUpdate = str_replace('quantity-', '', $k);
				$quantityUpdate = (int) $v;
				$_SESSION['cart'][$idUpdate] = $quantityUpdate;
			}
		}
	}
	
	//Calculating total number of items in cart after quantity has been updated
	foreach ($_SESSION['cart'] as $q) {
		$_SESSION['totalInCart'] += $q;
	}
	
	//Using header function to switch to checkout page if Proceed to Checkout button pressed
	if (isset($_POST['checkout'])) {
		header('Location: checkout.php');
		exit;
	}
	
	//Setting subtotal to 0 to be calculated later
	$subtotal = 0;
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
		
		<?php
			if(empty($_SESSION['cart'])) {
				echo "<h2>There are no items in the cart.</h2>";
			}
		?>
		
		<form action="cart.php" method="post" class="productForm">
			<?php foreach (array_keys($_SESSION['cart']) as $key): ?>
				<?php
					//Retrieving only products that have been added to cart by accessing array keys (product ids)
					$statement = $pdo->prepare('SELECT * FROM products WHERE id = ?');
					$statement->execute([$key]);
					$cartProduct = $statement->fetch(PDO::FETCH_ASSOC);
					
					//Calculating subtotal
					if (isset($_SESSION['username'])) {
						//Calculate subtotal using member price if user is logged in
						$subtotal += $_SESSION['cart'][$key] * $cartProduct['price'];
					}
					else {
						//Otherwise calculate using retail price
						$subtotal += $_SESSION['cart'][$key] * $cartProduct['rrp'];
					}
				?>
				
				<section class="cartProduct">
					<section class="container"><img src="<?= $cartProduct['image'] ?>"><p><strong><?= $cartProduct['name'] ?></strong></p></section>
					<p>Price: $
						<?php
							//Displaying member price if user is logged in, retail price otherwise
							if (isset($_SESSION['username'])) {
								echo $cartProduct['price'];
							}
							else {
								echo $cartProduct['rrp'];
							}
						?>
					</p>
					
					<section class="container"><p>Quantity: </p><input type="number" name="quantity-<?= $key ?>" value="<?= $_SESSION['cart'][$key] ?>" min="1" max="<?= $cartProduct['quantity'] ?>"></section>
					
					<p>Total Price: $
						<?php
							//Calculating total price per item using member price if user is logged in, retail price otherwise
							if (isset($_SESSION['username'])) {
								echo $cartProduct['price'] * $_SESSION['cart'][$key]; 
							}
							else {
								echo $cartProduct['rrp'] * $_SESSION['cart'][$key]; 
							}
						?>
					</p>
					<a href="cart.php?remove=<?= $key ?>" class="remove">Remove from Cart</a>
				</section>
			<?php endforeach; ?>
			
			<?php 
			//Creating a session variable for subtotal for use on the checkout page
			$_SESSION['subtotal'] = $subtotal;
			?>
			
			<?php
				if (!empty($_SESSION['cart'])) {
					echo "<section><p><strong>Subtotal: </strong>$$subtotal</p></section>";
					echo <<< MULTILINE
					<section class="buttonContainer">
						<input type="submit" name="update" value="Update">
						<input type="submit" name="checkout" value="Proceed to Checkout">
					</section>
					MULTILINE;
				}
			?>
		</form>
		
		<footer>Website created by Aidan Dunne and Alex Switzer, 2023</footer>
	</body>
</html>