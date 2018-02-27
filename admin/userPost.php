<?php
include "../db.php"; 
?>
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
		$sql = $db->query("INSERT INTO posts (postTitle,productCode,quantity,price,priceStatus,postDesc,postedBy,postDeadline,productLocation) 
		VALUES ('$postTitle','$productCode','$quantity','$price','$priceStatus','$postDesc','$postedBy','$postDeadline','$productLocation')")or die (mysqli_error());
		$sql2 = $db->query("SELECT * FROM posts ORDER BY postId DESC limit 1");
			while($row = mysqli_fetch_array($sql2)){
				$Imagename = $row['postId'];
			}
		
		if ($_FILES['fileField']['tmp_name'] != "") {																	 										 
			$newname = ''.$Imagename.'.jpg';
			move_uploaded_file( $_FILES['fileField']['tmp_name'], "../products/$newname");
		}
		header("location: user.php");
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
		
		header("location: user.php");
	}		
	
?>
<?php
if(isset($_GET['postId']))
{
	$postId = $_GET['postId'];
	$sql = $db->query("SELECT * FROM items1 WHERE itemId = '$postId'");
	while($row = mysqli_fetch_array($sql))
	{
		$postTitle = $row['itemName'];
		$quantity = $row['quantity'];
		$price = $row['unityPrice'];
		$priceStatus = $row['unit'];
		$postDesc = $row['description'];
		$postedDate = $row['postDeadline'];
		$postedBy = $row['postedBy'];
		$productLocation = $row['inDate'];
	}
	?>
	<?php include'userheader.php' ;?>
	<!-- main sidebar -->
   
	    <div id="page_content">
	        <div id="page_content_inner">

	            <h4 class="heading_b uk-margin-bottom">
	            <a href="items.php?companyid=<?php echo $companyid;?>"><i class="uk-icon-angle-double-left"></i> Back</a>&nbsp;&nbsp;&nbsp; MANAGE <?php echo $postTitle;?></h4>

	            <div class="uk-grid uk-grid-width-medium-1-3" data-uk-grid="{gutter:24}">
	                <div>
	                    <div class="md-card">
	                        <div class="md-card-toolbar">
	                           
	                            <h3 class="md-card-toolbar-heading-text">
	                                <?php echo $postTitle;?>
	                            </h3>
	                        </div>
	                        <div class="md-card-content">
								<div id="dynamiclist">
									<table width="100%" cellpadding="5">
										<tr><td><a href="products/<?php echo $postId;?>.jpg"><img src="../products/<?php echo $postId;?>.jpg" width="300"/></a></td></tr>
										<tr><td>
												<div id="loadedit">
													<form action="userpost.php" method="post" enctype="multipart/form-data">
														<input id="postId" name="postId" value="<?php echo $postId;?>" hidden>
														<div class="uk-form-row">
				                                            <label for="postTitle">Product Name</label>
				                                            <input id="postTitle" name="postTitle" class="md-input"  value="<?php echo $postTitle;?>">
				                                        </div>
														<div class="uk-form-row">
				                                            <div class="uk-input-group">
								                                <label for="price">Starting Price</label>
				                                           		<input id="price" name="price" class="md-input" value="<?php echo $price;?>">
				                                           		<span class="uk-input-group-addon">Rwf</span>
				                                        	</div>
				                                        </div>
														<div class="uk-form-row">
				                                            <label for="quantity">Quantity</label>
				                                            <input id="quantity" name="quantity" class="md-input" value="<?php echo $quantity;?>">
				                                        </div>
														<div class="uk-form-row">
				                                            <label for="postedBy">Product Owner</label>
				                                            <input id="postedBy" name="postedBy" class="md-input" value="<?php echo $postedBy;?>" disabled>
				                                        </div>
				                                        <div class="uk-form-row">
				                                            <label for="productLocation">Started On</label>
				                                            <input type="date" id="productLocation" name="productLocation" class="md-input" value="<?php echo $postedDate;?>" disabled>
				                                        </div>
				                                        <div class="uk-form-row">
				                                            <label for="postedDate">Ending On</label>
				                                            <input type="date" id="postedDate" name="postedDate" class="md-input" value="<?php echo $productLocation;?>" disabled>
				                                        </div>
				                                        <div class="uk-form-row">
				                                            <label for="postDesc">Product Description</label>
				                                            <textarea id="postDesc" name="postDesc" class="md-input"> <?php echo $postDesc;?> </textarea>
				                                        </div>
				                                        <input type="submit" class="md-btn md-btn-success" value="Modify" name="modify">
													</form>
												</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
	                    </div>
	                </div>
					<div>
	                    <div class="md-card">
	                        <div class="md-card-toolbar">
	                            <div class="md-card-toolbar-actions">
	                                <i class="md-icon material-icons md-card-fullscreen-activate">&#xE5D0;</i>
	                            </div>
	                            <h3 class="md-card-toolbar-heading-text">
	                                Comments
	                            </h3>
	                        </div>
	                        <div class="md-card-content">
							<table width="100%">
								<?php 
								include ("../db.php");
								$n=0;
								$sql4 = $db->query("SELECT * FROM `postscomments` WHERE postCode='$postId' ORDER by commentId DESC") or die(mysqli_error());
								$contcomments = mysqli_num_rows($sql4);
								if($contcomments > 0){
									while($row = mysqli_fetch_array($sql4)){
										$n++;
										$commentCode=$row['commentId'];
									echo'<tr>
										<td>'.$n.'. Comment: '.$row['commentNote'].'</td>
									</tr>
									<tr>
										<td><i>By:'.$row['commentBy'].'</i> <div id="reply'.$row['commentId'].'"><i><a href="javascript:void()" onclick="reply(commentId='.$row['commentId'].', postCode='.$postId.')">Reply</a></i></div></td>
									</tr>';
									$f=0;
									$sql5 = $db->query("SELECT * FROM `commentreplies` WHERE commentCode='$commentCode' ORDER by replyID DESC") or die(mysqli_error());
									while($row = mysqli_fetch_array($sql5))
										{
											$f++;
											echo'<tr><td>.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$f.' <b>'.$row['replyBy'].'</b>: '.$row['replyNotes'].'</td></tr> ';
										}
									echo'<tr>
										<td><hr/></td>
									</tr>';
									}
								}else{
									echo'Opps! you have no comment on '.$postTitle.' yet';
								}
								?>
							</table>
									
							</div>
	                    </div>
	                </div>
					<div>
	                    <div class="md-card">
	                        <div class="md-card-toolbar">
	                            <div class="md-card-toolbar-actions">
	                                <i class="md-icon material-icons md-card-fullscreen-activate">&#xE5D0;</i>
	                            </div>
	                            <h3 class="md-card-toolbar-heading-text">
	                                Bids
	                            </h3>
	                        </div>
	                        <div class="md-card-content">
	                        	<?php 
	                        		include '../db.php';
									$sqlbids = $db->query("SELECT * FROM bids WHERE itemCode = '$postId' ORDER BY transactionID DESC")or mysqli_error();
									$nbid = 0;
									$contbids = mysqli_num_rows($sqlbids);
									if($contbids > 0){
										While($row = mysqli_fetch_array($sqlbids)){
											$nbid++;
											$customerName = $row['customerName'];
											$customerRef = $row['customerRef'];
											$trUnityPrice = number_format($row['trUnityPrice']);
											echo $nbid.'. Bid: '.$trUnityPrice.'Rwf, From: '.$customerName.', Phone: '.$customerRef.' <hr/>';
										}
									}else{
									echo'Opps! you have no bid on '.$postTitle.' yet';
								}
								?>		
							</div>
	                    </div>
	                </div>
	            </div>
					 
	        </div>
	    </div>
	<?php
}

