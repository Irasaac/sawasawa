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

<?php

include 'db.php';
$transactions="";
$sql =$con->query("
	select
	tv.amount, tv.status, tv.3rdpartyId, tv.operation, tv.transaction_date, tv.bankName, tv.account_id accountId, tv.bankId, tv.clientId , tv.clientName, tv.to_from_client fromClientId,tv.to_from_account fromAccountId, 
	bc.accountNumber fromAccount, tv.to_from_bank fromBankId, b.name fromBank, c.name fromClient
	from transactionsview tv
	INNER JOIN bank_client bc
	ON tv.to_from_account = bc.id
	INNER JOIN banks b
	ON tv.to_from_bank = b.id
	INNER JOIN clients c
	ON tv.to_from_client = c.id
    WHERE operation = 'debit' ORDER BY trans_id DESC
	");
$n=0;
while ($row=mysqli_fetch_array($sql)) {
	$n++;
	$transactions.= '
		<tbody>
			<tr>
				<td>'.$n.'</td>
				<td>'.number_format($row['amount']).'</td>
				<td>'.$row['status'].'</td>
				<td>'.$row['3rdpartyId'].'</td>
				<td>'.$row['transaction_date'].'</td>
				<td><a href="account.php?accountId='.$row['accountId'].'&clientId='.$row['clientId'].'&page='.$row['bankId'].'">'.$row['clientName'].' | '.$row['bankName'].'</a></td>
				<td><a href="account.php?accountId='.$row['fromAccountId'].'&clientId='.$row['fromClientId'].'&page='.$row['fromBankId'].'">'.$row['fromClient'].'| '.$row['fromBank'].'</a></td>
			</tr>
		</tbody>';
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
							<div class="uk-margin-bottom" id="status">
								<a href="#" class="md-btn uk-margin-right" id="printTable">Print</a>
								<div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
									<button class="md-btn">Columns <i class="uk-icon-caret-down"></i></button>
									<div class="uk-dropdown">
										<ul class="uk-nav uk-nav-dropdown" id="columnSelector"></ul>
									</div>
								</div>
							</div>
							<div class="uk-overflow-container uk-margin-bottom">
								<table class="uk-table uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_pager_filter">
									<thead>
                                    <tr>
                                        <th data-name="Select" data-priority="1"><input type="checkbox" class="ts_checkbox_all"></th>
                                        <th data-priority="critical">Amount</th>
                                        <th data-priority="1">By</th>
                                        <th data-priority="1">For</th>
                                        <th data-priority="1">Date</th>
                                        <th class="filter-false remove sorter-false uk-text-center" data-priority="1">Actions</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th>Amount</th>
                                            <th>By</th>
                                            <th>For</th>
                                            <th>Date</th>
                                            <th class="uk-text-center">Actions</th>
                                        </tr>
                                    </tfoot>
									<tbody>
										<tr>
											<td><input type="checkbox" data-md-icheck class="ts_checkbox"></td>
											<td>200 Rwf</td>
											<td>200</td>
											<td>Fake weevs</td>
											<td>200</td>
											<td>Paid / Panding</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>	
			<div class="uk-grid uk-grid-medium" data-uk-grid-margin="">
				<div class="uk-width-xLarge-10-10  uk-width-large-10-10">
					<div class="md-card">
						<div class="md-card-content">
							<div class="uk-margin-bottom" id="status">
								<a href="#" class="md-btn uk-margin-right" id="printTable">Print</a>
								<div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
									<button class="md-btn">Columns <i class="uk-icon-caret-down"></i></button>
									<div class="uk-dropdown">
										<ul class="uk-nav uk-nav-dropdown" id="columnSelector"></ul>
									</div>
								</div>
							</div>
							<div class="uk-overflow-container uk-margin-bottom">
							<table class="uk-table uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_pager_filter">
								<thead>
									<tr>
										<th>#</th>
										<th>Amount</th>
										<th>Status</th>
										<th>3rdpartyId</th>
										<th>Date</th>
										<th>From</th>
										<th>To</th>
									</tr>
								</thead>
								<?php echo $transactions;?>
							</table>
							</div>
						</div>
					</div>
				</div>
			</div>	
			<div class="uk-grid uk-grid-medium" data-uk-grid-margin="">
				<div class="uk-width-xLarge-10-10  uk-width-large-10-10">
					<div class="md-card">
						<div class="md-card-content">
						<div class="col-md-3">
				
					<h4>Test Transfer</h4>
					Use: <select class="form-control" id="payMeth" onchange="givePay()" required>
							  <option></option>
							  <option value="phone">Phone</option>
							  <option value="card">Card</option>
							</select><br>
					  <div id="meth">
						From: 
						<input type="text" disabled class="form-control">
					  </div><br>
						To: 
						<input type="text" class="form-control">
						<br>
					  <button class="btn btn-primary">SEND</button>
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
	
</body>
</html>