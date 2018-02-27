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
			document.location.href = "admin/login.php?page=../payrol.php";
		</script>
		<?php
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
	    	.confirm {    
	    		border: 1px solid green;
			    padding: 13px;
			    width: 200px;
			    text-align: center;
			    margin: 70px auto;
			    float: right;
	    	}
	    	.confirmorder {    
	    		border: 1px solid green;
			    padding: 13px;
			    width: 200px;
			    text-align: center;
			    margin: 70px auto;
	    	}
	    	.cancel {    
	    		border: 1px solid red;
			    padding: 13px;
			    width: 200px;
			    text-align: center;
			    margin: 70px auto;
			    float: left;
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
	                        <li><span>Checkout Process</span></li>
	                    </ul>
	                </div>
	            </div>
	            <div class="page-content" style="padding: unset;">
	                <!-- Column left -->
					<div class="container">
						<div class="stepwizardcantainer">
							<div class="stepwizard">
							    <div class="stepwizard-row setup-panel">
							        <div class="stepwizard-step">
							            <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
							            <p>Order Summary</p>
							        </div>
							        <div class="stepwizard-step">
							            <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
							            <p>Payment Option</p>
							        </div>
							    </div>
							</div>
							<form role="form">
							    <div class="row setup-content" id="step-1">
							        <div class="col-xs-12 col-md-12">
			                            <div class="page-order">
			                                <div class="heading-counter warning"><?php echo $itemsarethere;?>
			                                </div>
			                                <div class="order-detail-content">
			                                    <table class="table table-bordered table-responsive cart_summary">
			                                        <thead>
			                                            <tr>
			                                                <th class="cart_product">Product</th>
			                                                <th>Unit price</th>
			                                                <th>Qty</th>
			                                                <th>Total</th>
			                                            </tr>
			                                        </thead>
			                                        <tbody>
			    										<?php echo $cartSummary;?>
			    									</tbody>
			                                        <tfoot>
			                                            <tr>
			                                                <td colspan="1" rowspan="2"></td>
			                                                <td colspan="2">Total products</td>
			                                                <td><?php echo $i;?></td>
			                                            </tr>
			                                            <tr>
			                                                <td colspan="2"><strong>Total</strong></td>
			                                                <td><strong><?php echo number_format($cartTotal);?> Frw</strong></td>
			                                            </tr>
			                                        </tfoot>   
			                                    </table>
			                                </div>
			                            </div>
								        <div class="col-xs-12">
							            	<a href="cart.php"><div class="cancel">BACK</div></a>
							            	<a href="javascript:;">
					                			<div class="confirm nextBtn">NEXT</div>
					                		</a>
								        </div>
							        </div>
							    </div>
							    <div class="row setup-content" id="step-2">
							        <div class="col-xs-12">
				                        <div class="page-order">
				                            <ul class="step clearfix"><?php echo $steps;?>
				                            </ul>
				                            <div class="heading-counter warning">Your Payment is : <?php echo number_format($cartTotal);?>, which is probably to be converted and added some transaction fee
				                            </div>
				                            <div class="order-detail-content">
				                                <table class="table table-bordered table-responsive cart_summary">
				                                    <thead>
				                                        <tr>
				                                            <th>Pay through</th>
				                                            <th>Conversion</th>
				                                            <th>Total</th>
				                                            <th>Action</th>
				                                        </tr>
				                                    </thead>
				                                    <tbody>
														<tr>
															<td>MTN Mobile Money</td>
															<td>Rwf</td>
															<td><?php echo''.number_format($cartTotal).' Rwf'?></td>
															<td><div data-toggle="modal" data-target="#mtn" class="cart_navigation"><a class="next-btn" style="background: #dcdc09;border: 1px solid #dcdc09;"> Chose</a></div></td>

				                                            <!-- Modal -->
				                                            <div id="mtn" class="modal fade" role="dialog">
				                                              <div class="modal-dialog">

				                                                <!-- Modal content-->
				                                                <div class="modal-content">
												                    <form class="form-search toggle-mobile" name="mtnForm">
																	<div class="modal-header" style="background-color: #dcdc09;">
																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																	<h4 class="modal-title">Enter Number to Pay with MTN Mobile Money</h4>
																	</div>
					                                                <div class="modal-body" style="min-height: 100px;">
											                            <div class="input-search">
											                               <input type="text" name="inputMtnNumber" class="form-control" value="+250">
											                            </div>
					                                                </div>
																	<div class="modal-footer" style="text-align: unset;background-color: #dcdc09">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				            											<a href="process.php"><div class="btn btn-success nextBtn" style="float: right;">Confirm Order</div></a>
																	</div>
												                    </form>
				                                                </div>

				                                              </div>
				                                            </div>

														</tr>
														<tr>
															<td>Tigo Cash</td>
															<td>Rwf</td>
															<td><?php echo''.number_format($cartTotal).' Rwf'?></td>
															<td><div data-toggle="modal" data-target="#tigo" class="cart_navigation"><a class="next-btn"> Chose</a></div></td>

				                                            <!-- Modal -->
				                                            <div id="tigo" class="modal fade" role="dialog">
				                                              <div class="modal-dialog">

				                                                <!-- Modal content-->
				                                                <div class="modal-content">
												                    <form class="form-search toggle-mobile" name="tigoForm">
																		<div class="modal-header" style="background-color: #1976d2;">
																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																		<h4 class="modal-title">Enter number Pay with Tigo Cash</h4>
																		</div>
						                                                <div class="modal-body" style="min-height: 100px;">
												                            <div class="input-search">
												                               <input type="text" value="+250" name="inputMtnNumber" class="form-control">
												                            </div>
						                                                </div>
																		<div class="modal-footer" style="text-align: unset;background-color: #1976d2">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					            											<a href="process.php"><div class="btn btn-success nextBtn" style="float: right;">Confirm Order</div></a>
																		</div>
												                	</form>
				                                                </div>

				                                              </div>
				                                            </div>

														</tr>
														<tr>
															<td>VISA CARD</td>
															<td>Rwf</td>
															<td><?php echo''.number_format($cartTotal).' Rwf'?></td>
															<td><div data-toggle="modal" data-target="#visa" class="cart_navigation"><a class="next-btn"> Chose</a></div></td>

				                                            <!-- Modal -->
				                                            <div id="visa" class="modal fade" role="dialog">
				                                              <div class="modal-dialog">

				                                                <!-- Modal content-->
				                                                <div class="modal-content">
												                    <form class="form-search toggle-mobile" name="mtnForm">
																		<div class="modal-header" style="background-color: #1976d2;">
																			<button type="button" class="close" data-dismiss="modal">&times;</button>
																		<h4 class="modal-title">Pay with VISA CARD</h4>
																		</div>
						                                                <div class="modal-body" style="min-height: 100px;">
												                            <div class="input-search">
												                               <input type="text" name="inputMtnNumber" class="form-control">
												                            </div>
						                                                </div>
																		<div class="modal-footer" style="text-align: unset;background-color: #1976d2">
																			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					            											<a href="process.php"><div class="btn btn-success nextBtn" style="float: right;">Confirm Order</div></a>
																		</div>
												                    </form>
				                                                </div>

														</tr>
													</tbody>
				                                </table>
				                            </div>
				                        </div>
							            <div class="col-md-12">
							            	<a href="cart.php"><div class="cancel">BACK</div></a>
							            </div>
							        </div>
							    </div>
							</form>
						</div>
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
	            	<!-- end part -->
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
			$(document).ready(function () {
			    var navListItems = $('div.setup-panel div a'),
			            allWells = $('.setup-content'),
			            allNextBtn = $('.nextBtn');

			    allWells.hide();

			    navListItems.click(function (e) {
			        e.preventDefault();
			        var $target = $($(this).attr('href')),
			                $item = $(this);

			        if (!$item.hasClass('disabled')) {
			            navListItems.removeClass('btn-primary').addClass('btn-default');
			            $item.addClass('btn-primary');
			            allWells.hide();
			            $target.show();
			            $target.find('input:eq(0)').focus();
			        }
			    });

			    allNextBtn.click(function(){
			        var curStep = $(this).closest(".setup-content"),
			            curStepBtn = curStep.attr("id"),
			            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
			            curInputs = curStep.find("input[type='text'],input[type='url'],input[type='email']"),
			            isValid = true;

			        $(".form-group").removeClass("has-error");
			        for(var i=0; i<curInputs.length; i++){
			            if (!curInputs[i].validity.valid){
			                isValid = false;
			                $(curInputs[i]).closest(".form-group").addClass("has-error");
			            }
			        }

			        if (isValid)
			            nextStepWizard.removeAttr('disabled').trigger('click');
			    });

			    $('div.setup-panel div a.btn-primary').trigger('click');
			});
		</script>

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