?>

<?php
	if (isset($_GET['carId'])) {
		$carId = $_GET['carId'];
		$sql = $db->query("SELECT * FROM shipper WHERE shippingId = '$carId'");
		while($row = mysqli_fetch_array($sql))
		{
			$title = $row['title'];
			$WeightLimit = $row['WeightLimit'];
			$pricepkilo = $row['pricepkilo'];
			$shipperId = $row['shipperId'];
			$selectCompany = $db->query("SELECT * FROM company1 WHERE companyUserCode = '$shipperId'");
			$company = mysqli_fetch_array($selectCompany);
			$companyName = $company['companyName'];
		}
	?>
		<?php include'userheader.php' ;?>
		<!-- main sidebar -->
	   
		    <div id="page_content">
		        <div id="page_content_inner">

		            <h4 class="heading_b uk-margin-bottom">
		            <a href="items.php?companyid=<?php echo $companyid;?>"><i class="uk-icon-angle-double-left"></i> Back</a>&nbsp;&nbsp;&nbsp; Manage <?php echo $title;?></h4>

		            <div class="uk-grid uk-grid-width-medium-1-3" data-uk-grid="{gutter:24}">
		                <div>
		                    <div class="md-card">
		                        <div class="md-card-toolbar">
		                           
		                            <h3 class="md-card-toolbar-heading-text">
		                                <?php echo $title;?>
		                            </h3>
		                        </div>
		                        <div class="md-card-content">
									<div id="dynamiclist">
										<table width="100%" cellpadding="5">
											<tr><td><a href="shipper/<?php echo $carId;?>.jpg"><img src="../shipper/<?php echo $carId;?>.jpg" width="300"/></a></td></tr>
											<tr><td>
													<div id="loadedit">
														<form action="userpost.php" method="post" enctype="multipart/form-data">
															<input id="postId" name="postId" value="<?php echo $postId;?>" hidden>
															<div class="uk-form-row">
					                                            <label for="carType">Car Type</label>
					                                            <input id="carType" name="carType" class="md-input"  value="<?php echo $title;?>">
					                                            <input id="carId" name="carId" class="md-input"  value="<?php echo $carId;?>" hidden>
					                                        </div>
															<div class="uk-form-row">
					                                            <div class="uk-input-group">
									                                <label for="price">Price Per Kilometer</label>
					                                           		<input id="pricepkilo" name="pricepkilo" class="md-input" value="<?php echo $pricepkilo;?>">
					                                           		<span class="uk-input-group-addon">Rwf</span>
					                                        	</div>
					                                        </div>
															<div class="uk-form-row">
					                                            <label for="quantity">Maximum Quantity</label>
					                                            <input id="WeightLimit" name="WeightLimit" class="md-input" value="<?php echo $WeightLimit;?>">
					                                        </div>
															<div class="uk-form-row">
					                                            <label for="postedBy">Car Owner</label>
					                                            <input id="postedBy" name="postedBy" class="md-input" value="<?php echo $companyName;?>" disabled>
					                                        </div>
					                                        <input type="submit" class="md-btn md-btn-success" value="Change" name="Change">
														</form>
													</div>
												</td>
											</tr>
										</table>
									</div>
								</div>
		                    </div>
		                </div>
		            </div>
						 
		        </div>
		    </div>
		<?php
	}
