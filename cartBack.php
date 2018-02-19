<?php 
	require('db.php');
?>

<?php 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Section 1 (if user attempts to add something to the cart from the product page)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_GET['pid'])) {
	    $pid = $_GET['pid'];
	    $qty = $_GET['qty'];
		$wasFound = false;
		$i = 0;
		// If the cart session variable is not set or cart array is empty
		if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) { 
		    // RUN IF THE CART IS EMPTY OR NOT SET
			$_SESSION["cart_array"] = array(0 => array("item_id" => $pid, "quantity" => $qty));
		} else {
			// RUN IF THE CART HAS AT LEAST ONE ITEM IN IT
			foreach ($_SESSION["cart_array"] as $each_item) { 
				$i++;
				while (list($key, $value) = each($each_item)) {
					if ($key == "item_id" && $value == $pid) {
					  // That item is in cart already so let's adjust its quantity using array_splice()
					  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $pid, "quantity" => $each_item['quantity'] + $qty)));
					  $wasFound = true;
					} // close if condition
				} // close while loop
			} // close foreach loop
			   if ($wasFound == false) {
				   array_push($_SESSION["cart_array"], array("item_id" => $pid, "quantity" => $qty));
			   }
		}
		header("location: cart.php"); 
	    exit();
	}
?>
<?php 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Section 2 (if user chooses to empty their shopping cart)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_GET['cmd']) && $_GET['cmd'] == "emptycart") {
	    unset($_SESSION["cart_array"]);
	}
?>
<?php 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Section 3 (if user chooses to adjust item quantity)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['item_to_adjust']) && $_POST['item_to_adjust'] != "") {
	    // execute some code
		$item_to_adjust = $_POST['item_to_adjust'];
		$quantity = $_POST['quantity'];
		$quantity = preg_replace('#[^0-9]#i', '', $quantity); // filter everything but numbers
		if ($quantity < 1) { $quantity = 1; }
		if ($quantity == "") { $quantity = 1; }
		$i = 0;
		foreach ($_SESSION["cart_array"] as $each_item) { 
			$i++;
			while (list($key, $value) = each($each_item)) {
			  if ($key == "item_id" && $value == $item_to_adjust) {
				  // That item is in cart already so let's adjust its quantity using array_splice()
				  array_splice($_SESSION["cart_array"], $i-1, 1, array(array("item_id" => $item_to_adjust, "quantity" => $quantity)));
			  } // close if condition
			} // close while loop
		} // close foreach loop
	}
?>
<?php 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Section 4 (if user wants to remove an item from cart)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['index_to_remove']) && $_POST['index_to_remove'] != "") {
	    // Access the array and run code to remove that array index
	 	$key_to_remove = $_POST['index_to_remove'];
		if (count($_SESSION["cart_array"]) <= 1) {
			unset($_SESSION["cart_array"]);
		} else {
			unset($_SESSION["cart_array"]["$key_to_remove"]);
			sort($_SESSION["cart_array"]);
		}
	}
