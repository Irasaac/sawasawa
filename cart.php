<?php 
session_start(); // Start session first thing in script<?php // Session user
require('db.php');
// This file is www.developphp.com curriculum material
// Written by Adam Khoury January 01, 2011
$link='';
$steps='';
// GIVE ME THE SIGNED IN USER IN CASE I NEED TO USE HIM/HER
if (isset($_SESSION["username"])) 
	{
		$username = preg_replace('#[^A-Za-z0-9]#i', '', $_SESSION["username"]);
		$getuserName = $db->query("SELECT * FROM users WHERE loginId='$username'");
		while($row= mysqli_fetch_array($getuserName))
			{
				$doneBy = $row['names'];
			}
		$link.='
            <a class="next-btn" href="admin/login.php?page=../payrol.php">Use another account</a> 
            <a class="next-btn"  href="payrol.php">Checkout as '.$username.'</a>
        ';
		$steps.='
            <li class="current-step"><span>01. Summary</span></li>
                <li><span>02. Shipping</span></li>
                <li><span>03. Payment Options</span></li>
            ';
	}else{
		$link.='<a class="next-btn" href="admin/login.php?page=../payrol.php">Procced to Checkout'; // filter everything but numbers and letters
		$steps.='
            <li class="current-step"><span>01. Summary</span></li>
            <li><span>02. Sign in</span></li>
            <li><span>03. Shipping</span></li>
            <li><span>04. Payment Options</span></li>
        ';
	}
?>
<?php
	include 'cartBack.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
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
        <link rel="stylesheet" type="text/css" href="assets/css/index2.css" />
        <link rel="stylesheet" type="text/css" href="assets/css/index9.css" />
        <link rel="stylesheet" type="text/css" href="assets/css/quick-view.css" />
        <link rel="stylesheet" type="text/css" href="assets/css/responsive2.css" />
        <link rel="stylesheet" type="text/css" href="css/stepwizard.css" />
        <title>Sawasawa_Cart</title>
      <style type="text/css">
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
                        <a href="index.php"><img alt="Cavada market" src="assets/images/logo9.png" /></a>
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
                            <li><span>Your shopping cart</span></li>
                        </ul>
                    </div>
                </div>
                <div class="page-content">
                    <!-- Column left -->
                    <div class="container">
                        <div class="page-title">
                            <h3 class="title">Shopping cart</h3>
                        </div>
                        <div class="row-none">
                            <!-- Main content -->
                            <div class="page-order">
                                <div class="heading-counter warning"><?php echo $itemsarethere;?>
                                </div>
                                <div class="order-detail-content">
                                    <table class="table table-bordered table-responsive cart_summary">
                                        <thead>
                                            <tr>
                                                <th class="cart_product">Product</th>
                                                <th>Description</th>
                                                <th>Unit price</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                                <th  class="action">Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody>
    										<?php echo $cartOutput;?>
    									</tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" rowspan="2"></td>
                                                <td colspan="2">Total products</td>
                                                <td><?php echo $i;?></td>
                                                <td rowspan="2"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><strong>Total</strong></td>
                                                <td><strong><?php echo number_format($cartTotal);?> Frw</strong></td>
                                            </tr>
                                        </tfoot>   
                                    </table>
                                    <div class="cart_navigation">
                                        <a class="prev-btn" href="index.php">Continue shopping</a>
                                        <?php echo $link;?>
                                    </div>
                                </div>
                            </div>
                            <!-- End Main content -->
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <div class="row">
                        <!-- Column left -->
                        <div class="stepwizardfooter">
                            <div class="row setup-content" id="step-2">
                                <div class="col-md-3"></div>
                                <div class="col-xs-6">
                                    <form class="trackingcode-form-search toggle-mobile" name="trackingCodeform">
                                        <div class="input-search">
                                            <input onkeyup="trackingCode()" type="text" name="trackingcode"  placeholder="Enter tracking code to see your order" class="form-control">
                                            <div class="trackingCode" id="trackingCodeSpace"></div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- end part -->
                    </div>
                </div>
                <!-- end part -->
            </div>
            <a href="order.html#" class="scroll_top" title="Scroll to Top" style="display: inline;">Scroll</a>
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
        <script type="text/javascript">
            function topsearch() {
                xmlhttp = new XMLHttpRequest();
                xmlhttp.open("GET","autosearched.php?search="+document._formsearch.inputsearch.value,false);
                xmlhttp.send(null);
                document.getElementById('getresult').innerHTML=xmlhttp.responseText;
            }
        </script>
    </body>
</html>