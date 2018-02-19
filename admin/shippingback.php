<?php

    if(is_array($_FILES)) {
        if(is_uploaded_file($_FILES['shippingphoto']['tmp_name'])) {
			$shippingphoto = $_FILES['shippingphoto']['name'];
			$shipperId = $_POST['shipperId'];
			$shippingtitle = $_POST['shipptitle'];
			$limitedweight = $_POST['limitedweight'];
			$price = $_POST['price'];
			$latitude = $_POST['latitude'];
			$longitude = $_POST['longitude'];
			$adrress = $_POST['adrress'];
            $sourcePath = $_FILES['shippingphoto']['tmp_name'];
        	$targetPath = "../assets/images/shipper/".$_FILES['shippingphoto']['name'];
	        $target_fil = $targetPath . basename($_FILES['shippingphoto']['name']);
        	include 'db.php';
			//insert new shipping product
			$insertshipping = $db -> query("INSERT INTO shipper(title,WeightLimit,pricepkilo,Photo,shipperId,latitude,longitude,address) VALUES('$shippingtitle', '$limitedweight', '$price', '$shippingphoto', '$shipperId','$latitude','$longitude','$address')");
	        $shippingid = mysqli_insert_id($db);
	        $newshippingphoto = "$shippingid.jpg";
		    $Update = $db -> query("UPDATE shipper SET Photo = '$newshippingphoto' WHERE shippingId = '$shippingid'");
        	$targetDir = "../assets/images/shipper/".$newshippingphoto;
            if(move_uploaded_file($sourcePath,$targetDir)) {
				if ($insertshipping) {
					echo '<p style="color: #000;">'.$shippingtitle.' Added successfully. Other time you go to list you will see it</p>';
				}
            }
        }
    }

?>