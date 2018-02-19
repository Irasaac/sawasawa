<?php
	if (isset($_GET['trackingCode'])) {
		$trackingCode = $_GET['trackingCode'];
		include 'db.php';
		$update = $db -> query("UPDATE orders SET orderStatus = 'Shipped' WHERE orderId = '$trackingCode'");
		echo "Shipped";
	}

?>
