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
$sql = $db->query("SELECT * FROM users u INNER JOIN useraccounttype ua WHERE u.id = ua.userId AND loginId = '$username' AND pwd = '$password' limit 1"); // query the person
// ------- MAKE SURE PERSON EXISTS IN DATABASE ---------
$existCount = mysqli_num_rows($sql); // count the row nums
if ($existCount > 0) { 
	while($row = mysqli_fetch_array($sql)){ 
			 $thisid = $row["id"];
			 $names = $row["names"];
			$userpic = $row["Pic"];
			 $account_type = $row["accName"];
			 if($account_type =='admin')
		{
			header("location: admin.php");
			exit();
		}
			}
		} 
		else{
		echo "
		
		<br/><br/><br/><h3>Your account has been temporally deactivated</h3>
		<p>Please contact: <br/><em>(+25) 0782010262</em><br/><b>uwamclemmy@gmail.com</b></p>		
		Or<p><a href='../logout.php'>Click Here to login again</a></p>
		
		";
	    exit();
	}
?>

<?php
	if(isset($_POST['addpst']))
	{
		$itemName = $_POST['itemName'];
		$productCode = $_POST['productCode'];
		$itemCompanyCode = $_POST['itemCompanyCode'];
		$unit = $_POST['unit'];
		$unityPrice = $_POST['unityPrice'];
		$quantity = $_POST['quantity'];
		$description = $_POST['description'];
		echo $itemName;
		echo' itemName<br/>';
		echo $productCode;echo' productCode<br/>';
		echo $itemCompanyCode;echo' itemCompanyCode<br/>';
		echo $unit;echo' unit<br/>';
		echo $unityPrice;echo' unityPrice<br/>';
		echo $quantity;echo' quantity<br/>';
		echo $description ;echo' description<br/>';
		$addtheitem = $db->query("INSERT INTO `items1`(`itemName`, `productCode`, `itemCompanyCode`, `unit`, `unityPrice`, description) 
		VALUES ('$itemName','$productCode','$itemCompanyCode','$unit','$unityPrice','$description')
		")or die (mysqli_error());
		
		
		$sql2 = $db->query("SELECT * FROM items1 ORDER BY itemId DESC limit 1");
			while($row = mysqli_fetch_array($sql2)){
				$Imagename = $row['itemId'];
			}
			
		$sql5 = $db->query("INSERT INTO `bids`
		(`trUnityPrice`, `qty`, `itemCode`, `operation`,`companyId`,`operationStatus`, doneBy) 
VALUES  ('$unityPrice','$quantity','$Imagename','In','$itemCompanyCode','1','$thisid')")or die(mysqli_error());
		
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

<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<?php include'userheader.php' ;?>
	<!-- main sidebar -->
<div id="new_comp">
	<div id="page_content">
        <div id="page_content_inner">
			<h3 class="heading_b uk-margin-bottom">MANAGE COMPANIES</h3>
			<div class="uk-grid uk-grid-medium" data-uk-grid-margin="">
				<?php 
					$sqlseller = $db->query("SELECT * FROM company1 WHERE cumpanyUserCode = '$thisid'");
					$countComanies = mysqli_num_rows($sqlseller);
					if($countComanies>0) {
						while($row = mysqli_fetch_array($sqlseller)) {
							$comanyId = $row['companyId'];
							echo '
        						<div class="uk-width-xLarge-2-10 uk-width-large-3-10 uk-row-first" >
									<a href="items.php?companyid='.$comanyId.'">
										<div class="md-card">
				                            <img src="../company/'.$comanyId.'.jpg" alt="">
				                            <div class="md-card-content">
				                                <strong>'.$row['companyName'].'</strong><br>
				                                <span class="uk-text-muted">'.$row['companyDescription'].'.</span>
				                            </div>
				                        </div>
			                        </a>		
								</div>
		                    ';
						}
						echo '
							<a href="javascript:void()" onclick="addcomp()">
								<div class="md-card">
		                            <div class="md-card-content">
		                                <strong>ADD A COMPANY</strong>
		                            </div>
		                        </div>
			                </a>
			            ';
					}
					else {
						echo'
							<a href="javascript:void()" onclick="addcomp()">
								<div class="md-card">
		                            <div class="md-card-content">
		                                <strong>ADD A COMPANY</strong>
		                            </div>
		                        </div>
			                </a>
			        	';
					}
				?>	
			</div>	
            <h4 class="heading_a uk-margin-bottom">Your Orders With Pandagali</h4>
            <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                <div class="uk-width-large-4-4">
                    <div class="md-card">
                        <div class="md-card-content">
                            <div class="dt_colVis_buttons"></div>
                            <table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Unit</th>
                                        <th>Unit Price</th>
                                        <th>From</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
										<?php 
											$sqlOrder = $db->query("SELECT * FROM orders WHERE customerCode = '$thisid'");
											$count = mysqli_num_rows($sqlOrder);
											if ($count > 0) {
												$i = 0;
												while($order = mysqli_fetch_array($sqlOrder)) {
													$i++;
													$orderId = $order['orderId'];
													$itemId = $order['itemCode'];
													$customerCode = $order['customerCode'];
													$quantity = $order['quantity'];
													$orderDate = $order['orderDate'];
													$unityPrice = $order['unityPrice'];
													$totalPrice = $order['TotalPrice'];
													$Orderstatus = $order['orderStatus'];
													$trackingCode = $order['trackingCode'];
													$itemCompanyCode = $order['itemCompanyCode'];
													$selectItem = $db ->query("SELECT * FROM items1 WHERE itemId = '$itemId'");
													$item = mysqli_fetch_array($selectItem);
													$itemName = $item['itemName'];
													$itemUnit = $item['unit'];
													$selectCustomer = $db ->query("SELECT * FROM users WHERE id = '$customerCode'");
													$Customer = mysqli_fetch_array($selectCustomer);
													$customerName = $Customer['names'];
													$selectCompany = $db ->query("SELECT * FROM company1 WHERE companyId = '$itemCompanyCode'");
													$Company = mysqli_fetch_array($selectCompany);
													$CompanyName = $Company['companyName'];
													echo '
					                                    <tr>
					                                        <td>'.$i.'</td>
					                                        <td>'.$itemName.'</td>
					                                        <td>'.$quantity.'</td>
					                                        <td>'.$itemUnit.'</td>
					                                        <td>'.$unityPrice.'</td>
					                                        <td>'.$CompanyName.'</td>
					                                        <td>'.$orderDate.'</td>
					                                        <td>'.$Orderstatus.'</td>
					                                    </tr>
					                                ';
												}
											}
											else {
												echo '
													<tr>
														<td colspan="8">
															<center><b>No Transaction yet</b></center>
														</td>
													</tr>
												';
											}
										?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
			</div>				 
        </div>
    </div>
</div>

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
    <!-- common functions -->
    <!-- page specific plugins -->
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
    
    <!-- d3 -->
    <script src="bower_components/d3/d3.min.js"></script>
    <!-- metrics graphics (charts) -->
    <script src="bower_components/metrics-graphics/dist/metricsgraphics.min.js"></script>
    <!-- c3.js (charts) -->
    <script src="bower_components/c3js-chart/c3.min.js"></script>
    <!-- chartist -->
    <script src="bower_components/chartist/dist/chartist.min.js"></script>

    <!--  charts functions -->
    <script src="assets/js/pages/plugins_charts.min.js"></script>


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
	
<script> <!--0 Add Company-->
function addcomp(){

	var comp = 'yes';
		
	$.ajax({
			type : "GET",
			url : "createCompany.php",
			dataType : "html",
			cache : "false",
			data : {
				
				comp : comp,
			},
			success : function(html, textStatus){
				$("#new_comp").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
</script>	
<script> <!--1 Show subcat-->
function get_sub(){
	var catId =$("#catId").val();
	//alert(catId);
	$.ajax({
		type : "GET",
		url : "userscript.php",
		dataType : "html",
		cache : "false",
		data : {
			catId : catId,
		},
		success : function(html, textStatus){
			$("#suboption").html(html);
		},
		error : function(xht, textStatus, errorThrown){
			alert("Error : " + errorThrown);
		}
	});
}
</script>
<script> <!--2 Show products-->
function get_prod(){
	var subCatId =$("#subCatId").val();
	//alert(subCatId);
	$.ajax({
			type : "GET",
			url : "userscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				subCatId : subCatId,
			},
			success : function(html, textStatus){
				$("#prodoption").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
</script>
<script> <!--3 start new post-->
function new_post(){
	var productId =$("#productId").val();
	//alert(productId);
	$.ajax({
			type : "GET",
			url : "userscript.php",
			dataType : "html",
			cache : "false",
			data : {
				
				productId : productId,
			},
			success : function(html, textStatus){
				$("#new_post_show").html(html);
			},
			error : function(xht, textStatus, errorThrown){
				alert("Error : " + errorThrown);
			}
	});
}
</script>
</body>
</html>