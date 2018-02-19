<?php
	include 'db.php';
	if (isset($_GET['trackingCode']) && $_GET['trackingCode']!='') {
		$trackingCode = $_GET['trackingCode'];
		$sql = $db -> query("SELECT * FROM orders WHERE trackingCode = '$trackingCode'");
		if($sql) {
			$count = mysqli_num_rows($sql);
			if ($count < 1) {
				echo '
					<br> <p><i>this tracking Code '.$trackingCode.' is invalid</i></p>
				';
			}
			else {
				while($trackingCode = mysqli_fetch_array($sql)) {
					$statustrackingCode = $trackingCode['orderStatus'];
					$itemCode = $trackingCode['itemCode'];
					$selectItem = $db -> query("SELECT * FROM items1 WHERE itemId = '$itemCode'");
					$Item = mysqli_fetch_array($selectItem);
					$itemName = $Item['itemName'];
					echo ''.$itemName.': '.$statustrackingCode.'<br>';
				}
			}
		}
		else {
			echo "No connection";
		}
	}

?>