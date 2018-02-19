<?php // Destry session if it hasn't been used for 15 minute.
session_start();
	$inactive = 900;
    if(isset($_SESSION['timeout']) ) 
	{
		$session_life = time() - $_SESSION['timeout'];
		if($session_life > $inactive)
		{
		header("Location: ../logout.php"); 
		}
    }
    $_SESSION['timeout'] = time();
	if (!isset($_SESSION["username"])) 
	{
		header("location: login.php"); 
		exit();
	}
include "../db.php"; 
	
?>
<?php 
$session_id = preg_replace('#[^0-9]#i', '', $_SESSION["id"]); // filter everything but numbers and letters
$username = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["username"]); // filter everything but numbers and letters
$password = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["password"]); // filter everything but numbers and letters
include "../db.php"; 
$sql = $db->query("SELECT * FROM users WHERE loginId='$username' AND pwd='$password' LIMIT 1"); // query the person
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
	
	?>
<?php
// get the subcategory list
if(isset($_GET['catId']))
{
	$catId = $_GET['catId'];
	$catoption="";
	$sql = $db->query("SELECT * FROM `productsubcategory` WHERE CatCode = '$catId' ");
	while($row = mysqli_fetch_array($sql))
	{
		$catoption.='<option value="'.$row['subCatId'].'">'.$row['subCatName'].'</option>
		';
	}echo'<select onchange="get_prod()" id="subCatId">
	<option></option>
	'.$catoption.'
	</select>
	';
}
// get the product list
if(isset($_GET['subCatId']))
{
	$subCatId = $_GET['subCatId'];
	include ("../db.php");
	$catoption="";
	$sql = $db->query("SELECT * FROM `products` WHERE subCatCode = '$subCatId' ");
	while($row = mysqli_fetch_array($sql))
	{
		$catoption.='<option value="'.$row['productId'].'">'.$row['productName'].'</option>
		';
	}echo'<select onchange="new_post()" id="productId">
	<option></option>
	'.$catoption.'
	</select>
	';
}
// get the form to post a new post
if(isset($_GET['productId']))
{
	$productId = $_GET['productId'];
	include ("../db.php");
	$sql = $db->query("SELECT * FROM `levels` WHERE id = '$productId'");
	$selectPercentage = $db->query("SELECT * FROM `charges` WHERE chargedFrom = 'saler'");
	$rowpercentage = mysqli_fetch_array($selectPercentage);
	$percentage = $rowpercentage['percentage'];
	while($row = mysqli_fetch_array($sql))
	{
		$productName = $row['name'];
		$productId = $row['id'];
		$sqlseller = $db->query("SELECT * FROM company1 WHERE cumpanyUserCode = '$thisid'");
		while($row = mysqli_fetch_array($sqlseller)) 
			{
				$comanyId = $row['companyId'];
				echo'
				<form method="post" action="addItem.php" enctype="multipart/form-data">
					
					<div class="uk-grid uk-grid-divider uk-grid-medium" data-uk-grid-margin="">
                        <div class="uk-width-large-1-2 uk-row-first">
                            <div class="uk-form-row">
                                <div class="md-input-wrapper md-input-filled">
                                	<label for="itemName">Product Name</label>
                                	<input required type="text" class="md-input" name="itemName">
                                	<span class="md-input-bar"></span>
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <div class="md-input-wrapper md-input-filled">
                                	<label for="unityPrice">Price</label>
                                	<input required type="number" onkeyup="pricechange('.$percentage.')" class="md-input" name="unityPrice" id="unityPrice">
                                	<span class="md-input-bar"></span>
                                </div>
                                <div class="md-input-wrapper md-input-filled">
                                	<label for="unityPrice">On web Price</label>
                                	<input type="number" class="md-input" name="onwebunityPrice" id="onwebunityPrice" disabled>
                                	<span class="md-input-bar"></span>
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <div class="md-input-wrapper md-input-filled">
                                	<label for="quantity">Quantity</label>
                                	<input required type="number" class="md-input" name="quantity" id="quantity">
                                	<span class="md-input-bar"></span>
                                </div>
                            </div>
                            <div class="uk-form-row">
                                <div class="md-input-wrapper md-input-filled">
                                	<label for="endingdate">Ending Date</label>
                                	<input type="date" class="md-input" name="endingdate" id="endingdate">
                                	<span class="md-input-bar"></span>
                                </div>
                            </div>
						</div>
                        <div class="uk-width-large-1-2">
                            <div class="uk-form-row">
                                <label class="uk-form-label" for="fileField-selectized">Image</label>
                                	<div class="uk-form-file md-btn md-btn-primary" data-uk-tooltip="">
			                            Import image
			                            <input required type="file" name="fileField" id="fileField"/> 
                                	</div>
                            </div>
                            <div class="uk-form-row">
                                <div class="md-input-wrapper md-input-filled">
                                	<label for="description">Description</label>
                                	<textarea required name="description" class="md-input" id="description" cols="30" rows="4"></textarea>
                           		 </div>
                            </div>
                        </div>
                    </div>
						<input  type="text" name="productCode" value="'.$productId.'" hidden/>				
						<input  type="text" name="itemCompanyCode" value="'.$comanyId.'" hidden/><br/>			
						<input  type="text" name="username" value="'.$username.'" hidden/><br/>	
						
					
				<div class="md-fab-wrapper">
        <button type="submit" class="md-fab md-fab-primary" id="product_edit_submit" name="addpst">
				<i class="material-icons"></i></button>
        
    </div></form>';
			}
	}
}
// get the post title
if(isset($_GET['posttilte']))
{
	$productId = $_GET['posttilte'];
	include ("../db.php");
	$sql = $db->query("SELECT * FROM `levels` WHERE id = '$productId'");
	while($row = mysqli_fetch_array($sql))
	{
		echo 'POST IN ('.$row['name'].')';
	
	}
}
// delete post
if(isset($_GET['removepostid'])){
	$removepostid = $_GET['removepostid'];
	include '../db.php';
	$sqlremove = $db->query("DELETE FROM `items1` WHERE `itemId` = '$removepostid'");
	unlink("../products/".$_GET['removepostid'].".jpg");
}