?>

<?php


	// MODIFY ITEM
	if(isset($_POST['modify'])) {
		$PostTitle = $_POST['postTitle'];
		$Price = $_POST['price'];
		$Quantity = $_POST['quantity'];
		$ProductLocation = $_POST[''];
		$PostDesc = $_POST['postDesc'];
		//$PriceStatus = $_POST['modifyPriceStatus'];
		$PostId = $_POST['postId'];
		$updatePost = $db ->query("UPDATE items1 SET itemName = '$PostTitle', unityPrice = '$Price', quantity = '$Quantity', description = '$PostDesc', postDeadline = '$ProductLocation' WHERE itemId = '$PostId'");
		?>
			<script type="text/javascript">
				document.location.href = "items.php";
			</script>
		<?php
	}

	// CHANGE CAR
	if (isset($_POST['Change'])) {
		$carType = $_POST['carType'];
		$carId = $_POST['carId'];
		$WeightLimit = $_POST['WeightLimit'];
		$pricepkilo = $_POST['pricepkilo'];
		$updateCar = $db ->query("UPDATE shipper SET title = '$carType', WeightLimit = '$WeightLimit', pricepkilo = '$pricepkilo' WHERE shippingId = '$carId'");
		?>
			<script type="text/javascript">
				document.location.href = "items.php";
			</script>
		<?php
	}

?>

