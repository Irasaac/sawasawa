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
			 $account_type = $row["account_type"];
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
<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<?php include'userheader.php' ;?>
	<!-- main sidebar -->
	<div id="page_content">
		<div id="page_content_inner">
			<h4 class="heading_b uk-margin-bottom">
			<a href="user.php"><i class="uk-icon-angle-double-left"></i> Back</a>&nbsp;&nbsp;&nbsp; Money Transactions in <?php echo $companyName;?></h4>
			<div class="uk-grid uk-grid-medium" data-uk-grid-margin="">
				<div class="uk-width-xLarge-10-10  uk-width-large-10-10">
					<div class="md-card">
						<div class="md-card-content">
							<div class="uk-overflow-container uk-margin-bottom">
								<table class="uk-table uk-table-nowrap tablesorter tablesorter-altair" id="ts_pager_filter">
									<thead>
	                                    <tr>
	                                        <th>No</th>
	                                        <th>Item Name</th>
	                                        <th>Unit Price</th>
	                                        <th>Quantity</th>
	                                        <th>Total Price</th>
	                                        <th>By</th>
	                                        <th>Time</th>
	                                        <th>Status</th>
	                                    </tr>
                                    </thead>
									<tbody>
										<?php

											include '../db.php';
											$selectOrder = $db->query("SELECT * FROM orders WHERE itemCompanyCode = '$companyid' ORDER BY orderId DESC");
											$i = 0;
											while ($order = mysqli_fetch_array($selectOrder)) {
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
												$selectItem = $db ->query("SELECT * FROM items1 WHERE itemId = '$itemId'");
												$item = mysqli_fetch_array($selectItem);
												$itemName = $item['itemName'];
												$itemUnit = $item['unit'];
												$selectCustomer = $db ->query("SELECT * FROM users WHERE id = '$customerCode'");
												$Customer = mysqli_fetch_array($selectCustomer);
												$customerName = $Customer['names'];
												if ($Orderstatus == 'Pending') {									
													echo '
														<tr>
															<td>'.$i.'</td>
															<td>'.$itemName.'</td>
															<td>'.number_format($unityPrice).' Frw</td>
															<td>'.number_format($quantity).' '.$itemUnit.'</td>
															<td>'.number_format($totalPrice).' Frw</td>
															<td>'.$customerName.'</td>
															<td>'.$orderDate.'</td>
															<td class="status'.$orderId.'">'.$Orderstatus.' <br>
		                                                    	<div class="actionBtn'.$orderId.'">
			                                                    	<button style="background-color:brown;" onclick="Receive(trackingCode='.$orderId.')">Shipped
			                                                    	</button>
		                                                    	</div>
		                                                    </td>
														</tr>
													';
												}
												else {
													echo'
														<tr>
															<td>'.$i.'</td>
															<td>'.$itemName.'</td>
															<td>'.number_format($unityPrice).' Frw</td>
															<td>'.number_format($quantity).' '.$itemUnit.'</td>
															<td>'.number_format($totalPrice).' Frw</td>
															<td>'.$customerName.'</td>
															<td>'.$orderDate.'</td>
															<td actionBtn'.$trackingCode.'>'.$Orderstatus.'</td>
														</tr>
													';	
												}
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

    <!-- page specific plugins -->
    <!-- tablesorter -->
    <script src="bower_components/tablesorter/dist/js/jquery.tablesorter.min.js"></script>
    <script src="bower_components/tablesorter/dist/js/jquery.tablesorter.widgets.min.js"></script>
    <script src="bower_components/tablesorter/dist/js/widgets/widget-alignChar.min.js"></script>
    <script src="bower_components/tablesorter/dist/js/widgets/widget-columnSelector.min.js"></script>
    <script src="bower_components/tablesorter/dist/js/widgets/widget-print.min.js"></script>
    <script src="bower_components/tablesorter/dist/js/extras/jquery.tablesorter.pager.min.js"></script>
    <!-- ionrangeslider -->
    <script src="bower_components/ion.rangeslider/js/ion.rangeSlider.min.js"></script>

    <!--  tablesorter functions -->
    <script src="assets/js/pages/plugins_tablesorter.min.js"></script>
    
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
	<script type="text/javascript">
		
		function Receive(trackingCode){
			var trackingCode = trackingCode;
            var yes = confirm("Is it shipped?");
            if (yes == true) {
				$.ajax({
					type : "GET",
					url : "editstatus.php",
					dataType : "html",
					cache : "false",
					data : {
						trackingCode : trackingCode,
					},
					success : function(html, textStatus){
						$(".actionBtn"+trackingCode).html("");
						$(".status"+trackingCode).html(html);
					},
					error : function(xht, textStatus, errorThrown){
						alert("Error : " + errorThrown);
					}
				});
			}
		}
	</script>
</body>
</html>