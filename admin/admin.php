<?php // Destry session if it hasn't been used for 15 minute.
session_start();
	
if (!isset($_SESSION["username"])) {
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
		$userpic = $row["Pic"];
		$account_type = $row["accName"];
		if ($account_type != 'admin') {
			header("location: user.php");
		}
	}
} 
else {
	echo "
	
	<br/><br/><br/><h3>Your account has been temporally deactivated</h3>
	<p>Please contact: <br/><em>(+25) 078 484-8236</em><br/><b>muhirwaclement@gmail.com</b></p>		
	Or<p><a href='../pages/logout.php'>Click Here to login again</a></p>
	
	";
    exit();
}
?>

<?php
//error_reporting(0);
if(isset($_POST['add']))
{
	$cat_name = $_POST['cat_name'];
	$cat_desc = $_POST['cat_desc'];
	include ("../db.php");
	$sql = $db->query("INSERT INTO `productcategory`(`catNane`, `catDesc`) 
	VALUES ('$cat_name', '$cat_desc')")or die (mysqli_error());
	header("location: admin.php");
	}
	elseif(isset($_POST['edit']))
	{
		$cat_id = $_POST['cat_id'];
		$cat_name = $_POST['cat_name'];
		$cat_desc = $_POST['cat_desc'];
		include ("../db.php");
		$sql = $db->query("UPDATE productcategory SET catNane = '$cat_name', catDesc= '$cat_desc' WHERE catId = '$cat_id'")or die (mysqli_error());
		header("location: admin.php");
	}
	
if(isset($_POST['adds']))
{
	$CatCode = $_POST['CatCode'];
	$subCatName = $_POST['subCatName'];
	$subCatDesc = $_POST['subCatDesc'];
	include ("../db.php");
	$sql = $db->query("INSERT INTO productsubcategory(subCatName, subCatDesc, CatCode) 
	VALUES ('$subCatName', '$subCatDesc', '$CatCode')")or die (mysqli_error());
	header("location: admin.php");
	}
if(isset($_POST['addcat']))
{
	$levelname = $_POST['levelname'];
	$locationId = $_POST['SavelocationId'];
	$cumpanyUserCode = $_POST['cumpanyUserCode'];
	//include ("../db.php");
	$sql = $db->query("INSERT INTO `levels` 
	(`name`, `parentId`, `createdBy`)
	VALUES ('$levelname', '$locationId', '$cumpanyUserCode')
	")or die (mysqli_error());
	header("location: admin.php");
}

if(isset($_POST['addtag']))
{
	$tagname = $_POST['tagname'];
	$levelId = $_POST['SavelocationId'];
	$cumpanyUserCode = $_POST['cumpanyUserCode'];
	//include ("../db.php");
	$sql = $db->query("INSERT INTO `tags` 
	(`name`, `levelId`, `createdBy`, `propertyId`)
	VALUES ('$tagname', '$levelId', '$cumpanyUserCode', 0)
	")or die (mysqli_error());
	header("location: admin.php");
}
	elseif(isset($_POST['edits']))
	{
		$subCatId = $_POST['subCatId'];
		$subCatName = $_POST['subCatName'];
		$subCatDesc = $_POST['subCatDesc'];
		include ("../db.php");
		$sql = $db->query("UPDATE productsubcategory SET subCatName = '$subCatName', subCatDesc= '$subCatDesc' WHERE subCatId = '$subCatId'")or die (mysqli_error());
		header("location: admin.php");
	}

// Add product	
if(isset($_POST['addp']))
{
	$productName = $_POST['productName'];
	$subCatCode = $_POST['subCatCode'];
	$productDesc = $_POST['productDesc'];
	echo $productName;
	echo '<br/>';
	echo $subCatCode;
	echo '<br/>';
	echo $productDesc;
	echo '<br/>';
	include ("../db.php");
	$sql = $db->query("INSERT INTO products(productName, productDesc, subCatCode, unit, status, createDate_By) 
	VALUES ('$productName', '$productDesc', '$subCatCode', '10', 'Active', 'me')")or die (mysqli_error());
	header("location: admin.php");
	}
// Edit product
	elseif(isset($_POST['editp']))
	{
		$productId = $_POST['productId'];
		$productName = $_POST['productName'];
		$productDesc = $_POST['productDesc'];
		include ("../db.php");
		$sql = $db->query("UPDATE products SET productName = '$productName', productDesc= '$productDesc' WHERE productId = '$productId'")or die (mysqli_error());
		header("location: admin.php");
	}
?>
<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<?php include'header.php' ;?>
	<!-- main sidebar -->
   
    <div id="page_content">
        <div id="page_content_inner">	
            <div class="uk-grid uk-grid-medium" data-uk-grid-margin>
                <div class="uk-width-large-4-4">
					<div class="md-card">
	                    <div class="md-card-content">
	                        <h4 class="heading_c uk-margin-bottom">Orders</h4>
	                        <div id="chartist_line_area" class="chartist"></div>
	                    </div>
                    </div>
                </div>
			</div>	
			<?php
				$output = '';
				$sqlOrder = $db->query("SELECT * FROM orders");
				$count = mysqli_num_rows($sqlOrder);
				if ($count > 0) {
					$i = 0;
					$s = 0;
					while($order = mysqli_fetch_array($sqlOrder)) {
						$Orderstatus = $order['orderStatus'];
						if($Orderstatus == 'Shipped') {
							$s++;
							$selectPercentage = $db->query("SELECT * FROM `charges` WHERE chargedFrom = 'saler'");
				            $rowpercentage = mysqli_fetch_array($selectPercentage);
				            $percentage = $rowpercentage['percentage'];
							$profit = (($percentage/100)*$order['unityPrice']);
							$totalprofit = $profit*$order['quantity'];
							$fullprofit = $fullprofit + $totalprofit;
						}
						$i++;
						$orderId = $order['orderId'];
						$itemId = $order['itemCode'];
						$customerCode = $order['customerCode'];
						$quantity = $order['quantity'];
						$orderDate = $order['orderDate'];
						$unityPrice = $order['unityPrice'];
						$totalPrice = $order['TotalPrice'];
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
						$selectPercentage = $db->query("SELECT * FROM `charges` WHERE chargedFrom = 'saler'");
			            $rowpercentage = mysqli_fetch_array($selectPercentage);
			            $percentage = $rowpercentage['percentage'];
						$waitedprofit = (($percentage/100)*$order['unityPrice']);
						$waitedtotalprofit = $waitedprofit*$quantity;
						$waitedfullprofit = $waitedfullprofit + $waitedtotalprofit;
						$output .= '
	                        <tr>
	                            <td>'.$i.'</td>
	                            <td>'.$itemName.'</td>
	                            <td>'.$quantity.'</td>
	                            <td>'.$itemUnit.'</td>
	                            <td>'.$unityPrice.'</td>
	                            <td>'.$CompanyName.'</td>
	                            <td>'.$waitedtotalprofit.'</td>
	                            <td>'.$orderDate.'</td>
	                            <td>'.$Orderstatus.'</td>
	                        </tr>
	                    ';
					}
				}
				else {
					$output .= '
						<tr>
							<td colspan="8">
								<center><b>No Transaction yet</b></center>
							</td>
						</tr>
					';
				}
			?>
            <h4 class="heading_a uk-margin-bottom">Total Orders: <?php echo number_format($i); ?>, Complete are: <?php echo number_format($s); ?> which makes profit of <?php echo number_format($fullprofit); ?></h4>
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
                                        <th>Profit</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
										<?php 
											echo $output;
										?>
                                </tbody>
                            </table>
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
</body>
</html>