// delete post
if(isset($_GET['removeshippingid'])){
	$removeshippingid = $_GET['removeshippingid'];
	include '../db.php';
	$sqlremoveshipping = $db->query("DELETE FROM `shipper` WHERE `shippingId` = '$removeshippingid'");
	unlink("../shipper/".$_GET['removeshippingid'].".jpg");
}
?>

<?php
	// MODIFY ITEM
	if(isset($_POST['modifyPostTitle'])) {
		$PostTitle = $_GET['modifyPostTitle'];
		$Price = $_GET['modifyPrice'];
		$Quantity = $_GET['modifyQuantity'];
		$ProductLocation = $_GET['modifyProductLocation'];
		$PostDesc = $_GET['modifyPostDesc'];
		//$PriceStatus = $_GET['modifyPriceStatus'];
		$PostId = $_GET['modifyPostId'];
		
		$updatePost = $db ->query("UPDATE items1 SET itemName = '$PostTitle', unityPrice = '$Price', quantity = '$Quantity', description = '$PostDesc', postDeadline = '$ProductLocation' WHERE itemId = '$PostId'") or die(mysqli_error());
		$sql2 = $db->query("SELECT * FROM items1 WHERE itemId = '$PostId'");
		while($row = mysqli_fetch_array($sql2)) {
			$postTitle = $row['itemName'];
			$quantity = $row['quantity'];
			$price = $row['unityPrice'];
			$priceStatus = $row['unit'];
			$postDesc = $row['description'];
			$postedDate = $row[''];
			$postedBy = $row['postedBy'];
			$productLocation = $row['productLocation'];
		}
		echo'
			<style> .notif{font-family: Arial, Helvetica, sans-serif;font-size: 14px;color: #fe2c2c;}></style>
			<div class="notif">Post modifiyed succesfully <a href="userPost.php?postId='.$PostId.'">Close</a></div>
			<table>
				<tr>
					<td>Name: </td>
					<td><input id="postTitle" value="'.$postTitle.'">
					<input id="postId" value="'.$PostId.'" hidden></td>
				</tr>
				<tr>
					<td>Price: </td>
					<td><input id="price" value="'.$price.'"> Rwf, 
					<select id="priceStatus">
						<option value="'.$priceStatus.'">'.$priceStatus.'</option>
						<option value="Negociable">Negociable</option>
						<option value="Fixed">Fixed</option>
					</select>
					</td>
				</tr>
				<tr>
					<td>Quantity: </td>
					<td><input id="quantity" value="'.$quantity.'"></td>
				</tr>
				<tr>
					<td>Owner: </td>
					<td><input id="postedBy" value="'.$postedBy.'" disabled></td>
				</tr>
				<tr>
					<td>Located: </td>
					<td><input id="productLocation" value="'.$productLocation.'"></td>
				</tr>
				<tr>
					<td>More Info: </td>
					<td><textarea id="postDesc">'.$postDesc.'</textarea></td>
				</tr>
				<tr>
					<td>Was here since: </td>
					<td><input id="postedDate" value="'.$postedDate.'" disabled></td>
				</tr>
			</table>
		';
	}
?>

<?php
// reply box
if(isset($_GET['commentId']))
{
	$commentId = $_GET['commentId'];
	$postCode = $_GET['postCode'];
	if (isset($_SESSION["username"])) {
$username = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["username"]);
	echo'<br/><textarea id="replyNote" placeholder="your comment Plz!"></textarea>
	<input id="replyBy" value="'.$username.'" hidden/>
	<input id="postCode" value="'.$postCode.'" hidden/>
	<input id="commentCode" value="'.$commentId.'" hidden/><br/>
	<select id="visibilityStatus">
		<option value=""></option>
		<option value="Private">Private</option>
		<option value="Public">Public</option>
		</select>
	<button onclick="replyComment()">Comment</button>
	';
}else{
	echo'please first <a href="login.php">sign</a> in or <a href="../register.php">register</a> to submit a comment.';
}
}
if(isset($_GET['replyNotes']))
{
	$replyNotes = $_GET['replyNotes'];
	$replyBy = $_GET['replyBy'];
	$postCode = $_GET['postCode'];
	$commentCode = $_GET['commentCode'];
	$visibilityStatus = $_GET['visibilityStatus'];
	
	
	$sql = $db->query("INSERT INTO `commentreplies`(replyNotes, replyBy, visibilityStatus, commentCode) 
	VALUES ('$replyNotes', '$replyBy', '$visibilityStatus', '$commentCode')")or (mysqli_error());
	echo'your reply has been successfully submited! <a href="userPost.php?postId='.$postCode.'">Click Here</a>
	<br/>
	<br/>
	';
}
?>