<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->


    <!-- google web fonts -->
    <script>
        WebFontConfig = {
            google: {
                families: [
                    'Source+Code+Pro:400,700:latin',
                    'Roboto:400,300,500,700,400italic:latin'
                ]
            }
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
            '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })();
    </script>

    <!-- common functions -->
    <script src="assets/js/common.min.js"></script>
    <!-- uikit functions -->
    <script src="assets/js/uikit_custom.min.js"></script>
    <!-- altair common functions/helpers -->
    <script src="assets/js/altair_admin_common.min.js"></script>

    <!-- datatables -->
    <script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <!-- datatables buttons-->
    <script src="bower_components/datatables-buttons/js/dataTables.buttons.js"></script>
    <script src="assets/js/custom/datatables/buttons.uikit.js"></script>
    <script src="bower_components/jszip/dist/jszip.min.js"></script>
    <script src="bower_components/pdfmake/build/pdfmake.min.js"></script>
    <script src="bower_components/pdfmake/build/vfs_fonts.js"></script>
    <script src="bower_components/datatables-buttons/js/buttons.colVis.js"></script>
    <script src="bower_components/datatables-buttons/js/buttons.html5.js"></script>
    <script src="bower_components/datatables-buttons/js/buttons.print.js"></script>
    
      <!-- datatables custom integration -->
    <script src="assets/js/custom/datatables/datatables.uikit.min.js"></script>

    <!--  datatables functions -->
    <script src="assets/js/pages/plugins_datatables.min.js"></script>
    <script>
        $(function() {
            if(isHighDensity()) {
                $.getScript( "bower_components/dense/src/dense.js", function() {
                    // enable hires images
                    altair_helpers.retina_images();
                });
            }
            if(Modernizr.touch) {
                // fastClick (touch devices)
                FastClick.attach(document.body);
            }
        });
        $window.load(function() {
            // ie fixes
            altair_helpers.ie_fix();
        });
    </script>

<script>
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
 if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 

today = yyyy+'-'+mm+'-'+dd;
document.getElementById("postedDate").setAttribute("min", today);
</script>

	
<script> 
<!--4 Load reply box-->
function reply(commentId,postCode)
{
	var commentId = commentId;
	var postCode = postCode;
	$.ajax({
		type : "GET",
		url : "userscript.php",
		dataType : "html",
		cache : "false",
		data : {
			
			commentId : commentId,
			postCode : postCode,
		},
		success : function(html, textStatus){
			$("#reply"+commentId+"").html(html);
		},
		error : function(xht, textStatus, errorThrown){
			alert("Error : " + errorThrown);
		}
	});
}

<!-- 5 save reply-->
function replyComment(){
	var replyBy = document.getElementById('replyBy').value;
	var postCode = document.getElementById('postCode').value;
	var replyNotes = document.getElementById('replyNote').value;
	if (replyNotes == null || replyNotes == "") {
        alert("replyNotes must be filled out");
        return false;
    }
	var visibilityStatus = document.getElementById('visibilityStatus').value;
	if (visibilityStatus == null || visibilityStatus == "") {
        alert("visibilityStatus must be filled out");
        return false;
    }
	var commentCode = document.getElementById('commentCode').value;
	if (commentCode == null || commentCode == "") {
        alert("commentCode must be filled out");
        return false;
    }
	$.ajax({
			type : "GET",
			url : "userscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				replyNotes : replyNotes,
				postCode : postCode,
				replyBy : replyBy,
				visibilityStatus : visibilityStatus,
				commentCode : commentCode,
			},
			success : function(html, textStatus){
				$("#reply"+commentCode+"").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
<!-- 5 MODIFY PRODUCT-->

function modifyItem(){
	//alert('yeah!');
	var postId = document.getElementById('postId').value;
	var postTitle = document.getElementById('postTitle').value;
	if (postTitle == null || postTitle == "") {
        alert("postTitle must be filled out");
        return false;
    }
	var price = document.getElementById('price').value;
	if (price == null || price == "") {
        alert("price must be filled out");
        return false;
    }
	var quantity = document.getElementById('quantity').value;
	if (quantity == null || quantity == "") {
        alert("quantity must be filled out");
        return false;
    }
	var productLocation = document.getElementById('productLocation').value;
	if (productLocation == null || productLocation == "") {
        alert("productLocation must be filled out");
        return false;
    }
	var postDesc = document.getElementById('postDesc').value;
	if (postDesc == null || postDesc == "") {
        alert("postDesc must be filled out");
        return false;
    }
	/*var priceStatus = document.getElementById('priceStatus').value;
	if (priceStatus == null || priceStatus == "") {
        alert("priceStatus must be filled out");
        return false;
    }*/
	$.ajax({
			type : "GET",
			url : "userscript.php",
			dataType : "html",
			cache : false,
			data : {
				modifyPostTitle : postTitle,
				modifyPrice : price,
				modifyQuantity : quantity,
				modifyProductLocation : productLocation,
				modifyPostDesc : postDesc,
				//modifyPriceStatus : priceStatus,
				modifyPostId : postId,
			},
			success : function(html, textStatus){
				$("#loadedit").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
</script>
</body>
</html>