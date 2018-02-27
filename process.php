<?php
	session_start();
	include 'cartBack.php';
	if (!isset($_SESSION['cart_array'])) {
		?>
		<script type="text/javascript">
			document.location.href = "index.php";
		</script>
		<?php
	}
	if (!isset($_SESSION['id'])) {
		?>
		<script type="text/javascript">
			document.location.href = "cart.php";
		</script>
		<?php
	}
	else {
		$customerCode = $_SESSION['id'];
	}
	if (!isset($_SESSION['Longitude'])) {
		?>
		<script type="text/javascript">
			document.location.href = "index.php";
		</script>
		<?php
	}
	else {
	    $cityLat = $_SESSION['Latitude'];
	    $cityLng = $_SESSION['Longitude'];
	    $address = $_SESSION['address'];
	}
	include 'db.php';
	include 'cartBack.php';
	$selectlastid = $db -> query("SELECT * FROM orders ORDER BY orderId DESC LIMIT 1");
	$selected = mysqli_fetch_array($selectlastid);
	$conf_id = $selected['orderId'] + 1;
	foreach ($_SESSION["cart_array"] as $each_item) { 
		$itemId = $each_item['item_id'];
		$itemQuantity = $each_item['quantity'];
		$sql = $db->query("SELECT * FROM items1 WHERE itemId='$itemId' LIMIT 1");
		while ($itemrow = mysqli_fetch_array($sql)) {
			$price = $itemrow["unityPrice"];
		}
		$pricetotal = $price * $itemQuantity;
		$getCompany = $db ->query("SELECT itemCompanyCode FROM items1 WHERE itemId = '$itemId'");
		$company = mysqli_fetch_array($getCompany);
		$itemCompanyCode = $company['itemCompanyCode'];
		$insertOrder = $db ->query("INSERT INTO `orders`(`trackingCode`, `itemCode`, `quantity`, `unityPrice`, `TotalPrice`, `customerCode`, `orderDate`, `orderStatus`, `latitude`, `longitude`, `address`, `itemCompanyCode`) VALUES ('$conf_id','$itemId','$itemQuantity','$price','$pricetotal','$customerCode',now(),'Pending','$cityLat','$cityLng','$address','$itemCompanyCode')");
	}
	if ($insertOrder) {
		unset($_SESSION['cart_array']);
?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" type="text/css" href="assets/lib/bootstrap/css/bootstrap.min.css" />
            <link rel="stylesheet" type="text/css" href="assets/lib/font-awesome/css/font-awesome.min.css" />
            <link rel="stylesheet" type="text/css" href="assets/lib/Linearicons/css/demo.css" />
            <link rel="stylesheet" type="text/css" href="assets/lib/select2/css/select2.min.css" />
            <link rel="stylesheet" type="text/css" href="assets/lib/jquery.bxslider/jquery.bxslider.css" />
            <link rel="stylesheet" type="text/css" href="assets/lib/owl.carousel/owl.carousel.css" />
            <link rel="stylesheet" type="text/css" href="assets/lib/fancyBox/jquery.fancybox.css" />
            <link rel="stylesheet" type="text/css" href="assets/css/animate.css" />
            <link rel="stylesheet" type="text/css" href="assets/css/reset.css" />
            <link rel="stylesheet" type="text/css" href="assets/css/index9.css" />
            <link rel="stylesheet" type="text/css" href="assets/css/index2.css" />
            <link rel="stylesheet" type="text/css" href="css/stepwizard.css" />
            <link rel="stylesheet" type="text/css" href="assets/css/quick-view.css" />
            <link rel="stylesheet" type="text/css" href="assets/css/responsive9.css" />
            <title>Pandagali_Checkout</title>
            <style type="text/css">
            	.md-card {
            		left: 20%; 
            		right: 20%; 
            		padding: 30px 20px;
            		color: #fff;
            		background-color: #1976d2;
            		box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 1px 2px rgba(0,0,0,.24);
            		border: none;
            		min-height: 120px;
            		margin: 15px auto;
            	}
            	.confirmorder {    
            		border: 1px solid green;
        		    padding: 13px;
        		    width: 200px;
        		    text-align: center;
        		    margin: 70px auto;
            	}
                .trackingCode {    
                    padding: 13px;
                    width: 300px;
                    text-align: left;
                    margin: auto;
                    font-weight: bold;
                }
                .trackingcode-form-search {
                    margin: 45px auto;
                    padding: 13px;
                    width: 300px;
                }
            </style>
        </head>
        <body class="onsale-product">
            <div class="main-header">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 logo">
                            <a href="#"><img alt="Cavada market" src="assets/images/logo9.png" /></a>
                        </div>
                        <div class="tool-header">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 header-search">
                                <span class="toggle-icon"></span>
                                <form class="form-search toggle-mobile" name="_formsearch">
                                    <div class="input-search">
                                        <input onkeyup="topsearch()" type="text" name="inputsearch"  placeholder="Search everything">
                                    </div>
        							
                                    <div class="form-category dropdown">
                                        <?php
                                            include ("db.php");
                                            $sql1 = $db->query("SELECT * FROM `levels` WHERE parentId = 0");
                                            echo'<select class="box-category">
                                                <option>All Category</option>
                                            ';
                                                
                                            while($row = mysqli_fetch_array($sql1)){
                                                $CatID = $row['catId'];
                                                echo'
                                                        <option value="'.$row['id'].'">'.$row['name'].'</option>
                                                ';
                                            }
                                            echo'</select>';

                                        ?>
                                    </div>
                                    <button type="submit" class="btn-search"></button>
                                    <div class="getresult" id="getresult"></div>
                                </form>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 right-main-header">
                                
                                <div class="action">
                                    <a title="Login" class="compare fa fa-user" href="admin/login.php"></a>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="wrapper">
                <div class="main-page">
                    <div class="breadcrumb clearfix">
                        <div class="container">
                            <ul class="list-breadcr">
                                <li class="home"><a href="index.php" title="Back to Home">Home</a></li>
                                <li><span>After Checkout Process</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="page-content">
                        <!-- Column left -->
        				<div class="stepwizardcantainer">
        				    <div class="row setup-content" id="step-2">
        				        <div class="col-xs-12">
        				            <div class="col-md-12">
        					            <p>
        					            	Thank you for Ordering here at sawasawa online store. <br>
        					            	You will get your product at your place! <br>
        					            	Your Tracking code is <strong><?php echo $conf_id; ?></strong>
        					            </p>
        				            	<a href="index.php"><div class="confirmorder">ReOrder</div></a>
        				            </div>
        				        </div>
        				    </div>
        				</div>
                    	<!-- end part -->
                        <!-- Column left -->
                    	<!-- end part -->
                	</div>
                    <div class="footer">
                        <div class="row">
                            <!-- Column left -->
                            <div class="stepwizardfooter" style="background-color: #1976d2;">
                                <div class="row setup-content" id="step-2">
                                    <div class="col-md-3"></div>
                                    <div class="col-xs-6">
                                        <form class="trackingcode-form-search toggle-mobile" name="trackingCodeform">
                                            <div class="input-search">
                                                <input onkeyup="trackingCode()" type="text" name="trackingcode"  placeholder="Track Your Order" class="form-control">
                                                <div class="trackingCode" id="trackingCodeSpace"></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- end part -->
                        </div>
                    </div>
        	        <a href="order.html#" class="scroll_top" title="Scroll to Top" style="display: inline;">Scroll</a>
        	    </div>
        	</div>
            <!-- Script-->
            <script type="text/javascript" src="assets/lib/jquery/jquery-1.11.2.min.js"></script>
            <script type="text/javascript" src="assets/lib/bootstrap/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="assets/lib/select2/js/select2.min.js"></script>
            <script type="text/javascript" src="assets/lib/jquery.bxslider/jquery.bxslider.min.js"></script>
            <script type="text/javascript" src="assets/lib/owl.carousel/owl.carousel.min.js"></script>
            <script type="text/javascript" src="assets/lib/jquery.countdown/jquery.countdown.min.js"></script>
            <script type="text/javascript" src="assets/lib/fancyBox/jquery.fancybox.js"></script>
            <script type="text/javascript" src="assets/lib/jquery.elevatezoom.js"></script>
            <script type="text/javascript" src="assets/js/theme-script.js"></script>
            <script type="text/javascript" src="assets/js/equalheight.js"></script>


            <script type="text/javascript">
                function trackingCode() {
                    xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("GET","trackingcode.php?trackingCode="+document.trackingCodeform.trackingcode.value,false);
                    xmlhttp.send(null);
                    document.getElementById('trackingCodeSpace').innerHTML=xmlhttp.responseText;
                }
            </script>
        </body>
    </html>
<?php
	}
	
?>