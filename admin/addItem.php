<?php // Destry session if it hasn't been used for 15 minute.
session_start();
	$inactive = 900;
    if(isset($_SESSION['timeout']) ) 
	{
		$session_life = time() - $_SESSION['timeout'];
		if($session_life > $inactive)
		{
		header("location: ../logout.php"); 
		}
    }
    $_SESSION['timeout'] = time();
	if (!isset($_SESSION["username"])) 
	{
		header("location: login.php"); 
		exit();
	}

	
?>
<?php 
$session_id = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
$username = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["username"]); // filter everything but numbers and letters
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
include "../db.php"; 
$sql = $db->query("SELECT * FROM users u INNER JOIN useraccounttype ua WHERE u.id = ua.userId AND loginId = '$username' AND pwd = '$password' limit 1"); // query the person
// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
$existCount = mysqli_num_rows($sql); // count the row nums
if ($existCount > 0) { 
	while($row = mysqli_fetch_array($sql)){ 
			 $thisid = $row["id"];
			 $names = $row["names"];
			}
		} 
		else{
		echo "
		
		<br/><br/><br/><h3>Your account has been temporally deactivated</h3>
		<p>Please contact: <br/><em>(+25) 078 484-8236</em><br/><b>muhirwaclement@gmail.com</b></p>		
		Or<p><a href='../logout.php'>Click Here to login again</a></p>
		
		";
	    exit();
	}
	
	if(isset($_POST['addpst']))
	{
		$itemName = $_POST['itemName'];
		$productCode = $_POST['productCode'];
		$quantity = $_POST['quantity'];
		$unityPrice = $_POST['unityPrice'];
		$postedBy = $username; //$_POST['postedBy'];
		$itemCompanyCode = $_POST['itemCompanyCode'];
		$description = $_POST['description'];
		$postDeadline = $_POST['endingdate'];
		$unit = $_POST['unit'];
		include ("../db.php");
		
		$sql = $db->query("INSERT INTO items1 (
		itemName, productCode, quantity, unityPrice, 
		inDate, postedBy,unit, itemCompanyCode, description, 
		postDeadline) 
		VALUES (
		'$itemName', '$productCode', '$quantity', '$unityPrice', 
		now(), '$postedBy', '$unit', '$itemCompanyCode', '$description', 
		'$postDeadline')");
		$sql9 = $db->query("SELECT * FROM items1 ORDER BY itemId DESC limit 1");
		while($row = mysqli_fetch_array($sql9)){
			$Imagename = $row['itemId'];
			if ($_FILES['fileField']['tmp_name'] != "") {		 
				$newname = ''.$Imagename.'.jpg';
				move_uploaded_file( $_FILES['fileField']['tmp_name'], "../products/$newname");
			}
			header("location: items.php?companyid=$itemCompanyCode");
		}
	}
	elseif(isset($_POST['editpst']))
	{
		$postId = $_POST['postId'];
		$postTitle = $_POST['postTitle'];
		$productCode = $_POST['productCode'];
		$quantity = $_POST['quantity'];
		$price = $_POST['price'];
		$priceStatus = $_POST['priceStatus'];
		$postDesc = $_POST['postDesc'];
		$postedBy = $username; //$_POST['postedBy'];
		$postDeadline = $_POST['postDeadline'];
		$productLocation = $_POST['productLocation'];
		
		include ("../db.php");
		$sql = $db->query("UPDATE posts SET postTitle='$postTitle',productCode='$productCode',quantity='$quantity',price='$price',priceStatus='$priceStatus',postDesc='$postDesc',postedBy='$postedBy',postDeadline='$postDeadline',productLocation='$productLocation' WHERE postId = '$postId'")or die (mysqli_error());
		
		header("location: items.php");
	}		
	