?>
<?php 
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Section 5  (render the cart for the user to view on the page)
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$cartOutput = "";
	$cartTotal = "";
	$i = 0;
	$pp_checkout_btn = '';
	$product_id_array = '';
	if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) 
	{
		$cartOutput = "";
	}
	else
		{
		// Start PayPal Checkout Button
		$pp_checkout_btn .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	    <input type="hidden" name="cmd" value="_cart">
	    <input type="hidden" name="upload" value="1">
	    <input type="hidden" name="business" value="you@youremail.com">';
		// Start the For Each loop
		$i = 0; 
	   foreach ($_SESSION["cart_array"] as $each_item) 
	   { 
			$item_id = $each_item['item_id'];
			$postId = $each_item['item_id'];
			include("db.php");
			$findmaxqty = $db->query("SELECT * FROM `items1` WHERE itemId = '$postId'");
			while($itemrow = mysqli_fetch_array($findmaxqty))
			{
				$maxqty = $itemrow['quantity'];
			}
			$sql = $db->query("SELECT * FROM items1 WHERE itemId='$item_id' LIMIT 1");
			$selectPercentage = $db->query("SELECT * FROM `charges` WHERE chargedFrom = 'saler'");
            $rowpercentage = mysqli_fetch_array($selectPercentage);
            $percentage = $rowpercentage['percentage'];
			while ($itemrow = mysqli_fetch_array($sql)) {
				$product_name = $itemrow["itemName"];
				$onWebprice = ($itemrow['unityPrice'] + (($percentage/100)*$itemrow['unityPrice']));
				$price = $itemrow["unityPrice"];
				$details = $itemrow["description"];
			}
			$pricetotal = $onWebprice * $each_item['quantity'];
			

	error_reporting( 0 );

			$cartTotal = $pricetotal + $cartTotal;
			setlocale(LC_MONETARY, "en_US");
	       // $pricetotal = money_format("%10.2n", $pricetotal);
			// Dynamic Checkout Btn Assembly
			$x = $i + 1;
			$pp_checkout_btn .= '<input type="hidden" name="item_name_' . $x . '" value="' . $product_name . '">
	        <input type="hidden" name="amount_' . $x . '" value="' . $onWebprice . '">
	        <input type="hidden" name="quantity_' . $x . '" value="' . $each_item['quantity'] . '">  ';
			// Create the product array variable
			$product_id_array .= "$item_id-".$each_item['quantity'].","; 
			$product_name_array .= "$product_name : ".$each_item['quantity']."<br>"; 
			// Dynamic table row assembly
			
			$cartOutput .= "<tr>";
			$cartOutput .= '
			<td class="cart_product">
			<a href="cart.php?postId='.$item_id.'"><img src="products/'.$item_id.'.jpg" alt="'.$product_name.'" title="'.$product_name.'"></a>
			</td>';
			$cartOutput .= '<td class="cart_description">
	                                                <p class="product-name"><a href="post.php?postId='.$item_id.'">'.$product_name.'</a></p>
	                                                <small><a href="post.php?postId='.$item_id.'">'.$details.'</a></small><br>
	                                            </td>';
			$cartOutput .= '<td>' . number_format($onWebprice) . ' Rwf</td>';
			$cartOutput .= '<td class="qty"><form action="cart.php" method="post">
			
	                                                <input class="option-product-qty" type="number" name="quantity" type="text" value="' . $each_item['quantity'] . '" min="1" max="'.$maxqty.'"/>
	                                                <div class="custom-qty">
	                                                    <a class="up" href="order.html#"></a>
	                                                    <a class="down" href="order.html#"></a>
	                                                </div>
	                                           
			<input name="adjustBtn' . $item_id . '" type="submit" value="change" />
			<input name="item_to_adjust" type="hidden" value="' . $item_id . '" />
			</form> </td>';
			$cartOutput .= '<td class="price"><span>' . number_format($pricetotal) . ' Rwf</span></td>';
			$cartOutput .= '<td>
								<form action="cart.php" method="post">
									<input class="btn btn-danger btn-xs" name="deleteBtn' . $item_id . '" type="submit" value="&times;">
									<input name="index_to_remove" type="hidden" value="' . $i . '" />
								</form>
							</td>';
			$cartOutput .= '</tr>';
			$i++; 
	    } 
		//setlocale(LC_MONETARY, "en_US");
	   // $cartTotal = money_format("%10.2n", $cartTotal);
		$cartTotal = "".$cartTotal." Rwf";
	    // Finish the Paypal Checkout Btn
		$pp_checkout_btn .= '
		<form action="peyment.php" method="post">
		<input type="text" name="itemCode" value="' . $item_id . '">
		<input type="text" name="quantity" value="' . $each_item['quantity'] . '">
		</form>';
	}

	if($i>0)
	{
		$itemsarethere = "Your shopping cart contains:  <span>".$i." Product</span>";
	}
	else
	{
		$itemsarethere = "Your shopping cart is empty";
	}
?>