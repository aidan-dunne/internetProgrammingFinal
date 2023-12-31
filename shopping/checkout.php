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
	
	//Connecting to the accounts Database
	$DATABASE_HOST = 'localhost';
	$DATABASE_USER = 'root';
	$DATABASE_PASS = 'root';
	$DATABASE_NAME = 'accounts';
	
	try
	{
		$connString = "mysql:host=$DATABASE_HOST;port=3306;dbname=$DATABASE_NAME";
		$pdoUsers = new PDO($connString, $DATABASE_USER, $DATABASE_PASS);
	}
	catch (PDOException $exception) //Displaying error message if connection unsuccessful
	{
		echo "Database Connection Unsuccessful.<br>";
		exit('Failed to connect to database!');
	}
	
	//Removing all items from the cart if order is placed
	if (isset($_POST['placeorder'])) {
		$_SESSION['totalInCart'] = 0;
		$_SESSION['subtotal'] = 0;
		foreach (array_keys($_SESSION['cart']) as $key) {
			unset($_SESSION['cart'][$key]);
		}
	}
	
	//Saving user payment and shipping info if a user is logged in
	if (isset($_SESSION['username']) && isset($_POST['placeorder'])) {
		//Updating table row corresponding with logged-in user
		$statement = $pdoUsers->prepare('UPDATE users SET cardNum = ?, cardExp = ?, cardSec = ?, address = ?, cityState = ?, zip = ? WHERE username = ?');
		$statement->bindValue(1, $_POST['cardNum'], PDO::PARAM_STR);
		$statement->bindValue(2, $_POST['cardExp'], PDO::PARAM_STR);
		$statement->bindValue(3, $_POST['cardSec'], PDO::PARAM_STR);
		$statement->bindValue(4, $_POST['address'], PDO::PARAM_STR);
		$statement->bindValue(5, $_POST['cityState'], PDO::PARAM_STR);
		$statement->bindValue(6, $_POST['zip'], PDO::PARAM_STR);
		$statement->bindValue(7, $_SESSION['username'], PDO::PARAM_STR);
		$statement->execute();
	}
	
	//Creating variables used for displaying information on the page
	$totalInCart = $_SESSION['totalInCart'];
	$subtotal = $_SESSION['subtotal'];
?>

<!DOCTYPE html>

<html lang="en">
	<head>
		<link rel="Mobile Stylesheet" href="../mobile.css" media="screen and (max-width: 480px)">
		<link rel="Default Stylesheet" href="../styles.css" media="screen and (min-width: 481px)">
		<title>Checkout</title>
	</head>
	
	<body>
		<header>
			<h1>Checkout</h1>
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
		
		<section class="checkoutMain">
			<?php
				//Displaying card information and shipping address form before the order is placed
				//and hiding the form after an order has been placed
				if (isset($_POST['placeorder'])) {
					echo "<p><strong>Thank you for your purhcase!</strong></p>";
				}
				else {
					echo "<p>You are purchasing <strong>$totalInCart items</strong> for <strong>$$subtotal</strong></p>";
					echo <<< MULTILINE
						<form action="checkout.php" method="post" id="checkoutForm">
							<h2>Credit Card Information</h2>
							<label for="cardNum">Credit Card Number: </label><br>
							<input type="text" name="cardNum" placeholder="1234-5678-1234-5678" id="cardNum"><br>
							<label for="cardExp">Card Expiration Date: </label><br>
							<input type="text" name="cardExp" placeholder="MM/YY" id="cardExp"><br>
							<label for="cardSec">Card Security Code: </label><br>
							<input type="text" name="cardSec" placeholder="123" id="cardSec"><br>
							<h2>Shipping Information</h2>
							<label for="address">Shipping Address: </label><br>
							<input type="text" name="address" placeholder="1234 Normal Street" id="address"><br>
							<label for "cityState">City and State: </label><br>
							<input type="text" name="cityState" placeholder="Kirksville, MO" id="cityState"><br>
							<label for "zip">Zipcode: </label><br>
							<input type="text" name="zip" placeholder="63501" id="zip"><br>
							<input type="submit" name="placeorder" value="Place Order">
						</form>
					MULTILINE;
				}
			?>
			
			<script>
				//Performing JavaScript inupt validation
				//Getting form element
				const formElement = document.querySelector("#checkoutForm");
				//Adding event listener for form submission
				formElement.addEventListener("submit", function (e) {
					//Storing submit button info to allow disabling the submit button later
					const submitButtonDisable = document.getElementById("placeorder");
					
					//Storing each form field
					let cardNum = document.querySelector("#cardNum").value;
					let cardExp = document.querySelector("#cardExp").value;
					let cardSec = document.querySelector("#cardSec").value;
					let address = document.querySelector("#address").value;
					let cityState = document.querySelector("#cityState").value;
					let zip = document.querySelector("#zip").value;
					
					//Validating input for each field
					if (cardNum.length != 19) {
						alert("Credit card number is invalid!");
						e.preventDefault();
						return;
					}
					
					if (cardExp.length != 5) {
						alert("Credit card expiration date is invalid!");
						e.preventDefault();
						return;
					}
					
					if (cardSec.length != 3) {
						alert("Credit card security code is invalid!");
						e.preventDefault();
						return;
					}
					
					if (address === "" || address === null) {
						alert("Shipping address cannot be blank!");
						e.preventDefault();
						return;
					}
					
					if (cityState === "" || cityState === null) {
						alert("City and state cannot be blank!");
						e.preventDefault();
						return;
					}
					
					if (zip === "" || zip === null) {
						alert("Zipcode cannot be blank!");
						e.preventDefault();
						return;
					}
				});
			</script>
		</section>
		
		<footer>Website created by Aidan Dunne and Alex Switzer, 2023</footer>
	</body>
</html>