?>
<?php 
if(isset($_GET['itm'])){
if($_GET['itm']=="item"){
	$getItemCompany = $_GET['itemCompanyCode'];
?>
<div id="page_content">
	<div id="page_content_inner">
		<h3 class="heading_b uk-margin-bottom">NEW POST</h3>
		<div class="uk-grid uk-grid-medium" data-uk-grid-margin="">
			<div class="uk-width-xLarge-2-10 uk-width-large-3-10 uk-row-first">
				<div class="md-card">
					<div class="md-card-toolbar">
						<h3 class="md-card-toolbar-heading-text" >
							CATEGORY
						</h3>
					</div>
					<div class="md-card-content">
						<form method="post" action="admin.php">
							Category
							<div id="locations">
								<?php 
									$sqllocation = $db->query("SELECT * FROM levels WHERE parentId = 0");
									$countResults = mysqli_num_rows($sqllocation);
									if($countResults > 0){
									echo '<select name="locationId" id="locationId" onchange="changelocation('.$getItemCompany.')">';
										echo'<option >--Select SubCategory--</option>';
										while($row = mysqli_fetch_array($sqllocation))
											{ 
												echo'<option value="'.$row['id'].'">'.$row['name'].'</option>';
											}
									echo'</select></br>';
									}else{
										echo'<input type="text" name="SavelocationId" value="0"/>';
									}
								?>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="uk-width-xLarge-8-10  uk-width-large-7-10">
				<div class="md-card">
					<div class="md-card-toolbar">
						<h3 class="md-card-toolbar-heading-text" id="new_post_title">
							POST
						</h3>
					</div>
					<div class="md-card-content large-padding" id="new_post_show">
					<h3>Select a category</h3>	
							
						</div>

				</div>
			</div>
		</div>				 
	</div>
</div>
<script src="assets/js/pages/ecommerce_product_edit.min.js"></script>
    
<?php 
}
elseif($_GET['itm']=="car"){
	$companyshipperId = $_GET['companyId'];
?>
<div id="page_content">
	<div id="page_content_inner">
		<h3 class="heading_b uk-margin-bottom">NEW TRANSPORT</h3>
		<div class="md-card">
			<div class="md-card-toolbar">
				<h3 class="md-card-toolbar-heading-text" >
					Add your car
				</h3>
			</div>
			<div class="md-card-content">
				<form method="post" action="addItem.php" enctype="multipart/form-data">
					<div class="uk-grid uk-grid-divider uk-grid-medium" data-uk-grid-margin="">
                        <div class="uk-width-large-1-2 uk-row-first">
                            <div class="uk-form-row">
                                <div class="md-input-wrapper md-input-filled">
                                	<label for="pricePK">Car Type</label>
                                	<input required type="text" class="md-input" name="carType">
                                	<input required type="hidden" value="<?php echo $companyshipperId;?>" name="companyId">
                                	<span class="md-input-bar"></span>
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <div class="md-input-wrapper md-input-filled">
                                	<label for="pricePK">Price per kilometer (frw)</label>
                                	<input required type="number" class="md-input" name="pricePK" id="pricePK">
                                	<span class="md-input-bar"></span>
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <div class="md-input-wrapper md-input-filled">
                                	<label for="maxWeight">Max Weight (kg)</label>
                                	<input required type="number" class="md-input" name="maxWeight" id="maxWeight">
                                	<span class="md-input-bar"></span>
                                </div>
                            </div>
						</div>
                        <div class="uk-width-large-1-2">
                            <div class="uk-form-row">
                                <label class="uk-form-label" for="carImage-selectized">Image</label>
                            	<div class="uk-form-file md-btn md-btn-primary" data-uk-tooltip="">
		                            Import image
		                            <input required type="file" name="carImage" id="carImage"/> 
	                            </div>
                            </div>
                            <div class="uk-form-row">
						        <input type="submit" value="ADD" class="md-fab md-fab-primary" style="color: #fff; font-weight: bold;">
                            </div>
                        </div>
                    </div>	
				</form>
			</div>
		</div>				 
	</div>
</div>
<script src="assets/js/pages/ecommerce_product_edit.min.js"></script>
 
<?php }
else{echo $_GET['itm'];}
}
else{
	echo "no set";
}
if(isset($_POST['carType'])){
	$carType	= $_POST['carType'];
	$maxWeight	= $_POST['maxWeight'];
	$pricePK	= $_POST['pricePK'];
	$companyId  = $_POST['companyId'];
	include 'db.php';

	$sql = $db->query("INSERT INTO shipper(title, WeightLimit, pricepkilo, shipperId, companyId) VALUES('$carType','$maxWeight','$pricePK','$thisid', '$companyId')") or die(mysql_error($db));
	$sql10 = $db->query("SELECT * FROM shipper ORDER BY shippingId DESC limit 1");
	while($row = mysqli_fetch_array($sql10)){
		$Imagename = $row['shippingId'];
		if ($_FILES['carImage']['tmp_name'] != "") {	
			$newname = ''.$Imagename.'.jpg';
			move_uploaded_file( $_FILES['carImage']['tmp_name'], "../shipper/$newname");
		}
		header("location: items.php");
	}
}
?>

